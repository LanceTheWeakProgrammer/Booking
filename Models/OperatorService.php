<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorService extends Model
{
    protected $table = 'operator_service';
    protected $primaryKey = 'entryID';
    public $timestamps = false;

    protected $fillable = ['entryID', 'operator_id', 'service_id'];

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
