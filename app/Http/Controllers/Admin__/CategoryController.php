<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class CategoryController extends CommonController
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
    $this->middleware('permission:category-list', ['only' => ['index','show']]);
    $this->middleware('permission:category-create', ['only' => ['create','store']]);
    $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
    $this->middleware('permission:category-delete', ['only' => ['destroy']]);
	}
  
	// ADD NEW
	public function index(){
		return view('admin.categories.list');
	}

	// CREATE
	public function create(){

		return view('admin.categories.add');
	}
  
	public function ajax($id = null){
		try{
			// GET LIST
			$query = Category::orderBy('id','DESC')->get(); 
			if($query){
				foreach($query as $key=> $list){
					$query[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("categories.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
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
		$categories = Category::find($id);
		return view('admin.categories.update',compact('categories'));
	}
  
	// STORE
	public function store(Request $request){

		$validator = Validator::make($request->all(), [
			'title_en'   				 			 => 'required|min:3|max:99',	
			'description_en'   				 => 'required|min:3|max:99',	
			'status'             			 => 'required',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		$data =  $request->all();
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
				'title:en'       => $request->title_en,
				'description:en' => $request->description_en,
				'status'         => $request->status,
			];
			
			// // MEDIA UPLOAD
			// if(!empty($request->image)){
			// 	$data['image'] =  $this->saveMedia($request->file('image'));
			// }
			
			if($request->item_id){
				// UPDATE
				$category  =  Category::find($request->item_id);
				$category->fill($data);
				$return  =  $category->save();
				
				if($return){
					$this->sendResponse([], trans('category.updated_success'));
				}
			} else{
				// CREATE
				$return = Category::create($data);
				if($return){
					$this->sendResponse([], trans('category.added_success'));
				}
			}
			// $this->ajaxError([], trans('common.try_again'));

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
			$query = Category::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}