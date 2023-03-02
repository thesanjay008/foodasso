<?php
namespace App\Http\Controllers\Api\Customer;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\Helpers\CommonHelper;
use App\Models\Hospital;
use App\Models\MedicalRoom;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\InsuranceCompany;
use App\Http\Resources\HospitalResource;
use App\Http\Resources\HospitalListResource;
use App\Http\Resources\HospitalRoomResource;
use App\Http\Resources\ClinicListResource;
use App\Http\Resources\DoctorListResource;
use App\Http\Resources\HospitalDoctorListResource;
use App\Http\Resources\InsuranceCompanyResource;

class HospitalController extends BaseController
{
    /**
     * Insurance List
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
      
      $search   = $request->search;
      $order_by = "id";
      $order    = "DESC";
      $page     = $request->page ?? '1';
      $count    = $request->count ?? '10000';
      $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'en';
      
      if ($page <= 0){ $page = 1; }
      $start  = $count * ($page - 1);


      DB::beginTransaction();
      try{

          $query = Hospital::select('hospital.*')->where('status','active');
          
          // SEARCH
          if($search){
            $query = $query->whereHas('translation',function($query) use ($search){
              $query->where('hospital_name','like','%'.$search.'%');
            });
          }

          

          // ORDER BY NAME
          if($request->order){
            if($request->order == 'AtoZ'){
              $query->orderBy('hospital_name','ASC');
              $query->where('locale', $language);
            } else if($request->order == 'ZtoA'){
              $query->orderBy('hospital_name','DESC');
              $query->where('locale', $language);
            }
          }

          // NEARBY LIST
          if($request->show_nearest_first == 1){
            if(!empty($request->latitude) && !empty($request->longitude)){
              $query = $query->Distance($request->latitude, $request->longitude);
              $order_by = 'distance';
              $order    = 'ASC';
            }
          }

          // RATINGS
          if($request->ratings){
            $query = $query->whereNotNull('total_rating');
            $order_by = 'total_rating';
            $order    = 'ASC';
          }

          // ORDER BY REGISTRATION FEES
          if($request->registration_fees){
            if($request->registration_fees == 'Min'){
              $order_by = 'registration_fees';
              $order    = 'ASC';

            } else if($request->order == 'Max'){
              $order_by = 'registration_fees';
              $order    = 'DESC';
            }
          }

          // ORDER BY REGISTRATION FEES
          if($request->working_hours){
            if($request->working_hours == '1'){
              $query->where('timeslot','Morning');

            } else if($request->working_hours == '2'){
              $query->where('timeslot','Evening');

            } else if($request->working_hours == '3'){
              $query->where('timeslot','24');
            }
          }

          // LIST BY INSURANCE COMPANY
          if($request->insurance_company_id || $request->order){
            
            $query = $query->join('hospital_translations', 'hospital_translations.hospital_id','=', 'hospital.id');
          }

          //$query = $query->orderBy($order_by, $order)->groupBy('id')->offset($start)->limit($count)->get();
          $query = $query->groupBy('id')->offset($start)->limit($count)->get();

          if($query->count() > 0) {
            foreach($query as $key=> $list){
              $query[$key]->contact = [
                'lattitude'    =>$list->lattitude ? $list->lattitude : "",
                'longitude'    =>$list->longitude ? $list->longitude : "",
                'address'      =>$list->address ? $list->address : "",
                'phone_number' =>$list->phone_number ? $list->phone_number : "",
                'email_address'=>$list->email_address ? $list->email_address : ""
              ];

              $inc = InsuranceCompany::select('insurance_company.*')
              ->join('hospital_insurance_company', 'hospital_insurance_company.ins_com_id','=', 'insurance_company.id')
              ->where('hospital_insurance_company.hospital_id',$list->id)
              ->where('insurance_company.status','active')
              ->get();
              $query[$key]->insurance_companies = InsuranceCompanyResource::collection($inc);
              $query[$key]->clinics = ClinicListResource::collection(Clinic::where(['hospital_id'=>$list->id, 'status'=>'active'])->get());
              $query[$key]->doctors = HospitalDoctorListResource::collection(Doctor::where(['owner_id'=>$list->id, 'owner_type'=>'Hospital', 'status'=>'active'])->get());
            }

            return $this->sendArrayResponse(HospitalListResource::collection($query),trans('customer_api.data_found_success'));
          }
          return $this->sendArrayResponse('',trans('customer_api.data_found_empty'));
      }catch (\Exception $e) {
        DB::rollback();
        return $this->sendError('', $e->getMessage());
      }
    }

    public function index_OLD(Request $request){
        
      $search = $request->search;
      $page   = $request->page ?? '1';
      $count  = $request->count ?? '10000';

      if ($page <= 0){ $page = 1; }
      $start  = $count * ($page - 1);

      DB::beginTransaction();
      try{

          $query = Hospital::where('status','active')->paginate();
          // Search
          if($search){
              $query = $query->whereHas('translation',function($query) use ($search){
                $query->where('hospital_name','like','%'.$search.'%');
              });
          }
          
          if(count($query)>0) {
            return $this->sendArrayResponse(HospitalListResource::collection($query),trans('customer_api.data_found_success'));
          }
          return $this->sendArrayResponse('',trans('customer_api.data_found_empty'));
      }catch (\Exception $e) {
        DB::rollback();
        return $this->sendError('', $e->getMessage());
      }
    }

    public function show($id = null){
        try{
            $query = Hospital::where('id',$id)->first();
            if($query) {
              $query->insurance_companies = InsuranceCompanyResource::collection(InsuranceCompany::get());
              $query->clinics = ClinicListResource::collection(Clinic::where(['hospital_id'=>$query->id, 'status'=>'active'])->get());
              $query->doctors = DoctorListResource::collection(Doctor::where(['owner_id'=>$query->id, 'owner_type'=>'Hospital', 'status'=>'active'])->get());
              return $this->sendResponse(new HospitalResource($query),trans('customer_api.data_found_success'));
            }
            return $this->sendResponse('',trans('customer_api.data_found_empty'));
        }catch (\Exception $e) { 
          return $this->sendError('', $e->getMessage()); 
        }
    }

    // GET HOSPITAL ROOMS
    public function hospitalRooms(Request $request){
      $validator = Validator::make($request->all(), [
          'hospital_id' => 'required'
      ]);
      if($validator->fails()){
        return $this->sendValidationError('', $validator->errors()->first());
      }

      DB::beginTransaction();
      try{
        $details = Hospital::where('id',$request->hospital_id)->first();
        if(!empty($details)){
          return $this->sendResponse(new HospitalRoomResource($details),trans('customer_api.data_found_success'));
        }
        return $this->sendResponse('',trans('customer_api.data_found_empty'));
      }catch (\Exception $e) { 
        DB::rollback();
        return $this->sendError('', $e->getMessage()); 
      }
    }
}
