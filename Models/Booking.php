<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'user_info_id',
        'start_time',
        'end_time',
        'car_type', 
        'service_type',
        'service_price', 
        'discount_price',
        'status',
        'isapproved',
        'additional_info',
        'booking_ticket', 
        'isActive',
        'resched_info',
        'resched_start_time',
        'resched_end_time',
        'cancel_reason',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    public function user()
    {
        return $this->belongsTo(UserInfo::class, 'user_info_id');
    }

    public function promos()
    {
        return $this->belongsToMany(Promo::class);
    }

}
