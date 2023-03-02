<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\UserInfo;

class ProfileController extends CommonController
{   
	use CommonHelper;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	*--------------------------
	* View Profile Page
	*--------------------------
	*/
	public function index(){
	
		$user = Auth()->user();
 		if(empty($user)){
			return redirect()->route('login');
		}
		
		try{
			$page 		= 'profile';
			$page_title = 'Profile';
			$userInfo 	= UserInfo::where(['user_id'=>$user->id])->first();
			
			return view("backend.settings.profile", compact(['page', 'page_title', 'user', 'userInfo']));
			
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
  
	/**
	*--------------------------
	* View for Change Password
	*--------------------------
	*/
	public function change_password(){
		$page 		= 'change-password';
		$page_title = 'Change Password';
		return view('backend.settings.change-password',compact(['page', 'page_title']));
	}
	
	/**
	*--------------------------
	* Change Password
	*--------------------------
	*/
	public function ajax_change_password(Request $request){
	    
		$validator = Validator::make($request->all(), [
			'old_password'				=> 'required|min:3|max:99',
			'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
			'password_confirmation' => 'required|min:8'
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		DB::beginTransaction();
	    try {
			
			$user = User::findOrFail($user->id);
			if($user){
				if (Hash::check($request->old_password, $user->password)) {
					$user->fill(['password' => Hash::make($request->password)])->save();
					
					DB::commit();
					$this->sendResponse(['status'=>'success'], trans('common.updated_success'));
				}
				DB::rollback();
				$this->ajaxError('', trans('common.updated_faild'));
			}
	    } catch (Exception $e) {
	        DB::rollback();
	        $this->ajaxError([], $e->getMessage());
	    }
	}
		/**
	*--------------------
	* Change Password
	*--------------------
	*/
	public function ajax_update(Request $request){
	    $user = Auth()->user();
 		if(empty($user)){
			$this->sendUnauthorizedError([], trans('common.invalid_user'));
		}
		
		$validator = Validator::make($request->all(), [
			'name'			=> 'required|min:3|max:99',
			'email'			=> 'required|unique:users,email,'.$user->id,
			'phone_number'	=> 'required|unique:users,phone_number,'.$user->id,
		]);
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		
		DB::beginTransaction();
	    try {
			// Store Data
			$data = [
				'name'			=> $request->name,
				'email'			=> $request->email,
				'phone_number'	=> $request->phone_number,
			];
			
			// MEDIA UPLOAD
			if(!empty($request->image) && $request->image != 'undefined'){
				$validator = Validator::make($request->all(), [
					'image'	=> 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
				]);
				if($validator->fails()){
					$this->ajaxError($validator->errors()->first(), trans('common.error'));
				}
				$data['profile_image'] =  $this->saveMedia($request->file('image'));
			}
				
			$user = User::where('id', $user->id)->update($data);
			if($user){
				DB::commit();
				$this->sendResponse(['status'=>'success'], trans('common.updated_success'));
			}
			
			DB::rollback();
			$this->ajaxError('', trans('common.faild_to_updated'));
	    } catch (Exception $e) {
	        DB::rollback();
	        $this->ajaxError([], $e->getMessage());
	    }
	}
}