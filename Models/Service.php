<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'service';
    protected $primaryKey = 'serviceID';
    public $timestamps = false;

    protected $fillable = ['serviceIcon', 'serviceType', 'sDescription', 'servicePrice'];

    public function operators()
    {
        return $this->belongsToMany(Operator::class, 'operator_service', 'service_id', 'operator_id');
    }
}
