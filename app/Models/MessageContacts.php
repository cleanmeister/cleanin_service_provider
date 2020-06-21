<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MessageContacts extends Model
{

    protected $fillable = [
        'user_id', 'contact_id'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function contacts(){
        return $this->belongsTo('App\User', 'contact_id');
    }
}