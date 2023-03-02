<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\State;
use App\Http\Resources\StateListResource;

class StateController extends BaseController
{
    /**
     * State List
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        $search = $request->search;
        $start  = '0';
        $count  = '10000';
		
        try{
            $query = State::query();
            /* SEARCH */
            if($search){
                $query->where('name','like','%'.$search.'%');
            }
            $query = $query->orderBy('id')->offset($start)->limit($count)->get();
            return $this->sendArrayResponse(StateListResource::collection($query), trans('customer_api.data_found_success'));
        }catch (\Exception $e) { 
          return $this->sendError('', $e->getMessage());
        }
    }
}
