<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Operator extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'operator';
    protected $primaryKey = 'operatorID';
    protected $fillable = [
        'operatorName',
        'operatorImg',
        'opAddress',
        'operatorTel',
        'operatorEmail',
        'jobAge',
        'opDescription',
        'hRate',
        'status',
        'removed',
        'serialNumber',
    ];

    protected $hidden = [
        'serialNumber',
    ];

    public function cars()
    {
        return $this->belongsToMany(Car::class, 'operator_car', 'operator_id', 'car_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'operator_service', 'operator_id', 'service_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'operator_id', 'operatorID');
    }

    public function getAuthPassword()
    {
        return $this->attributes['serialNumber'];
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'operator_id');
    }
}
