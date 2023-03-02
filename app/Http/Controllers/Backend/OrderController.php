<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\CommonController;

use Carbon\Carbon;
use Validator,Auth,DB,Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use App\Models\Helpers\CommonHelper;
use App\Models\MenuCategory;
use App\Models\Order;
use App\Models\Booking;
use App\Models\Product;

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
        //$this->middleware('permission:res_order-list', ['only' => ['index','show']]);
        //$this->middleware('permission:res_order-create', ['only' => ['create','store']]);
        //$this->middleware('permission:res_order-edit', ['only' => ['edit','update']]);
        //$this->middleware('permission:res_order-delete', ['only' => ['destroy']]);
    }
	
	// CREATE NEW ORDER
	public function create(){
		
		$categories = MenuCategory::where('status', 'active')->get();
		
		return view('backend.orders.create', compact('categories'));
	}
	
	// SHOW OPEN ORDER PAGE
	public function open(){
		
		$new			= Order::where('status', 'New')->orderBy('id', 'DESC')->get();
		$preparing		= Order::where('status', 'Preparing')->orderBy('id', 'DESC')->get();
		$dispatched		= Order::where('status', 'Dispatched')->orderBy('id', 'DESC')->get();
		$outForDelivery	= Order::where('status', 'Out-For-Delivery')->orderBy('id', 'DESC')->get();
		return view('backend.orders.open-orders', compact('new', 'preparing', 'dispatched', 'outForDelivery'));
	}
	
	// List Page
	public function index(){
		return view('backend.orders.list');
	}

	// SHOW
	public function show($id = null){
		$order = Order::find($id);
		return view('backend.orders.show',compact('order'));
	}
	
	// AJAX List 
	public function ajax(Request $request){
		$page		= $request->page ?? '1';
		$count		= $request->count ?? '20';
		$search		= $request->search ?? '';
		$date		= $request->date ?? '';
		$status		= $request->status ?? 'all';
		
		if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
		try{
			// GET LIST
			$query = Order::where('status','!=','Temporary');
			
			/* SEARCH */
			if($search){
				$query->where('custom_order_id','like','%'.$search.'%');
			}
			
			/* DATE */
			if($date){
				$query->where('order_date','=',$date);
			}
			
			// STATUS
			if($status != 'all'){
				$query->where('status', $status);
			}
			
			$query = $query->orderBy('id', 'DESC')->offset($start)->limit($count)->get();
			if(count($query) > 0){
				foreach($query as $key=> $list){
					$query[$key]->action = '<div class="widget-content-right widget-content-actions">
											<a class="border-0 btn-transition btn btn-outline-success" href="'. route("orders.show",$list->id) .'"><i class="fa fa-eye"></i></a>
											</div>';
					if($list->payment_status == null){
						$query[$key]->payment_status = 'Undefined';
					}
				}
				$this->sendResponse($query, trans('order.data_found_success'));
			}
			$this->sendResponse([], trans('order.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
	
	// CHANGE STATUS
	public function status(Request $request){
	    DB::beginTransaction();
	    try {
	        $query = Order::where('id',$request->id)->update(['status'=>$request->status]);
	        if($query){
	          DB::commit();
	          $this->sendResponse(['status'=>'success'], trans('order.status_updated_successfully'));
	        }
	        else{
	          DB::rollback();
	          $this->sendResponse(['status'=>'error'], trans('order.status_not_updated'));
	        }
	    } catch (Exception $e) {
	        DB::rollback();
	        $this->ajaxError([], $e->getMessage());
	    }
	}


	/**
	* CHECK NEW ORDERS
	*/
	public function ajax_checkNewOrders(){
      try {
		  $order 	= Order::where('status','=','New')->get();
		  $booking  = Booking::where('status','=','New')->get();
		  
		  
		  if(count($order) > 0 || count($booking) > 0){
			$return['count']	= $order->count() + $booking->count();
			$return['html']		= "<audio controls autoplay hidden='true'><source src='". asset('default/ringtone.mpeg') ."' type='audio/mpeg'></audio>";
			$this->sendResponse($return, trans('common.data_found_success'));
		  }
		  $this->sendResponse([], trans('common.data_found_empty'));
      } catch (Exception $e) {
          $this->ajaxError([], $e->getMessage());
      }
    }
	
	// OPEN ORDER COUNT
	public function ajax_count(Request $request){
		try{
			$cartData = order::where('status','=','New')->get();
			if($cartData){
				$return['count'] = $cartData->count();
				$this->sendResponse($return, trans('cart.data_found_success'));
			}
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}