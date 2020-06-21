<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ClientSchedule extends JsonResource
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
            'servie_id' => $this->schedule->service_id,
            'service_name' => $this->schedule->service->name,
            'service_desc' => $this->schedule->service->desc,
            'cleaner_id' => $this->schedule->cleaner_id,
            'day_id' => $this->schedule->day_id,
            'day_desc' => $this->schedule->day->desc,
            'day_initial' => $this->schedule->day->initial,
            'cleaner_firstname' => $this->schedule->cleaner->profile->firstname,
            'cleaner_middlename' => $this->schedule->cleaner->profile->middlename,
            'cleaner_lastname' => $this->schedule->cleaner->profile->lastname,
            'cleaner_email' => $this->schedule->cleaner->email,
            'formated_start_time' => Carbon::createFromFormat('Y-m-d H:i:s',$this->sched_start_time)->format('F d, Y, g:i A'),
            'formated_end_time' => Carbon::createFromFormat('Y-m-d H:i:s',$this->sched_end_time)->format('g:i A'),
            'time_in' => $this->time_in != null ? Carbon::createFromFormat('H:i:s',$this->time_in)->format('g:i A') : null,
            'time_out' => $this->time_out != null ? Carbon::createFromFormat('H:i:s',$this->time_out)->format('g:i A') : null,
            'status' => $this->status,
            'rating' => $this->rating,
            'client_rating' => $this->rating_client,
            'feedback'=> $this->feedback
        ];
    }
}
