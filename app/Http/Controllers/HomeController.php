<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App;

use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Product;
use App\Models\MenuCategory;

class HomeController extends Controller
{
    /**
	*
	* @return void
	*/
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
	* Show the application first page.
	*/
    public function index(){
		$page				= 'home';
		$page_title			= '';
		
        $top_categories		= MenuCategory::where(['status'=> 'active'])->skip(0)->take(9)->orderBy('id', 'DESC')->get();
        $top_list			= Product::where(['status'=> 'active'])->skip(0)->take(6)->orderBy('id', 'DESC')->get();
		
		return view('theme/firstPage', compact('page','page_title','top_list','top_categories'));
    }
	
	/**
	* 	CHECK NEW ORDER STATUS
	*/
	public function ajax_checkNewOrder(){
      try {
		  $html = "<audio controls autoplay hidden='true'><source src='http://www.w3schools.com/html/horse.ogg' type='audio/ogg'></audio>";
          return response()->json(['success' => '1', 'status' => '200', 'data' => [], 'html' => $html, 'message' => trans('order.check_status')]);
      } catch (Exception $e) {
          return response()->json(['success' => '0', 'status' => '201', 'data' => [], 'message' => $e->getMessage()]);
      }
    }
	
    //Localization function
    public function lang($locale){
        App::setLocale($locale);
        session()->put('locale', $locale);
        return redirect()->back();
    }
    /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
    public function get_country(){
      try {
          $countries = Country::where('status',Country::ACTIVE_STATUS)->pluck('name','id');
          return response()->json(['success' => '1', 'data' => $countries, 'message' => 'country_list']);
      } catch (Exception $e) {
          return response()->json(['success' => '0', 'data' => [], 'message' => $e->getMessage()]);
      }
    }
    /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
    public function get_state($country_id){
      try {
          $states = State::where('country_id',$country_id)->pluck('name','id');
          return response()->json(['success' => '1', 'data' => $states, 'message' => 'state_list']);
      } catch (Exception $e) {
          return response()->json(['success' => '0', 'data' => [], 'message' => $e->getMessage()]);
      }
    }


    public function get_hospital($country_id){
      try {
          $states = HospitalTranslation::pluck('hospital_name','hospital_id');
          // print_r($states);die;
          return response()->json(['success' => '1', 'data' => $states, 'message' => 'state_list']);
      } catch (Exception $e) {
          return response()->json(['success' => '0', 'data' => [], 'message' => $e->getMessage()]);
      }
    }
    /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
    public function get_city($state_id){
      try {
          $cities = City::where('state_id',$state_id)->pluck('name','id');
          return response()->json(['success' => '1', 'data' => $cities, 'message' => 'city_list']);
      } catch (Exception $e) {
          return response()->json(['success' => '0', 'data' => [], 'message' => $e->getMessage()]);
      }
    }

    public function get_clinic(){
      try {
          $cities = ClinicTranslation::pluck('clinic_name','clinic_id');
          return response()->json(['success' => '1', 'data' => $cities, 'message' => 'city_list']);
      } catch (Exception $e) {
          return response()->json(['success' => '0', 'data' => [], 'message' => $e->getMessage()]);
      }
    }

    public function get_department($clinic_id){
      try {
        $department = Department::select('departments.*')->where('status','active')
        ->join('clinics_departments', 'clinics_departments.department_id','=', 'departments.id')
        ->groupBy('departments.id')
        ->where('clinics_departments.clinic_id', $clinic_id)
        ->get();
          return response()->json(['success' => '1', 'data' => $department, 'message' => 'department_list']);
      } catch (Exception $e) {
          return response()->json(['success' => '0', 'data' => [], 'message' => $e->getMessage()]);
      }
    }

    public function get_hospital_department($hospital_id){
      try {
        
        $department = Department::select('departments.*')->where('status','active')
        ->join('hospital_departments', 'hospital_departments.department_id','=', 'departments.id')
        ->groupBy('departments.id')
        ->where('hospital_departments.hospital_id', $hospital_id)
        ->get();
          return response()->json(['success' => '1', 'data' => $department, 'message' => 'department_list']);

      } catch (Exception $e) {
          return response()->json(['success' => '0', 'data' => [], 'message' => $e->getMessage()]);
      }
    }

    public function get_medical_equipment(){
      try {
        // print_r($state_id);die;
        $query = new Product();   
        $query = $query->where('type','medical_equipment')->get();
        foreach ($query as $key=>$value) {
          $department =  ProductTranslation::where('product_id',$value->id)->pluck('title','product_id')->toArray();
        }
 
          return response()->json(['success' => '1', 'data' => $department, 'message' => 'Medical Equipment list']);
      } catch (Exception $e) {
          return response()->json(['success' => '0', 'data' => [], 'message' => $e->getMessage()]);
      }
    }

    public function get_nursing_home_care(){
      try {
        // print_r($state_id);die;
        $department = NursingHomeTranslation::pluck('nursing_home_name','nursing_home_id');
        
          return response()->json(['success' => '1', 'data' => $department, 'message' => 'Nursing Home Care list']);
      } catch (Exception $e) {
          return response()->json(['success' => '0', 'data' => [], 'message' => $e->getMessage()]);
      }
    }

    public function get_laboratory(){
      try {
        $laboratories = LaboratoryTranslation::pluck('laboratory_name','laboratory_id');
          return response()->json(['success' => '1', 'data' => $laboratories, 'message' => 'laboratory  list']);
      } catch (Exception $e) {
          return response()->json(['success' => '0', 'data' => [], 'message' => $e->getMessage()]);
      }
    }
}
