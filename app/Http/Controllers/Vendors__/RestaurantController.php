<?php

namespace App\Http\Controllers\Vendors;

 use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Models\Helpers\CommonHelper;
use Validator,Auth,DB,Storage;
use Illuminate\Validation\Rule;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\RestaurantCategory;
use App\Models\Country;
use Carbon\Carbon;
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
     //$this->middleware(['auth','vendor_approved','vendor_subscribed']);
    $this->middleware('permission:vendor_restaurant-edit', ['only' => ['edit','update']]);
  }
  
	// ADD NEW
	public function index(){
		return view('vendors.product.list');
	}

	public function edit(){
		try
	  {
		$page_title = trans('restaurants.update');
		$user = Auth()->user();
		
		if(!empty($user)){
			$data 	 = Restaurant::where('owner_id',$user->id)->first();
			$country = Country::where('status','active')->get();
      $categories = Category::where('status','active')->get();

      $restaurant_categories = RestaurantCategory::where('restaurant_id',$data->id)->pluck('category_id')->toArray();
			
			if(!empty($data)){
				return view('vendors.restaurant.edit',compact(['data','page_title', 'country','categories','restaurant_categories']));
			}
		}
		return redirect()->route('vendorDashboard')->with('error', trans('common.invalid_restaurant'));
		  
      } catch (Exception $e) {
          return redirect()->route('vendorDashboard')->with('error', $e->getMessage());
      }
    }
  
	// STORE
	public function store(){
		return view('vendors.product.add');
	}
  

	public function update(Request $request){
        // echo "<pre>";print_r($request->all());exit;
        $home 		= auth()->user()->restaurant;
        $restaurant = Restaurant::find($home->id);
        $validator = Validator::make($request->all(), [
          'title_in_english'   => 'required|min:3|max:99',
          'title_in_armenians' => 'required|min:3|max:99',
          'title_in_russia'    => 'required|min:3|max:99',
          // 'company_name:en' => 'required|min:3|max:99',
          // 'company_name:hy' => 'required|min:3|max:99',
          // 'company_name:ru' => 'required|min:3|max:99',
          'email'              => 'required|email|max:50',
          'phone_number'       => 'required|numeric',
          'address_en'         => 'required|min:3|max:1000',
          'address_hy'         => 'required|min:3|max:1000',
          'address_ru'         => 'required|min:3|max:1000',
          // 'flat_discount'      => 'required|numeric|min:1|max:100',
          'zip_code'           => 'required|numeric',
          'city_id'            => 'required|numeric',
          // 'state_id'           => 'required|numeric',
          'country_id'         => 'required|numeric',
          'categories'         => 'required',
        ]);

        if($validator->fails()){
          $this->ajaxValidationError($validator->errors(), trans('common.error'));
        }

        DB::beginTransaction();
        try {
            $updateArray               = $request->all();
            $updateArray['city_id']    = $request->city_id;
            $updateArray['state_id']   = $request->state_id;
            $updateArray['country_id'] = $request->country_id;
            $updateArray['title:en']   = $request->title_in_english;
            $updateArray['title:hy']   = $request->title_in_armenians;
            $updateArray['title:ru']   = $request->title_in_russia;
            $updateArray['address:en'] = $request->address_en;
            $updateArray['address:hy'] = $request->address_hy;
            $updateArray['address:ru'] = $request->address_ru;
            $updateArray['latitude']   = $request->latitude;
            $updateArray['longitude']  = $request->longitude;
            $updateArray['email']  = $request->email;
            $updateArray['phone_number']  = $request->phone_number;
            $updateArray['zip_code']  = $request->zip_code;
            $updateArray['flat_discount']  = $request->flat_discount ? $request->flat_discount:0;
    
            if(empty($restaurant) && $restaurant->count() == 0){
                return redirect()->route('vendor_edit')->with('error', trans('restaurants.data_not_found'));
            }

			      $updateArray['image'] = $restaurant->image;
            $updateArray['banner_image'] = $restaurant->banner_image;
            if($request->image != "undefined"){
                if(file_exists($restaurant->image)){
                  unlink($restaurant->image);
                }
                $path = $this->saveMedia($request->file('image'));
                $updateArray['image'] = $path;
            }

            if($request->banner_image != "undefined"){
                if(file_exists($restaurant->banner_image)){
                  unlink($restaurant->banner_image);
                }
                $banner_img_path = $this->saveMedia($request->file('banner_image'));
                $updateArray['banner_image'] = $banner_img_path;
            }
            
            $restaurant->fill($updateArray);
            $rest_res = $restaurant->save();

            //delete old and update new restaurant categories
            $cat_ids = explode(',', $request->categories);
            $rest_categories = RestaurantCategory::where('restaurant_id',$restaurant->id)->get();
            foreach ($rest_categories as $rest_cat) {
              $rest_cat->delete();
            }
            foreach ($cat_ids as $cat_id) {
              $cat_data = [
                'user_id' => auth()->user()->id,
                'restaurant_id' => $restaurant->id,
                'category_id' => $cat_id
              ];
              RestaurantCategory::create($cat_data);
            }
            if($rest_res){
                DB::commit();
				        $this->sendResponse([], trans('common.updated'));
            } 
            $this->ajaxError([], trans('common.try_again'));
        } catch (Exception $e) {
            DB::rollback();
            $this->ajaxError([], trans('common.try_again'));
        }
	}
  
	// DESTROY
	public function destroy(){
		return view('vendors.product.add');
	}
}