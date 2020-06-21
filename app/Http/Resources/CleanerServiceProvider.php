<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CleanerServiceProvider extends JsonResource
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
            'id' => $this->cleaner_id,
            'fullname' => $this->cleaner->profile->lastname.', '.$this->cleaner->profile->firstname.' '.$this->cleaner->profile->middlename,
            'firstname' => $this->cleaner->profile->firstname,
            'middlename' => $this->cleaner->profile->middlename,
            'lastname' => $this->cleaner->profile->lastname,
            'address' => $this->cleaner->profile->address,
            'age' => $this->cleaner->profile->age,
            'date_of_birth' => $this->cleaner->profile->date_of_birth,
            'mobile_number' => $this->cleaner->profile->mobile_number,
            'email' => $this->cleaner->email,
            'username' => $this->cleaner->username,
            'restrictions' => $this->cleaner_restrictions
        ];
    }
}
