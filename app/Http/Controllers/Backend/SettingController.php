<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\Helpers\CommonHelper;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserInfo;


class SettingController extends CommonController
{   
	use CommonHelper;
	/**
	* Create a new controller instance.
	* @return void
	*/
	public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:res_order-list', ['only' => ['index','show']]);
        $this->middleware('permission:res_order-create', ['only' => ['create','store']]);
        $this->middleware('permission:res_order-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:res_order-delete', ['only' => ['destroy']]);
    }
  
	// General Settings
	public function general_settings(){
		$data = Setting::where('status','active')->get();
		return view('backend.settings.general-settings',compact('data'));
	}
	
	
	// Page My Profile
	public function myProfile(){
		$user = auth()->user();
		
		$data 		= User::where(['id'=>$user->id])->first();
		$userInfo 	= UserInfo::where(['user_id'=>$user->id])->first();
		
		return view('backend.settings.my-profile',compact('data','userInfo'));
	}

	// Store Data
	public function store(Request $request){
		$validator = Validator::make($request->all(), [
			'site_name'			=> 'required|min:2|max:99',
			//'site_email' 		=> 'required|min:3|max:99',
			//'app_version'		=> 'required|min:1|max:99',
			//'copy_rights_year'	=> 'required|min:1|max:99',
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		DB::beginTransaction();
		try{
			$update = '0';
			foreach($request->all() as $key=> $value){
				
				$data = Setting::where('name',$key)->first();
				if(!empty($data) && $data->count() > 0){
					$data->value = $value;
					$return = $data->save();
					if($return){ DB::commit(); $update = '1'; }
				}
			}
			if($update == 1){
				$this->sendResponse([], trans('common.saved_success'));
			}
			
			$this->ajaxError([], trans('common.try_again'));
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	/**
	* Store QR.
	* @return void
	*/
	public function store_qr(Request $request){
		$validator = Validator::make($request->all(), [
			'file'	=> 'required|min:2|max:199',
		]);
		if($validator->fails()){
			$this->ajaxError('', $validator->errors()->first());
		}
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		DB::beginTransaction();
		try{
			$data = Setting::firstOrCreate(array('name' => 'qr_code'));
			if(!empty($data) && $data->count() > 0){
				$data->value = $request->file;
				$return = $data->save();
				if($return){
					DB::commit();
					$this->sendResponse([], trans('common.saved_success'));
				}
			}
			
			$this->ajaxError([], trans('common.try_again'));
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	/**
	* Store LOGO.
	* @return void
	*/
	public function store_logo(Request $request){
		$validator = Validator::make($request->all(), [
			'logo'	=> 'required|min:2|max:199',
		]);
		if($validator->fails()){
			$this->ajaxError('', $validator->errors()->first());
		}
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		DB::beginTransaction();
		try{
			
			$logo = '';
			
			// MEDIA UPLOAD
			if(!empty($request->logo) && $request->logo != 'undefined'){
				$validator = Validator::make($request->all(), [
					'logo'	=> 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
				]);
				if($validator->fails()){
					$this->ajaxError($validator->errors(), trans('common.error'));
				}
				$logo =  $this->saveMedia($request->file('logo'));
			}
			
			if(empty($logo)){
				$this->ajaxError([], trans('common.error'));
			}
		
			$data = Setting::firstOrCreate(array('name' => 'logo'));
			if(!empty($data) && $data->count() > 0){
				$data->value = $logo;
				$return = $data->save();
				if($return){
					DB::commit();
					$this->sendResponse([], trans('common.saved_success'));
				}
			}
			
			$this->ajaxError([], trans('common.try_again'));
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
}