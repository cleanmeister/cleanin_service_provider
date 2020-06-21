<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Notification extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sender_fullname' => $this->sender->profile->firstname.' '.$this->sender->profile->middlename.' '.$this->sender->profile->lastname,
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'notification' => $this->notification,
            'date' => $this->created_at
        ];
    }
}
