<?php

namespace App\Http\Controllers\theme;

use Validator,Auth,DB,Storage;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use App\Models\Helpers\CommonHelper;
use App\Models\Table;
use App\Models\Booking;

class BookingController extends CommonController
{
	use CommonHelper;
    /**
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
	* Show the application Booking page.
	*/
	public function index(){
		try {
			$page       = 'booking';
			$page_title = trans('title.booking');

			$data = Table::where('status', 'active')->groupBy('id')->get();
			if(!empty($data)){
				return view('theme/booking', compact('page','page_title','data'));
			}

		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	/**
	* Show the application Booking Success message page.
	* @return void
	*/
	public function messageSuccess(){
		$page		= 'booking_messageSuccess';
        $page_title = trans('title.booking_success');
		
		return view('theme.order.booking-success-message',compact('page', 'page_title'));
    }
	
	/**
	* Show the application Booking Failed message page.
	* @return void
	*/
	public function messageFailed(){
		$page		= 'booking_messageFailed';
        $page_title = trans('title.booking_failed');
		
		return view('theme.order.booking-failed-message',compact('page', 'page_title'));
    }
	
	/**
	* Show the application Booking checkout page.
	*/
	public function checkout(){
		
		$user = Auth()->user();
		if(empty($user)){
			//$this->ajaxError([], trans('common.login_to_continue'));
		}
		
		try {
			$page       = 'booking_checkout';
			$page_title = trans('title.booking_checkout');

			if(empty($user)){
				$data = Booking::where('token', csrf_token())->where('process_completed', 'No')->orderBy('id', 'DESC')->first();
			}else{
				$data = Booking::where('user_id', $user->id)->where('process_completed', 'No')->orderBy('id', 'DESC')->first();
			}
			if(!empty($data)){
				return view('theme/order/booking-checkout', compact('page','page_title','data'));
			}
			return redirect('/booking');
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	/**
	* 
	* Create a new Booking
	* @return void
	*
	*/
	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'booking_day' 	=> 'required',
			'booking_time' 	=> 'required',
			'guest' 		=> 'required',
		]);
		if($validator->fails()){
			$this->ajaxError([], $validator->errors()->first());
		}

		$user = Auth()->user();
		if(empty($user)){
			//$this->ajaxError([], trans('common.login_to_continue'));
		}
		
		DB::beginTransaction();
		try{
			
			if(empty($user)){
				$cartData = Booking::where('token', csrf_token())->where('process_completed', 'No')->get();
				if($cartData->count() > 0){
					Booking::where('token', csrf_token())->where('process_completed', 'No')->delete();
				}
				$data['token'] 	= csrf_token();
			}else{
				$cartData = Booking::where('user_id', $user->id)->where('process_completed', 'No')->get();
				if($cartData->count() > 0){
					Booking::where('user_id', $user->id)->where('process_completed', 'No')->delete();
				}
				$data['user_id'] 	= $user->id;
			}
			
			// CREATE ORDER
			$data['custom_order_id'] 	= time();
			$data['date']				= $request->booking_day;
			$data['start_time']			= $request->booking_time;
			$data['end_time']			= $request->booking_time;
			$data['quantity']			= $request->guest;
			$data['total']				= $request->guest * 0;
			$data['grand_total']		= $request->guest * 0;
			
			$insert = Booking::create($data);
			if($insert){
				DB::commit();
				$this->sendResponse($insert, trans('booking.create_success'));
			}
			
			$this->sendResponse([], trans('booking.failed_to_create'));
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// LIST
	public function ajax_checkoutList(Request $request){
		$user = Auth()->user();
		if(empty($user)){
			//$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			if(empty($user)){
				$data = Booking::where('token', csrf_token())->where('process_completed', 'No')->orderBy('id', 'DESC')->first();
			}else{
				$data = Booking::where('user_id', $user->id)->where('process_completed', 'No')->orderBy('id', 'DESC')->first();
			}
			
			if($data){
				$return['booking_date']  = $data->date;
				$return['booking_time']  = $data->start_time;
				$return['booking_guest'] = $data->quantity .' Guest';
				$return['sub_total'] 	 = number_format($data->total, 2, '.', '');
				$return['tax'] 			 = '0.00';
				$return['grand_total'] 	 = number_format($data->grand_total, 2, '.', '');
				$this->sendResponse($return, trans('booking.data_found_success'));
			}
			
			$this->sendResponse([], trans('booking.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	
	// LIST
	public function confirm(Request $request){
		
		$validator = Validator::make($request->all(), [
			'name'			 => 'required',
			'email' 		 => 'required',
			'phone_number'   => 'required',
			'payment_method' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError([], $validator->errors()->first());
		}
		
		$user = Auth()->user();
		if(empty($user)){
			//$this->ajaxError([], trans('common.invalid_user'));
		}
		
		DB::beginTransaction();
		try{
			if(empty($user)){
				$data = Booking::where('token', csrf_token())->where('process_completed', 'No')->orderBy('id', 'DESC')->first();
			}else{
				$data = Booking::where('user_id', $user->id)->where('process_completed', 'No')->orderBy('id', 'DESC')->first();
			}
			
			if($data){
				
				$data->name = $request->name;
				$data->email = $request->email;
				$data->phone_number = $request->phone_number;
				$data->payment_method = $request->payment_method;
				$data->save();
				DB::commit();
				
				if($request->payment_method == 1){
					CommonHelper::booking_finalization($data->id, $user);
				}else{
					$this->ajaxError([], trans('booking.invalid_payment_method'));
				}
				
				$this->sendResponse($data, trans('booking.booking_confirmed'));
			}
			
			$this->sendResponse([], trans('booking.booking_confirmed_failed'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	/**
	* 
	* Validate the Booking
	* @return void
	*
	*/
	public function validateOrder(Request $request){
		
		
		return redirect()->route('order_successPage');
    }
}