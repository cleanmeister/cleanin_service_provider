<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleanerReview extends Model
{
    protected $fillable = [
    	'cleaner_id', 
    	'client_id',
    	'feedback',
    	'rate',
    ];

    public function cleaner(){
    	return $this->belongsTo('App\User', 'cleaner_id');
    }

    public function client(){
    	return $this->belongsTo('App\User', 'client_id');
    }

}
