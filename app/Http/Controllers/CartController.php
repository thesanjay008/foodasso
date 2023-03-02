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
	
	// ADDD TO CART
	public function ajax_add(Request $request){
		
		$quantity 	= $request->quantity ?? 1;
		
		$validator = Validator::make($request->all(), [
			'item_id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError([], trans('common.error'));
		}
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.login_to_continue'));
		}
		
		DB::beginTransaction();
		try{
			$data = Product::where('id', $request->item_id)->first();
			
			if($data){
				
				$item_exist = Cart::where('user_id', $user->id)->where('product_id',$data->id)->first();

				// INSERT INTO CART
				$insertData = [
					'user_id'    	=> $user->id,
					'product_id'    => $data->id,
					'customer_id'   => $user->id,
					'title'   		=> $data->title,
					'quantity'      => $quantity,
					'price'         => $data->price,
					'total'         => $data->price * $quantity,
					'date'          => date('Y-m-d'),
				];

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
				
				$cartData = Cart::where('user_id', $user->id)->get();
				if($cartData){
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
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			$cartData = Cart::where([''=>$id, 'user_id', $user->id])->delete();
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
}