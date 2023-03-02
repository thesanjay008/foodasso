<?php

namespace App\Http\Controllers\Api\Customer;

use Validator,DB;
use Authy\AuthyApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use App\Models\UserInterestedTags;
use App\Models\Dependent;
use App\Models\Address;
use App\Models\MedicalReminder;
use App\Models\Booking;
use App\Models\DeviceDetail;
use App\Http\Resources\UserResource;
use App\Http\Resources\DependentListResource;
use App\Http\Resources\MyActivityDependentListResource;
use App\Http\Resources\DependentResource;
use App\Http\Resources\AddressListResource;
use App\Http\Resources\AddressResource;
use App\Http\Resources\MyActivityAppointmentListResource;
use App\Http\Resources\MyActivityMedicalReminderListResource;
use App\Http\Resources\AppointmentListResource;

class UserController extends BaseController
{
    /**
     * GET PROFILE
     *
     * @return \Illuminate\Http\Response
     */ 
    public function profile(Request $request)
    {
        DB::beginTransaction();
        try{
            
            //Get User Data
            $user_id = Auth::user()->id;
            if(empty($user_id)){
                return $this->sendError('',trans('customer_api.invalid_user'));
            }

            // GET USER DATA
            $user = User::where('id', $user_id)->first();
            return $this->sendResponse(new UserResource($user), trans('customer_api.data_found_success'));
        }catch(\Exception $e){
            DB::rollback();
            return $this->sendError('',trans('customer_api.data_found_empty'));
        }
    }

