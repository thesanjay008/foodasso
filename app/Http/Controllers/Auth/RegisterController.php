<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\CommonController;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\DeviceDetail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;


class RegisterController extends CommonController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        // print_r("this"); exit;
        // $vendor_types = VendorType::where('status','active')->get();
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

      // print_r($data);die;
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // 'dob' => 'required|max:10',
            // 'phone_number' => 'required|min:8|unique:users',
            'password' => 'required|min:8',
            // 'gender'  => 'required',
            'check'         =>  'required',
            "restaurent_english" => "required_if:check,==,on",
            "restaurent_armenian" => "required_if:check,==,on",
            "restaurent_russian" => "required_if:check,==,on",
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
		$is_restaurant = $request->is_restaurant ? 1 : 0;
		$user_tyle	   = 'Customer';
		
        $validator = Validator::make($request->all(), [
            'email'				=> 'required|email|string|max:99|unique:users',
            'name'				=> 'required|string|min:3|max:99',
            'mobile_number'		=> 'required|min:8|max:15|unique:users',
            'password'			=> 'required|min:6|max:15|confirmed',
        ]);
        
        if($validator->fails()){
            $this->ajaxValidationError($validator->errors(), trans('common.error'));
        }
		
		if($is_restaurant){
			$validator = Validator::make($request->all(), [
				'restaurent_english'	=> 'required|string|min:3|max:99',
			]);
			if($validator->fails()){
				$this->ajaxValidationError($validator->errors(), trans('common.error'));
			}
			$user_tyle = 'Customer';
		}
		
		DB::beginTransaction();
		try
		{
			// USER REGISTER
			$user = User::create(['email'=>$request->email, 'name'=>$request->name, 'mobile_number'=>$request->mobile_number, 'user_type'=>$user_tyle, 'email_verified_at'=>date('Y-m-d h:i:s'), 'password'=>Hash::make($request->password)]);
            //$user->assignRole('Vendor');
			if($user){
				$return = Auth::guard('web')->login($user);
				DB::commit();
                
				$response = [
                  'success' => "1",
                  'status'  => "200",
                  'message' => trans('common.register_success'),
                  'html' => trans('common.verify_account'),
                  'data'  => $user
                ];
                //return response()->json($response);
				return $this->sendResponse($response, trans('common.register_success'));
			}
			
			DB::rollback();
			$this->ajaxError([], trans('common.try_again'));
			
		} catch (Exception $e) {
            DB::rollback();
            $this->ajaxError([], trans('common.try_again'));
        }
    }
}
