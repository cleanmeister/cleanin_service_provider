<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class Complaints extends JsonResource
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
            'firstname' => $this->userProfile->firstname,
            'middlename' => $this->userProfile->middlename,
            'lastname' => $this->userProfile->lastname,
            'fullname' => $this->userProfile->firstname.' '.$this->userProfile->middlename.' '.$this->userProfile->lastname,
            'complain' => $this->complain,
            'client_schedule_id' => $this->client_schedule->id,
            'time_in' => Carbon::createFromFormat('Y-m-d H:i:s',$this->client_schedule->time_in)->format('g:i A'),
            'time_out' => Carbon::createFromFormat('Y-m-d H:i:s',$this->client_schedule->time_out)->format('g:i A')
        ];
    }
}
