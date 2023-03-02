<?php

namespace App\Http\Controllers\Backend\Locations;

use App\Http\Controllers\CommonController;

use App,Validator,Auth,DB,Storage;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Helpers\CommonHelper;

class CountryController extends CommonController
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
        //$this->middleware('permission:product-list', ['only' => ['index','show']]);
        //$this->middleware('permission:product-create', ['only' => ['create','store']]);
        //$this->middleware('permission:product-edit', ['only' => ['edit','update']]);
        //$this->middleware('permission:product-delete', ['only' => ['destroy']]);
    }
  
	// List Page
	public function index(){
		return view('backend.locations.country.list');
	}
  
	// LIST
	public function ajax_list(Request $request){
		$page     = $request->page ?? '1';
		$count    = $request->count ?? '10';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			// GET LIST
			$query = Country::query();
			
			if(!empty($request->status) && $request->status != 'all'){
				$query->where('status', $request->status);
			}
			
			// SEARCH
			if($request->search != ""){
				$query->where('title','like','%'.$request->search.'%');
			}
			
			$data  = $query->orderBy('id', 'DESC')->offset($start)->limit($count)->get();
			if($data){
				foreach($data as $key=> $list){
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("countries.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
											<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>
											</div>';
					
					$status_array = ['active'=>'', 'inactive'=>''];
					if($list->status == 'active') { $status_array['active'] = 'selected'; }
					if($list->status == 'inactive') { $status_array['inactive'] = 'selected'; }
					$data[$key]->status = "<select class='form-control status' id='$list->id'>
										<option value='active' 	". $status_array['active'] .">Active</option>
										<option value='inactive'". $status_array['inactive'] .">Inactive</option>
									</select>";
				}
				$this->sendResponse($data, trans('common.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}

	// CREATE
	public function create(){
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		return view('backend.locations.country.add');
	}
	
	// EDIT
	public function edit($id = null){
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		$data = Country::find($id);
		return view('backend.locations.country.edit',compact('data'));
	}
  
	// STORE
	public function store(Request $request){
		
		$validator = Validator::make($request->all(), [
			'title'			=> 'required|min:3|max:99',
			'iso_code'		=> 'required|min:2|max:12',
			'calling_code'	=> 'required|min:1|max:6',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		DB::beginTransaction();
		try{
			
			$data = [
				'title' 		=> $request->title,
				'iso_code' 		=> $request->iso_code,
				'calling_code' 	=> $request->calling_code,
				'currency_code' => $request->currency_code,
				'currency' 		=> $request->currency,
				'currency_symbol' => $request->currency_symbol,
				'status' 		=> $request->status
			];
				
			if(!empty($request->item_id)){
				if(Country::where('id',$request->item_id)->update($data)){
					DB::commit();
					$this->sendResponse([], trans('common.updated_success'));
				}
			}else{
				if(Country::create($data)){
					DB::commit();
					$this->sendResponse([], 'Country added successfully');
				}
			}
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
  
	/**
	* Change Table Status.
	* @return void
	*/
	public function change_status(Request $request){
		$validator = Validator::make($request->all(), [
			'item_id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError($validator->errors(), trans('common.error'));
		}
		
		DB::beginTransaction();
		try {
			$query = Country::where('id',$request->item_id)->update(['status'=>$request->status]);
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
			$query = Country::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}