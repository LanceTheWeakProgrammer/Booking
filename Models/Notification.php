<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'operator_id',
        'user_id',
        'admin_id',
        'message',
        'operator_message',
        'admin_message',
        'isRead',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id', 'operatorID');
    }

    public function user()
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'adminID');
    }
}
