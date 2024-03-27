<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $table = 'car';
    protected $primaryKey = 'carID';
    public $timestamps = false;

    protected $fillable = ['carID', 'carName', 'carModel', 'carType'];

    public function operators()
    {
        return $this->belongsToMany(Operator::class, 'operator_car', 'car_id', 'operator_id');
    }
}
