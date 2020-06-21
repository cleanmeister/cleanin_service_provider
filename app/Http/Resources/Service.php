<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Service extends JsonResource
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
            'name' => $this->name,
            'desc' => $this->desc,
            'rate' => $this->rate,
            'rateStr' => ($this->rate == 1 ? 'Hour' : 'Day'),
            'price' => $this->price,
        ];
    }
}
