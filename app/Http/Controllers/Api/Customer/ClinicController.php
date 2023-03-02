<?php

namespace App\Http\Controllers\Api\customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Validator;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Resources\ClinicResource;
use App\Http\Resources\ClinicDetailResource;
use App\Http\Resources\ClinicListResource;
use App\Models\Hospital;
use App\Models\Clinic;
use App\Models\ClinicDepartment;

class ClinicController extends BaseController
{
  public function index(Request $request) {

      $search = $request->search;
      $department_id = $request->department_id ?? '';
      $page   = $request->page ?? 1;
      $count  = $request->count ?? '10000';

      if ($page <= 0){ $page = 1; }
       $start = $count * ($page - 1);
      DB::beginTransaction();
      try{
          $query = Clinic::query();
          /* SEARCH */
          if($search){
            $query = $query->whereHas('translation',function($query) use ($search){
              $query->where('clinic_name','like','%'.$search.'%');
            });
          }
          if($department_id){ 
            $query = $query->whereHas('department',function($query) use ($department_id){
              $query->where('department_id','=', $department_id);
            });
          }
          $query = $query->orderBy('id','DESC')->offset($start)->limit($count)->get();
        if($query->count() > 0){
            return $this->sendArrayResponse(ClinicListResource::collection($query), trans('customer_api.data_found_success'));
          }
          return $this->sendArrayResponse('', trans('customer_api.data_found_empty'));
      }catch (\Exception $e) { 
        DB::rollback();
        return $this->sendError('', $e->getMessage()); 
      }
    }

    public function show($id = null){
      try{
        $query = Clinic::where('id', $id)->first();
        
        if($query){
          return $this->sendResponse(new ClinicDetailResource($query), trans('customer_api.data_found_success'));
        }
        return $this->sendResponse('', trans('customer_api.data_found_empty'));

      }catch (\Exception $e) {
        return $this->sendError('', $e->getMessage());
      }
    }

}
