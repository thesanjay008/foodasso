<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;

use App;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Helpers\CommonHelper;

class StateController extends CommonController
{
	use CommonHelper;

    // LIST
	public function list(Request $request){
		try{
			// GET LIST
			$query = State::where(['status'=>'active']);
			if($request->country_id){
				$query->where('country_id', $request->country_id);
			}
			$result = $query->get();
			
			if($result){
				foreach($result as $key=> $list){
					
				}
				$this->sendResponse($result, trans('state.data_found_success'));
			}
			$this->sendResponse([], trans('state.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}
