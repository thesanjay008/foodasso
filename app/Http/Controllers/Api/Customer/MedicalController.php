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
use App\Models\Helpers\CommonHelper;
use App\Models\MedicalRoom;
use App\Models\MedicalEquipment;
use App\Http\Resources\MedicalResource;
use App\Http\Resources\MedicalEquipmentResource;

class MedicalController extends BaseController
{
    /**
     * Insurance List
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        $search = $request->search;
        $start  = '0';
        $count  = '10000';

        DB::beginTransaction();
        try{
            $query = MedicalRoom::query();
            
            if($request->order_by){
              //$list->orderBy('insurance_company_name', $request->order_by);
            }

            /* SEARCH */
            if($search){
              $query = $query->whereHas('translation',function($query) use ($search){
                $query->where('insurance_company_name','like','%'.$search.'%');
              });
            }
            $query = $query->orderBy('id')->offset($start)->limit($count)->get();
            return $this->sendArrayResponse($query, trans('customer_api.data_found_success'));
        }catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }
    
    public function show(Request $request){
      try{
        if ($request->id == null){
          return $this->sendError('',trans('ins_package.id_not_found'));
        }
        $ins_package = MedicalResource::collection(MedicalRoom::where('ins_com_id',$request->id)->paginate());
        if(count($ins_package)>0) {
          return $this->sendPaginateResponse($ins_package,trans('ins_package.ins_package_found'));
        }else{
          return $this->sendPaginateResponse('',trans('ins_package.ins_package_not_found')); 
        }
      }catch (\Exception $e) { 
          return $this->sendError('', $e->getMessage()); 
        }
    }

    public function equipments(Request $request){
    $search = $request->search;
    $page   = $request->page;
    $count  = '10000';

    if ($page <= 0){ $page = 1; }
    $offset = $count * ($page - 1);

    DB::beginTransaction();
    try{
        $query = MedicalEquipment::query();
         /* SEARCH */
          if($search){
            $query = $query->whereHas('translation',function($query) use ($search){
              $query->where('medical_equipment_name','like','%'.$search.'%');
            });
          }
          $query = $query->where('status','active')->orderBy('id', 'DESC')->offset($offset)->limit($count)->get();

          if(count($query)>0){
            return $this->sendResponse(MedicalEquipmentResource::collection($query), trans('customer_api.data_found_success'));
          }
          return $this->sendResponse('', trans('customer_api.data_found_empty'));
      }catch (\Exception $e) { 
        DB::rollback();
        return $this->sendError('', $e->getMessage()); 
      }
    }
}
