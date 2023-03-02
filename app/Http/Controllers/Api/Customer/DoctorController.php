<?php

namespace App\Http\Controllers\Api\customer;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Validator;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\DoctorDetailResource;
use App\Http\Resources\DoctorAvailabilityResource;
use App\Http\Resources\DoctorAppointmentPreviewResource;
use App\Http\Resources\DependentBookingPrewResource;
use App\Models\Hospital;
use App\Models\Doctor;
use App\Models\Booking;
use App\Models\DoctorDepartment;
use App\Models\Dependent;

class DoctorController extends BaseController
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
      $query = Doctor::query()->where(['status'=>'active']);
          
      /* SEARCH */
      if($search){
        $query = $query->whereHas('translation',function($query) use ($search){
          $query->where('doctor_name','like','%'.$search.'%');
        });
      }
      /* GET BY OWNER */
      if($request->owner_id){
        $query->where('owner_id',$request->owner_id);
      }
      /* GET BY DEPRTMENT */
      if($department_id){ 
        $query = $query->whereHas('department',function($query) use ($department_id){
          $query->where('department_id','=', $department_id);
        });
      }

      $query = $query->orderBy('id','DESC')->offset($start)->limit($count)->get();
      if($query->count() > 0) {
        return $this->sendArrayResponse(DoctorResource::collection($query), trans('customer_api.data_found_success'));
      }
      return $this->sendArrayResponse('', trans('customer_api.data_found_empty'));
    }
    catch (\Exception $e) {
      DB::rollback();
      return $this->sendError('', $e->getMessage());    
    }
  }

  public function show($id = null) {
    try{
      $query = Doctor::where('id', $id)->first();
      
      if($query) {
        return $this->sendResponse(new DoctorDetailResource($query), trans('customer_api.data_found_success'));
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
        $start = date('H:i',strtotime($start_time));
        $end = date('H:i',strtotime('+'.$interval.' minutes',strtotime($start_time)));
        $start_time = date('H:i',strtotime('+'.$interval.' minutes',strtotime($start_time)));
   
        if(strtotime($start_time) <= strtotime($end_time)){
          $query = Booking::where(['booking_date'=>$date, 'start_time'=>$start, 'end_time'=>$end,'booking_type_id'=>$id,'booking_type'=>$type,'status'=>'Booked'])->first();
          if(empty($query)){
            $time[] = array('start'=>$start,'end'=>$end, 'time'=>$start .' - '. $end);
          }
        }
    }
    return $time;
  }
  
  public function availabilities(Request $request) {
    
    $validator = Validator::make($request->all(), [
      'doctor_id' => 'required|exists:doctors,id',
      'date' => 'required'
    ]);
    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());
    }

    $id   = $request->doctor_id;
    
    try{
      $query = Doctor::where('id', $id)->first();
      if($query) {
        $start_time = $query->start_time;
        $end_time   = $query->end_time;
        $type       = 'Doctor';
        if(!empty($start_time) && !empty($end_time)){
          $slots = $this->getTimeSlot(60, $request->date, $start_time, $end_time,$id,$type);
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
      'doctor_id' => 'required|exists:doctors,id'
    ]);
    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());       
    }
    
    $user = Auth::user();
    if(empty($user)){
      return $this->sendError('',trans('customer_api.invalid_user'));
    }
    
    try{
      $query = Doctor::where('id', $request->doctor_id)->first();
      if($query) {
        $query->appointment_for = DependentBookingPrewResource::collection(Dependent::where(['user_id'=>$user->id])->get());
        return $this->sendResponse(new DoctorAppointmentPreviewResource($query), trans('customer_api.data_found_success'));
      }
      return $this->sendResponse('', trans('customer_api.data_found_empty'));
    } catch (\Exception $e) {
      DB::rollback();
      return $this->sendError('', $e->getMessage());    
    }
  }

  // BOOK DOCTOR
  public function book(Request $request) {
    
    $user_type = 'Customer';
    
    $validator = Validator::make($request->all(), [
      'doctor_id'     => 'required|int|exists:doctors,id',
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
      $query = Doctor::where('id', $request->doctor_id)->first();
      if($query) {
        
        $data['owner_id']       = $query->owner_id;
        $data['owner_type']     = $query->owner_type;
        $data['booking_type']   = 'Doctor';
        $data['booking_type_id']= $request->doctor_id;
        $data['user_id']        = $user->id;
        $data['phone_no']       = $user->country_code .' '. $user->phone_number;
        $data['dependent_id']   = $request->dependent_id;
        $data['booking_date']   = $request->booking_date;
        $data['start_time']     = $request->start_time;
        $data['end_time']       = $request->end_time;
        $booking = Booking::create($data);
        
        if($booking){
          
          $success['owner_id']       = (string) $query->owner_id;
          $success['owner_type']     = (string) $query->owner_type;
          $success['user_id']        = (string) $user->id;
          $success['user_type']      = (string) $user_type;
          $success['booking_date']   = $request->booking_date;
          $success['start_time']     = $request->start_time;
          $success['end_time']       = $request->end_time;
          return $this->sendResponse($success, trans('customer_api.appointment_booked'));

        }
        return $this->sendArrayResponse('', trans('customer_api.appointment_booking_failed'));
      }
      return $this->sendArrayResponse('', trans('customer_api.invalid_doctor'));
    } catch (\Exception $e) {
      DB::rollback();
      return $this->sendError('', $e->getMessage());    
    }
  }
}
