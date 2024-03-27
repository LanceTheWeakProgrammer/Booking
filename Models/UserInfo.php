<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserInfo extends Authenticatable
{
    use HasFactory, Notifiable;

    
    protected $table = 'user_info';
    protected $primaryKey = 'id';

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'contactNumber',
        'address',
        'zipcode',
        'gender',
        'birthdate',
        'password',
        'picture',
        'isVerified',
        'is_online',
        'token',
        't_expire',
        'datentime',
        'status',
        'flag',
    ];

    protected $casts = [
        'datentime' => 'datetime',
    ];

    protected $hidden = [
        'password', 
        'remember_token',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'user_id');
    }

    public function isOnline()
    {
        return $this->is_online ? 'Online' : 'Offline';
    }
}
