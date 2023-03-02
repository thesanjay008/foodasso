<?php

namespace App\Http\Controllers\Vendors;

use App\Http\Controllers\CommonController;

use Illuminate\Http\Request;
use Validator,Auth,DB,Storage;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use Illuminate\Validation\ValidationException;

class HomeController extends CommonController
{   
	use CommonHelper;
	/**
	* Create a new controller instance.
	*
	* @return void
	*/
	public function __construct()
	{
		//$this->middleware(['auth','vendor_approved','vendor_subscribed']);
	}
  
	public function index(){
        
		$user = Auth()->user();
		if(empty($user)){ exit('Unauthorized access'); }

		if($user->user_type != 'Vendor'){ exit('Unauthorized access'); }		
		
		return redirect()->route('vendorDashboard');
	}
	
	// DASHBOARD
	public function dashboard(){
		return view('vendors.dashboard');
	}
  
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
    public function show($id){
      try {
        
      } catch (Exception $e) {
        return redirect()->back()->withError($e->getMessage());            
      }
    }
}