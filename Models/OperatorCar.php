<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorCar extends Model
{
    protected $table = 'operator_car';
    protected $primaryKey = 'entryID';
    public $timestamps = false;

    protected $fillable = ['entryID', 'operator_id', 'car_id'];

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
