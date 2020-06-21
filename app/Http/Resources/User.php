<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'firstname' => $this->profile->firstname,
            'lastname' => $this->profile->lastname,
            'middlename' => $this->profile->middlename,
            'fullname' => $this->profile->lastname.', '.$this->profile->firstname.' '.$this->profile->middlename,
            'age' => $this->profile->age,
            'address' => $this->profile->address,
            'mobile_number' => $this->profile->mobile_number,
            'date_of_birth' => $this->profile->date_of_birth,
            'role_id' => $this->role_id,
            'is_active' => $this->is_active,
        ];
    }
}
