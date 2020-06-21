<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CleanerServiceProvider extends Model
{
    protected $table ='cleaner_service_provider';

    protected $fillable = [
        'cleaner_id',
        'service_provider_id',
        'cleaner_restrictions',
        'status'
    ];

    public function service_provider(){
        return $this->belongsTo('App\Models\ServiceProvider','service_provider_id');
    }

    public function cleaner(){
        return $this->belongsTo('App\User', 'cleaner_id');
    }
}
