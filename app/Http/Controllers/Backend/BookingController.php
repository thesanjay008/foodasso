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
use App\Models\Table;
use App\Models\Booking;

class BookingController extends CommonController
{   
	use CommonHelper;
	
	/**
	* Create a new controller instance.
	* @return void
	*/
	public function __construct()
	{
		$this->middleware('auth');
	}

	// List of Tables
	public function index(){
		$page_title    	= '';
		return view('backend.bookings.list', compact('page_title'));
	}
  
	// SHOW
	public function show($id = null){
		$page_title    	= '';
		$data 			= Booking::find($id);
		return view('backend.bookings.show',compact('page_title', 'data'));
	}

	/**
	* List Tables.
	* @return void
	*/
	public function ajax($id = null)
	{
		try{
			// GET LIST
			$query = Booking::where('process_completed','Yes')->orderBy('id','DESC')->get();
			if($query){
			foreach($query as $key=> $list){
				$query[$key]->action = '<div class="widget-content-right widget-content-actions">
										<a class="border-0 btn-transition btn btn-outline-success" href="'. route("bookings.show",$list->id) .'"><i class="fa fa-eye"></i></a>
										</div>';
				
				$status = '';
				if($list->status == 'New'){
					$status = "<select class='form-control status' id='$list->id'>
									<option value='New'>New</option>
									<option value='Accepted'>Accept</option>
									<option value='Rejected'>Reject</option>
								</select>";
				}
				if($list->status == 'Accepted'){
					$status = "<select class='form-control status' id='$list->id'>
									<option value='Accepted'>Accepted</option>
									<option value='Completed'>Complete</option>
									<option value='Rejected'>Reject</option>
								</select>";
				}
				
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
	* List Table.
	* @return void
	*/
	public function edit($id = null)
	{
		try{
			$page_title = trans('table.update');
			$data    	= Table::where('id',$id)->first();
			if(!empty($data)){
				return view('backend.table.edit',compact(['page_title','data']));
			}
			return redirect()->route('homePage')->with('error', trans('common.invalid_table'));
      
		} catch (Exception $e) {
			return redirect()->route('homePage')->with('error', $e->getMessage());
		}
	}

	/**
	* Save Table.
	* @return void
	*/
	public function store(Request $request){
		
		$validator = Validator::make($request->all(), [
			  'title'				=> 'required|min:3|max:99',
			  'table_number'		=> 'required|max:11',
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
				$data = [
					'title:en'              => $request->title,
					'table_number'			=> $request->table_number,
					'status'                => $request->status,
				];
				$insert = Table::create($data);
				if($insert){
					DB::commit();
					$this->sendResponse([], trans('common.added_success'));
				}
			}else{
				$data = [
					'title:en'              => $request->title,
					'table_number'			=> $request->table_number,
					'status'                => $request->status,
				];
				
				$query 	= Table::find($request->item_id);
				if($query){
					$return	=  $query->update($data);
					if($return){
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
	* Change Table Status.
	* @return void
	*/
	public function change_status(Request $request){
		DB::beginTransaction();
		try {
			$query = Booking::where('id',$request->id)->update(['status'=>$request->status]);
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