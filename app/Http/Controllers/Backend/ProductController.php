<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\Product;
use App\Models\MenuCategory;
use App\Models\AddonGroup;
use App\Models\MenuAddon;
use App\Models\Variation;
use App\Models\VariationGroup;
use App\Models\MenuVariation;

class ProductController extends CommonController
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
        $this->middleware('permission:res_menu-list', ['only' => ['index','show']]);
        $this->middleware('permission:res_menu-create', ['only' => ['create','store']]);
        $this->middleware('permission:res_menu-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:res_menu-delete', ['only' => ['destroy']]);
    }
  
	// ADD NEW
	public function index(){
		return view('backend.product.list');
	}

	// CREATE
	public function create(){
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		$categories	=  MenuCategory::where('owner_id', $user->id)->get();
		$addonGrps 	= AddonGroup::where('status','active')->get();
		$variations = VariationGroup::where('status','active')->get();
		if($variations){
			foreach($variations as $key=> $row){
				$variations[$key]->list = Variation::where(['group_id'=>$row->id, 'status'=>'active'])->get();
			}
		}
		return view('backend.product.add',compact('categories','addonGrps','variations'));
	}
  
	// LIST
	public function ajax(Request $request){
		$page     = $request->page ?? '1';
		$count    = $request->count ?? '10';
		$status    = $request->status ?? 'all';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			// GET LIST
			$query = Product::query();
			
			// STATUS
			if($status != 'all'){
				$query->where('status', $status);
			}
			
			$data  = $query->orderBy('id', 'DESC')->offset($start)->limit($count)->get();
			if($data){
				foreach($data as $key=> $list){
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("products.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
											<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>
											</div>';
					if($list->image){ $data[$key]->image  = asset($list->image); }else { $data[$key]->image  = asset(config('constants.DEFAULT_MENU_IMAGE')); }
				}
				$this->sendResponse($data, trans('product.data_found_success'));
			}
			$this->sendResponse([], trans('product.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
  
	// EDIT
	public function edit($id = null){
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		$categories  =  MenuCategory::where('owner_id', $user->id)->get();
		$addonGrps = AddonGroup::where('status','active')->get();
		$variations = Variation::where('status','active')->get();
		$product = Product::find($id);
		
		return view('backend.product.update',compact('product','categories', 'addonGrps','variations'));
	}
  
	// STORE
	public function store(Request $request){
		$validator = Validator::make($request->all(), [
			'title'       		=> 'required|min:3|max:99',
			//'description' 		=> 'required|min:3|max:10000',
			'price'				=> 'required|min:1|numeric',
			'menu_category_id'	=> 'required',
			'delivery_type'		=> 'required',
			'is_taxable'		=> 'required',
			'choice'			=> 'required',
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
				'title:en'         => $request->title,
				'description:en'   => $request->description,
				'price'            => $request->price,
				'menu_category_id' => $request->menu_category_id,
				'delivery_type'    => $request->delivery_type,
				'is_taxable'       => $request->is_taxable ? $request->is_taxable : 'no',
				'choice'           => $request->choice,
			];
			
			
			// MEDIA UPLOAD
			if(!empty($request->image) && $request->image != 'undefined'){
				$validator = Validator::make($request->all(), [
					'image'	=> 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
				]);
				if($validator->fails()){
					$this->ajaxValidationError($validator->errors(), trans('common.error'));
				}
				$data['image'] =  $this->saveMedia($request->file('image'));
			}

			if($request->item_id){
				// UPDATE
				$product  =  Product::find($request->item_id);
				if($request->image == 'undefined'){
					$data['image'] = $product->image;
				}
				$product->fill($data);
				$return  =  $product->save();
				
				if($return){
					$this->sendResponse([], trans('product.updated_success'));
				}

			} else{
				$data['owner_id'] = $user->id;
				
				// CREATE
				$return = Product::create($data);
				
				if($return){
					//Create product addon Group
					if($request->addon_groups){
						$groups = explode(',', $request->addon_groups);
						foreach ($groups as $group_id) {
							$insertData = ['menu_id' => $return->id, 'addon_group_id' => trim($group_id)];
							//MenuAddonGroup::create($data);
							MenuAddon::firstOrCreate($insertData);
						}
					}
					
					//Create product variation
					if($request->variations){
						print_r($request->variations); exit;
						$groups = explode(',', $request->variations);
						foreach ($groups as $group_id) {
							$data = ['product_id' => $return->id, 'variation_id' => $variation_id, 'price' => $price];
							echo print_r($data); exit;
							$result = MenuVariation::where(['product_id' => $return->id, 'variation_id' => $var_id])->first();
							if ($result){
								$result->fill($data)->save();
							}else{
								MenuVariation::create($data);
							}
						}
					}
					$this->sendResponse([], trans('product.added_success'));
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
			$query = Product::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}