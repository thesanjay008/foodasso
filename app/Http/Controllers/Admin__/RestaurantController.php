<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Country;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RestaurantController extends CommonController
{   
  use CommonHelper;
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('auth');
  }
  
  // List of restaurants
  public function index(){
    return view('admin.restaurants.list');
  }
  
  // CREATE
  public function create(){
    $page_title    	= '';
    $country 		= Country::where('status','active')->get();
    $categories 	= Category::where('status','active')->get();
    return view('admin.restaurants.add',compact('page_title','country','categories'));
  }

  public function edit($id = null){
    try
    {
      $page_title = trans('restaurants.update');
      $data    = Restaurant::where('owner_id',$id)->first();
      $country = Country::where('status','active')->get();
      $categories = Category::where('status','active')->get();
      $restaurant_categories = RestaurantCategory::where('restaurant_id',$data->id)->pluck('category_id')->toArray();
      
      if(!empty($data)){
        return view('admin.restaurants.edit',compact(['data','page_title', 'country','categories','restaurant_categories']));
      }

    return redirect()->route('vendorDashboard')->with('error', trans('common.invalid_restaurant'));
      
      } catch (Exception $e) {
          return redirect()->route('vendorDashboard')->with('error', $e->getMessage());
      }
    }

  // STORE
  public function store(Request $request){

  if(empty($request->item_id)){
    // CREATE VALIDATION CHECKS 
    $validator = Validator::make($request->all(), [
          'title_en'           => 'required|min:3|max:99',
          'email'              => 'required|email|max:50|unique:users',
          'phone_number'       => 'required|numeric|unique:users',
          'password'           => 'required',
          'address_en'         => 'required|min:3|max:1000',
          // 'flat_discount'      => 'required|numeric|min:1|max:100',
          'zip_code'           => 'required|numeric',
          'city_id'            => 'required|numeric',
          'state_id'           => 'required|numeric',
          'country_id'         => 'required|numeric',
          'categories'         => 'required',
          'status'             => 'required',
          'latitude'           => 'required',
          'longitude'          => 'required',
          'status'             => 'required',
    ]);
    if($validator->fails()){
      $this->ajaxValidationError($validator->errors(), trans('common.error'));
    }

    // UPDATE VALIDATION CHECKS 
  }else {         
          $restaurant = Restaurant::find($request->item_id);
          $validator = Validator::make($request->all(), [
              'item_id'            => 'required',
              'title_en'           => 'required|min:3|max:99',
              'email'              =>  ['required','max:99',Rule::unique('restaurants')->ignore($restaurant->id)],
              'phone_number'       =>  ['required','numeric',Rule::unique('restaurants')->ignore($restaurant->id)],
              // 'password'           => 'required',
              'address_en'         => 'required|min:3|max:1000',
              // 'flat_discount'      => 'required|numeric|min:1|max:100',
              'zip_code'           => 'required|numeric',
              'city_id'            => 'required|numeric',
              'state_id'           => 'required|numeric',
              'country_id'         => 'required|numeric',
              'categories'         => 'required',
              'status'             => 'required',
              'latitude'           => 'required',
              'longitude'          => 'required',
              'status'             => 'required', 
          ]);
          if($validator->fails()){
            $this->ajaxValidationError($validator->errors(), trans('common.error'));
          } 
        }

    DB::beginTransaction();
    try{
      $userdata = [
        'name'              => $request->title_en,
        'email'             => $request->email,
        'password'          => Hash::make($request->password),
        'phone_number'      => $request->phone_number,
        'user_type'         => 'Vendor',
        'status'            => $request->status,
        'vendor_approved'   => '1',  
        'email_verified_at' => date('Y-m-d h:i:s'),
      ];
      
      if($request->item_id){
        // UPDATE USER
        $user        =  User::find($restaurant->owner->id);
        $user_update        =  $user->update($userdata);

      } else{
        // CREATE USER
        $user = User::create($userdata);
        $user->assignRole('Vendor');
      }
      if($user){
          $restaurants = [
            'owner_id'              => $user->id,
            'title:en'              => $request->title_en,
            'phone_number'          => $request->phone_number,
            'email'                 => $request->email,
            'country_id'            => $request->country_id,
            'latitude'              => $request->latitude,            
            'longitude'             => $request->longitude,             
            'zip_code'              => $request->zip_code,             
            'city_id'               => $request->city_id,  
            // 'categories'            => $request->categories,           
            'state_id'              => $request->state_id,             
            'country_id'            => $request->country_id,             
            'flat_discount'         => $request->flat_discount,             
            'address:en'            => $request->address_en,             
            'user_type'             => 'Vendor',
            'status'                => $request->status,
            'vendor_approved'       => '1',  
            'email_verified_at'     => date('Y-m-d h:i:s'),
          ];
          // SAVE IMAGES AND BANNER 
          if($request->image != "undefined"){
              // if(file_exists($restaurants->image)){
              //   unlink($restaurants->image);
              // }
              $path = $this->saveMedia($request->file('image'));
              $restaurants['image'] = $path;
          }
          if($request->banner_image != "undefined"){
              // if(file_exists($restaurant->banner_image)){
              //   unlink($restaurant->banner_image);
              // }
              $banner_img_path = $this->saveMedia($request->file('banner_image'));
              $restaurants['banner_image'] = $banner_img_path;
          }

          // UPDATED RESTAURANTS 
          if($request->item_id){
            $restaurant  =  Restaurant::find($request->item_id);
            $return  =  $restaurant->update($restaurants);
          // CREATE RESTAURANTS
          }else{
              $restaurant = Restaurant::create($restaurants);
          }
          // REMOVE THE CATEGORY 
          $cat_ids = explode(',', $request->categories);
          $restaurant_categories  = RestaurantCategory::where('restaurant_id',$request->item_id)->get();

          foreach ($restaurant_categories as $category) {
            $category->delete();
          }
          // SAVE CATEGORY 
          foreach ($cat_ids as $cat_id) {
              $cat_data = [
                'owner_id' => $restaurant->owner->id,
                'restaurant_id' => $restaurant->id,
                'category_id' => $cat_id
              ];
              RestaurantCategory::create($cat_data);
           }
          if($restaurant || $return){
           DB::commit();
           $this->sendResponse([], trans('common.updated_success'));
          }else{
            DB::rollback();
          }
      }  
      // $this->ajaxError([], trans('common.try_again'));
    } catch (Exception $e) {
      $this->ajaxError([], $e->getMessage());
    }
  }



  public function ajax($id = null){
    try{
      // GET LIST
      $query = User::with(['restaurant'])->orderBy('id','DESC')->where('user_type','Vendor')->get(); 
      if($query){
        foreach($query as $key=> $list){
          $query[$key]->action = '<div class="widget-content-right widget-content-actions">
                      <a class="border-0 btn-transition btn btn-outline-success" href="'. route("restaurants.edit",$list->id) .'"><i class="fa fa-eye"></i></a>
                      </div>';
          $vendor_approved = '';
          if($list->vendor_approved == '1')
          {
            $vendor_approved = 'selected';
          }
          $vendor_not_approved = '';
          if($list->vendor_approved == '0')
          {
            $vendor_not_approved = 'selected';
          }
          $query[$key]->restaurant_status = '<select class="form-control restaurant_status" name="vendor_approved" id="'.$list->id.'"><option value="1" '.$vendor_approved .'>'.trans('restaurants.approved').'</option><option value="0" '.$vendor_not_approved.'>'.trans('restaurants.not_approved').'</option></select>';
        }
        $this->sendResponse($query, trans('common.data_found_success'));
      }
      $this->sendResponse([], trans('common.data_found_empty'));

    } catch (Exception $e) {
      $this->ajaxError([], $e->getMessage());
    }

  }
  public function restaurant_status(Request $request){
    DB::beginTransaction();
      try {
        $res = User::where('id',$request->id)->update(['vendor_approved'=>$request->vendor_approved]);

        if($res)
        {
          DB::commit();
          $this->sendResponse(['status'=>'success'], trans('restaurants.status_updated_success'));

        }
        else
        {
          DB::rollback();
          $this->sendResponse(['status'=>'error'], trans('common.status_updated_error'));
        }
        
      } catch (Exception $e) {
        DB::rollback();
        $this->ajaxError([], $e->getMessage());
      }


  }
  
  
}