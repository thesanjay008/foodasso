<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Subscription;
use Carbon\Carbon;

class VendorSubscribed
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
        $owner_id = $request->user()->id;
        $sub = Subscription::where(['owner_id'=>$owner_id,'status'=>'active'])->first();
        if($sub != null){
            $current_date = Carbon::now();
            $expiry_date  = Carbon::parse($sub->expiry_date);
            
            if($current_date->greaterThan($expiry_date)){
                
                // Redirect to Subscriptions
                if($request->user()->user_type == 'Hospital'){
                    return redirect()->route('hospital_subscription.index')->with('error', trans('common.vendor_not_subscribed'));
                }
                if($request->user()->user_type == 'Clinic'){
                    return redirect()->route('clinic_subscription.index')->with('error', trans('common.vendor_not_subscribed'));
                }
                if($request->user()->user_type == 'Pharmacy'){
                    return redirect()->route('pharmacy_subscription.index')->with('error', trans('common.vendor_not_subscribed'));
                }
                if($request->user()->user_type == 'PortableInspection'){
                    return redirect()->route('portable_inspection_subscription.index')->with('error', trans('common.vendor_not_subscribed'));
                }
                if($request->user()->user_type == 'HomeNursing'){
                    return redirect()->route('nursing_home_subscription.index')->with('error', trans('common.vendor_not_subscribed'));
                }

            }else{

                //Redirect to dashboard
                return $next($request);
            }
            
        }else{
            
            //ERROR: REDIRECT TO SUBSCRIPTIONS
            // Redirect to Subscriptions
                if($request->user()->user_type == 'Hospital'){
                    return redirect()->route('hospital_subscription.index')->with('error', trans('common.vendor_not_subscribed'));
                }
                if($request->user()->user_type == 'Clinic'){
                    return redirect()->route('clinic_subscription.index')->with('error', trans('common.vendor_not_subscribed'));
                }
                if($request->user()->user_type == 'Pharmacy'){
                    return redirect()->route('pharmacy_subscription.index')->with('error', trans('common.vendor_not_subscribed'));
                }
                if($request->user()->user_type == 'PortableInspection'){
                    return redirect()->route('portable_inspection_subscription.index')->with('error', trans('common.vendor_not_subscribed'));
                }
                if($request->user()->user_type == 'HomeNursing'){
                    return redirect()->route('nursing_home_subscription.index')->with('error', trans('common.vendor_not_subscribed'));
                }
        }

        return $next($request);
    }
}
