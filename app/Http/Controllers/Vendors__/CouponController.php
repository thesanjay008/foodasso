<?php

namespace App\Http\Controllers\Vendors;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class CouponController extends CommonController
{   
	use CommonHelper;
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	 public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:res_coupon-list', ['only' => ['index','show']]);
        $this->middleware('permission:res_coupon-create', ['only' => ['create','store']]);
        $this->middleware('permission:res_coupon-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:res_coupon-delete', ['only' => ['destroy']]);
    }
  
	// ADD NEW
	public function index(){
		return view('vendors.coupons.list');
	}

	// CREATE
	public function create(){
		$coupons  =  Coupon::all();
		return view('vendors.coupons.add',compact('coupons'));
	}
  
	public function ajax($id = null){
		try{
			// GET LIST
			$restaurant_id = Auth()->user()->restaurant->id;
			$query = Coupon::where('owner_id',$restaurant_id)->orderBy('id','DESC')->get(); 
			if($query){
				foreach($query as $key=> $list){
					$query[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("coupons.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
											<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>
											</div>';
				}
				$this->sendResponse($query, trans('coupons.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}

	}
  
	// EDIT
	public function edit($id = null){
		$coupons = Coupon::find($id);
		return view('vendors.coupons.update',compact('coupons'));
	}
  
	// STORE
	public function store(Request $request){
		if(isset($request->item_id))
		{
			$coupon_rule = 'required|min:3|max:6|unique:coupons,code,'.$request->item_id;
		}
		else
		{
			$coupon_rule = 'required|min:3|max:6|unique:coupons';
		}
		$validator = Validator::make($request->all(), [
			'title_in_english'   => 'required|min:3|max:99',
			'title_in_armenians' => 'required|min:3|max:99',
			'title_in_russia'    => 'required|min:3|max:99',
			'code'               => $coupon_rule,
			'discount'           => 'required|numeric',
			'discount_type'      => 'required',
			'start_date'         => 'required|date',
            'end_date' 			 => 'required|date|after:start_date',
			'status'             => 'required'
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		$data =  $request->all();
		$user = Auth()->user();
		$restaurant_id = Auth()->user()->restaurant->id;
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
	
		if(isset($request->item_id)){
			$validator = Validator::make($request->all(), [
				'item_id' => 'required',
			]);
			if($validator->fails()){
				$this->ajaxValidationError($validator->errors(), trans('common.error'));
			}
		}
	
		try{
			$data = [
				'title:en'      => $request->title_in_english,
				'title:hy'      => $request->title_in_armenians,
				'title:ru'      => $request->title_in_russia,
				'code'          => $request->code,
				'discount'      => $request->discount,
				'discount_type' => $request->discount_type,
				'start_date'    => date('Y-m-d',strtotime($request->start_date)),
				'end_date'      => date('Y-m-d',strtotime($request->end_date)),
				'status'        => $request->status,
				'owner_id'      => $restaurant_id,
				'user_id'       => $user->id,
			];
			
			if($request->item_id){
				// UPDATE
				$coupon  =  Coupon::find($request->item_id);
				$coupon->fill($data);
				$return  =  $coupon->save();
				
				if($return){
					$this->sendResponse([], trans('coupon.updated_success'));
				}
			} else{
				// CREATE
				$return = Coupon::create($data);
				if($return){
					$this->sendResponse([], trans('coupon.added_success'));
				}
			}


		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
  }
  
	// DESTROY
	public function destroy(Request $request){
		$validator = Validator::make($request->all(), [
			'item_id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError($validator->errors(), trans('common.error'));
		}
		
		try{
			// DELETE
			$query = Coupon::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}