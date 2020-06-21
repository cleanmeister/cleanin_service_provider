<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    protected $table ='blocked_users';
    protected $fillable = [
    	'blocker_id',
    	'blocked_id',
    	'reason',
    	'approved'
    ];

    public function blocker(){
    	return $this->belongsTo('App\Models\ServiceProvider', 'blocker_id');
    }

    public function blocked(){
    	return $this->belongsTo('App\User', 'blocked_id');
    }


}
