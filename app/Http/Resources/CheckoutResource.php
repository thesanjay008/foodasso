<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CartItemResource;
use App\Http\Resources\AddressListResource;

class CheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    /* STATIC DATA */
    public function toArray($request)
    {
        // return parent::toArray($request);
         return [
            'total_item'        => (string) $this->total_item,
            'tax'               => $this->tax ? (string)$this->tax : '0.00',
            'total_amount'      => $this->total_amount ? (string)$this->total_amount : '0.00',
            'items'             => CartItemResource::collection($this->items),
            'address'           => AddressListResource::collection($this->address),
            'payment_methods'   => $this->payment_methods,
        ];
    }
}
