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
use App\Models\Helpers\CommonHelper;
use App\Models\Order;
use App\Models\PurchaseHistory;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Http\Resources\PharmacyPurchaseListResource;
use App\Http\Resources\OfferPurchaseListResource;
use App\Http\Resources\PortableInspectionPurchaseListResource;
use App\Http\Resources\InsurancePackagePurchaseListResource;
use App\Http\Resources\MedicationMedicalOrderDetailResource;
use App\Http\Resources\MedicationMedicalOrderDetailItemsResource;

class OrderController extends BaseController
{

  /**
   * ORDERS
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request){
      
    $user_id = Auth::user()->id;
    if(empty($user_id)){
      return $this->sendError('',trans('customer_api.invalid_user'));
    }

    $return = [
      'medication_medical' => [],
      'offer' => [],
      'portable_inspection' => [],
      'insurance_package' => [],
    ];

    try{

      // PHARMACY
      $return['medication_medical'] = PharmacyPurchaseListResource::collection(Order::where(['user_id'=>$user_id, 'payment_status'=>'1'])->orderBy('id', 'DESC')->get());
      
      // OFFER
      $return['offer'] = OfferPurchaseListResource::collection(PurchaseHistory::where(['user_id'=>$user_id, 'owner_type'=>'Offer','payment_status'=>'1'])->orderBy('id', 'DESC')->get());

      $return['portable_inspection'] = PortableInspectionPurchaseListResource::collection(PurchaseHistory::where(['user_id'=>$user_id, 'owner_type'=>'PortableInspection', 'payment_status'=>'1'])->orderBy('id', 'DESC')->get());

      $return['insurance_package'] = InsurancePackagePurchaseListResource::collection(PurchaseHistory::where(['user_id'=>$user_id, 'owner_type'=>'HealthInsurance', 'payment_status'=>'1'])->orderBy('id', 'DESC')->get());
      return $this->sendArrayResponse($return, trans('customer_api.data_found_success'));
      //return $this->sendArrayResponse($return, trans('customer_api.data_found_empty'));

    }catch (\Exception $e) { 
      return $this->sendError('', $e->getMessage()); 
    }
  }

  /**
   * PAYMENT SUCCESS
   * @return \Illuminate\Http\Response
   */
  public function paymet_success(Request $request){
    
    DB::beginTransaction();
    try{
      
      switch ($request->module) {
        case "Offer":
          $PurchaseHistory = PurchaseHistory::where(['id'=>$request->id])->first();
          if($PurchaseHistory){
            $PurchaseHistory->payment_status = 1;
            $PurchaseHistory->status = 'pending';
            $PurchaseHistory->save();
            DB::commit();
          }
          break;
        case "HealthInsurance":
          $PurchaseHistory = PurchaseHistory::where(['id'=>$request->id])->first();
          if($PurchaseHistory){
            $PurchaseHistory->payment_status = 1;
            $PurchaseHistory->status = 'pending';
            $PurchaseHistory->save();
            DB::commit();
          }
          break;
        case "PortableInspection":
          $PurchaseHistory = PurchaseHistory::where(['id'=>$request->id])->first();
          if($PurchaseHistory){
            $PurchaseHistory->payment_status = 1;
            $PurchaseHistory->status = 'pending';
            $PurchaseHistory->save();
            DB::commit();
          }
          break;
        case "Pharmacy":
          $OrderItem = Order::where(['id'=>$request->id])->first();
          if($OrderItem){
            
            $OrderItem->payment_status = 1;
            $OrderItem->save();
            DB::commit();

            // DELETE DATA FROM CART
            Cart::where(['user_id'=>$OrderItem->user_id])->delete();
          }
          break;
        default:
          return redirect('api/customer/payment-success-message');
      }
      return redirect('api/customer/payment-success-message');

    }catch (\Exception $e) { 
      DB::rollback();
      return redirect('api/customer/payment-success-message');
    }
  }

  /**
   * PAMENT ERROR
   * @return \Illuminate\Http\Response
   */
  public function paymet_failed(Request $request){
    
    DB::beginTransaction();
    try{
      
      $OrderItem = Order::where(['id'=>$request->id])->first();
      if($OrderItem){
        
        $OrderItem->payment_status = 2;
        $OrderItem->save();
        DB::commit();
        
      }
      return redirect('api/customer/payment-failed-message');

    }catch (\Exception $e) { 
      DB::rollback();
      return redirect('api/customer/payment-failed-message');
    }
  }

  /**
   * PAMENT SUCCESS MESSAHE
   * @return \Illuminate\Http\Response
   */
  public function paymet_success_message(Request $request){
    echo trans('payment_success');
  }

  /**
   * PAMENT ERROR MESSAHE
   * @return \Illuminate\Http\Response
   */
  public function paymet_failed_message(Request $request){
    echo trans('payment_failed');
  }

  public function MedicationMedicalOrderDetail(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'order_id'  => 'required|exists:orders,id',
    ]);
    if($validator->fails()){
      return $this->sendValidationError('', $validator->errors()->first());       
    }

    $user = Auth::user();
    if(empty($user)){
      return $this->sendError('',trans('customer_api.invalid_user'));
    }

    DB::beginTransaction();
    try {
      
      $query = Order::where(['id'=>$request->order_id])->first();
      if($query){
        $query->items = MedicationMedicalOrderDetailItemsResource::collection(OrderItem::where(['order_id'=>$request->order_id])->get());
        return $this->sendResponse(new MedicationMedicalOrderDetailResource($query), trans('customer_api.data_found_success'));
      }else{
        return $this->sendError('',trans('customer_api.invalid_item'));
      }

    }catch (Exception $e) {
      DB::rollback();
      return $this->sendException($this->object,$e->getMessage());
    }

  }
}
