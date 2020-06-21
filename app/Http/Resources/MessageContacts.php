<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MessageContacts extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $_user_id = $this->user_id;
        $_contact_id = $this->contact_id;
        $lastMessage = Message::where(function($query) use ($_user_id, $_contact_id){
                $query->where('sender_id', '=', $_user_id);
                $query->where('receiver_id', '=', $_contact_id);
            })
            ->orWhere(function($query) use ($_user_id, $_contact_id){
                $query->where('receiver_id', '=', $_user_id);
                $query->where('sender_id', '=', $_contact_id);
            })
            ->orderBy('id', 'desc')->first();


        return [
            'contact_id' => $this->contact_id,
            'firstname' => $this->contacts->profile->firstname,
            'middlename' => $this->contacts->profile->middlename,
            'lastname' => $this->contacts->profile->lastname,
            'fullname' => $this->contacts->profile->firstname.' '.($this->contacts->profile->middlename)[0].'. '.$this->contacts->profile->lastname,
            'email' => $this->contacts->email,
            'profile_pic' => $this->contacts->profile->profile_pic,
            'lastmessage' => ($lastMessage ? [
                    'message' => Str::limit($lastMessage->text_message, 75, $end='...'),
                    'date' => Carbon::parse($lastMessage->created_at)->format('M d, Y'),
                    'status' => $lastMessage->status,
                    'SenderID' => $lastMessage->sender_id,
                    'ReceiverID' => $lastMessage->receiver_id
                ] : null)
        ];
    }
}
