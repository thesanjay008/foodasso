<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class CmsController extends Controller
{

    /**
     * Show the application CMS pages.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function aboutUs(){
		$page		= 'about-us';
        $page_title = trans('title.about_us');
		
		return view('theme/about-us',compact('page', 'page_title'));
    }
	
	public function takeAtour(){
		$page		= 'about-us';
        $page_title = trans('title.take_a_tour');
		
		return view('theme/about-us',compact('page', 'page_title'));
    }
	
	public function qrCode(){
		$page		= 'qr-code';
        $page_title = trans('title.qr_code');
		
		return view('theme/qr-code',compact('page', 'page_title'));
    }
	
	public function contactUs(){
		$page		= 'contact-us';
        $page_title = trans('title.contact_us');
		
		return view('theme/contact-us',compact('page', 'page_title'));
    }
	
	public function terms(){
		$page		= 'terms';
        $page_title = trans('title.terms');
		
		return view('theme/terms',compact('page', 'page_title'));
    }
	
	public function privacy(){
		$page		= 'privacy';
        $page_title = trans('title.privacy_policy');
		
		return view('theme/privacy',compact('page', 'page_title'));
    }
	
	public function refund(){
		$page		= 'refund';
        $page_title = trans('title.refund');
		
		return view('theme/refund',compact('page', 'page_title'));
    }
}