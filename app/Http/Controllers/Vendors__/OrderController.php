<?php

namespace App\Http\Controllers\Vendors;

use App\Http\Controllers\CommonController;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use App\Models\MenuCategory;
use App\Models\Order;
use App\Models\HospitalDepartment;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class OrderController extends CommonController
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
        $this->middleware('permission:res_order-list', ['only' => ['index','show']]);
        $this->middleware('permission:res_order-create', ['only' => ['create','store']]);
        $this->middleware('permission:res_order-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:res_order-delete', ['only' => ['destroy']]);
    }
  
	// List Page
	public function index(){
		return view('vendors.order.list');
	}
  
	// List 
	public function ajax($id = null){
		try{
			// GET LIST
			$query = Order::where('status','!=','Unconfirmed')->orderBy('id', 'DESC')->get();
			if(count($query) > 0){
				foreach($query as $key=> $list){
					$query[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("orders.show",$list->id) .'"><i class="fa fa-eye"></i></a>
											</div>';

					$query[$key]->customer_name = $list->user->name;
					if($list->payment_method_id == '1')
					{
						$query[$key]->payment_mode = 'COD';
					}
					else
					{
						$query[$key]->payment_mode = 'Online';
					}
					
					if($list->payment_status == null){
						$query[$key]->payment_status = 'Pending';
					}

					//order status
					$unconfirmed = '';
					$received = '';
					$accepted = '';
					$preparing = '';
					$dispatched = '';
					$delivered = '';
					$rejected = '';
					if($list->status == 'Unconfirmed')
					{
						$unconfirmed = 'selected';
					} elseif ($list->status == 'Received') {
						$received = 'selected';
					} elseif ($list->status == 'Accepted') {
						$accepted = 'selected';
					} elseif ($list->status == 'Preparing') {
						$preparing = 'selected';
					} elseif ($list->status == 'Dispatched') {
						$dispatched = 'selected';
					} elseif ($list->status == 'Delivered') {
						$delivered = 'selected';
					} elseif ($list->status == 'Rejected') {
						$rejected = 'selected';
					}
					$query[$key]->status = '<select class="form-control status" name="status" id="'.$list->id.'">
								<option value="Unconfirmed" '.$unconfirmed.'>'.trans('order.unconfirmed').'</option>
								<option value="Received" '.$received.'>'.trans('order.received').'</option>
								<option value="Accepted" '.$accepted.'>'.trans('order.accepted').'</option>
								<option value="Preparing" '.$preparing.'>'.trans('order.preparing').'</option>
								<option value="Dispatched" '.$dispatched.'>'.trans('order.dispatched').'</option>
								<option value="Delivered" '.$delivered.'>'.trans('order.delivered').'</option>
								<option value="Rejected" '.$rejected.'>'.trans('order.rejected').'</option>
						</select>';

				}
				$this->sendResponse($query, trans('order.data_found_success'));
			}
			$this->sendResponse([], trans('order.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}

	public function status(Request $request){
	    DB::beginTransaction();
	    try {
	        $res = Order::where('id',$request->id)->update(['status'=>$request->status]);

	        if($res)
	        {
	          DB::commit();
	          $this->sendResponse(['status'=>'success'], trans('order.status_updated_successfully'));

	        }
	        else
	        {
	          DB::rollback();
	          $this->sendResponse(['status'=>'error'], trans('order.status_not_updated'));
	        }
	        
	    } catch (Exception $e) {
	        DB::rollback();
	        $this->ajaxError([], $e->getMessage());
	    }


	}

	// SHOW
	public function show($id = null){
		$order = Order::find($id);
		return view('vendors.order.show',compact('order'));
	}
  
}