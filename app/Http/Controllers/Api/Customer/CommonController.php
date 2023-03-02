<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB,Settings;
use App\Models\Helpers\CommonHelper;
use App\Models\SmsVerificationNew;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;

class CommonController extends BaseController
{
    
    /**
     * Splash Screen Data
     *
     * @return \Illuminate\Http\Response
     */
    public function getsplashScreen()
    {
        // Temporary LOGO
		$success['logo'] = 'https://www.nashikproperty.com/uploads/builder-logo/default-logo.png';
        return $this->sendResponse($success, trans('customer_api.splashscreen_found'));
    }

    /**
     * SEND OTP
     *
     * @return \Illuminate\Http\Response
     */
    public function sendOTP(Request $request){
        $validator = Validator::make($request->all(),[
            'phone_number' => 'required|min:8|max:15',
            'country_code' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }

        DB::beginTransaction();
        try{
            $fourRandomDigit = rand(1000,9999);
            $message ='You have made a request for OTP Please Use '. $fourRandomDigit .' to reset your password';
            //$sent = CommonHelper::sendOTP($request->country_code . $request->phone_number, $message);
            $sent = true;
            if($sent){
                $query = SmsVerificationNew::create(['mobile_number' => $request->country_code . $request->phone_number,'code'    => $fourRandomDigit]);
                if($query){
                    DB::commit();
                    return $this->sendResponse("", trans('customer_api.otp_sent_success'));
                }else{
                    DB::rollback();
                    return $this->sendError('', trans('customer_api.otp_sent_error'));
                }
            } else {
                DB::rollback();
                return $this->sendError('', trans('customer_api.otp_sent_error'));
            }     
        } catch (\Twilio\Exceptions\RestException $ex) {
            DB::rollback();
            return $this->sendError('', $ex->getMessage()); 
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    public function verifyOTP(Request $request){
        $validator = Validator::make($request->all(),[
            'otp' => 'required|min:4|max:4',
            'phone_number' => 'required|min:8|max:15',
            'country_code' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }
        
        DB::beginTransaction();
        try{
            $dataArray = $request->all();
            $status = SmsVerificationNew::where(array('mobile_number'=>$request->country_code . $request->phone_number, 'code'=>$request->otp, 'status'=>'pending'))->first();
            if(empty($status)){
                return $this->sendError('', trans('customer_api.invalid_otp'));
            }
            
            $status->status = 'verified';
            $status->update();
            DB::commit();
            return $this->sendResponse("", trans('customer_api.otp_verified_success'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', trans('customer_api.otp_verified_error'));
        }
    }

    // TEST FUNCTION
    public function test(Request $request){
        
        $user = User::where(['id'=>'4'])->first();
        $return = $this->sendNotificationNew($user,'Offer Purchased','You have Successfully Purchased Offer: 20% discount in medicine','Offer','9');
        return $this->sendResponse($return, trans('customer_api.splashscreen_found'));
    }
}
