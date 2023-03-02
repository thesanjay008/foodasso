<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;

use App\Models\Helpers\CommonHelper;
use App\Models\UserInfo;
use App\Models\User;
use App\Models\State;
use App\Models\City;
use App\Models\Wallet;
class UserManagementController extends CommonController
{   
	use CommonHelper;
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
		//$this->middleware('permission:user-management-list', ['only' => ['index','ajax','show']]);
		//$this->middleware('permission:user-management-create', ['only' => ['create','store']]);
		//$this->middleware('permission:user-management-edit', ['only' => ['edit','update']]);
		//$this->middleware('permission:user-management-delete', ['only' => ['destroy']]);
	}
  
	// LIST
	public function index($role = ''){
		try{			
			return view("backend.user-management.$role.list", compact(['role']));
			
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	public function create($role = ''){		
		$data 		= new \stdClass();
		$state 		= State::where('country_id', 1)->where('status','active')->get();
		
		return view("backend.user-management.$role.add", compact(['role', 'state']));
	}

	// AJAX LIST
	public function ajax_list(Request $request){
		$page     = $request->page ?? '1';
		$count    = $request->count ?? '10';
		$status    = $request->status ?? 'all';
		$search    = $request->search ?? '';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.invalid_user'));
		}
		
		try{
			$query = User::where('user_type', $request->role);			
					
			// SEARCH
			if($search != ""){
				$query->where(function($query) use ($search){
					$query->where('name', 'LIKE', "%{$search}%");
					$query->orWhere('phone_number', 'LIKE', "%{$search}%");
					$query->orWhere('email', 'LIKE', "%{$search}%");
				});
			}
			
			// Check Status
			if($status != 'all'){
				$query->where('status', $status);
			}

			$data = $query->orderBy('id', 'DESC')->offset($start)->limit($count)->get();
			
			if($data){
				foreach($data as $key=> $list){
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. url('backend/manage/'. $request->role .'/'.$list->id) .'"><i class="fa fa-eye"></i></a>
											<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>
											';
					if($request->role == "Customer"){
						$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. url('backend/manage/'. $request->role .'/'.$list->id) .'"><i class="fa fa-eye"></i></a>
											<button class="border-0 btn-transition btn btn-outline-danger"    onClick="notdelete()"><i class="fa fa-trash-alt"></i></button>';
					}
					else{
						$data[$key]->action .=  '</div>';
					}

					if($list->status == 'pending'){
						$data[$key]->status = '<select class="form-control change_status" id="'.$list->id.'">
											<option value="pending" selected>Pending</option>
											<option value="active">Active</option>
											<option value="inactive">Inactive</option>
											<option value="blocked">Block</option>
										  </select>';
					}
					if($list->status == 'active'){
						$data[$key]->status = '<select class="form-control change_status" id="'.$list->id.'">
											<option value="pending">Pending</option>
											<option value="active" selected>Active</option>
											<option value="inactive">Inactive</option>
											<option value="blocked">Block</option>
										  </select>';
					}
					if($list->status == 'inactive'){
						$data[$key]->status = '<select class="form-control change_status" id="'.$list->id.'">
											<option value="pending">Pending</option>
											<option value="inactive" selected>Inactive</option>
											<option value="active">Active</option>
											<option value="blocked">Block</option>
										  </select>';
					}
					if($list->status == 'blocked'){
						$data[$key]->status = '<select class="form-control change_status" id="'.$list->id.'">
											<option value="pending">Pending</option>					
											<option value="blocked" selected>Block</option>
											<option value="active">Active</option>
											<option value="inactive">Inactive</option>
										  </select>';
					}
					
					if($list->profile_image){ $data[$key]->image  = asset($list->profile_image); }else { $data[$key]->image  = asset(config('constants.DEFAULT_USER_IMAGE')); }
				}
				$this->sendResponse($data, trans('common.data_not_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// DETAILS PAGE
	public function show($role = null, $id = ''){
		try {			
			$data 		= User::find($id);
			$userInfo	= UserInfo::where('user_id',$id)->first();
			$state 		= State::where('country_id', 1)->where('status','active')->get();
			$city		= [];
			$wallet		= Wallet::firstOrCreate(['user_id'=>$id], ['user_id'=>$id, 'balance'=>'0']);
			
			if($userInfo){
				$city	= City::where('state_id',$userInfo->state_id)->where('status','active')->get();
			}
			
			if($role == 'Restaurant'){
				return view("backend.user-management.$role.edit", compact(['data','userInfo','wallet','state','city']));
			}
			else{
				return view("backend.user-management.$role.edit", compact(['data','userInfo','wallet','city']));
			}
			return redirect()->route('homePage');
		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	// STORE
	public function store(Request $request){
		
		$validator = Validator::make($request->all(), [
			'role'			=> 'required',
			'name'			=> 'required|min:3|max:99',
			'status'		=> 'required',
		]);
		if(empty($request->user_id))
		{
			$validator = Validator::make($request->all(), [
				'email'			=> 'required|email|unique:users,email',
				'phone_number'	=> 'required|digits:10|unique:users,mobile_number',
			]);
		}
		if($validator->fails()){
			$this->ajaxValidationError($validator->errors(), trans('common.error'));
		}
		if($request->password){
			$validator = Validator::make($request->all(), [
				'password'	=>  'required|min:8|max:12|',
			]);
			if($validator->fails()){
				$this->ajaxValidationError($validator->errors(), trans('common.error'));
			}
		}
		
		$user = User::where('id',$request->user_id)->first();
		if(empty($user->id)){
			// CHECK EMAIL EXIST OR NOT
			$email = User::where('email', $request->email)->whereNotIn('id', [$request->user_id])->first();
			if(!empty($email)){
				return $this->ajaxError('',trans('customer_api.email_already_exist'));
			}

			// CHECK MOBILE NO EXIST OR NOT
			$phone_number = User::where('mobile_number', $request->phone_number)->whereNotIn('id', [$request->user_id])->first();
			if(!empty($phone_number)){
				return $this->ajaxError('',trans('customer_api.phone_number_already_exist'));
			}
		}
		
		DB::beginTransaction();
		
		try{
			$data = [
				'user_type'		=> $request->role,
				'name'       	=> $request->name,
				'email'			=> $request->email,
				'mobile_number'	=> $request->phone_number,
				'status'	  	=> $request->status,
			];
			$userInfoData	= [];
			
			if($request->is_available != ''){
				$data['is_available'] = $request->is_available;
			}
			
			// MEDIA UPLOAD
			if(!empty($request->image) && $request->image != 'undefined'){
				$validator = Validator::make($request->all(), [
					'profile_image'	=> 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
				]);
				if($validator->fails()){
					$this->ajaxValidationError($validator->errors(), trans('common.error'));
				}
				$data['profile_image'] =  $this->saveMedia($request->file('image'));
			}
			if(!empty($request->password)){
				$data['password'] = Hash::make($request->password);
			}		
			
			
			if($request->role == 'Restaurant'){			
				$userInfoData['address'] 		= $request->address;
				$userInfoData['state_id']		= $request->state_id;
				$userInfoData['city_id']		= $request->city_id;
				$userInfoData['latitude'] 		= $request->latitude;
				$userInfoData['longitude']		= $request->longitude;
			}
			elseif($request->role == 'DeliveryBoy'){
				$userInfoData['document_type'] 	= $request->document_type;
				$userInfoData['start_time']		= $request->starttime;
				$userInfoData['end_time'] 		= $request->endtime;
				$userInfoData['dob'] 			= $request->dob;
				$userInfoData['city_id'] 		= $request->city_id;
				$userInfoData['state_id'] 		= $request->state_id;
				$userInfoData['latitude'] 		= $request->latitude;
				$userInfoData['longitude'] 		= $request->longitude;
				$userInfoData['address'] 		= $request->address;
				$userInfoData['organization_id']	= $request->organization;
				$userInfoData['is_occupie'] 		= $request->is_occupie;
			}
			
			// Update
			if(!empty($user->id)){
				// UPDATE
				$user->update($data);
				$return = UserInfo::where('user_id',$user->id)->update(['speciality'=>$request->speciality,'category_id'=>$request->category,'license_id'=> $request->license,'qualification'=>$request->qualification,'bio'=>$request->bio]);
				if($return){
					DB::commit();    
					$this->sendResponse([], trans('common.updated_success'));
				}
				
			} else{
				// CREATE
				$return = User::create($data);
				if($return){					
					$userInfoData['user_id'] = $return->id;
					UserInfo::create($userInfoData);
					DB::commit();
					$this->sendResponse([], trans('common.added_success'));
				}
			}
			DB::rollback();
			$this->ajaxError([], trans('common.try_again'));

		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	/**
	* --------------
	* Change Status
	* --------------
	*/
	public function change_status(Request $request){
		DB::beginTransaction();
		try {
			$query = User::where('id',$request->id)->update(['status'=>$request->status]);
			if($query){
			  DB::commit();
			  $this->sendResponse(['status'=>'success'], trans('common.updated_success'));
			}else{
			  DB::rollback();
			  $this->sendResponse(['status'=>'error'], trans('common.updated_error'));
			}
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	/**
	* --------------
	* DESTROY
	* --------------
	*/
	public function destroy(Request $request){
		$validator = Validator::make($request->all(), [
			'item_id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError($validator->errors(), trans('common.error'));
		}
		
		try{
			// DELETE
			$query = User::where(['id'=>$request->item_id])->delete();
			if($query){
				$this->sendResponse([], trans('common.delete_success'));
			}
			$this->sendResponse([], trans('common.delete_error'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}

	public function viewsubScription(Request $request){
		$validator = Validator::make($request->all(), [
			'id' => 'required',
		]);
		if($validator->fails()){
			$this->ajaxError($validator->errors(), trans('common.error'));
		}
		try{
			$subscription = UserRestaurantSubscription::where('user_id',$request->id)->get();
			$user = User::find($request->id);
			foreach($subscription as$key=>$ressub)
			{
				$restaurant = User::where(['id'=>$ressub->restaurant_id])->first();
				if(isset($restaurant->name) && $restaurant->name != "")
				{
					$subscription[$key]->name = $restaurant->name;
				}
				else
				{
					$subscription[$key]->name = "";
				}
				if($ressub->diet_plan_id)
				{
					$diet  = DietPlan::where('id',$ressub->diet_plan_id)->first();
					if(isset($diet->title) && $diet->title != "")
					{
						$subscription[$key]->dietplan_name = $diet->title;
					}
					else
					{
						$subscription[$key]->dietplan_name = "";
					}
				}
				else
				{
					$subscription[$key]->dietplan_name = "";
				}
			}
			$usedietsubscription = UserDietPlan::where('user_id',$request->id)->get();
			foreach($usedietsubscription as $key=>$diet)
			{
				$dietplan = DietPlan::where(['id'=>$diet->diat_plan_id])->first();
				if(isset($dietplan->title) && $dietplan->title != "")
				{
					$usedietsubscription[$key]->title = $dietplan->title;
				}
				else
				{
					$usedietsubscription[$key]->title = "";
				}
			}
			$datajson = [
				'restaurant'=>$subscription,
				'dietplan'=>$usedietsubscription,
			];
			$this->sendResponse($datajson, trans('common.data_found_success'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	//Vendor Assign Delivery Boy List
	public function deliveryboy_vendor_list(Request $request){
		try{
				$vendor = User::where(['user_type'=>$request->assignvendor,'status'=>'active'])->get();
				if($vendor){
					$this->sendResponse($vendor, trans('common.data_found_success'));
				}
			} catch (Exception $e) {
				$this->ajaxError([], $e->getMessage());
			}
	}
	
	/**
	* --------------
	* Update Wallet
	* --------------
	*/
	public function updateWallet(Request $request){
		$validator = Validator::make($request->all(), [
            'user_id' 	=> 'required',
            'title' 	=> 'required|min:3|max:99',
            'amount' 	=> 'required|max:55',
            'type' 		=> 'required|in:Deposit,Withdraw'
        ]);
        if($validator->fails()){
            return $this->sendValidationError([], $validator->errors()->first());       
        }
		
		DB::beginTransaction();
		try {
			$user = User::where('id', $request->user_id)->first();
			if(empty($user)){
				$this->ajaxError([], 'Invalid User');
			}
			// Update Wallet
			$return = CommonHelper::updateWallet($user, $request->title, $request->amount, $request->type);
			if($return){
				DB::commit();
				$this->sendResponse([], trans('common.updated_success'));
			}
			DB::rollback();
			$this->ajaxError([], trans('common.updated_error'));
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
}