<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class AdminInfo extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admin_info';
    protected $primaryKey = 'adminID';
    protected $fillable = ['adminID', 'adminUsername', 'adminPassword', 'created_at', 'updated_at'];

    protected static function booted()
    {
        static::saving(function ($admin) {
            if (!empty($admin->password)) {
                $admin->password = Hash::make($admin->password);
            }
        });
    }

    public function getAuthIdentifierName()
    {
        return 'adminID';
    }

    public function isAdmin()
    {
        return true;
    }
}
