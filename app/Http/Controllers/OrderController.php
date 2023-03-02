<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Helpers\CommonHelper;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;

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
		//$this->middleware(['auth','vendor_approved','vendor_subscribed']);
		// $this->middleware('permission:vendor-product-edit', ['only' => ['edit','update']]);
	}
	
	// Create Order
	public function create(Request $request){
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		DB::beginTransaction();
		try{
			
			// GET CART DATA
			$cartData = Cart::where('user_id', $user->id)->get();
			if($cartData->count() > 0){
				
				// CREATE ORDER
				$data['custom_order_id'] 	= rand(1111, 9999);
				$data['order_date']    		= date('Y-m-d');
				$data['user_id']       		= $user->id;
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
					
					DB::commit();
					$this->sendResponse($insert, trans('order.add_success'));
				}
			}
			
			$this->sendResponse([], trans('order.add_failed'));
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	
	// Confirm Order
	public function confirm(Request $request){
		
		$validator = Validator::make($request->all(), [
			'payment_method' => 'required',
			'delivery_address' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError([], $validator->errors()->first());
		}
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		if($request->payment_method == '2'){
			$this->ajaxError([], trans('Please choose cash on delivery'));
		}
		
		DB::beginTransaction();
		try{
			// GET ORDER DATA
			$order = Order::where(['user_id'=> $user->id, 'status'=>'Temporary'])->orderBy('id','DESC')->first();
			if($order){
				
				// GET Address
				$shipping_address = '123, Businees Hub, Corporate Road, Satellite, 380015, Ahmedabd';
				$address = Address::where(['id'=> $request->delivery_address])->first();
				if(!empty($address)){
					$shipping_address = '('. $address->address_type .') '. $address->address .' '. $address->city->name .' '. $address->address;
					//$this->ajaxError([], trans('Invalid Address'));
				}
		
				if($request->payment_method == '1'){
					$order->status			= 'Received';
				}
				//Update Order
				$order->payment_method_id	= $request->payment_method;
				$order->address_id			= $request->delivery_address;
				$order->shipping_address	= $shipping_address;
				$order->order_date			= date('Y-m-d');
				$order->save();
				
				if($request->payment_method == '2'){
					// CREATE PAYMET GETWAY ORDER
					
					
				}
				

				
				DB::commit();
				$this->sendResponse($order, trans('order.confirm_success'));
			}
			
			$this->ajaxError([], trans('common.try_again'));
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	public function orderSuccess(){
		$page		= 'orderSuccess';
        $page_title = trans('title.order_success');
		
		return view('theme.order.success-message',compact('page', 'page_title'));
    }
}