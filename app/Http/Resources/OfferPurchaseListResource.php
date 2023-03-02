<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Offer;

class OfferPurchaseListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      
      $offer = Offer::where(['id'=>$this->owner_id])->first();
      $avail_for_purchase = $offer->avail_for_purchase;
      if(strtotime($offer->end_date) > time()){
        $avail_days = (int)((strtotime($offer->end_date) - strtotime($offer->start_date))/86400);
      }
      $valid_days         = 'Available For '. $avail_days .' Days';
      
      // return parent::toArray($request);
      return[
        'id'            => (string) $this->id,
        'image'         => $offer->image ? asset($offer->image) : asset(env("DEFAULT_IMAGE")),
        'title'         => $offer ? $offer->offer_name : '',
        'date'          => date('d-m-Y h:i a', strtotime($this->created_at)),
        'valid_days'    => $valid_days ? $valid_days : '',
        'total'         => $this->amount ? $this->amount : '',
      ];
    }
}
