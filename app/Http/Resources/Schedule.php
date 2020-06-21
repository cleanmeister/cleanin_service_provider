<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class Schedule extends JsonResource
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
            'day_id' => $this->day_id,
            'day_desc' => $this->day->desc,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'formated_start_time' => Carbon::createFromFormat('H:i:s',$this->start_time)->format('g:i A'),
            'formated_end_time' => Carbon::createFromFormat('H:i:s',$this->end_time)->format('g:i A'),
            'service_id' => $this->service_id,
            'service_desc' => $this->service->desc,
            'service_name' => $this->service->name,
            'available' => $this->available,
        ];
    }
}
