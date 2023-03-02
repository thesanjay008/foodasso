<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use App\Models\User;
use App\Models\Table;
use App\Models\Booking;

class CustomerController extends CommonController
{   
	use CommonHelper;
	
	public function __construct()
	{
		$this->middleware('auth');
	}

	// List of Customers
	public function index(){
		$page_title    	= '';
		return view('backend.customers.list', compact('page_title'));
	}
  
	// SHOW
	public function show($id = null){
		$page_title    	= '';
		$data 			= Booking::find($id);
		return view('backend.customers.show',compact('page_title', 'data'));
	}

	/**
	* Ajax List Customers.
	* @return void
	*/
	public function ajax()
	{
		try{
			// GET LIST
			$query = User::where('user_type','Customer')->orderBy('id','DESC')->get();
			if($query){
				foreach($query as $key=> $list){
					$query[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("customers.show",$list->id) .'"><i class="fa fa-eye"></i></a>
											</div>';
					
					$status = '';
					if($list->status == 'active'){
						$status = "<select class='form-control status' id='$list->id'>
										<option value='active'>Active</option>
										<option value='inactive'>Inactive</option>
										<option value='blocked'>Block</option>
									</select>";
					}
					if($list->status == 'inactive'){
						$status = "<select class='form-control status' id='$list->id'>
										<option value='inactive'>Inactive</option>
										<option value='active'>Active</option>
										<option value='blocked'>Block</option>
									</select>";
					}
					if($list->status == 'pending'){
						$status = "<select class='form-control status' id='$list->id'>
										<option value='pending'>Pending</option>
										<option value='active'>Active</option>
										<option value='blocked'>Block</option>
									</select>";
					}
					if($list->status == 'blocked'){
						$status = "<select class='form-control status' id='$list->id'>
										<option value='blocked'>Blocked</option>
										<option value='active'>Active</option>
									</select>";
					}
					
					$query[$key]->status = $status;
				}
				$this->sendResponse($query, trans('common.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	

	/**
	* Change Table Status.
	* @return void
	*/
	public function change_status(Request $request){
		
		DB::beginTransaction();
		try {
			$query = User::where('id',$request->id)->update(['status'=>$request->status]);
			if($query){
			  DB::commit();
			  $this->sendResponse(['status'=>'success'], trans('common.updated_success'));
			}else{
			  DB::rollback();
			  $this->sendResponse(['status'=>'error'], trans('common.updated_error'));
			}
			
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
}