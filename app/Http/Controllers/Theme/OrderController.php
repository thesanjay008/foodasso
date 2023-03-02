<?php

namespace App\Http\Controllers\theme;

use App\Http\Controllers\CommonController;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Helpers\CommonHelper;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\SmsVerification;

class OrderController extends CommonController
{   
	use CommonHelper;
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
		//$this->middleware(['auth']);
	}
	
	/**
	* 
	* Create a new Order
	* @return void
	*
	*/
	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'payment_method' 	=> 'required',
			'delivery_address' 	=> 'required',
		]);
		if($validator->fails()){
			$this->ajaxError([], $validator->errors()->first());
		}

		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.login_to_continue'));
		}
		
		DB::beginTransaction();
		try{
			$shipping_address 	= 'Undefined address';
			$payment_url 		= '';
			
			// GET Address
			$address = Address::where(['id'=> $request->delivery_address])->first();
			if(!empty($address)){
				$shipping_address = '('. $address->address_type .') '. $address->address .' '. $address->city->name .' '. $address->postal_code;
			}

			// GET CART DATA
			$cartData = Cart::where('user_id', $user->id)->get();
			if($cartData->count() > 0){
				
				if($request->payment_method == 1){
					$data['status'] = 'New';
				}
				
				// CREATE ORDER
				$data['custom_order_id'] 	= time();
				$data['order_date']    		= date('Y-m-d');
				$data['user_id']       		= $user->id;
				$data['contact_person']		= $user->name;
				$data['contact_number']		= $user->mobile_number;
				$data['payment_method_id']	= $request->payment_method;
				$data['address_id']			= $request->delivery_address;
				$data['address']			= $shipping_address;
				$data['shipping_address']	= $shipping_address;
				$data['item_count']    		= $cartData->count();
				$data['quantity']      		= $cartData->sum('quantity');
				$data['total']         		= $cartData->sum('total');
				$data['grand_total']   		= $cartData->sum('total');
				
				$insert = Order::create($data);
				if($insert){
					// INSERT ORDER ITEMS
					foreach($cartData as $key=> $list){
						$orderItems = array(
						  'order_id'			=> $insert->id,
						  'custom_order_id'		=> $insert->custom_order_id,
						  'product_id'			=> $list->product_id,
						  'title'				=> $list->title,
						  'quantity'			=> $list->quantity,
						  'price'				=> $list->price,
						  'total'				=> $list->total,
						);
						OrderItem::create($orderItems);
					}
					
					if($request->payment_method == 1){
						// Finalize Order
						CommonHelper::order_finalization($insert->id, $user);
						
						// Delete Cart Data
						Cart::where('user_id', $user->id)->delete();
						
						DB::commit();
						$response['payment_url'] = '';
						$this->sendResponse($response, trans('order.confirm_success'));
					}else{
						// Ctreate Payment Order
						$amount = $insert->grand_total * 100;
						$reuurn = CommonHelper::gateway_create_order(['amount'=>$amount,'currency'=>'INR','receipt'=>$user->name]);
						if($reuurn){
							if(isset($reuurn['id'])){
								$insert->tracking_id = $reuurn['id'];
								$insert->update();
								DB::commit();
								
								$response['payment_url'] = url('payment/'.$insert->id);
								$this->sendResponse($response, trans('order.confirm_success'));
							}
						}
						$this->ajaxError([], trans('order.failed_to_create'));
					}
				}
			}
			
			DB::rollback();
			$this->sendResponse([], trans('order.failed_to_create'));
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	/**
	* 
	* Send Table OTP
	* @return void
	*
	*/
	public function send_otp(Request $request){
		
		$validator = Validator::make($request->all(), [
			'name' 			=> 'required',
			'phone_number'  => 'required',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}

		$user = Auth()->user();
		if(empty($user)){
			//$this->ajaxError([], trans('common.login_to_continue'));
		}
		
		DB::beginTransaction();
		try{
			$otp = rand(111111,999999);
			$message ='Hello '. $request->name .', Use '. $otp .' for OTP on TeaPost. Team CodeFencers!';
			$sent = CommonHelper::sendSMS($request->phone_number, $message);
			if($sent){
				$query = SmsVerification::create(['mobile_number' => $request->phone_number,'code' => $otp]);
				if($sent){
					DB::commit();
					return $this->sendResponse("", trans('common.otp_sent_success'));
				}
			}
			return $this->sendResponse("", trans('common.try_again'));
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
    }
	
	/**
	* Verify Payment
	* @return void
	*/
	public function verifyPayment(Request $request){
		
		$data = Order::where('custom_order_id', $request->id)->first();
		if($data){
			//CommonHelper::order_finalization($data->id, $user);
			return redirect()->route('order_successPage');
		}
		return redirect()->route('order_failedPage');
    }
	
	// Show Success Message
	public function orderSuccess(){
		$page		= 'orderSuccess';
        $page_title = trans('title.order_success');
		
		return view('theme.order.success-message',compact('page', 'page_title'));
    }
	
	// Show Failed Message
	public function orderFailed(){
		$page		= 'orderFailed';
        $page_title = trans('title.order_failed');
		
		return view('theme.order.failed-message',compact('page', 'page_title'));
    }
	
	

	/**
	* -----------------------------
	* Create Di-in(QR TABLE) Order
	* @return void
	* -----------------------------
	*/
	public function createTableOrder(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' 				=> 'required',
			'phone_number' 		=> 'required',
			'otp_code'			=> 'required',
			'payment_method'	=> 'required',
			'table_number'		=> 'required',
		]);
		if($validator->fails()){
			$this->ajaxError([], $validator->errors()->first());
		}
		
		$user = Auth()->user();
		if(empty($user)){
			$user = User::firstOrCreate(['mobile_number' => $request->phone_number], ['email' => NULL, 'name' => $request->name, 'user_type' => 'Customer']);
		}
		if(empty($user)){
			$this->ajaxError([], trans('common.login_to_continue'));
		}
		
		DB::beginTransaction();
		try{
			
			// GET CART DATA
			if(empty(Auth()->user())){
				$cartData = Cart::where('token', csrf_token())->get();
			}else{
				$cartData = Cart::where('user_id', $user->id)->get();
			}
			
			if($cartData->count() > 0){
				
				$payment_mode 	= 'Online';
				$payment_method = $request->payment_method ?? '1';
				
				if($payment_method == 1){
					$data['status'] 		= 'New';
					$data['payment_mode'] 	= 'Cash';
				}
				
				// CREATE ORDER
				$data['custom_order_id'] 	= md5(time());
				$data['order_date']    		= date('Y-m-d');
				$data['order_type']    		= 'Dine-In';
				$data['table_id']			= $request->table_number;
				$data['user_id']       		= $user->id;
				$data['payment_mode']		= $payment_mode;
				$data['payment_method_id']	= $payment_method;
				$data['item_count']    		= $cartData->count();
				$data['quantity']      		= $cartData->sum('quantity');
				$data['total']         		= $cartData->sum('total');
				$data['grand_total']   		= $cartData->sum('total');

				$insert = Order::create($data);
				if($insert){
					// INSERT ORDER ITEMS
					foreach($cartData as $key=> $list){
						$orderItems = array(
						  'order_id'			=> $insert->id,
						  'custom_order_id'		=> $insert->custom_order_id,
						  'product_id'			=> $list->product_id,
						  'quantity'			=> $list->quantity,
						  'price'				=> $list->price,
						  'total'				=> $list->total,
						);
						OrderItem::create($orderItems);
					}
					
					if($payment_method == 1){
						CommonHelper::order_finalization($insert->id, $user);
						DB::commit();
						$this->sendResponse($insert, trans('order.confirm_success'));
					}else{
						// Ctreate Payment Order
						$paymentData = [
							'amount'=>$insert->grand_total * 100,
							'currency'=>'INR',
							//'receipt'=>$user->name,
							'expire_by'=>strtotime ( '+1 month' , strtotime (date("Y-m-d"))),
							'reference_id'=>$insert->custom_order_id,
							'description'=>'Payment for order no '. $insert->custom_order_id,
							'customer'=>['name'=>$request->name, 'contact'=>$request->phone_number],
							'notify'=>['sms'=>true, 'email'=>false],
							'callback_url'=>url('verify-payment/'. $insert->custom_order_id),
							'callback_method'=>'get'
						];
						$reuurn = CommonHelper::gateway_create_order($paymentData);
						if($reuurn){
							if(isset($reuurn['short_url'])){
								$insert->tracking_id = $reuurn['id'];
								$insert->update();
								DB::commit();
								$response['payment_url'] = $reuurn['short_url'];
								return $this->sendResponse($response, trans('order.confirm_success'));
							}
						}
					}
				}
			}
			$this->ajaxError([], trans('order.failed_to_create'));
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	/**
	* Verify Dine-in(QR TABLE) Payment
	* @return void
	*/
	public function verifyTablePayment(Request $request){
		
		$data = Order::where('custom_order_id', $request->id)->first();
		if($data){
			//CommonHelper::order_finalization($data->id, $user);
			return redirect()->route('table.order_successPage', [$request->id]);
		}
		return redirect()->route('table.order_failedPage', [$request->id]);
    }
	
	// Success Message for Dine-in(QR TABLE)
	public function table_orderSuccess($table_id = ''){
		$page		= 'orderSuccess';
        $page_title = trans('title.order_success');
		
		return view('theme.table.success-message',compact('page', 'page_title','table_id'));
    }
	// Failed Message for Dine-in(QR TABLE)
	public function table_orderFailed($table_id = ''){
		$page		= 'orderFailed';
        $page_title = trans('title.order_failed');
		
		return view('theme.table.failed-message',compact('page', 'page_title','table_id'));
    }
}