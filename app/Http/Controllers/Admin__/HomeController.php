<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
	* Show the application dashboard.
	*
	* @return \Illuminate\Contracts\Support\Renderable
	*/
	public function index(){
        
		$user = Auth()->user();
        if(empty($user)){ exit('Unauthorized access'); }
		
		if(in_array($user->user_type, ['superAdmin','Editor','Vendor'])){
            return redirect()->route('dashboard');

        }else{
            return redirect()->route('firstPage');
        }
    }
	
	public function dashboard(){
		return redirect()->route('dashboard');
    }
}