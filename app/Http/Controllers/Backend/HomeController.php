<?php
namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;

class HomeController extends Controller
{
	/**
	* Create a new controller instance.
	* @return void
	*/
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
	* Show the application dashboard.
	* @return \Illuminate\Contracts\Support\Renderable
	*/
	public function index(){
        
		$user = Auth()->user();
        if(empty($user)){ exit('Unauthorized access'); }
		
		// Superadmin
        if(in_array($user->user_type, ['superAdmin','Restaurant'])){
			
			return redirect()->route('create.new.order');
        }else{
            return redirect()->route('firstPage');
        }
    }
	
	public function dashboard(){
		
		$user = Auth()->user();
        if(empty($user)){ exit('Unauthorized access'); }
		
		$page		= 'dashboard';
		$page_title = trans('backend.dashboard');
		
		// Superadmin Dashboard
        if(in_array($user->user_type, ['superAdmin','Editor'])){
			
			// Statistics
			$data 						= new \stdClass();
            $data->total_orders			= Order::where('status','!=','Temporary')->count();
            $data->open_orders			= Order::where('status','=','New')->count();
			$data->recent_orders		= Order::where('status', '!=', 'Temporary')->orderBy('id', 'DESC')->offset(0)->limit(5)->get();
            $data->today_revenue		= Order::whereDate('created_at', Carbon::today())->where('status','=','Delivered')->sum('grand_total');
            $data->this_month_revenue	= Order::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->where('status','=','Delivered')->sum('grand_total');
            $data->total_revenue		= Order::where('status','=','Delivered')->sum('grand_total');
            return view('backend.dashboard.'.$user->user_type,compact('page', 'page_title','data'));
        }
    }
}