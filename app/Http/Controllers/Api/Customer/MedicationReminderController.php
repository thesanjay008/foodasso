<?php

namespace App\Http\Controllers\Api\customer;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Validator;
use DB,Settings;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Resources\MedicalReminderListResource;
use App\Http\Resources\MedicalReminderDetailResource;
use App\Models\MedicalReminder;
use App\Models\ReminderDay;
use Auth;


class MedicationReminderController extends BaseController

{
	public function add_medical_reminder(Request $request) {
    	
	    $validator = Validator::make($request->all(), [

      		'title'            => 'required|max:50',
      		'dosage'           => 'required|max:50',
      		'time'             => 'required',
      		'medication_taken' => 'required|in:0,1,2',
      		'notes'   		   => 'sometimes|nullable|max:250',
      		'days'   		   => 'sometimes|nullable|',

        ]);

	    if($validator->fails()){

	      return $this->sendValidationError('', $validator->errors()->first());

	    }

	    DB::beginTransaction();
	    try{

	    	//Add values to DB table empty fields
		    $user 				 = Auth::user();
		    $data 				 = $request->all();
		    $data['date']        = date("Y-m-d");
		    $data['owner_type']  = 'customer';
		    $data['owner_id']    = $user->id;    

		    //Add data to table
		    $medical = new MedicalReminder();
		    $medical->fill($data);

		    if($medical->save()){


		    	if($request->days != null){

		    		//Turn days string into array
		    		$days   = explode(',',$request->days);
	         		
	         		//Add reminder days table entry for each selected day
	                foreach ($days as $key => $value) {

	                    $reminder_day                      =  new ReminderDay();
	                    $reminder_day->medical_reminder_id =  $medical->id;
	                    $reminder_day->days                =  $value;

	                    $reminder_day->save();
	                }
		    	}

		    	DB::commit();
		    	return $this->sendResponse('',trans('medical_reminder.add_reminder_success'));

		    }else{

		    	DB::rollback();
		    	return $this->sendError('', trans('medical_reminder.error'));
		    }

	    }catch(\Exception $e){

	    	DB::rollback();
	    	return $this->sendError('', $e->getMessage());
	    }
	}

	public function index(Request $request) {
    	
    	DB::beginTransaction();
	    try{
	    	
	    	$user      = Auth::user();
	    	$reminders = MedicalReminder::where('owner_id',$user->id)->orderBy('created_at','DESC')->get();

	    	if($reminders->count() > 0){

	    		foreach($reminders as $reminder){

	    			$reminder = $this->prepare_response($reminder);
	    		}

	    		return $this->sendArrayResponse(MedicalReminderListResource::collection($reminders),trans('medical_reminder.reminder_list_success'));

	    	}else if($reminders->count() == 0){

	    		return $this->sendArrayResponse('',trans('medical_reminder.reminder_list_empty'));

	    	}else{

	    		return $this->sendArrayError('',trans('medical_reminder.error'));
	    	}
	    	
		}catch (\Exception $e) { 

		  	return $this->sendArrayError('', $e->getMessage());    
		}
	}


	public function reminder_details(Request $request) {

		$validator = Validator::make($request->all(), [

      		'medical_reminder_id'       => 'required|exists:medical_reminders,id'

        ]);

	    if($validator->fails()){

	      return $this->sendValidationError('', $validator->errors()->first());

	    }
    	
    	DB::beginTransaction();
	    try{
	    	
	    	$user     = Auth::user();
	    	$reminder = MedicalReminder::where(['owner_id'=> $user->id,'id' => $request->medical_reminder_id])->first();

	    	if($reminder != null){

	    		$reminder = $this->prepare_response($reminder);

	    		return $this->sendResponse(new MedicalReminderDetailResource($reminder),trans('medical_reminder.reminder_detail_success'));

	    	}else{

	    		return $this->sendError('',trans('medical_reminder.reminder_detail_empty'));
	    	}
	    	
		}catch (\Exception $e) { 

		  	return $this->sendArrayError('', $e->getMessage());    
		}
	}

