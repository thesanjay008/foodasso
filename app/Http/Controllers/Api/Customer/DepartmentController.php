<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Http\Resources\DepartmentResource;
use DB,Validator,Auth;

class DepartmentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  public function index(Request $request) {
    $search = $request->search;
    $page   = $request->page ?? 1;
    $count  = $request->count ?? '5000';

    if ($page <= 0){ $page = 1; }
    $start = $count * ($page - 1);
    DB::beginTransaction();
    try{
        $query = Department::query();       
        $query = $query->orderBy('id', 'DESC')->offset($start)->limit($count)->get();
        if(count($query) > 0) {
          return $this->sendArrayResponse(DepartmentResource::collection($query), trans('customer_api.data_found_success'));
      }
      return $this->sendArrayResponse($query, trans('customer_api.data_found_empty'));
    }
    catch (\Exception $e) {
      DB::rollback();
      return $this->sendError('', $e->getMessage());    
    }
  }
    
    public function show($id)
    {
        //
    }

}
