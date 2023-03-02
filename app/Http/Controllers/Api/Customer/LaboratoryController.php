<?php

namespace App\Http\Controllers\Api\customer;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Validator;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Resources\LaboratoryResource;
use App\Http\Resources\LaboratoryPackageResource;
use App\Http\Resources\LaboratoryPackageDetailResource;
use App\Models\Laboratory;
use App\Models\LaboratoryPackage;

class LaboratoryController extends BaseController
{
  public function index(Request $request) {

    $search = $request->search;
    $page   = $request->page ?? 1;
    $count  = $request->count ?? '5000';

    if ($page <= 0){ $page = 1; }
    $offset = $count * ($page - 1);
    DB::beginTransaction();
    try{
          $query = Laboratory::query();
          
          /* SEARCH */
          if($search){
            $query = $query->whereHas('translation',function($query) use ($search){
              $query->where('laboratory_name','like','%'.$search.'%');
            });
          }

          // FILTER BY PRICE
          if($request->subscription_price){
            $query = $query->join('laboratories_package', 'laboratories_package.laboratory_id','=', 'laboratories.id');
            $query->where('laboratories_package.price','<=', $request->subscription_price);
          }

          $query = $query->orderBy('laboratories.id', 'DESC')->offset($offset)->limit($count)->get();

          if(count($query)>0){
            return $this->sendArrayResponse(LaboratoryResource::collection($query), trans('customer_api.data_found_success'));
          }
          return $this->sendArrayResponse($query, trans('customer_api.data_found_empty'));
      }catch (\Exception $e) { 
        DB::rollback();
        return $this->sendError('', $e->getMessage()); 
      }
    }

    // MASTER DATA FOR FILTERS
    public function masterData() {
      try{

        $price = LaboratoryPackage::where('status', 'active')->max('price');
        
       
        $success['package_max_price'] = $price ? (string) $price : '0';
        return $this->sendResponse($success,trans('customer_api.data_found_success')); 
      } catch (\Exception $e) { 
        return $this->sendError('', $e->getMessage());
      }
    }

    // laboratory packages index
  	public function package_index(Request $request) {
      $search = $request->search;
      $page   = $request->page ?? 1;
      $count  = $request->count ?? '5000';

      if ($page <= 0){ $page = 1; }
      $offset = $count * ($page - 1);
      DB::beginTransaction();
        try{
          $query = LaboratoryPackage::query();
          /* SEARCH */
          if($search){
            $query = $query->whereHas('translation',function($query) use ($search){
              $query->where('package_name','like','%'.$search.'%');
            });
          }
          if($request->laboratory_id){
          $query->where('laboratory_id', $request->laboratory_id);
          }
          $query = $query->orderBy('id', 'DESC')->offset($offset)->limit($count)->get();

          if(count($query)>0){
            return $this->sendArrayResponse(LaboratoryPackageResource::collection($query), trans('customer_api.data_found_success'));
          }
          return $this->sendArrayResponse($query, trans('customer_api.data_found_empty'));
        }catch (\Exception $e) { 
        DB::rollback();
        return $this->sendError('', $e->getMessage()); 
      }
    }

    // laboratory pacakge detail
    public function package_show($package_id = '') {
      try{
    		if(!$package_id || $package_id == '') {
    			return $this->sendError('',trans('customer_api.data_found_empty'));
    		}

        $query = LaboratoryPackage::where('id', $package_id)->first();
        if(empty($query)) {
          return $this->sendResponse('',trans('customer_api.data_found_empty'));
        }
		    return $this->sendResponse(new LaboratoryPackageDetailResource($query),trans('customer_api.data_found_success')); 
      } catch (\Exception $e) { 
        return $this->sendError('', $e->getMessage());
      }
    }
}
