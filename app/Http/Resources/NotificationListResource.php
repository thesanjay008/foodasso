<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Offer;

class NotificationListResource extends JsonResource
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
      return[
        'notification_id'   => (string) $this->id,
        'title'             => $this->title ? (string) $this->title : '',
        'message'           => $this->message ? (string) $this->message : '',
        'type'              => $this->type ? (string) $this->type : '',
        'type_id'           => $this->type_id ? (string) $this->type_id : '',
        'date_time'         => (string) date('Y-m-d H:i:s', strtotime($this->created_at)),
      ];
    }
}
