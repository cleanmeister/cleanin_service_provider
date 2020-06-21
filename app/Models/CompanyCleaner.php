<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyCleaner extends Model
{
    protected $fillable = [
    	'cleaner_id',
    	'company_id',
    ];

    public function cleaner(){
    	return $this->belongsTo('App\User',  'cleaner_id');
    }

    public function company(){
    	return $this->belongsTo('App\Models\Company');
    }
}
