<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CartItemResource;

class CartResource extends JsonResource
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
            'items'       		=> CartItemResource::collection($this->items),
        ];
    }
}
