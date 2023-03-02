<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Models\Helpers\CommonHelper;
use App\Models\Restaurant;
use App\Models\Product;
use App\Models\MenuCategory;

class RestaurantController extends CommonController
{   
	use CommonHelper;
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
		//$this->middleware(['auth','vendor_approved','vendor_subscribed']);
		// $this->middleware('permission:vendor-product-edit', ['only' => ['edit','update']]);
	}
  
	// LIST
	public function index(){
		try{
			
			$page_title = trans('restaurants.update');
			$data 		= Restaurant::all();
			
			return view('theme.restaurents.list', compact(['data','page_title']));
			
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	// LIST
	public function ajax(Request $request){
		$searchValue = $request->search ? $request->search : '';
		
		try{
			
			$query = Restaurant::where('delete_at', null);
			// SEARCH
			if($request->search){
				$query->whereHas('translation',function($q) use ($searchValue){
					$q->where('title','like','%'.$searchValue.'%');
				});
			}
			$data = $query->get();
			
			if($data){
				$image = asset('public/' . config('constants.DEFAULT_IMAGE'));
				foreach($data as $key=> $list){
					$data[$key]['url'] = url('restaurants/'.$list->id);
					if($list->image){ $data[$key]['image'] = asset('public/' . $list->image); }else{ $data[$key]['image'] = $image; }
				}
				$this->sendResponse($data, trans('restaurant.data_found_success'));
			}
			$this->sendResponse([], trans('restaurant.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// DETAILS PAGE
	public function show($id = null){
		try {
			
			$page_title = trans('restaurants.update');
			$data 		= Restaurant::where('id',$id)->first();
			if($data){
				$menu = MenuCategory::select('menu_category.*')
						->join('products as t1', 't1.category_id', '=', 'menu_category.id')
						->where('t1.status', 'active')
						->where('t1.owner_id', $id)
						->groupBy('menu_category.id')
						->get();
				if(!empty($menu)){
					foreach($menu as $key=> $list){
						$menu[$key]['products'] = Product::where(['status'=>'active', 'category_id'=>$list->id, 'owner_id'=>$id])->get();
					}
				}
				return view('theme.restaurents.details', compact(['page_title','data','menu'])); 
			}
			
			//return redirect()->route('firstPage');
		  
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
}