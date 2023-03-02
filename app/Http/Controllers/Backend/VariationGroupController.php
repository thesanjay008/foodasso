<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\Helpers\CommonHelper;
use App\Models\VariationGroup;

class VariationGroupController extends CommonController
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
        //$this->middleware('permission:variation_group-list', ['only' => ['index','show']]);
        //$this->middleware('permission:variation_group-create', ['only' => ['create','store']]);
        //$this->middleware('permission:variation_group-edit', ['only' => ['edit','update']]);
        //$this->middleware('permission:variation_group-delete', ['only' => ['destroy']]);
    }
  
	// ADD NEW
	public function index(){
		return view('backend.variation_groups.list');
	}

	// CREATE
	public function create(){

		$data = VariationGroup::all();
		return view('backend.variation_groups.add',compact('data'));
	}
  
	public function ajax(Request $request){
		try{
			// GET LIST
			$query = VariationGroup::orderBy('id','DESC')->get();
			if($query){
				foreach($query as $key=> $list){
					$query[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("variation_groups.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
											<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>
											</div>';
				}
				$this->sendResponse($query, trans('common.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}

	}
  
	// EDIT
	public function edit($id = null){
		$data = VariationGroup::find($id);
		return view('backend.variation_groups.update',compact('data'));
	}
  
	// STORE
	public function store(Request $request){
		
		$validator = Validator::make($request->all(), [
			'title'     => 'required|min:3|max:99',
			'status'	=> 'required',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		$user = Auth()->user();
 		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
	
		if($request->item_id){
			$validator = Validator::make($request->all(), [
				'item_id' => 'required',
			]);
			if($validator->fails()){
				$this->ajaxValidationError($validator->errors(), trans('common.error'));
			}
		}
	
		try{
			$data = [
				'title:en' => $request->title,
				'status'        => $request->status,
			];
			
			if($request->item_id){
				// UPDATE
				$addon_group  =  VariationGroup::find($request->item_id);
				$addon_group->fill($data);
				$return  =  $addon_group->save();
				
				if($return){
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				// CREATE
				$return = VariationGroup::create($data);
				if($return){
					$this->sendResponse([], trans('common.added_success'));
				}
			}
			
			$this->ajaxError([], trans('common.try_again'));
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
			$query = VariationGroup::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}