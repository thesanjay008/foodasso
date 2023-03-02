<?php

namespace App\Http\Controllers\Api\customer;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Resources\NurseResource;
use App\Http\Resources\NurseDetailResource;
use App\Http\Resources\nurseAppointmentPreviewResource;
use App\Http\Resources\DependentBookingPrewResource;
use App\Models\NursingHome;
use App\Models\Nurse;
use App\Models\Booking;
use App\Models\Dependent;

class NurseController extends BaseController
{
    public function index(Request $request) {
    try{
      $nurse = NurseResource::collection(Nurse::where(['status'=>'active','nursing_home_id'=>$request->nursing_home_id])->paginate());
      if(count($nurse) > 0) {
        return $this->sendArrayResponse($nurse,trans('nurse.nurse_found'));
      }else{
         return $this->sendArrayResponse('',trans('nurse.nurse_not_found')); 
      }
    } catch (\Exception $e) { 
        return $this->sendError('', $e->getMessage());    
    	}
    }

   public function show($id = '') {
     try{
      $query = Nurse::where('id', $id)->first();
      
      if($query) {
        return $this->sendResponse(new NurseDetailResource($query), trans('customer_api.data_found_success'));
      }
      return $this->sendResponse($query, trans('customer_api.data_found_empty'));
    } catch (\Exception $e) {
      DB::rollback();
      return $this->sendError('', $e->getMessage());    
    }
  }

    public function getTimeSlot($interval, $date = '', $start_time = '', $end_time = '' ,$id = '',$type =''){
    date_default_timezone_set(env('DEFAULT_TIMEZONE'));
    $time = array();
    if($date < date('Y-m-d')){
      return $time;
    }
    if($date == date('Y-m-d')){
      
      if(strtotime($start_time) <= strtotime(date('H:i'))){
        $start_time = date('H') + 1 .':00';
      }
      
    }

    while(strtotime($start_time) <= strtotime($end_time)){
        $start = $start_time;
        $end = date('H:i',strtotime('+'.$interval.' minutes',strtotime($start_time)));
        $start_time = date('H:i',strtotime('+'.$interval.' minutes',strtotime($start_time)));
     
        if(strtotime($start_time) <= strtotime($end_time)){
          $query = Booking::where(['booking_date'=>$date, 'start_time'=>$start, 'end_time'=>$end,'booking_type_id'=>$id,'booking_type'=>$type, 'status'=>'Booked'])->first();
          if(empty($query)){
            $time[] = array('start'=>$start,'end'=>$end, 'time'=>$start .' - '. $end);
          }
        }
    }
    return $time;
  }
  
  public function availabilities(Request $request) {
    
    $validator = Validator::make($request->all(), [
      'id' => 'required|exists:nurses,id',
      'date' => 'required'
    ]);
    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());
    }

    $id   = $request->id;
    $date = $request->date;
    
    if(empty($id)){
      return $this->sendError('', trans('customer_api.invalid_id')); 
    }
    
    try{
      $query = Nurse::where('id', $id)->first();
      if($query) {
        $start_time = $query->start_time;
        $end_time   = $query->end_time;
        $type = 'Nurse';
        if(!empty($start_time) && !empty($end_time)){
          $slots = $this->getTimeSlot(60, $date,$start_time, $end_time,$id,$type);
          
          return $this->sendArrayResponse($slots, trans('customer_api.data_found_success'));
        }
        return $this->sendArrayResponse('', trans('customer_api.data_found_empty'));
      }
      return $this->sendArrayResponse($query, trans('customer_api.data_found_empty'));
    } catch (\Exception $e) {
      DB::rollback();
      return $this->sendError('', $e->getMessage());    
    }
  }

  // GET BOOKING PREVIEW SCREEN DATA
  public function appointmentPreview(Request $request) {
    $validator = Validator::make($request->all(), [
      'nurse_id' => 'required',
    ]);
    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());       
    }
    
    $user = Auth::user();
    if(empty($user)){
      return $this->sendError('',trans('customer_api.invalid_user'));
    }
    
    try{
      $query = Nurse::where('id', $request->nurse_id)->first();
      if($query) {
        $query->appointment_for = DependentBookingPrewResource::collection(Dependent::where(['user_id'=>$user->id])->get());
        return $this->sendResponse(new nurseAppointmentPreviewResource($query), trans('customer_api.data_found_success'));
      }
      return $this->sendResponse('', trans('customer_api.data_found_empty'));
    } catch (\Exception $e) {
      DB::rollback();
      return $this->sendError('', $e->getMessage());    
    }
  }

  // BOOK NURSE
  public function book(Request $request) {
    
    $user_type = 'Customer';

    $validator = Validator::make($request->all(), [
      'nurse_id'      => 'required|int|exists:nurses,id',
      'booking_date'  => 'required',
      'start_time'    => 'required',
      'end_time'      => 'required',
    ]);
    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());       
    }

    $user = Auth::user();
    if(empty($user)){
      return $this->sendError('',trans('customer_api.invalid_user'));
    }

    if($request->dependent_id){
      $user_type = 'Dependent';
    }
    

    try{
      $query = Nurse::where('id', $request->nurse_id)->first();
      if($query) {
        
        $data['owner_id']       = $query->nursing_home_id;
        $data['owner_type']     = 'HomeNursing';
        $data['booking_type']   = 'Nurse';
        $data['booking_type_id']= $query->id;
        $data['user_id']        = $user->id;
        $data['phone_no']       = $user->country_code .' '. $user->phone_number;
        $data['dependent_id']   = $request->dependent_id;
        $data['booking_date']   = $request->booking_date;
        $data['start_time']     = $request->start_time;
        $data['end_time']       = $request->end_time;
        $booking = Booking::create($data);

        if($booking){
          $success['id']           = (string) $query->id;
          $success['owner_type']   = (string) $booking->owner_type;;
          $success['user_id']      = (string) $user->id;
          $success['booking_type'] = (string) $booking->booking_type;
          $success['booking_type_id'] = (string) $booking->booking_type_id;
          $success['booking_date'] = (string) $request->booking_date;
          $success['start_time']   = (string) $request->start_time;
          $success['end_time']     = (string) $request->end_time;
          return $this->sendResponse($success, trans('customer_api.appointment_booked'));
        }
      }
      return $this->sendArrayResponse('', trans('customer_api.invalid_nurse'));
    } catch (\Exception $e) {
      DB::rollback();
      return $this->sendError('', $e->getMessage());    
    }
  }
}