	public function edit_medical_reminder(Request $request) {
    	
	    $validator = Validator::make($request->all(), [

	    	'medical_reminder_id'       => 'required|exists:medical_reminders,id',
      		'title'            			=> 'required|max:50',
      		'dosage'           			=> 'required|max:50',
      		'time'            			=> 'required',
      		'medication_taken' 			=> 'required|in:0,1,2',
      		'notes'   		   			=> 'sometimes|nullable|max:250',
      		'days'   		   			=> 'sometimes|nullable|',

        ]);

	    if($validator->fails()){

	      return $this->sendValidationError('', $validator->errors()->first());

	    }

	    DB::beginTransaction();
	    try{

	    	$user     = Auth::user();
	    	$reminder = MedicalReminder::where(['owner_id'=> $user->id,'id' => $request->medical_reminder_id])->first();
	 
		    //Add data to table
		    $reminder->fill($request->all());

		    if($reminder->save()){
		    	if($request->days != null){

		    		//Delete Old days value if exists 
		    		ReminderDay::where('medical_reminder_id',$reminder->id)->delete();

		    		//Turn days string into array
		    		$days   = explode(',',$request->days);
	         		
	         		//Add reminder days table entry for each selected day
	                foreach ($days as $key => $value) {
	                    $reminder_day                      =  new ReminderDay();
	                    $reminder_day->medical_reminder_id =  $reminder->id;
	                    $reminder_day->days                =  $value;
	                    $reminder_day->save();
	                }

	               $reminder = $this->prepare_response($reminder);
			    }

		    	DB::commit();
		    	return $this->sendResponse(new MedicalReminderDetailResource($reminder),trans('medical_reminder.edit_reminder_success'));

		    }else{
		    	DB::rollback();
		    	return $this->sendError('', trans('medical_reminder.error'));
		    }

	    }catch(\Exception $e){
	    	DB::rollback();
	    	return $this->sendError('', $e->getMessage());
	    }	
	}

	public function delete_reminder(Request $request) {

		$validator = Validator::make($request->all(), [

      		'medical_reminder_id'       => 'required|exists:medical_reminders,id'

        ]);

	    if($validator->fails()){

	      return $this->sendValidationError('', $validator->errors()->first());

	    }
    	
    	DB::beginTransaction();
	    try{
	    	
	    	$user     = Auth::user();
	    	$reminder = MedicalReminder::where(['owner_id'=> $user->id,'id' => $request->medical_reminder_id])->first();

	    	if($reminder != null){

	    		$reminder_days = ReminderDay::where('medical_reminder_id',$reminder->id)->get();

	    		if($reminder_days->count() > 0){

	    			foreach ($reminder_days as $day) {

	    				$day->delete();
	    			}
	    		}

	    		$reminder->delete();
	    		DB::commit();
	    		return $this->sendResponse('',trans('medical_reminder.reminder_delete_success'));

	    	}else{


	    		DB::rollback();
	    		return $this->sendError('',trans('medical_reminder.reminder_delete_error'));
	    	}
	    	
		}catch (\Exception $e) { 

		  	return $this->sendArrayError('', $e->getMessage());    
		}
	}

	public function toggle_status(Request $request) {

	

		$validator = Validator::make($request->all(), [

      		'medical_reminder_id'       => 'required|exists:medical_reminders,id',
      		'status'                    => 'required|in:active,inactive'

        ]);

	    if($validator->fails()){

	      return $this->sendValidationError('', $validator->errors()->first());

	    }
    	
    	DB::beginTransaction();
	    try{
	    	
	    	$user     = Auth::user();
	    	$reminder = MedicalReminder::where(['owner_id'=> $user->id,'id' => $request->medical_reminder_id])->first();

	    	if($reminder != null){

	    		$reminder->status = $request->status;
	    		$reminder->save();

	    		DB::commit();

	    		//Reminder List
	    		$reminders =  MedicalReminder::where(['owner_id'=> $user->id])->orderBy('created_at','DESC')->get();

	    		foreach ($reminders as $reminder) {
	    			
	    			$reminder = $this->prepare_response($reminder);

	    		}


	    		return $this->sendArrayResponse(MedicalReminderListResource::collection($reminders),trans('medical_reminder.toggle_status_success'));

	    	}else{


	    		DB::rollback();
	    		return $this->sendError('',trans('medical_reminder.toggle_status_error'));
	    	}
	    	
		}catch (\Exception $e) { 

		  	return $this->sendArrayError('', $e->getMessage());    
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
		if($reminder->medication_taken == 1){
		
			$reminder->medication_taken_title = 'None';

		}else if($reminder->medication_taken == 0){
			
			$reminder->medication_taken_title = 'Before Meal';
			
		}else if($reminder->medication_taken == 2){
			
			$reminder->medication_taken_title = 'After Meal';
		}
		
	   	//Add days to response resource
	   	$reminder->days = $week;

	   	return $reminder;	
	}
	
}
