<?php

namespace App\Http\Controllers\theme;

use Auth;
use App;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Order;

class CheckoutController extends CommonController
{
    /**
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
	* Show the application Checkout page.
	*/
	public function index(){
		
		$user = Auth()->user();
		if(empty($user)){
			return redirect()->route('menuPage');
		}
		
		try {
			$page       = 'checkoutPage';
			$page_title = trans('title.checkout');
			
			// GET CART DATA
			$cartData = Cart::with(['product'])->where('user_id', $user->id)->get();
			$addresses = Address::where('user_id', $user->id)->get();
			
			if($cartData->count() > 0){
				return view('theme.order.checkout', compact('page','page_title','cartData','addresses'));
			}
			
			return redirect()->route('menuPage');

		} catch (Exception $e) {
		  return redirect()->route('menuPage')->withError($e->getMessage());
		}
	}
	
    /**
	* Show the application Table Checkout page.
	*/
	public function table($table_id = ''){
		
		try {
			$page       = 'tableCheckoutPage';
			$page_title = trans('title.checkout');
			
			// GET CART DATA
			$user = Auth()->user();
			if(empty($user)){
				$cartData = Cart::with(['product'])->where(['table_id' => $table_id, 'token' => csrf_token()])->get();
			}else{
				$cartData = Cart::with(['product'])->where(['table_id' => $table_id, 'user_id' => $user->id])->get();
			}
			
			if($cartData->count() > 0){
				return view('theme.table.checkout', compact('page','page_title', 'table_id', 'cartData'));
			}
			
			return redirect()->route('table.menu',[$table_id]);
			
		} catch (Exception $e) {
			return redirect()->route('table.menu',[$table_id])->withError($e->getMessage());
		}
	}
}