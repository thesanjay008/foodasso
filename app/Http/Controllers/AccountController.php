<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Helpers\CommonHelper;
use App\Models\Restaurant;
use App\Models\Product;
use App\Models\MenuCategory;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;

class AccountController extends CommonController
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
  
	// MY Account
	public function index(){
		
		if(! Auth::user()){ return redirect()->route('firstPage'); }
		
		try{
			$page 		= 'myAccount';
			$page_title = trans('theme.my_account_title');
			
			return view('theme.myAccount.index', compact(['page','page_title']));
			
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	// MY Orders
	public function myOrders(){
		
		if(! Auth::user()){ return redirect()->route('firstPage'); }
		
		try{
			$page 		= 'myOrders';
			$page_title = trans('theme.my_orders_title');
			
			return view('theme.myAccount.index', compact(['page','page_title']));
			
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	// Orders List
	public function ajax_myOrders(Request $request){
		
		$user = Auth::user();
        if(empty($user)){
            return $this->sendError('',trans('customer_api.invalid_user'));
        }
		
		try{
			$query = Order::with('order_items')->where('user_id', $user->id);
			$query->where('status','!=','Temporary');
			if($request->type == 'open'){
				$query->whereIn('status', ['New','Accepted','Preparing','Dispatched','Out-For-Delivery','On-Hold','Delivered']);
			}
			$data = $query->get();
			
			if($data){
				$finalArray = [];
				$order_status = ['New'=>'Pending','Accepted'=>'Accepted','Preparing'=>'Preparing','Dispatched'=>'Dispatched','Out-For-Delivery'=>'Out-For-Delivery','Delivered'=>'Delivered'];
				foreach($data as $list){
					$array = [
						'title' 		=> 'Order '. $list->order_date,
						'address'		=> $list->shipping_address ?? '',
						'grand_total'	=> $list->grand_total,
						'image'			=> '',
						'status'		=> $order_status[$list->status],
					];
					
					$order_items = [];
					foreach($list->order_items as $items){
						$image = asset('public/'. config("constants.DEFAULT_THUMBNAIL"));	
						$title = 'Deleted item';
						if($items->product){
							$image = $items->product->image ? asset('public/'. $items->product->image) : $image;
							$title = $items->product->title;
						}
						$order_items[] = '<li><img src="'. $image .'" class="order-item-img"> <b class="order-item-title">'. $title .' </b> <small class="order-item-price">'. $items->total .'</small></li>';
					}
					
					$array['order_items'] = $order_items;
					$finalArray[] = $array;
				}
				$this->sendResponse($finalArray, trans('theme.data_found_success'));
			}
			$this->sendResponse([], trans('theme.data_found_success'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// Settings
	public function wishList(){
		
		if(! Auth::user()){ return redirect()->route('firstPage'); }
		
		try{
			$page 		= 'myWishList';
			$page_title = trans('theme.my_wish_list_title');
			
			return view('theme.myAccount.index', compact(['page','page_title']));
			
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	// Settings
	public function settings(){
		
		if(! Auth::user()){ return redirect()->route('firstPage'); }
		
		try{
			$page 		= 'profileSettings';
			$page_title = trans('theme.my_orders_title');
			$data 		= Restaurant::all();
			
			return view('theme.myAccount.index', compact(['data','page','page_title']));
			
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	// Update Profile
	public function updateProfile(Request $request){
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		$validator = Validator::make($request->all(), [
			'name'			=> 'required|min:3|max:55',
			'phone_number'	=> 'required|min:1|numeric',
			'email'			=> 'required|email|unique:users,email,'.$user->id,
		]);
		
		if($validator->fails()){
			$this->ajaxError([], $validator->errors()->first());
		}
		
		DB::beginTransaction();
		try{
			$query = User::where('id', $user->id)->update([
				'name'          => $request->name,
				'phone_number'  => $request->phone_number,
				'email'         => $request->email,
			]);
			if($query){
				DB::commit();
			}
			$this->sendResponse([], trans('common.update_success'));
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// ADDD TO CART
	public function ajax_saveAddress(Request $request){
		
		$validator = Validator::make($request->all(), [
			'address_type' 	=> 'required',
			'postal_code' 	=> 'required',
			'address' 		=> 'required',
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
			// INSERT INTO CART
			$insertData = [
				'user_id'    	=> $user->id,
				'address_type'	=> $request->address_type,
				'postal_code'   => $request->postal_code,
				'address'		=> $request->address,
				'country_id'	=> '53',
				'city_id'		=> '7',
			];
			if(Address::create($insertData)){
				DB::commit();
				$this->sendResponse([], trans('common.saved_success'));
			}
			
			DB::rollback();
			$this->ajaxError([], trans('common.save_failed'));
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
}