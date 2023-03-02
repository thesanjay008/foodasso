<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CommonController;

use App;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Helpers\CommonHelper;

class CountryController extends CommonController
{
	use CommonHelper;

    // LIST
	public function list(){
		try{
			// GET LIST
			$query = Country::where(['status'=>'active'])->get();
			if($query){
				foreach($query as $key=> $list){
					
				}
				$this->sendResponse($query, trans('city.data_found_success'));
			}
			$this->sendResponse([], trans('city.data_found_empty'));
		} catch (Exception $e) {
			$this->ajaxError([], $e->getMessage());
		}
	}
}