	 /**
     * PROFILE UPDATE
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|min:3|max:99',
            'gender'    => 'required|min:4|max:6',
            'dob'       => 'required',
            'phone_number' => 'required|min:6|max:15',
            'email'     => 'required|string|email|max:99',
            'country_code' => 'required|min:1|max:4',
        ]);
        if($validator->fails()){
            return $this->sendValidationError('', $validator->errors()->first());
        }

        $user = Auth::user()->id;
        if(empty($user)){
            return $this->sendError('',trans('customer_api.invalid_user'));
        }

        // EMAIL VALIDATION
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return $this->sendError('',trans('customer_api.invalid_email'));
        }
        
        // CHECK EMAIL EXIST OR NOT
        $email = User::where('email', $request->email)->whereNotIn('id', [$user])->first();
        if(!empty($email)){
            return $this->sendError('',trans('customer_api.email_already_exist'));
        }

        // CHECK MOBILE NO EXIST OR NOT
        $phone_number = User::where('phone_number', $request->phone_number)->whereNotIn('id', [$user])->first();
        if(!empty($phone_number)){
            return $this->sendError('',trans('customer_api.phone_number_already_exist'));
        }
       
        DB::beginTransaction();
		try{
			$query = User::where('id', $user)->update([
				'name'          => $request->name,
				'gender'        => $request->gender,
                'dob'           => date('Y-m-d', strtotime($request->dob)),
				'age'           => $request->age,
                'country_code'  => $request->country_code,
				'phone_number'  => $request->phone_number,
				'email'         => $request->email,
			]);
			if($query){
                DB::commit();

                //Get User Data
                $user = User::where('id', $user)->first();

                $success['id']               =  (string)$user->id;
                $success['gender']           =  $user->gender ? $user->gender : '';
                $success['age']              =  $user->dob ? (string) date_diff(date_create($user->dob), date_create('today'))->y : '';
                $success['dob']              =  $user->dob ? date('d-m-Y', strtotime($user->dob)) : '';
                $success['name']             =  $user->name;
                $success['email']            =  $user->email;
                $success['phone_number']     =  $user->phone_number;
                $success['status']           =  $user->status;
                $success['user_type']        =  $user->user_type;
				return $this->sendResponse($success, trans('customer_api.profile_update_success'));
			}else{
				DB::rollback();
				return $this->sendError('',trans('customer_api.profile_update_error'));
			}
		}catch(\Exception $e){
            DB::rollback();
			return $this->sendError('',trans('customer_api.profile_update_error'));
        }
    }

    /**
     * SAVE NOTIFICATION SETTINGS
     *
     * @return \Illuminate\Http\Response
     */
    public function savegeneralSettings(Request $request){
        $validator = Validator::make($request->all(),[
            'via_nitification' => 'max:1',
            'via_email' => 'max:1',
            'time_format' => 'max:1'
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }

        $user = Auth::user();
        if(empty($user)){
            return $this->sendResponse("", trans('customer_api.invalid_user'));
        }
        
        DB::beginTransaction();
        try{
            if($request->via_nitification != ''){
                $query = User::where('id', $user->id)->update(['noti_via_nitification' => $request->via_nitification]);
                if($query){
                    DB::commit();
                }
            }
            if($request->via_email != ''){
                $query = User::where('id', $user->id)->update(['noti_via_email' => $request->via_email]);
                if($query){
                    DB::commit();
                }
            }
            if($request->time_format != ''){
                $query = User::where('id', $user->id)->update(['time_format' => $request->time_format]);
                if($query){
                    DB::commit();
                }
            }
            //Get User Data
            $user = User::where('id', $user->id)->first();
            $success['via_nitification'] = $user->noti_via_nitification ? (string) $user->noti_via_nitification : '0';
            $success['via_email']        = $user->noti_via_email ? (string) $user->noti_via_email : '0';
            $success['time_format']      = $user->time_format ? (string) $user->time_format : '0';
            return $this->sendResponse($success,trans('customer_api.save_success'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    /**
     * GET SETTINGS
     *
     * @return \Illuminate\Http\Response
     */
    public function getgeneralSettings(Request $request){
        
        $user = Auth::user();
        if(empty($user)){
            return $this->sendResponse("", trans('customer_api.invalid_user'));
        }
        
        DB::beginTransaction();
        try{
            //Get User Data
            $user = User::where('id', $user->id)->first();
            $success['via_nitification'] = $user->noti_via_nitification ? (string) $user->noti_via_nitification : '0';
            $success['via_email']        = $user->noti_via_email ? (string) $user->noti_via_email : '0';
            $success['time_format']        = $user->time_format ? (string) $user->time_format : '0';
            return $this->sendResponse($success,trans('customer_api.data_found_success'));
        } catch (\Exception $e) {
          DB::rollback();
          return $this->sendError('', $e->getMessage());
        }
    }

	/**
	* SAVE INTERESTED TAGS
	*
	* @return \Illuminate\Http\Response
	*/
    public function save_tags(Request $request){
        $validator = Validator::make($request->all(),[
            'tag_ids' => 'required|min:1'
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }

        $user = Auth::user();
        if(empty($user)){
            return $this->sendResponse("", trans('customer_api.invalid_user'));
        }
        $tags = explode(',', $request->tag_ids);
        $insertArray = [];
        foreach($tags as $key=>$list){
          $insertArray[$key] = ['user_id'=>$user->id, 'tag_id'=>$list, 'created_at'=>Carbon::now()]; 
        }

        DB::beginTransaction();
        try{
            $query = UserInterestedTags::insert($insertArray);
            if($query){
                DB::commit();
                return $this->sendResponse('',trans('customer_api.save_success'));
            }
            return $this->sendResponse('',trans('customer_api.save_error'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    /**
    * GET DEPENDENT TYPES
    *
    * @return \Illuminate\Http\Response
    */
    public function get_dependent_types(Request $request){
        $user = Auth::user();
        if(empty($user)){
            return $this->sendResponse("", trans('customer_api.invalid_user'));
        }
        
        try{
            $query = [['title'=>'Father', 'value'=>'father'],['title'=>'Mother', 'value'=>'mother'],['title'=>'Sister', 'value'=>'sister'],['title'=>'Brother', 'value'=>'brother']];
            if($query){
                DB::commit();
                return $this->sendResponse($query,trans('customer_api.data_found_success'));
            }
            return $this->sendResponse('',trans('customer_api.data_found_empty'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    /**
    * SAVE DEPENDENTS
    *
    * @return \Illuminate\Http\Response
    */
    public function save_dependent(Request $request){
        $validator = Validator::make($request->all(),[
            'name'      => 'required|min:3|max:55',
            'gender'    => 'required|min:3|max:15',
            'dob'       => 'required|min:6|max:10',
            'dependent_type' => 'required|min:3|max:15'
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }

        $user = Auth::user();
        if(empty($user)){
            return $this->sendResponse("", trans('customer_api.invalid_user'));
        }
        
        DB::beginTransaction();
        try{
            if($request->id){
                $rowItem = Dependent::where(['id'=>$request->id, 'user_id'=>$user->id])->first();
                if($rowItem){
                    $rowItem->name            = $request->name;
                    $rowItem->gender          = $request->gender;
                    $rowItem->dob             = $request->dob;
                    $rowItem->dependent_type  = $request->dependent_type;
                    $rowItem->save();
                    DB::commit();
                    return $this->sendResponse('',trans('customer_api.save_success'));
                }
            }else{
                $insertData = ['user_id'=>$user->id, 'name'=>$request->name, 'gender'=>$request->gender, 'dob'=>$request->dob, 'dependent_type'=>$request->dependent_type];
                $query = Dependent::insert($insertData);
                if($query){
                    DB::commit();
                    return $this->sendResponse('',trans('customer_api.save_success'));
                }
            }
            DB::rollback();
            return $this->sendResponse('',trans('customer_api.save_error'));
        } catch (\Exception $e) {
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    /**
    * DELETE DEPENDENTS
    *
    * @return \Illuminate\Http\Response
    */
    public function delete_dependent(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required|exists:dependents,id',
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }
        
        $user = Auth::user();
        if(empty($user)){
            return $this->sendResponse("", trans('customer_api.invalid_user'));
        }

        DB::beginTransaction();
        try{
            $delete = Dependent::where(['id'=>$request->id])->delete();
            if($delete){
              DB::commit();
              return $this->sendResponse('', trans('customer_api.data_delete_success'));
            }
            DB::rollback();
            return $this->sendResponse('',trans('customer_api.data_delete_failed'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    /**
    * GET DEPENDENTS
    *
    * @return \Illuminate\Http\Response
    */
    public function get_dependents(Request $request){
        
        $search = $request->search;
        $page   = $request->page ?? 1;
        $count  = $request->count ?? '5000';

        if ($page <= 0){ $page = 1; }
        $offset = $count * ($page - 1);
        
        $user = Auth::user();
        if(empty($user)){
            return $this->sendError("", trans('customer_api.invalid_user'));
        }
        
        try{
            $query = Dependent::query();
            /* SEARCH */
            if($search){
                $query->where('name','like','%'.$search.'%');
            }
            $query->where('user_id','=',$user->id);
            $query = $query->orderBy('id', 'DESC')->offset($offset)->limit($count)->get();
            if($query){
                DB::commit();
                return $this->sendArrayResponse(DependentListResource::collection($query),trans('customer_api.data_found_success'));
            }
            return $this->sendArrayResponse('',trans('customer_api.data_found_empty'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    /**
    * GET DEPENDENT
    *
    * @return \Illuminate\Http\Response
    */
    public function show_dependent($id = ''){
        
        if(empty($id)){
            return $this->sendError("", trans('customer_api.invalid_id'));
        }
        
        try{
            $query = Dependent::where('id', $id)->first();
            if($query){
                DB::commit();
                return $this->sendResponse(new DependentResource($query),trans('customer_api.data_found_success'));
            }
            return $this->sendResponse('',trans('customer_api.data_found_empty'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    /**
    * GET ADDRESSES
    *
    * @return \Illuminate\Http\Response
    */
    public function addresses(Request $request){
        
        $user = Auth::user();
        if(empty($user)){
            return $this->sendError("", trans('customer_api.invalid_user'));
        }
        
        try{
            $query = Address::query();
            $query->where('owner_id','=',$user->id);
            $query->where('owner_type','=','Customer');
            $query = $query->orderBy('id', 'DESC')->get();
            if($query){
                DB::commit();
                return $this->sendArrayResponse(AddressListResource::collection($query),trans('customer_api.data_found_success'));
            }
            return $this->sendArrayResponse('',trans('customer_api.data_found_empty'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    /**
    * GET APPOINTMENTS
    *
    * @return \Illuminate\Http\Response
    */
    public function appointments(Request $request){
        
        $user = Auth::user();
        if(empty($user)){
            return $this->sendError("", trans('customer_api.invalid_user'));
        }
        
        try{
            $appointments = Booking::where(['user_id'=>$user->id])->orderBy('booking_date', 'ASC')->get();
            if($appointments){
                return $this->sendArrayResponse(AppointmentListResource::collection($appointments),trans('customer_api.data_found_success'));
            }
            return $this->sendArrayResponse('',trans('customer_api.data_found_empty'));
        } catch (\Exception $e) { 
          return $this->sendError('', $e->getMessage());
        }
    }

    /**
    * EDIT APPOINTMENT
    *
    * @return \Illuminate\Http\Response
    */
    public function editAppointment(Request $request){
        
        $validator = Validator::make($request->all(), [
          'appointment_id' => 'required|int|exists:bookings,id',
          'booking_date'   => 'required',
          'start_time'     => 'required',
          'end_time'       => 'required',
        ]);
        if($validator->fails()){
          return $this->sendValidationError('', $validator->errors()->first());       
        }

        $user = Auth::user();
        if(empty($user)){
          return $this->sendError('',trans('customer_api.invalid_user'));
        }
        
        DB::beginTransaction();
        try{
            $data = Booking::where(['id'=>$request->appointment_id])->first();
            if($data){
                $data->booking_date     = date('Y-m-d', strtotime($request->booking_date));
                $data->start_time       = $request->start_time;
                $data->end_time         = $request->end_time;
                $data->save();
                DB::commit();
                return $this->sendResponse('',trans('customer_api.update_success'));
            }
            return $this->sendResponse('',trans('customer_api.update_error'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    /**
    * CANCEL APPOINMENT
    *
    * @return \Illuminate\Http\Response
    */
    public function cancelAppointment(Request $request){
        
        $validator = Validator::make($request->all(), [
          'appointment_id' => 'required|int|exists:bookings,id',
        ]);
        if($validator->fails()){
          return $this->sendValidationError('', $validator->errors()->first());       
        }

        $user = Auth::user();
        if(empty($user)){
          return $this->sendError('',trans('customer_api.invalid_user'));
        }
        
        DB::beginTransaction();
        try{
            $data = Booking::where(['id'=>$request->appointment_id])->first();
            if($data){
                $data->status     = 'Cancelled';
                $data->save();
                DB::commit();
                return $this->sendResponse('',trans('customer_api.appoiment_cancelled_success'));
            }
            return $this->sendResponse('',trans('customer_api.appoiment_cancelled_error'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    /**
    * SAVE ADDRESS
    *
    * @return \Illuminate\Http\Response
    */
    public function save_address(Request $request){
        
        $validator = Validator::make($request->all(),[
            'address'       => 'required|min:3|max:1000',
            'city_id'       => 'required',
            'country_id'    => 'required',
            'postal_code'    => 'required|min:3|max:10'
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }

        $user = Auth::user();
        if(empty($user)){
            return $this->sendError("", trans('customer_api.invalid_user'));
        }

        $data = array(
            'address_type'=>'home',
            'city_id'=>$request->city_id,
            'country_id'=>$request->country_id,
            'address'=>$request->address,
            'postal_code'=>$request->postal_code,
            'owner_id'=>$user->id,
            'owner_type'=>'Customer',
        );
        
        try{
            $query = Address::create($data);
            if($query){
                DB::commit();
                return $this->sendArrayResponse(new AddressResource($query),trans('customer_api.data_saved_success'));
            }
            return $this->sendArrayResponse('',trans('customer_api.data_saved_error'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage());
        }
    }

    /**
    * DELETE ADDRESS
    *
    * @return \Illuminate\Http\Response
    */
    public function delete_address(Request $request){
        
        $validator = Validator::make($request->all(),[
            'address_id'       => 'required|exists:addresses,id',
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }

        $user = Auth::user();
        if(empty($user)){
            return $this->sendError("", trans('customer_api.invalid_user'));
        }
        
        try{
            $delete = Address::where(['id'=>$request->address_id, 'owner_id'=>$user->id])->delete();
            if($delete){
                DB::commit();
                return $this->sendArrayResponse('',trans('customer_api.data_delete_success'));
            }
            return $this->sendArrayResponse('',trans('customer_api.data_delete_error'));
        } catch (\Exception $e) {
          DB::rollback();
          return $this->sendError('', $e->getMessage());
        }
    }

    /**
    * MY ACTIVITY
    *
    * @return \Illuminate\Http\Response
    */
    public function myActivity(Request $request){
        
        $date = date('Y-m-d');
        $user = Auth::user();
        if(empty($user)){
            return $this->sendError("", trans('customer_api.invalid_user'));
        }
        
        try{

            $appointments = Booking::where(['user_id'=>$user->id, 'status'=>'Booked'])->where('booking_date','>=',$date)->orderBy('booking_date', 'DESC')->offset('0')->limit('2')->get();
            $reminders = MedicalReminder::where('owner_id',$user->id)->orderBy('created_at','DESC')->offset('0')->limit('2')->get();

            $dependent = Dependent::where('user_id','=',$user->id)->get();

            $return['name']         = $user->name;
            $return['age']          = $user->dob ? (string) date_diff(date_create($user->dob), date_create('today'))->y .' years' : '';
            $return['dependents']   = MyActivityDependentListResource::collection($dependent);
            $return['appointments'] = MyActivityAppointmentListResource::collection($appointments);
            $return['reminders']    = MyActivityMedicalReminderListResource::collection($reminders);
            if($return){
                return $this->sendArrayResponse($return,trans('customer_api.data_found_success'));
            }
            return $this->sendArrayResponse('',trans('customer_api.data_found_empty'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    private function prepare_response(MedicalReminder $reminder) {

        //Array For Response
        $weeks = ReminderDay::where('medical_reminder_id',$reminder->id)->pluck('days');
        $week  = array();

        foreach($weeks as $key => $value){
            
            $value  = (string)$value;
            $week[] = $value;

        }

        //Medication Title
        if($reminder->medication_taken == 0){
        
            $reminder->medication_taken_title = 'None';

        }else if($reminder->medication_taken == 1){
            
            $reminder->medication_taken_title = 'Before Meal';
            
        }else if($reminder->medication_taken == 2){
            
            $reminder->medication_taken_title = 'After Meal';
        }
        
        //Add days to response resource
        $reminder->days = $week;

        return $reminder;   
    }

    /**
    * UPDATE DEVICE TOKEN
    *
    * @return \Illuminate\Http\Response
    */
    public function update_deviceToken(Request $request){
        
        $validator = Validator::make($request->all(),[
            'device_token'  => 'required',
        ]);
        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }

        $user = Auth::user();
        if(empty($user)){
            return $this->sendError("", trans('customer_api.invalid_user'));
        }
        
        try{
            $data = DeviceDetail::where(['user_id'=>$user->id])->first();
            if($data){
                $data->device_token = $request->device_token;
                if($request->device_type){
                    $data->device_type = $request->device_type;
                }
                $data->save();
                DB::commit();
                return $this->sendResponse('',trans('customer_api.update_success'));
            }
            return $this->sendResponse('',trans('customer_api.update_error'));
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('', $e->getMessage());
        }
    }
}
