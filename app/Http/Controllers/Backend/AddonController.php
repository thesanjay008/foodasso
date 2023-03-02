<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use App\Models\Addon;
use App\Models\AddonGroup;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class AddonController extends CommonController
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
        $this->middleware('permission:addon-list', ['only' => ['index','show']]);
        $this->middleware('permission:addon-create', ['only' => ['create','store']]);
        $this->middleware('permission:addon-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:addon-delete', ['only' => ['destroy']]);
    }
  
	// ADD NEW
	public function index(){
		return view('backend.addons.list');
	}

	// CREATE
	public function create(){

		$addon_groups  =  AddonGroup::where('status','active')->get();
		return view('backend.addons.add',compact('addon_groups'));
	}
  
	public function ajax($id = null){
		try{
			// GET LIST
			$user = Auth()->user();
			$query = Addon::orderBy('id','DESC')->get(); 
			if($query){
				foreach($query as $key=> $list){
					$query[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("addons.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
											<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>
											</div>';
				}
				$this->sendResponse($query, trans('addons.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}

	}
  
	// EDIT
	public function edit($id = null){
		$addons       = Addon::find($id);
		$addon_groups = AddonGroup::where('status','active')->get();
		return view('backend.addons.update',compact('addons','addon_groups'));
	}
  
	// STORE
	public function store(Request $request){
		
		$validator = Validator::make($request->all(), [
			'title'          => 'required|min:3|max:99',
			'addon_group_id' => 'required',
			'choice'         => 'required',
			'price'          => 'required|numeric',
			'status'         => 'required',
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
				'title:en'       => $request->title,
				'addon_group_id' => $request->addon_group_id,
				'price'          => $request->price,
				'choice'         => $request->choice,
				'status'         => $request->status,
			];
			
			// // MEDIA UPLOAD
			// if(!empty($request->image)){
			// 	$data['image'] =  $this->saveMedia($request->file('image'));
			// }
			
			if($request->item_id){
				// UPDATE
				$addon  =  Addon::find($request->item_id);
				$addon->fill($data);
				$return  =  $addon->save();
				
				if($return){
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				// CREATE
				$return = Addon::create($data);
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
			$query = Addon::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}