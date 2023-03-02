<?php

namespace App\Http\Controllers\backend\Locations;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Country;
use App\Models\City;
use App\Models\Helpers\CommonHelper;
use App,Validator,Auth,DB,Storage;

class CityController extends CommonController
{
    use CommonHelper;


 	// List Page
    public function index() {
        return view('backend.locations.city.list');
	}
	
	// CREATE
	public function create(){
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		$states	= State::where('status', 'active')->get();
		return view('backend.locations.city.add', compact('states'));
	}
	
	// EDIT
	public function edit($id = null){
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		$data 		= City::find($id);
		$states 	= State::where('status', 'active')->get();
		
		return view('backend.locations.city.edit', compact('data', 'states'));
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
			$query = City::query();
			
			if(!empty($request->status) && $request->status != 'all'){
				$query->where('cities.status', $request->status);
			}
			if(!empty($request->state_id) && $request->state_id != ''){
				$query->where('cities.state_id', $request->state_id);
			}			
		
			// SEARCH
			if($request->search != ""){
				$query->select('cities.*','t2.title','cities.title as city_title')
				->join('states as t2','t2.id','=','cities.state_id')
				->where('t2.title','like','%'.$request->search.'%')
				->orWhere('cities.title','like','%'.$request->search.'%');
			}
			$data  = $query->orderBy('cities.id', 'DESC')->offset($start)->limit($count)->get();
			if($data){
				foreach($data as $key=> $list){
					if(!isset($list->city_title) && $list->city_title == "")
					{
						$data[$key]->city_title = 	$list->title;
					}
				$State = State::where('id',$list->state_id )->first();
				if($State) 
					{
						$data[$key]->state_name = $State->title;
					}
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("cities.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
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
  
	// STORE
	public function store(Request $request){
		
		$validator = Validator::make($request->all(), [
			'title'		=> 'required|min:3|max:99',
			'state_id'	=> 'required|min:1|max:21',
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
				'title'		=> $request->title,
				'state_id'	=> $request->state_id,
				'status'	=> $request->status
			];
				
			if(!empty($request->item_id)){
				if(City::where('id',$request->item_id)->update($data)){
					DB::commit();
					$this->sendResponse([], trans('common.updated_success'));
				}
			}else{
				if(City::create($data)){
					DB::commit();
					$this->sendResponse([], 'City added successfully');
				}
			}
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
  
	/**
	* Change Status.
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
			$query = City::where('id',$request->item_id)->update(['status'=>$request->status]);
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
			$query = City::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}