<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'id' => (string) $this->id,
            'icon' => $this->icon ? asset($this->icon) : '',
            'service_name' => $this->service_name,
            'status'        =>  $this->status,
        ];
    }
}
