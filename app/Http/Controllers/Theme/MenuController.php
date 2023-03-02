<?php

namespace App\Http\Controllers\theme;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use Auth;
use App;

use App\Models\Product;
use App\Models\MenuCategory;

class MenuController extends CommonController
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
	* Show the application Menu page.
	*/
	public function index(){
		try {
			$page       = 'menu';
			$page_title = trans('title.menu');

			$menu = MenuCategory::select('menu_category.*')
			  ->join('products as t1', 't1.menu_category_id', '=', 'menu_category.id')
			  ->where('t1.status', 'active')
			  ->groupBy('menu_category.id')
			  ->get();
			if(!empty($menu)){
				foreach($menu as $key=> $list){
					$menu[$key]['products'] = Product::where(['status'=>'active', 'menu_category_id'=>$list->id])->get();
				}
			}
			return view('theme/menuPage', compact('page','page_title','menu'));

		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
	
	    /**
	* Show the application Table Menu.
	*/
	public function tableMenu($table_id = ''){
		try {
			$page       = 'tableMenu';
			$page_title = trans('title.menu');

			$menu = MenuCategory::select('menu_category.*')
			  ->join('products as t1', 't1.menu_category_id', '=', 'menu_category.id')
			  ->where('t1.status', 'active')
			  ->groupBy('menu_category.id')
			  ->get();
			if(!empty($menu)){
				foreach($menu as $key=> $list){
					$menu[$key]['products'] = Product::where(['status'=>'active', 'menu_category_id'=>$list->id])->get();
				}
			}
			return view('theme/table/menuPage', compact('page','page_title','table_id','menu'));

		} catch (Exception $e) {
			return redirect()->back()->withError($e->getMessage());
		}
	}
}