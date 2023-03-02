<?php

namespace App\Http\Controllers\Api\Customer\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;
use DB,Settings;
use Authy\AuthyApi;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\CustomerPassword;
use App\Models\Helpers\CommonHelper;
use App\Models\SmsVerificationNew;

class PasswordResetController extends BaseController
{
    /**
     * Reset password
     * @return \Illuminate\Http\Response
     */
    public function forgot_password(Request $request){
        $validator = Validator::make($request->all(),[
            'phone_number' => 'required|min:8|max:15',
            'country_code' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }

        DB::beginTransaction();
        try{
            $dataArray = $request->all();
            $user = User::where('phone_number',$request->phone_number)->first();
            if(empty($user)){
                return $this->sendError('', trans('customer_api.no_account_user'));
            }

            $fourRandomDigit = rand(1000,9999);
            $message ='You have made a request for forgot password Please Use '. $fourRandomDigit .' to reset your password';
            //$sent = CommonHelper::sendOTP($request->country_code . $request->phone_number, $message);
            $sent = $this->sendMailNew($user->email, trans('customer_api.reset_pass_token'), $message);
            if($sent){
                $query = SmsVerificationNew::create(['mobile_number' => $request->country_code . $request->phone_number,'code' => $fourRandomDigit]);
                if($query){
                    DB::commit();
                    return $this->sendResponse("", trans('customer_api.reset_token_sent_email'));
                }else{
                    DB::rollback();
                    return $this->sendError('', trans('customer_api.otp_sent_error'));
                }
            } else {
                DB::rollback();
                return $this->sendError('', trans('customer_api.otp_sent_error'));
            }     
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    public function reset_password(Request $request){
        $validator = Validator::make($request->all(),[
            'phone_number' => 'required|min:8|max:15',
            'country_code' => 'required',
            'password'   => 'required|max:15|min:6',
            'confirm_password' => 'required|max:15|min:6|same:password',
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());
        }

        $user = User::where('phone_number',$request->phone_number)->first();
        if(empty($user)){
            return $this->sendError('', trans('customer_api.no_account_user'));
        }
        
        DB::beginTransaction();
        try{
            $query = User::where('id', $user->id)->update(['password' => Hash::make($request->password)]);
            if($query){
                DB::commit();
                return $this->sendResponse('',trans('customer_api.reset_password_success'));
            }
            return $this->sendResponse('',trans('customer_api.reset_password_error'));
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }

    // Verify Number
    public function verify_me($token)
    {
        DB::beginTransaction();
        try{
            $this->authy = new AuthyApi(config('services.twilio.authy_api_key'));

            $phone_number = explode('&',decrypt($token));
            $country_code = $phone_number[0];
            $phone_no = $phone_number[1];

            $user = User::where('phone_number',$country_code.' '.$phone_no)->first();
            if(empty($user)){
                return $this->sendError('', trans('customer_api.forgot_password_user'));
            }
            if($user->forgot_password_request == 'yes'){
                if(Settings::has('twilio_sid')){
                    $twilio_id = Settings::get('twilio_sid');
                } else {
                    $twilio_id = config('services.twilio.sid');
                }
                if(Settings::has('twilio_auth_token')){
                    $twilio_token = Settings::get('twilio_auth_token');
                } else {
                    $twilio_token = config('services.twilio.token');
                }
                if(Settings::has('twilio_from')){
                    $twilio_number  = Settings::get('twilio_from');
                } else {
                    $twilio_number  = config('services.twilio.from');
                }
           
                //password encrypt - decrypt
                $password_encrypted = @$user->get_password->password;
                $password_decrypted = ($password_encrypted) ? decrypt($password_encrypted): ''; 
            
                $message ='Hello '.$user->name.', Your phone number is : '.$user->phone_number.' and new password is: '.$password_decrypted;
                $twilio = new Client($twilio_id, $twilio_token);

                $message = $twilio->messages
                          ->create($user->phone_number, 
                           [
                               "body" => $message,
                               "from" => $twilio_number
                           ]);

                if($message){
                    $user->forgot_password_request = 'no';
                    $user->update();
                    DB::commit();
                    return $this->sendResponse('', trans('customer_api.forgot_password_success'));
                }else{
                    DB::rollback();
                    return $this->sendError('', trans('customer_api.forgot_password_error')); 
                }
            } else {
                DB::rollback();
                return $this->sendError('', trans('customer_api.no_forgot_request_found'));     
            }    
        } catch (\Twilio\Exceptions\RestException $ex) {
            DB::rollback();
            return $this->sendError('', $ex->getMessage()); 
        } catch (DecryptException $e) {
            //return "dsadsad";
            DB::rollback();
            return $this->sendError('', $e->getMessage()); 
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', $e->getMessage()); 
        }
    }
    /**
     * Reset password
     * @return \Illuminate\Http\Response
     */
    public function change_password(Request $request){
        $validator = Validator::make($request->all(),[
            'password' => 'required',
            'new_password'   => 'required|max:15|min:6',
            'confirm_password' => 'required|max:15|min:6|same:new_password',
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }
        try {
            if(Hash::check($request->password, auth()->user()->password)){
                $user = auth()->user();
                $user->update(['password'=> Hash::make($request->new_password)]);

                // updating password in customer passwords table
                $customer_pass = CustomerPassword::where('customer_id', $user->id)->get();
                if($customer_pass->count()){
                    CustomerPassword::where('customer_id', $user->id)->update(['password' => encrypt($request->new_password)]);
                }
                return $this->sendResponse('',trans('customer_api.reset_password_success'));
            } else {
                return $this->sendError('', trans('customer_api.old_password_not_matched')); 
            }
        } catch (Exception $e) {
            return $this->sendError('', $e->getMessage()); 
        }
    }
}
