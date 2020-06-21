<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
    	'sender_id', 'notification', 'viewed', 'receiver_id'
    ];

    public function sender(){
    	return $this->belongsTo('App\User', 'sender_id');
    }

   	public function receiver(){
   		return $this->belongsTo('App\User', 'receiver_id');
   	}
}
