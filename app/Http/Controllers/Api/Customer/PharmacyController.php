<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\Helpers\CommonHelper;
use App\Models\Pharmacy;
use App\Models\PharmacyMedicine;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Category;
use App\Models\ProductCategory;
use App\Models\PharmacyAnalytics;
use App\Http\Resources\PharmacyResource;
use App\Http\Resources\PharmacyDetailResource;
use App\Http\Resources\ProductListResource;

class PharmacyController extends BaseController
{
  /**
   * Insurance List
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request){
      
    $search = $request->search;
    $page   = $request->page ?? 1;
    $count  = $request->count ?? '10000';

    if ($page <= 0){ $page = 1; }
    $offset = $count * ($page - 1);

    try{
        $query = Pharmacy::select('pharmacies.*')->IsValid();
        /* SEARCH */
        if($search){
          $query = $query->whereHas('translation',function($query) use ($search){
            $query->where('pharmacy_name','like','%'.$search.'%');
          });
        }
        $query = $query->orderBy('id', 'DESC')->offset($offset)->limit($count)->get();

        if($query){
          return $this->sendArrayResponse(PharmacyResource::collection($query), trans('customer_api.data_found_success'));
        }
        return $this->sendArrayResponse('', trans('customer_api.data_found_empty'));
    }catch (\Exception $e) { 
      DB::rollback();
      return $this->sendError('', $e->getMessage()); 
    }
  }

  public function show(Request $request){
    $validator = Validator::make($request->all(), [
      'id'  => 'required|exists:pharmacies,id',
    ]);
    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());       
    }

    $order_by = 'id';
    $order    = 'DESC';
    /* PRICE FILTERS */
    if($request->price){
      if($request->price == 'high_to_low'){
        $order_by = 'price';
        $order    = 'DESC';
      }else if($request->price == 'low_to_high'){
        $order_by = 'price';
        $order    = 'ASC';
      }
    }

    try{
      
      $query = Pharmacy::where('id', $request->id)->first();

      if($query){

        // UPDATE ANALYTICS
        PharmacyAnalytics::create(['pharmacy_id'=>$query->id, 'date'=>date('Y-m-d')]);

        $productsList = Product::where(['owner_id'=> $query->id,'status'=>'active'])->limit('4')->get();
        $query->medicines = [];
        $query->medical_equipment = [];
        if($productsList->count()){
          
          $products = ProductListResource::collection($productsList);
          
          $medicines_cat = Category::select('categories.*')
            ->join('product_categories', 'product_categories.category_id','=', 'categories.id')
            ->where('product_categories.owner_id',$request->id)
            ->where('categories.status','active')
            ->where('categories.modules_type','medicine')
            ->groupBy('categories.id')
            ->get();

          $medical_equipment_cat = Category::select('categories.*')
            ->join('product_categories', 'product_categories.category_id','=', 'categories.id')
            ->where('product_categories.owner_id',$request->id)
            ->where('categories.status','active')
            ->where('categories.modules_type','medical_equipment')
            ->groupBy('categories.id')
            ->get();

          $medicines_cat_final   = [];
          $medical_equipment_cat_final = [];

          foreach($medicines_cat as $key=> $list){
            $products = Product::where(['category_id'=> $list['id'], 'owner_id'=> $query->id,'status'=>'active'])->limit('4')->orderBy($order_by, $order)->get();

            if($products->count()){
              $medicines_cat_final[] = [
                'id'=>$list['id'],
                'title'=>$list['title'],
                'total_product_count'=>(string)$count = Product::where(['category_id'=> $list['id'], 'owner_id'=> $query->id,'status'=>'active'])->get()->count(),
                'products'=>ProductListResource::collection($products),
              ];
            }
          }

          foreach($medical_equipment_cat as $key=> $list){
            $products = Product::where(['category_id'=> $list['id'], 'owner_id'=> $query->id,'status'=>'active'])->limit('4')->orderBy($order_by, $order)->get();
            if($products->count()){
              $medical_equipment_cat_final[] = [
                'id'=>$list['id'],
                'title'=>$list['title'],
                'total_product_count'=>(string)$count = Product::where(['category_id'=> $list['id'], 'owner_id'=> $query->id,'status'=>'active'])->get()->count(),
                'products'=>ProductListResource::collection($products),
              ];
            }
          }

          $query->medicines         = $medicines_cat_final;
          $query->medical_equipment = $medical_equipment_cat_final;
        }
        
        $query->quantity_in_cart  = Cart::where(['user_id'=>$request->user_id])->get()->sum("quantity");
        $query->products_in_cart  = Cart::where(['user_id'=>$request->user_id])->get()->count();

        return $this->sendResponse(new PharmacyDetailResource($query), trans('customer_api.data_found_success'));
      }
      return $this->sendResponse('', trans('customer_api.data_found_empty'));
    }catch (\Exception $e) {
      return $this->sendError('', $e->getMessage());
    }
  }


  public function show_new(Request $request){
    $validator = Validator::make($request->all(), [
      'id'  => 'required|exists:pharmacies,id',
    ]);
    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());       
    }

    $order_by = 'id';
    $order    = 'DESC';
    /* PRICE FILTERS */
    if($request->price){
      if($request->price == 'high_to_low'){
        $order_by = 'price';
        $order    = 'DESC';
      }else if($request->price == 'low_to_high'){
        $order_by = 'price';
        $order    = 'ASC';
      }
    }

    try{
      
      $query = Pharmacy::where('id', $request->id)->first();

      if($query){
        $productsList = Product::where(['owner_id'=> $query->id,'status'=>'active'])->limit('4')->get();
        $query->medicines = [];
        $query->medical_equipment = [];
        if($productsList->count()){
          
          $products = ProductListResource::collection($productsList);
          
          $medicines_cat         = [['id'=>'1', 'title'=>'Antipyretic'], ['id'=>'2', 'title'=>'Diuretics']];
          $medical_equipment_cat = [['id'=>'3', 'title'=>'Traction equipment']];

          $medicines_cat_final   = [];
          $medical_equipment_cat_final = [];

          foreach($medicines_cat as $key=> $list){
            $products = Product::where(['category_id'=> $list['id'], 'owner_id'=> $query->id,'status'=>'active'])->limit('4')->orderBy($order_by, $order)->get();

            if($products->count()){
              $medicines_cat_final[] = [
                'id'=>$list['id'],
                'title'=>$list['title'],
                'total_product_count'=>(string)$products->count(),
                'products'=>ProductListResource::collection($products),
              ];
            }
          }

          foreach($medical_equipment_cat as $key=> $list){
            $products = Product::where(['category_id'=> $list['id'], 'owner_id'=> $query->id,'status'=>'active'])->limit('4')->orderBy($order_by, $order)->get();
            if($products->count()){
              $medical_equipment_cat_final[] = [
                'id'=>$list['id'],
                'title'=>$list['title'],
                'total_product_count'=>(string)$products->count(),
                'products'=>ProductListResource::collection($products),
              ];
            }
          }

          $query->medicines         = $medicines_cat_final;
          $query->medical_equipment = $medical_equipment_cat_final;
        }
        
        $query->quantity_in_cart  = Cart::where(['user_id'=>$request->user_id])->get()->sum("quantity");
        $query->products_in_cart  = Cart::where(['user_id'=>$request->user_id])->get()->count();

        return $this->sendResponse(new PharmacyDetailResource($query), trans('customer_api.data_found_success'));
      }
      return $this->sendResponse('', trans('customer_api.data_found_empty'));
    }catch (\Exception $e) {
      return $this->sendError('', $e->getMessage());
    }
  }  
}
