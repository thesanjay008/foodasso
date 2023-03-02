<?php

namespace App\Http\Controllers\Api\Customer\Auth;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator,DB;
use App\Models\DeviceDetail;
use Spatie\Permission\Models\Role;
use Twilio\Rest\Client;
use Authy\AuthyApi;
use App\Models\SmsVerification;

class AuthController extends BaseController
{
    
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required|min:2|max:4',
            'phone_number' => 'required|min:8|max:15',
            'password' => 'required|min:6|max:55'
        ]);
        if($validator->fails()){
            return $this->sendValidationError('', $validator->errors()->first());       
        }
        if($request->phone_number){
        $auth_check = Auth::attempt(['country_code' => $request->country_code, 'phone_number' => $request->phone_number, 'password' => $request->password,'user_type'=>'Customer']);
        }else{
            $validator = Validator::make($request->all(), [
                'email' => 'required|min:5|max:99',
            ]);
            //return \Hash::make($request->password);
            if($validator->fails()){
                return $this->sendValidationError('', $validator->errors()->first());       
            }
            $auth_check = Auth::attempt(['email' => $request->email, 'password' => $request->password,'user_type'=>'Customer']);
        }
        if($auth_check){
            $user = Auth::user();
            if($user){
                DB::table('oauth_access_tokens')
                ->where('user_id', $user->id)
                ->update([
                    'revoked' => true
                ]);
            }
            
            //Add response details into variable
            $success['token']            =  $user->createToken(config('app.name'))->accessToken;
            $success['id']               =  (string)$user->id;
            $success['gender']           =  $user->gender ? $user->gender : '';
            $success['age']              =  $user->dob ? (string) date_diff(date_create($user->dob), date_create('today'))->y : '';
            $success['dob']              =  $user->dob ? date('d-m-Y', strtotime($user->dob)) : '';
            $success['name']             =  $user->name;
            $success['email']            =  $user->email;
            $success['country_code']     =  $user->country_code;
            $success['phone_number']     =  $user->phone_number;
            $success['status']           =  $user->status;
            $success['user_type']        =  $user->user_type;
            
            $data = $request->except('phone_number','password','user_type');
            $createArray = array();

            foreach ($data as $key => $value) {
                $createArray[$key] = $value;
            }

            $device_detail = DeviceDetail::where('user_id',Auth::user()->id)->first();
            if($device_detail){
                $device_detail->update($createArray);
            } else {
                $createArray['user_id'] = Auth::user()->id;
                DeviceDetail::create($createArray);
            }

            if(strtolower($user->status) != 'active') {
                return $this->sendError('',trans('customer_api.login_status'), 200, 202);
            } else { 
                return $this->sendResponse($success, trans('customer_api.login_success'));
            }  
        }  else {
            return $this->sendError('',trans('customer_api.login_error'));
        }
    }

    /**
     * Registration api
     *
     * @return \Illuminate\Http\Response
     */
    public function registration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|min:3|max:99',
            'gender'    => 'required|min:4|max:6',
            'dob'       => 'required',
            'phone_number' => 'required|min:8|max:15|unique:users',
            'email'     => 'required|string|email|max:99|unique:users',
            'country_code' => 'required|min:2|max:4',
            'password'  => 'required|min:6|max:10',
        ]);
        
        //return \Hash::make($request->password);
        if($validator->fails()){
            return $this->sendValidationError('', $validator->errors()->first());       
        }

        // EMAIL VALIDATION
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return $this->sendError('',trans('customer_api.invalid_email'));
        }
        
        $data = array(
            'name'      => $request->name,
            'gender'    => $request->gender,
            'dob'       => date('Y-m-d', strtotime($request->dob)),
            'country_code' => $request->country_code,
            'phone_number' => $request->phone_number,
            'profile_image' => '',
            'status'    => 'active',
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'user_type' => 'Customer'
        );

        $user = new User();
        $user->fill($data);

        DB::beginTransaction();
        try {
            if($user->save()){
                
                // Save Device Details
                $inputs = $request->except('country_code','phone_number','password','user_type');
                $inputs['user_id'] = $user->id;
                $createArray = array();
                foreach ($inputs as $key => $value) {
                    $createArray[$key] = $value;
                }
                DeviceDetail::create($createArray);
                DB::commit();

                // Send OTP
                //$fourRandomDigit = rand(1000,9999);
                $fourRandomDigit = 1234;
                //$query = SmsVerification::create(['mobile_number' => $request->country_code . $request->phone_number,'code'    => $fourRandomDigit
                //]);

                //Add response details into variable
                $success['token']            =  $user->createToken(config('app.name'))->accessToken;
                $success['id']               =  (string)$user->id;
                $success['gender']           =  $user->gender ? $user->gender : '';
                $success['age']              =  $user->dob ? (string) date_diff(date_create($user->dob), date_create('today'))->y : '';
                $success['dob']              =  $user->dob ? date('d-m-Y', strtotime($user->dob)) : '';
                $success['name']             =  $user->name;
                $success['email']            =  $user->email;
                $success['country_code']     =  $user->country_code;
                $success['phone_number']     =  $user->phone_number;
                $success['status']           =  'active';
                $success['user_type']        =  $user->user_type;
                
                return $this->sendResponse($success, trans('customer_api.registration_success'));
            }else{
                DB::rollback();
                return $this->sendError($this->object,trans('auth.registration_error'));
            }
        }catch (Exception $e) {
            DB::rollback();
            return $this->sendException($this->object,$e->getMessage());
        }
        
        return $this->sendError('',trans('customer_api.registration_error'));
    }

    /**
     * Active account
     *
     * @return [string] message
     */
    public function active(Request $request){
        $validator = Validator::make($request->all(),[
            'otp' => 'required|min:4|max:4',
            'country_code' => 'required|min:2|max:4',
            'phone_number' => 'required|min:8|max:15'
        ]);

        if($validator->fails()){
            return $this->sendValidationError('',$validator->errors()->first());       
        }
        
        $user = User::where(array('country_code'=>$request->country_code, 'phone_number'=>$request->phone_number, 'status'=>'pending'))->first();
        if(empty($user)){
            return $this->sendError('', trans('customer_api.invalid_user'));
        }

        DB::beginTransaction();
        try{
            $dataArray = $request->all();
            $result = SmsVerification::where(array('mobile_number'=>$request->country_code . $request->phone_number, 'code'=>$request->otp, 'status'=>'pending'))->first();
            if(empty($result)){
                return $this->sendError('', trans('customer_api.invalid_otp'));
            }
            $result->status = 'verified';
            $result->update();
            //DB::commit();

            $user = User::where(array('phone_number'=>$request->phone_number, 'status'=>'pending'))->first();
            if(empty($user)){
                return $this->sendError('', trans('customer_api.invalid_user'));
            }
            
            // Update user status
            $user->status = 'active';
            $user->update();
            DB::commit();

            //Set response details into variable
            $success['token']            =  $user->createToken(config('app.name'))->accessToken;
            $success['id']               =  (string)$user->id;
            $success['name']             =  $user->name;
            $success['email']            =  $user->email;
            $success['phone_number']     =  $user->phone_number;
            $success['gender']           =  $user->gender ? $user->gender : '';
            $success['age']              =  $user->dob ? (string) date_diff(date_create($user->dob), date_create('today'))->y : '';
            $success['dob']              =  $user->dob ? date('d-m-Y', strtotime($user->dob)): '';
            $success['status']           =  'active';
            $success['user_type']        =  $user->user_type;
            return $this->sendResponse($success, trans('customer_api.account_act_success'));
            
        } catch (\Exception $e) { 
          DB::rollback();
          return $this->sendError('', trans('customer_api.account_act_error'));
        }
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(){
        $user = Auth::user();
        /*$device_detail = $user->device_detail;
        if($device_detail){
            $device_detail->delete();
        }*/
        $user->token()->revoke();
        return $this->sendResponse('', trans('customer_api.logout'));
    }
}
