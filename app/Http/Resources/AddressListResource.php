<?php

namespace App\Http\Resources;
use App\Models\City;
use App\Models\Country;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $city           = City::where(['id'=>$this->city_id])->first();
        $country        = Country::where(['id'=>$this->country_id])->first();
        $city_name      = $city ? (string) $this->city->name : '';
        $country_name   = $country ? (string) $this->country->name : '';

        // return parent::toArray($request);
        return [
            'id'            => (string) $this->id,
            'address'       => $this->address ? (string) $this->address .', '. $city_name .', '.$country_name : '',
            'postal_code'   => $this->postal_code ? (string) $this->postal_code : '',
        ];
    }
}
