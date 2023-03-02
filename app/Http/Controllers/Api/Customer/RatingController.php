<?php
namespace App\Http\Controllers\Api\Customer;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\Helpers\CommonHelper;
use App\Models\Rating;
use App\Models\Doctor;
use App\Models\Nurse;
use App\Models\Clinic;
use App\Models\Hospital;
use App\Models\NursingHome;

class RatingController extends BaseController
{
    /**
     * QUESTION LIST
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
      
      $organigation   = '';
      $booking_person = '';
      $owner_type     = '';
      $owner_id       = '';

      $validator = Validator::make($request->all(), [
          'booking_type' => 'required',
          'booking_type_id' => 'required'
      ]);
      if($validator->fails()){
        return $this->sendValidationError('', $validator->errors()->first());
      }

      if($request->booking_type == 'Doctor'){
        $Doctor = Doctor::where('id',$request->booking_type_id)->first();
        if($Doctor){ $booking_person = $Doctor->doctor_name; $owner_id = $Doctor->owner_id; $owner_type = $Doctor->owner_type; }

      }else if($request->booking_type == 'Nurse'){
        $Nurse = Nurse::where('id',$request->booking_type_id)->first();
        if($Nurse){ $booking_person = $Nurse->nurse_name; $owner_id = $Nurse->nursing_home_id; $owner_type = 'HomeNursing'; }
      }

      try{

        switch ($owner_type) {
          case "Clinic":
            $Clinic = Clinic::where('id',$owner_id)->first();
            if($Clinic){ $organigation = $Clinic->clinic_name; }
            break;

          case "Hospital":
            $Hospital = Hospital::where('id',$owner_id)->first();
              if($Hospital){ $organigation = $Hospital->hospital_name; }
            break;

          case "HomeNursing":
            $NursingHome = NursingHome::where('id',$owner_id)->first();
            if($NursingHome){ $organigation = $NursingHome->nursing_home_name; }
            break;
          default:
            return $this->sendResponse('', trans('customer_api.something_went_wrong'));
        }

        $success = [
          'booking_type' => $request->booking_type,
          'booking_type_id' => $request->booking_type_id,
          'ratings' => [['id'=>'1', 'title'=>$organigation, 'rating'=>'1', ], ['id'=>'2', 'title'=>$booking_person, 'rating'=>'1']]
        ];
        return $this->sendArrayResponse($success,trans('customer_api.data_found_success'));
      }catch (\Exception $e) {
        return $this->sendError('', $e->getMessage());
      }
    }

    

    // SUBMIT REVIEW
    public function submit(Request $request){
      $validator = Validator::make($request->all(), [
          'booking_type'    => 'required',
          'booking_type_id' => 'required',
          'first_rating'    => 'required',
          'second_rating'   => 'required'
      ]);
      if($validator->fails()){
        return $this->sendValidationError('', $validator->errors()->first());
      }

      $user = Auth::user();
      if(empty($user)){
        return $this->sendError("", trans('customer_api.invalid_user'));
      }

      DB::beginTransaction();
      try{
        
        if($request->booking_type == 'Doctor'){
          $Doctor = Doctor::where('id',$request->booking_type_id)->first();
          if($Doctor){
            $data = [
              'owner_id'    => $Doctor->owner_id,
              'owner_type'  => $Doctor->owner_type,
              'user_id'     => $user->id,
              'rating'      => $request->first_rating,
            ];
            Rating::create($data);
          }
        }else if($request->booking_type == 'Nurse'){
          $Nurse = Nurse::where('id',$request->booking_type_id)->first();
          if($Nurse){ 
            $data = [
              'owner_id'    => $Nurse->nursing_home_id,
              'owner_type'  => 'HomeNursing',
              'user_id'     => $user->id,
              'rating'      => $request->first_rating,
            ];
            Rating::create($data);
          }
        }

        $data = [
          'owner_id'    => $request->booking_type_id,
          'owner_type'  => $request->booking_type,
          'user_id'     => $user->id,
          'rating'      => $request->second_rating,
        ];
        Rating::create($data);

        DB::commit();

        return $this->sendResponse('',trans('customer_api.review_submitted_success'));
      }catch (\Exception $e) { 
        DB::rollback();
        return $this->sendError('', $e->getMessage()); 
      }
    }
}
