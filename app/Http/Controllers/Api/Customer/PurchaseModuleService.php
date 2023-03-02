<?php

namespace App\Http\Controllers\Api\Customer;

use Validator;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Helpers\CommonHelper;
use App\Models\Offer;
use App\Models\InsurancePackage;
use App\Models\LaboratoryPackage;
use App\Models\PurchaseHistory;
use App\Http\Resources\OfferResource;

class PurchaseModuleService extends BaseController
{

  // PURCHASE
  public function purchase(Request $request)
  {
    $amount     = '0';
    $quantity   = $request->quantity ?? '1';
    $validator  = Validator::make($request->all(), [
      'id'        => 'required|int',
      'module'    => 'required',
    ]);
    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());
    }

    $user_id = Auth::user()->id;
    if(empty($user_id)){
      return $this->sendError('',trans('customer_api.invalid_user'));
    }

    DB::beginTransaction();
    try{
        
      switch ($request->module) {
        case "Offer":
          $data = Offer::where(['id'=>$request->id])->first();
          if(empty($data)){
            return $this->sendError('', trans('customer_api.something_went_wrong'));
          }
          $amount = $data->offer_price;
          break;
        case "HealthInsurance":
          $data = InsurancePackage::where('id', $request->id)->first();
          if(empty($data)){
            return $this->sendError('', trans('customer_api.something_went_wrong'));
          }
          $amount = $data->price;
          break;
        case "PortableInspection":
          $data = LaboratoryPackage::where('id', $request->id)->first();
          if(empty($data)){
            return $this->sendError('', trans('customer_api.something_went_wrong'));
          }
          $amount = $data->price;
          break;
        default:
          return $this->sendResponse($success, trans('customer_api.invalid_module'));
      }

      if(empty($amount)){
        return $this->sendError('', trans('customer_api.something_went_wrong'));
      }

      $insertData = [
        'user_id'           =>$user_id,
        'owner_id'          =>$request->id,
        'owner_type'        =>$request->module,
        'amount'            =>$amount,
        'payment_method_id' =>'1',
        'date'              =>date('Y-m-d'),
      ];
      $return = PurchaseHistory::create($insertData);
      
      if($return){
        $postfields = array(
          'merchantCode'  => env("HESABE_MERCHENT_CODE"),
          'amount'        => $amount,
          'responseUrl'   => env('APP_URL').'/api/customer/payment-success?id='. $return->id .'&module='. $request->module,
          'failureUrl'    => env('APP_URL').'/api/customer/payment-failed?id='. $return->id .'&module='. $request->module,
          'description'   => 'Description',
          'variable1'     => 'variable2',
          'variable2'     => 'module:'. $request->module,
          'variable3'     => '',
          'variable4'     => '',
          'variable5'     => '',
          'version'       => '2',
          'paymentType'   => '0',
        );
        $hesabe_data = CommonHelper::hesabe_create_order($postfields);
        
        DB::commit();
        $success['id']                =  $return->id;
        $success['payment_url']       =  'http://api.hesbstck.com/payment?data='.$hesabe_data;
        return $this->sendResponse($success, trans('customer_api.order_ready_to_payment'));
      }
      return $this->sendError('', trans('customer_api.something_went_wrong'));
      
    }catch (\Exception $e) { 
      DB::rollback();
      return $this->sendError('', $e->getMessage()); 
    }
  }
}
