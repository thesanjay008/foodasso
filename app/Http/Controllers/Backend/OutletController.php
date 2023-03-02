<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use App\Models\User;
use App\Models\Outlet;

class OutletController extends CommonController
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

	// List of restaurants
	public function index(){
		$page_title    	= '';
		return view('backend.outlets.list', compact('page_title'));
	}
  
	// CREATE
	public function create(){
		$page_title    	= '';
		return view('backend.outlets.add', compact('page_title'));
	}

	/**
	* 
	* List Outlets.
	* @return void
	*/
	public function ajax($id = null)
	{
		try{
			// GET LIST
			$query = Outlet::where('delete_at',NULL)->orderBy('id','DESC')->get();
			if($query){
			foreach($query as $key=> $list){
				$query[$key]->action = '<div class="widget-content-right widget-content-actions">
										<a class="border-0 btn-transition btn btn-outline-success" href="'. route("outlets.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
										<button class="border-0 btn-transition btn btn-outline-danger" onclick="deleteThis('. $list->id .')"><i class="fa fa-trash-alt"></i></button>
										</div>';
										
				$status_array = ['Active'=>'', 'Inactive'=>'', 'Closed'=>'', 'PickupOnly'=>''];
				if($list->status == 'Active') { $status_array['Active'] = 'selected'; }
				if($list->status == 'Inactive') { $status_array['Inactive'] = 'selected'; }
				if($list->status == 'Closed') { $status_array['Closed'] = 'selected'; }
				if($list->status == 'Inactive') { $status_array['Inactive'] = 'selected'; }
				if($list->status == 'PickupOnly') { $status_array['PickupOnly'] = 'selected'; }
				$status = "<select class='form-control change_status' id='$list->id'>
									<option value='Active' 	". $status_array['Active'] .">Active</option>
									<option value='Inactive'". $status_array['Inactive'] .">Inactive</option>
									<option value='Closed'". $status_array['Closed'] .">Closed</option>
									<option value='PickupOnly'". $status_array['PickupOnly'] .">PickupOnly</option>
								</select>";
				
				$query[$key]->status = $status;
			}
			$this->sendResponse($query, trans('common.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));

		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	/**
	* 
	* List Outlet.
	* @return void
	*/
	public function edit($id = null)
	{
		try
		{
			$page_title = trans('outlets.update');
			$data    	= Outlet::where('id',$id)->first();
			if(!empty($data)){
				return view('admin.restaurants.edit',compact(['page_title','data']));
			}
			return redirect()->route('homePage')->with('error', trans('common.invalid_restaurant'));
      
		} catch (Exception $e) {
			return redirect()->route('homePage')->with('error', $e->getMessage());
		}
	}

	/**
	* 
	* Save Outlet.
	* @return void
	*/
	public function store(Request $request){
		
		$validator = Validator::make($request->all(), [
			  'title'				=> 'required|min:3|max:99',
			  'email'				=> 'required|email|max:50',
			  'address'				=> 'required|min:3|max:1000',
			  'zip_code'			=> 'required|numeric',
			  'country'				=> 'required|numeric',
			  'state'				=> 'required|numeric',
			  'city'				=> 'required|numeric',
			  'area'				=> 'required|min:3|max:1000',
			  'latitude'			=> 'required|min:3|max:51',
			  'longitude'			=> 'required|min:3|max:51',
			  'status'				=> 'required|min:3|max:51',
		]);
		if($validator->fails()){
		  $this->ajaxValidationError($validator->errors(), trans('common.error'));
		}

		$user = Auth()->user();
		if(empty($user)){
			$this->ajaxError([], trans('common.unauthorized_access'));
		}
			
		DB::beginTransaction();
		try{
			if(empty($request->item_id)){
				
				// CHECK Login Validation
				$validator = Validator::make($request->all(), [
					  'email'				=> 'required|email|max:50|unique:users',
				]);
				if($validator->fails()){
				  $this->ajaxValidationError($validator->errors(), trans('common.error'));
				}
				
				$userdata = [
					'name'              => $request->title,
					'email'             => $request->email,
					'password'          => bcrypt($request->email),
					'user_type'         => 'Outlet',
					'status'            => 'active',
					'email_verified_at' => date('Y-m-d h:i:s'),
				  ];
				// CREATE USER
				$outlet = User::create($userdata);
				$outlet->assignRole('Outlet');
				if($user){
					$data = [
						'user_id'              => $outlet->id,
						'owner_id'              => $user->id,
						'title:en'              => $request->title,
						'email'                 => $request->email,
						'phone_number'          => $request->phone_number,
						'latitude'              => $request->latitude,
						'longitude'             => $request->longitude,
						'zip_code'              => $request->zip_code,
						'country'				=> $request->country,
						'state'               	=> $request->state,
						'city'               	=> $request->city,
						'area:en'            	=> $request->area,
						'address:en'            => $request->address,
						'status'                => $request->status,
					];
					$insert = Outlet::create($data);
					if($insert){
						DB::commit();
						$this->sendResponse([], trans('common.added_success'));
					}
				}
			}else{
				$data = [
					'title:en'              => $request->title,
					'email'                 => $request->email,
					'phone_number'          => $request->phone_number,
					'latitude'              => $request->latitude,
					'longitude'             => $request->longitude,
					'zip_code'              => $request->zip_code,
					'country'				=> $request->country,
					'state'               	=> $request->state,
					'city'               	=> $request->city,
					'area:en'            	=> $request->area,
					'address:en'            => $request->address,
					'status'                => $request->status,
				];
				$query 	= Outlet::find($request->item_id);
				if($query){
					$return	=  $query->update($data);
					if($insert){
						DB::commit();
						$this->sendResponse([], trans('common.updated_success'));
					}
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
	* 
	* Change Outlet Status.
	* @return void
	*/
	public function change_status(Request $request){
		DB::beginTransaction();
		try {
			$query = Outlet::where('id',$request->id)->update(['status'=>$request->status]);
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
}