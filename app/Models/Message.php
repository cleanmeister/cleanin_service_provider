<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\MessageCreated;
use App\Events\MessageUpdated;

class Message extends Model
{
    /*statuses
        0 = new
        1 = viewed
        2 = removed/deleted
    */

    protected $fillable = [
        'sender_id', 'receiver_id', 'status', 'text_message'
    ];

    protected $dispatchesEvents = [
        'created' => MessageCreated::class,
        'updated' => MessageUpdated::class,
    ];

    public function sender(){
        return $this->belongsTo('App\User', 'sender_id');
    }

    public function receiver(){
        return $this->belongsTo('App\User', 'receiver_id');
    }
    
}
