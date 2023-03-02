<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use App\Models\MenuCategory;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class MenuCategoryController extends CommonController
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
        $this->middleware('permission:res_menu_category-list', ['only' => ['index','show']]);
        $this->middleware('permission:res_menu_category-create', ['only' => ['create','store']]);
        $this->middleware('permission:res_menu_category-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:res_menu_category-delete', ['only' => ['destroy']]);
    }
  
	// ADD NEW
	public function index(){
		return view('vendors.menu_categories.list');
	}

	// CREATE
	public function create(){

		$menu_categories  =  MenuCategory::all();
		return view('vendors.menu_categories.add',compact('menu_categories'));
	}
  
	public function ajax($id = null){
		try{
			// GET LIST
			$user = Auth()->user();
			$query = MenuCategory::where('owner_id',$user->id)->orderBy('id','DESC')->get(); 
			if($query){
				foreach($query as $key=> $list){
					$query[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("menu_categories.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
											<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>
											</div>';
				}
				$this->sendResponse($query, trans('menu_categories.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}

	}
  
	// EDIT
	public function edit($id = null){
		$menu_categories = MenuCategory::find($id);
		return view('vendors.menu_categories.update',compact('menu_categories'));
	}
  
	// STORE
	public function store(Request $request){
		
		$priority = $request->priority ? $request->priority : '';
		
		$validator = Validator::make($request->all(), [
			'title_in_english'		=>  'required|min:3|max:99',
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
				'title:en'	=> $request->title_in_english,
				'priority'	=> $priority,
				'status'	=> $request->status,
				'owner_id'	=> $user->id,
			];
			
			// // MEDIA UPLOAD
			// if(!empty($request->image)){
			// 	$data['image'] =  $this->saveMedia($request->file('image'));
			// }
			
			if($request->item_id){
				// UPDATE
				$menu_category  =  MenuCategory::find($request->item_id);
				$menu_category->fill($data);
				$return  =  $menu_category->save();
				
				if($return){
					$this->sendResponse([], trans('common.updated_success'));
				}
			} else{
				// CREATE
				$return = MenuCategory::create($data);
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
			$query = MenuCategory::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}