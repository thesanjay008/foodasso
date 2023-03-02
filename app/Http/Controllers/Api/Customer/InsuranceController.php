<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\User;
use App\Models\CustomerPassword;
use App\Models\Helpers\CommonHelper;
use App\Models\InsuranceCompany;
use App\Models\InsurancePackage;
use App\Http\Resources\InsuranceCompanyResource;
use App\Http\Resources\InsurancePackageResource;
use App\Http\Resources\InsurancePackageDetailResource;

class InsuranceController extends BaseController
{
    /**
     * Insurance List
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
    
        $order_by = "id";
        $order    = "DESC";
        $start    = '0';
        $count    = '10000';
        $search   = $request->search;

        DB::beginTransaction();
        try{
            $query = InsuranceCompany::select('insurance_company.*')->where('insurance_company.status','active');
            
            /* SEARCH */
            if($search){
              $query = $query->whereHas('translation',function($query) use ($search){
                  $query->where('insurance_company_name','like','%'.$search.'%');
              });
            }

            // WHEN FILTER APPLY
            if($request->subscription_price || $request->subscription_period || $request->dentist_coverage || $request->birth_coverage != ''){
              $query = $query->join('ins_packages', 'ins_packages.ins_com_id','=', 'insurance_company.id');
            }

            // PRICE FILTER
            if($request->subscription_price){
              $query->where('ins_packages.price','<=', $request->subscription_price);
            }

            // SUBSCRIPTION TIME FILTER
            if($request->subscription_period){
              $query->where('ins_packages.package_duration','=', $request->subscription_period);
            }

            // DENTIS COVERAGE FILTERS
            if($request->dentist_coverage){
              $query->where('ins_packages.dentist_coverage','=', $request->dentist_coverage);
            }

            // DENTIS COVERAGE FILTERS
            if($request->birth_coverage != ''){
              $query->where('ins_packages.birth_grace_period','=', $request->birth_coverage);
            }




            $query = $query->orderBy($order_by, $order)->offset($start)->limit($count)->get();
            if($query){
              return $this->sendArrayResponse(InsuranceCompanyResource::collection($query), trans('customer_api.data_found_success'));
            }
        }catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    // MASTER DATA FOR FILTERS
    public function masterData() {
      try{

        $package = array();
        $price          = InsurancePackage::where('status', 'active')->max('price');
        $subscriptions  = InsurancePackage::select('package_duration')->where('status', 'active')->groupBy('package_duration')->get();
        if($subscriptions->count() > 0){
          foreach($subscriptions as $list){
              $package[] = array('title'=>$list->package_duration . ' Year/s', 'value'=>$list->package_duration);
          }
        }
        
        $success['package_max_price'] = $price ? (string) $price : '0';
        $success['subscriptions']     = $package;
        return $this->sendResponse($success,trans('customer_api.data_found_success')); 
      } catch (\Exception $e) { 
        return $this->sendError('', $e->getMessage());
      }
    }

    public function packages(Request $request){

      $search = $request->search;
      $page   = $request->page ?? 1;
      $count  = $request->count ?? '10000';
      $insurance_company_id  = $request->company_id;

      if ($page <= 0){ $page = 1; }
      $offset = $count * ($page - 1);
      
      DB::beginTransaction();
        try{

          $query = InsurancePackage::query();

          /* Filter For Package Duration*/
          if(isset($request->years_of_subscription) && $request->years_of_subscription != null){

             $query->where('package_duration','like','%'.$request->years_of_subscription.'%');

          }

          /* Filter For Max Price*/
          if(isset($request->max_price) && $request->max_price != null){

             $query->where('price','<=', DB::raw($request->max_price));

          }

          /* Filter For Dentist Coverage*/
          if(isset($request->dentist_coverage) && $request->dentist_coverage != null){


             $query->where('dentist_coverage', $request->dentist_coverage);

          }

          /* Filter For Birth Grace Period*/
          if(isset($request->birth_grace_period) && $request->birth_grace_period != null){


             $query->where('birth_grace_period', $request->birth_grace_period);

          }

          /* SEARCH */
          if($search){
            $query = $query->whereHas('translation',function($query) use ($search){
              $query->where('package_name','like','%'.$search.'%');
            });
          }
          if($request->insurance_company_id){
          $query->where('ins_com_id', $request->insurance_company_id);
          }
          $query = $query->where('status','active')->orderBy('id', 'DESC')->offset($offset)->limit($count)->get();

          if(count($query)>0){
            return $this->sendArrayResponse(InsurancePackageResource::collection($query), trans('customer_api.data_found_success'));
          }
          return $this->sendArrayResponse('', trans('customer_api.data_found_empty'));
        }catch (\Exception $e) { 
        DB::rollback();
          return $this->sendError('', $e->getMessage()); 
      }
    }

    public function showpackage($package_id = ''){
      try{
        if(!$package_id || $package_id == '') {
          return $this->sendError('',trans('customer_api.data_found_empty'));
        }
        $query = InsurancePackage::where('id', $package_id)->first();
        if(empty($query)) {
          return $this->sendResponse('',trans('customer_api.data_found_empty'));
        }
        return $this->sendResponse(new InsurancePackageDetailResource($query),trans('customer_api.data_found_success'));
      } catch (\Exception $e) {
        return $this->sendError('', $e->getMessage());
      }
    }
}
