<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;

use App;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Helpers\CommonHelper;

class CityController extends CommonController
{
	use CommonHelper;

    // LIST
	public function list(Request $request){
		try{
			// GET LIST
			$query = City::where(['status'=>'active']);
			if($request->state_id){
				$query->where('state_id', $request->state_id);
			}
			$result = $query->get();
			if($result){
				foreach($result as $key=> $list){
					
				}
				$this->sendResponse($result, trans('city.data_found_success'));
			}
			$this->sendResponse([], trans('city.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}
