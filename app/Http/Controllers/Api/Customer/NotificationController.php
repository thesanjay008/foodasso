<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Resources\NotificationListResource;
use DB,Validator,Auth;

class NotificationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request) {
      
      $search = $request->search;
      $page   = $request->page ?? '0';
      $count  = $request->count ?? '10000';

      if ($page <= 0){ $page = 1; }
      $start  = $count * ($page - 1);

      $user = Auth::user();
      if(empty($user)){
        return $this->sendError('',trans('customer_api.invalid_user'));
      }

      try{

        $query = Notification::where(['user_id'=>$user->id])->orderBy('id')->offset($start)->limit($count)->get();
        if(empty($query)){
          return $this->sendArrayResponse('',trans('customer_api.data_found_empty'));
        }
        return $this->sendArrayResponse(NotificationListResource::collection($query),trans('customer_api.data_found_success'));
      }catch (\Exception $e) { 
        return $this->sendError('', $e->getMessage()); 
      }
    }

    // NOTIFICATION DETAILS
    public function show($id = null) {
      
      try{
        $query = Notification::where(['id'=>$request->notification_id])->first();
        return $this->sendResponse(new NotificationListResource($query),trans('customer_api.data_found_success'));
      }catch (\Exception $e) { 
        return $this->sendError('', $e->getMessage()); 
      }
    }
}
