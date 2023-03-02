<?php

namespace App\Http\Controllers\Api\Customer;


use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\Event;
use App\Http\Resources\EventResource;

class EventController extends BaseController
{
    /**
     * Event List
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        $search = $request->search;
        $page   = $request->page ?? '0';
        $count  = $request->count ?? '10000';

        if ($page <= 0){ $page = 1; }
        $start  = $count * ($page - 1);
		
        try{
            $query = Event::query();
            $query = $query->where('date', '>=', date('Y-m-d'));
            /* SEARCH */
            if($search){
                $query = $query->whereHas('translation',function($query) use ($search){
                    $query->where('event_name','like','%'.$search.'%');
                });
            }if($request->start_date && $request->end_date){
                $start_date = date('Y-m-d', strtotime($request->start_date));
                $end_date   = date('Y-m-d', strtotime($request->end_date));
                $query = $query->whereBetween('date', [$start_date, $end_date]);
                //$query = $query->where('DATE(date)', 'between', '2018-06-18' and '2018-06-18');
            }
            $query = $query->orderBy('id')->offset($start)->limit($count)->get();
            $return['upcoming'] = EventResource::collection($query);

            $query2 = Event::query();
            $query2 = $query2->where('date', '<=', date('Y-m-d'));
            $query2 = $query2->orderBy('id')->offset($start)->limit($count)->get();
            $return['archived'] = EventResource::collection($query2);
            return $this->sendArrayResponse($return, trans('customer_api.data_found_success'));
        }catch (\Exception $e) { 
          return $this->sendError('', $e->getMessage()); 
        }
    }

    public function event($id = null){
        
        try{
            $query = Event::where('id', $id)->first();
            return $this->sendResponse(new EventResource($query), trans('customer_api.data_found_success'));
        }catch (\Exception $e) {
          return $this->sendError('', $e->getMessage());
        }
    }
}
