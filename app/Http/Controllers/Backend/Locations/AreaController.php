<?php

namespace App\Http\Controllers\backend\Locations;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\City;
use App\Models\Area;
use App\Models\Helpers\CommonHelper;
use App,Validator,Auth,DB,Storage;

class AreaController extends CommonController
{
    use CommonHelper;


 	// List Page
    public function index() {
        return view('backend.locations.area.list');
	}
	
	// CREATE
	public function create(){
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		$cities	= City::where('status', 'active')->get();
		return view('backend.locations.area.add', compact('cities'));
	}
	
	// EDIT
	public function edit($id = null){
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		$data 		= Area::find($id);
		$cities 	= City::where('status', 'active')->get();
		
		return view('backend.locations.area.edit', compact('data', 'cities'));
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
			$query = Area::query();
			
			if(!empty($request->status) && $request->status != 'all'){
				$query->where('areas.status', $request->status);
			}
			
			// SEARCH
			if($request->search != ""){
				$query->select('areas.*','t2.title','areas.title as area_title')
				->join('cities as t2','t2.id','=','areas.city_id')
				->where('t2.title','like','%'.$request->search.'%')
				->orWhere('areas.title','like','%'.$request->search.'%');
			}

			$data  = $query->orderBy('areas.id', 'DESC')->offset($start)->limit($count)->get();
			if($data){
				foreach($data as $key=> $list){
					if(!isset($list->area_title) && $list->area_title == "")
					{
						$data[$key]->area_title = 	$list->title;
					}

					$City = City::where('id',$list->city_id )->first();
					if($City) 
						{
							$data[$key]->City_name = $City->title;
						}
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("areas.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
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
			'title'			=> 'required|min:3|max:99',
			'city_id'		=> 'required|min:1|max:21',
			'postal_code' 	=> 'required|min:2|max:12',
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
				'title'			=> $request->title,
				'city_id'		=> $request->city_id,
				'postal_code'	=> $request->postal_code,
				'status'		=> $request->status
			];
				
			if(!empty($request->item_id)){
				if(Area::where('id',$request->item_id)->update($data)){
					DB::commit();
					$this->sendResponse([], trans('common.updated_success'));
				}
			}else{
				if(Area::create($data)){
					DB::commit();
					$this->sendResponse([], 'Area added successfully');
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
			$query = Area::where('id',$request->item_id)->update(['status'=>$request->status]);
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
			$query = Area::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}