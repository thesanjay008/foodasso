<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Hospital;
use Carbon\Carbon;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $offer_byData = Hospital::where('id',$this->owner_id)->first();
        $offer_by = '';
        if($offer_byData){ $offer_by = $offer_byData->hospital_name ? $offer_byData->hospital_name : ''; }
        
        $avail_days = 0;
        $avail_for_purchase = $this->avail_for_purchase;
        if(strtotime($this->end_date) > time()){
            $avail_days = (int)((strtotime($this->end_date) - strtotime($this->start_date))/86400);
        }
        $valid_days         = 'Available For '. $avail_days .' Days';
        $is_expire          = $avail_days ? 0 : 1;
        if($is_expire == 1) { $avail_for_purchase = 0; }
        
        // return parent::toArray($request);
         return [
            'id'                    => (string) $this->id,
            'image'                 => $this->image ? asset($this->image) : asset(env("DEFAULT_IMAGE")),
            'offer_name'            => $this->offer_name ? $this->offer_name : '',
            'offer_by'              => (string) $offer_by,
            'description'           => $this->description ? $this->description : '',
            'module'                => 'Offer',
            'price'                 => $this->offer_price ? $this->offer_price : '0.00',
            'is_expire'             => (string) $is_expire,
            'avail_for_purchase'    => (string) $avail_for_purchase,
            'avail_days'            => (string) $avail_days,
            'valid_days'            => (string) $valid_days,
            'start_date'            => date('j F, Y', strtotime($this->start_date)),
            'end_date'              => date('j F, Y', strtotime($this->end_date)),
            'discount_percentage'   => $this->discount_percentage,
            'status'                => $this->status,
        ];
    }
}
