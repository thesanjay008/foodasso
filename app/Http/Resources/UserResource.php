<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'gender'        => $this->gender ? $this->gender : '',
            'age'           => $this->dob ? (string) (string) date_diff(date_create($user->dob), date_create('today'))->y : '',
            'dob'           => $this->dob ? date('d-m-Y', strtotime($this->dob)) : '',
            'name'          => $this->name,
            'email'         => $this->email,
            'phone_number'  => $this->phone_number,
            'status'        => $this->status,
            'user_type'     => $this->user_type,
        ];
    }
}
