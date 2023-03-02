<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InterestedTag;
use App\Http\Resources\InterestedTagResource;
use DB,Validator,Auth;

class InterestedTagController extends BaseController
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
        $interested_tag = InterestedTagResource::collection(InterestedTag::where('status','active')->paginate());

        //echo "<pre>";print_r($interested_tag->toArray());exit;
        if($interested_tag) {
          return $this->sendArrayResponse($interested_tag,trans('interested_tag.interested_tag_found'));
        }else{
           return $this->sendArrayResponse('',trans('interested_tag.interested_tag_not_found')); 
        }
      }catch (\Exception $e) { 
          return $this->sendError('', $e->getMessage()); 
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
