<?php

namespace App\Http\Controllers\Api\customer;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Validator;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Resources\HospitalListMedicalRoomResource;
use App\Http\Resources\MedicalRoomDetailResource;
use App\Http\Resources\HospitalMedicalRoomResource;
use App\Http\Resources\HospitalMedicalRoomTypesResource;
use App\Http\Resources\HospitalMedicalRoomViewsResource;
use App\Models\MedicalRoom;
use App\Models\Hospital;
use App\Models\MedicalRoomType;
use App\Models\MedicalRoomView;

class MedicalRoomController extends BaseController
{
	public function index(Request $request) {
    	
	    $search = $request->search;
		$page   = $request->page ?? '1';
		$count  = $request->count ?? '10000';

	    if ($page <= 0){ $page = 1; }
		$start  = $count * ($page - 1);
		
	    try{
	    	$query = MedicalRoom::select('medical_rooms.*');
	    	
	    	// Search
			if($search){
				$query = $query->whereHas('translation',function($query) use ($search){
					$query->where('room_name','like','%'.$search.'%');
				});
			}

	    	// FILTER ROOM VIEW
	    	if($request->room_views_id){
	    		$query->join('medical_room_select_views', 'medical_room_select_views.medical_room_id','=','medical_rooms.id');
	    		$query->join('medical_room_views', 'medical_room_views.id','=','medical_room_select_views.room_view_id');
	    		$query->where('medical_room_select_views.room_view_id',$query->room_views_id);
	    		$query->where('medical_room_views.status','active');
	    	}

	    	// FILTER ROOM TYPE
	    	if($request->room_type_id){
	    		$query->join();
	    	}

			
			$query = $query->groupBy('medical_rooms.id')->where('medical_rooms.status','active')->offset($start)->limit($count)->get();

	      	return $this->sendArrayResponse(HospitalListMedicalRoomResource::collection($query),trans('customer_api.data_found_success'));
		}catch (\Exception $e) { 
		  	return $this->sendError('', $e->getMessage());    
		}
	}

	public function hospital_rooms(Request $request) {
  	try{
		if($request->hospital_id){
	      	$medical_room = MedicalRoom::where('hospital_id',$request->hospital_id)->count();
	      	if($medical_room == 0){
	      		return $this->sendError('',trans('medical_room.hospital_not_found'));
	      	}
		}
		$medical_room = HospitalMedicalRoomResource::collection(Hospital::where(['status'=>'active','id'=>$request->hospital_id])->paginate());
		if(count($medical_room) > 0) {
	        return $this->sendResponse($medical_room,trans('medical_room.medical_room_found'));
		}else{
	    	return $this->sendResponse('',trans('medical_room.medical_room_not_found')); 
		}
  	} catch (\Exception $e) { 
      return $this->sendError('', $e->getMessage());    
  	}
  }

	public function show($id = '') {
		try{
	      $query = MedicalRoom::where('id', $id)->first();
	      
	      if($query) {
	        return $this->sendResponse(new MedicalRoomDetailResource($query), trans('customer_api.data_found_success'));
	      }
	      return $this->sendResponse($query, trans('customer_api.data_found_empty'));
	    } catch (\Exception $e) {
	      DB::rollback();
	      return $this->sendError('', $e->getMessage());    
	    }
	}

	// MASTER DATA FOR FILTERS
	public function masterData() {
		try{
	      	$price             = MedicalRoom::where('status', 'active')->max('offer_price');
	        $MedicalRoomTypes  = HospitalMedicalRoomTypesResource::collection(MedicalRoomType::where('status', 'active')->get());
	        $MedicalRoomViews  = HospitalMedicalRoomViewsResource::collection(MedicalRoomView::where('status', 'active')->get());
	        //$room_sizes        = [['title'=>'20X16'],['title'=>'20X18']];
	        
	        $success['max_price'] = $price ? (string) floor($price) : '0';
	        $success['room_types'] = $MedicalRoomTypes;
	        $success['room_views'] = $MedicalRoomViews;
	        //$success['room_sizes'] = $room_sizes;
	        return $this->sendResponse($success,trans('customer_api.data_found_success'));
	    } catch (\Exception $e) {
	      return $this->sendError('', $e->getMessage());    
	    }
	}
}
