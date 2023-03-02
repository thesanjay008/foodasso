<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\City;
use App\Http\Resources\CityListResource;

class CityController extends BaseController
{
    /**
     * City List
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        $search = $request->search;
        $page   = $request->page ?? 1;
        $count  = $request->count ?? '5000';
		
        if ($page <= 0){ $page = 1; }
        $offset = $count * ($page - 1);
        
        try{
            $query = City::query();
            /* SEARCH */
            if($search){
                $query->where('name','like','%'.$search.'%');
            }
            $query = $query->orderBy('id')->offset($offset)->limit($count)->get();
            return $this->sendArrayResponse(CityListResource::collection($query), trans('customer_api.data_found_success'));
        }catch (\Exception $e) { 
          return $this->sendError('', $e->getMessage()); 
        }
    }
}
