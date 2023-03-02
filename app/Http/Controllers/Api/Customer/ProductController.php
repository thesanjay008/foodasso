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
use App\Models\Product;
use App\Http\Resources\ProductListResource;

class ProductController extends BaseController
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
        $query = Product::query(); 
        
        /* SEARCH */
        if($search){
          $query = $query->whereHas('translation',function($query) use ($search){
            $query->where('title','like','%'.$search.'%');
          });
        }

        /* PRICE FILTERS */
        if($request->price){
          if($request->price == 'high_to_low'){
            $query = $query->orderBy('price', 'DESC');
          }else if($request->price == 'low_to_low'){
            $query = $query->orderBy('price', 'ASC');
          }
        }else{
          $query = $query->orderBy('id', 'DESC');
        }

        $query = $query->orderBy('id', 'DESC')->offset($offset)->limit($count)->get();

        if($query){
          return $this->sendArrayResponse(ProductListResource::collection($query), trans('customer_api.data_found_success'));
        }
        return $this->sendArrayResponse('', trans('customer_api.data_found_empty'));
    }catch (\Exception $e) { 
      DB::rollback();
      return $this->sendError('', $e->getMessage()); 
    }
  }

  public function categoryProducts(Request $request){
    
    $search = $request->search;
    $page   = $request->page ?? 1;
    $count  = $request->count ?? '10000';

    $validator = Validator::make($request->all(), [
      'category_id'  => 'required',
    ]);
    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());       
    }
    
    if ($page <= 0){ $page = 1; }
    $offset = $count * ($page - 1);

    try{
      $query = Product::query()->where(['category_id'=>$request->category_id]);
      
      /* SEARCH */
      if($search){
        $query = $query->whereHas('translation',function($query) use ($search){
          $query->where('title','like','%'.$search.'%');
        });
      }

      /* PRICE FILTERS */
      if($request->price){
        if($request->price == 'high_to_low'){
          $query = $query->orderBy('price', 'DESC');
        }else if($request->price == 'low_to_high'){
          $query = $query->orderBy('price', 'ASC');
        }
      }else{
        $query = $query->orderBy('id', 'DESC');
      }

      $query = $query->offset($offset)->limit($count)->get();
      
      if($query){
        return $this->sendArrayResponse(ProductListResource::collection($query), trans('customer_api.data_found_success'));
      }
      return $this->sendArrayResponse('', trans('customer_api.data_found_empty'));
    }catch (\Exception $e) {
      return $this->sendError('', $e->getMessage());
    }
  }
}
