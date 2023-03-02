<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\PaymentGateway;

class PaymentGatewayController extends CommonController
{   
	use CommonHelper;
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct(){
		$this->middleware('auth');
		// $this->middleware('permission:restaurant-list', ['only' => ['index','show']]);
		// $this->middleware('permission:restaurant-create', ['only' => ['create','store']]);
		// $this->middleware('permission:restaurant-edit', ['only' => ['edit','update']]);
		// $this->middleware('permission:restaurant-delete', ['only' => ['destroy']]);
	}
  
	// List of restaurants
	public function index(){
		return view('backend.settings.paymentGateways.list');
	}

	public function ajax(Request $request){
		$page		= $request->page ?? '1';
		$count		= $request->count ?? '100';
		$search		= $request->search ?? '';
		$status		= $request->status ?? 'all';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		try{
			// GET LIST
			$query = PaymentGateway::where('delete_at','=',NULL);
			
			// STATUS
			if($status != 'all' && !empty($status)){
				$query->where('status', $status);
			}
			
			/* SEARCH */
			if($search){
				$query->where('title','like','%'.$search.'%');
			}
			
			$data  = $query->orderBy('id', 'DESC')->offset($start)->limit($count)->get();
			if($data){
				foreach($data as $key=> $list){
				
					$data[$key]->image = '';
					$data[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("paymentGateway.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
										  </div>';
					
					if($list->status == 'active'){
					$data[$key]->status = '<select class="form-control status" id="'.$list->id.'">										
										<option value="active" selected>Active</option>
										<option value="inactive">Inactive</option>										
										</select>';
					}
					if($list->status == 'inactive'){
						$data[$key]->status = '<select class="form-control status" id="'.$list->id.'">											
											<option value="active">Active</option>											
											<option value="inactive" selected>Inactive</option>
										  	</select>';
					}
				}
			$this->sendResponse($data, trans('common.data_found_success'));
			}
			$this->sendResponse([], trans('common.data_found_empty'));

			} catch (Exception $e) {
				$this->ajaxError([], $e->getMessage());
			}
	}
	
	public function change_status(Request $request){
		DB::beginTransaction();
		try {
			$res = PaymentGateway::where('id',$request->id)->update(['status'=>$request->status]);

			if($res){
			  DB::commit();
			  $this->sendResponse(['status'=>'success'], "Upadated Successfully");

			} else {
			  DB::rollback();
			  $this->sendResponse(['status'=>'error'], "Update Error");
			}
        
		} catch (Exception $e) {
			DB::rollback();
			$this->ajaxError([], $e->getMessage());
		}
	}
}