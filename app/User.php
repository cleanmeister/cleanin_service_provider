<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $fillable = [
        'email', 'email_verified_at', 'password', 'role_id', 'approved', 'active', 'username', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isDeactivated()
    {
        return $this->is_deactivated == 1;
    }

    public function cleaner_service_provider(){
        return $this->hasOne('App\Models\CleanerServiceProvider', 'cleaner_id', 'id');
    }

    public function client_schedules(){
        return $this->hasMany('App\Models\CleanerSchedule', 'client_id', 'id');
    }

    public function cleaner_schedules(){
        return $this->hasMany('App\Models\CleanerSchedule', 'cleaner_id', 'id');
    }

    public function service_provider(){
        return $this->hasOne('App\Models\ServiceProvider', 'ownder_id', 'id');
    }

    public function schedule(){
        return $this->hasManyThrough('App\Models\Schedule', 'cleaner_id', 'id');
    }

    public function services(){
        return $this->belongsToMany('App\Models\Service', 'cleaner_service', 'cleaner_id', 'service_id');
    }

    public function profile(){
        return $this->hasOne('App\Models\UserProfile');
    }

    public function role(){
        return $this->hasOne('App\Models\Role','id');
    }

    public function cleaner_review(){
        return $this->hasMany('App\Models\CleanerReview', 'cleaner_id', 'id');
    }

    public function client_review(){
        return $this->hasMany('App\Models\CleanerReview', 'client_id', 'id');
    }

    public function company(){
        return $this->hasOne('App\Models\Company', 'owner_id', 'id');
    }

    public function send_message(){
        return $this->hasMany('App\Models\Message', 'sender_id', 'id');
    }

    public function receive_message(){
        return $this->hasMany('App\Models\Message', 'receiver_id', 'id');
    }

    public function notif_sender(){
        return $this->hasMany('App\Models\Notification', 'sender_id', 'id');
    }

    public function notif_receiver(){
        return $this->hasMany('App\Models\Notifications', 'receiver_id', 'id');
    }

    public function complains(){
        return $this->hasMany('App\complaints', 'complainant_id', 'id');
    }

    public function complainee_complains(){
        return $this->hasMany('App\complaints', 'complainee_id', 'id');
    }

    public function blocked(){
        return $this->hasMany('App\Models\BlockedUser', 'blocked_id', 'id');
    }

    public function messagecontacts_user(){
        return $this->hasMany('App\Models\MessageContacts', 'user_id', 'id');
    }
    public function messagecontacts_contacts(){
        return $this->hasMany('App\Models\MessageContacts', 'contact_id', 'id');
    }

}