<?php

namespace App\Http\Controllers\Vendors;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use App\Models\Variation;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class VariationController extends CommonController
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
        $this->middleware('permission:variation-list', ['only' => ['index','show']]);
        $this->middleware('permission:variation-create', ['only' => ['create','store']]);
        $this->middleware('permission:variation-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:variation-delete', ['only' => ['destroy']]);
    }
  
	// ADD NEW
	public function index(){
		return view('vendors.variations.list');
	}

	// CREATE
	public function create(){

		$variations  =  Variation::all();
		return view('vendors.variations.add',compact('variations'));
	}
  
	public function ajax($id = null){
		try{
			// GET LIST
			$user = Auth()->user();
			$query = Variation::orderBy('id','DESC')->get(); 
			if($query){
				foreach($query as $key=> $list){
					$query[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("variations.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
											<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>
											</div>';
				}
				$this->sendResponse($query, trans('variations.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}

	}
  
	// EDIT
	public function edit($id = null){
		$variations = Variation::find($id);
		return view('vendors.variations.update',compact('variations'));
	}
  
	// STORE
	public function store(Request $request){
		
		$validator = Validator::make($request->all(), [
			'variation_name'		=>  'required|min:3|max:99',
			'status'				=>  'required',
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
				'variation_name:en'	=> $request->variation_name,
				'status'	=> $request->status,
			];
			
			// // MEDIA UPLOAD
			// if(!empty($request->image)){
			// 	$data['image'] =  $this->saveMedia($request->file('image'));
			// }
			
			if($request->item_id){
				// UPDATE
				$variation  =  Variation::find($request->item_id);
				$variation->fill($data);
				$return  =  $variation->save();
				
				if($return){
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				// CREATE
				$return = Variation::create($data);
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
			$query = Variation::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}