<?php

namespace App\Http\Controllers\theme;

use App\Http\Controllers\CommonController;
use Session;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Helpers\CommonHelper;
use App\Models\Product;
use App\Models\Cart;

class CartController extends CommonController
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
	
	/**
	* Cart List.
	* @return void
	*/
	public function index(Request $request){
		try{
			
			$user = Auth()->user();
			if(empty($user)){
				$cartData = Cart::with(['product'])->where('token', csrf_token())->get();
				if($cartData->count() > 0){
					DB::commit();
					$return['list'] = $cartData;
					$return['sub_total'] = number_format($cartData->sum('total'), 2, '.', '');
					$return['delivery_fee'] = '0.00';
					$return['tax'] = '0.00';
					$return['total'] = number_format($cartData->sum('total'), 2, '.', '');
					$this->sendResponse($return, trans('cart.data_found_success'));
				}
			}else{
				$cartData = Cart::with(['product'])->where('user_id', $user->id)->get();
				if($cartData->count() > 0){
					DB::commit();
					$return['list'] = $cartData;
					$return['sub_total'] = number_format($cartData->sum('total'), 2, '.', '');
					$return['delivery_fee'] = '0.00';
					$return['tax'] = '0.00';
					$return['total'] = number_format($cartData->sum('total'), 2, '.', '');
					$this->sendResponse($return, trans('cart.data_found_success'));
				}
			}
			
			$this->sendResponse([], trans('cart.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// ADDD TO CART
	public function ajax_add(Request $request){
		
		$validator = Validator::make($request->all(), [
			'item_id' 	=> 'required',
			'quantity' 	=> 'required|min:1|max:99',
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
			$quantity 	= $request->quantity ?? 1;
			$data 		= Product::where('id', $request->item_id)->first();
			
			if($data){

				if(empty($user)){
					$item_exist = Cart::where('token', csrf_token())->where('product_id',$data->id)->first();
				}else{
					$item_exist = Cart::where('user_id', $user->id)->where('product_id',$data->id)->first();
				}
				// INSERT INTO CART
				$insertData = [
					'order_type'    => $request->order_type,
					'table_id'    	=> $request->table_id,
					'product_id'    => $data->id,
					'title'   		=> $data->title,
					'quantity'      => $quantity,
					'price'         => $data->price,
					'total'         => $data->price * $quantity,
					'date'          => date('Y-m-d'),
				];
				if(empty($user)){
					$insertData['token'] = csrf_token();
				}else{
					$insertData['user_id'] = $user->id;
				}
				
				//Update quantity if item already exist in the cart
				if($item_exist){
					$item_exist->quantity 	= $quantity;
					$item_exist->price 		= $data->price;
					$item_exist->total 		= $data->price * $quantity;
					$item_exist->save();
				}
				else {
					Cart::create($insertData);
				}
				
				if(empty($user)){
					$cartData = Cart::where('token', csrf_token())->get();
				}else{
					$cartData = Cart::where('user_id', $user->id)->get();
				}
				if($cartData->count() > 0){
					DB::commit();
					$this->sendResponse($cartData, trans('cart.add_success'));
				}
			}
			DB::rollback();
			$this->ajaxError([], trans('cart.add_failed'));
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// LIST
	public function ajax_list(Request $request){
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			$cartData = Cart::with(['product'])->where('user_id', $user->id)->get();
			if($cartData){
				DB::commit();
				$return['list'] = $cartData;
				$return['sub_total'] = number_format($cartData->sum('total'), 2, '.', '');
				$return['delivery_fee'] = '0.00';
				$return['tax'] = '0.00';
				$return['total'] = number_format($cartData->sum('total'), 2, '.', '');
				$this->sendResponse($return, trans('cart.data_found_success'));
			}
			
			$this->sendResponse([], trans('cart.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// Delete Cart
	public function deleteCart(Request $request){
		$user = Auth()->user();
		if(empty($user)){
			//$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			if(empty($user)){
				$cartData = Cart::where('token', csrf_token())->where('id',$request->item_id)->delete();
			}else{
				$cartData = Cart::where('user_id', $user->id)->where('id',$request->item_id)->delete();
			}
			//$cartData = Cart::where([''=>$id, 'user_id', $user->id])->delete();
			if($cartData){
				DB::commit();
				$return['list'] = $cartData;
				$this->sendResponse($return, trans('cart.delete_success'));
			}
			
			$this->ajaxError([], trans('cart.delete_failed'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}