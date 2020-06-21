<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Messages extends JsonResource
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
            'count' => $this->id,
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'text_message' => $this->text_message,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'sender_firstname' => $this->sender->profile->firstname,
            'sender_middlename' => $this->sender->profile->middlename,
            'sender_lastname' => $this->sender->profile->lastname,
            'profile_pic' => $this->sender->profile->profile_pic,
            'receiver_firstname' => $this->receiver->profile->firstname,
            'receiver_middlename' => $this->receiver->profile->middlename,
            'receiver_lastname' => $this->receiver->profile->lastname
        ];
    }
}
