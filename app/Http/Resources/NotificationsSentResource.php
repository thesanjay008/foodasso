<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsSentResource extends JsonResource
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
            'notification_id'	=> (string)$this->id,
            'title'          	=> $this->title ? (string)$this->title : '',
            'message'      		=> $this->message ? (string)$this->message : '',
            'type'     			=> $this->type ? (string)$this->type : '',
            'type_id'        	=> $this->type_id ? (string)$this->type_id : '',
            'date_time'        	=> $this->created_at ? (string)$this->created_at : (string) date('Y-m-d H:i:s'),
        ];
    }
}
