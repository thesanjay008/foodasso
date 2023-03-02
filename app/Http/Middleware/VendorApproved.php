<?php

namespace App\Http\Middleware;

use Closure;

class VendorApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->vendor_approved == 0) {

            if($request->user()->user_type == 'Hospital'){
                return redirect()->route('hospital_dashboard')->with('error', trans('common.vendor_not_approved'));
            }
            if($request->user()->user_type == 'Clinic'){
                return redirect()->route('clinic_dashboard')->with('error', trans('common.vendor_not_approved'));
            }
            if($request->user()->user_type == 'Pharmacy'){
                return redirect()->route('pharmacy_dashboard')->with('error', trans('common.vendor_not_approved'));
            }
            if($request->user()->user_type == 'PortableInspection'){
                return redirect()->route('portable_inspection_dashboard')->with('error', trans('common.vendor_not_approved'));
            }
            if($request->user()->user_type == 'HomeNursing'){
                return redirect()->route('nursing_dashboard')->with('error', trans('common.vendor_not_approved'));
            }
            
            return $next($request);
        }   
        return $next($request);
    }
}
