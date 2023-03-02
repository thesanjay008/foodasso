<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\Country;
use App\Http\Resources\CountryListResource;

class CountryController extends BaseController
{
    /**
     * Country List
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        $search = $request->search;
        $page   = $request->page ?? 1;
        $count  = $request->count ?? '10000';
        
        if ($page <= 0){ $page = 1; }
        $offset = $count * ($page - 1);
		
        try{
            $query = Country::query();
            /* SEARCH */
            if($search){
                $query->where('name','like','%'.$search.'%');
            }
            $query = $query->orderBy('id')->offset($offset)->limit($count)->get();
            return $this->sendArrayResponse(CountryListResource::collection($query), trans('customer_api.data_found_success'));
        }catch (\Exception $e) { 
          return $this->sendError('', $e->getMessage());
        }
    }
}
