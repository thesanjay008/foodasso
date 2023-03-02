<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id'            => (string)$this->id,
            'name'          => (string)$this->name,
            'sortname'      => (string)$this->sortname,
            'phonecode'     => (string)$this->phonecode,
            'status'        => (string)$this->status,
        ];
    }
}
