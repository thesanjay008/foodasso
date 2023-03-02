<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Cart;

class ProductListResource extends JsonResource
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
		$count_in_cart = Cart::select('quantity')->where(['product_id'=>$this->id, 'user_id'=>$request->user_id])->first();
        // return parent::toArray($request);
         return [
            'id'                => (string) $this->id,
            'title'             => $this->title ? $this->title : '',
            'image'             => $this->image ? asset($this->image) : '',
            'owner_id'          => (string)$this->owner_id,
            'count_in_cart'     => $count_in_cart ? (string)$count_in_cart->quantity : '0',
            'quantity'          => $this->quantity ? (string)$this->quantity : '0',
            'price'             => $this->price ? (string)$this->price : '0.00',
            'price_txt'         => $this->price ? (string)$this->price .' KD' : '0.00 KD',
            'description'       => $this->description ? (string)$this->description : '',
            'status'            => $this->status,
        ];
    }
}
