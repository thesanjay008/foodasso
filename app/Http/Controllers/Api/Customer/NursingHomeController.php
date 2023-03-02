<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Resources\NursingHomeResource;
use App\Http\Resources\NursingHomeDetailResource;
use App\Models\NursingHome;

class NursingHomeController extends BaseController
{
   public function index(Request $request) {
    
    $order_by = "id";
    $order    = "DESC";
    $search   = $request->search;
    $page     = $request->page ?? '1';
    $count    = $request->count ?? '5000';
    $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'en';

    if ($page <= 0){ $page = 1; }
    $start  = $count * ($page - 1);

    DB::beginTransaction();
    try{
        $query = NursingHome::select('nursing_home.*')->where('status','active');
        
        /* SEARCH */
        if($search){
          $query = $query->whereHas('translation',function($query) use ($search){
            $query->where('nursing_home_name','like','%'.$search.'%');
          });
        }

        // ORDER BY NAME
        if($request->order){
          $query = $query->join('nursing_home_translations', 'nursing_home_translations.nursing_home_id','=', 'nursing_home.id');
          $query->where('locale', $language);
          if($request->order == 'AtoZ'){
            $order_by = 'nursing_home_name';
            $order    = 'ASC';
          } else if($request->order == 'ZtoA'){
            $order_by = 'nursing_home_name';
            $order    = 'DESC';
          }
        }

        // NEARBY LIST
        if($request->show_nearest_first == 1){
          if(!empty($request->lalt) && !empty($request->long)){
            $query = $query->Distance($request->lalt, $request->long);
            $order_by = 'distance';
            $order    = 'ASC';
          }
        }

        $query = $query->orderBy($order_by, $order)->groupBy('nursing_home.id')->offset($start)->limit($count)->get();

        if(count($query)>0){
          return $this->sendArrayResponse(NursingHomeResource::collection($query), trans('customer_api.data_found_success'));
        }
        return $this->sendArrayResponse('', trans('customer_api.data_found_empty'));
      }catch (\Exception $e) { 
        DB::rollback();
        return $this->sendError('', $e->getMessage()); 
      }
    }

  public function show($id = null) {
    try{
      $query = NursingHome::where('id', $id)->first();
      
      if($query) {
        return $this->sendResponse(new NursingHomeDetailResource($query), trans('customer_api.data_found_success'));
      }
      return $this->sendResponse($query, trans('customer_api.data_found_empty'));
    } catch (\Exception $e) {
      DB::rollback();
      return $this->sendError('', $e->getMessage());    
    }
  }
}
