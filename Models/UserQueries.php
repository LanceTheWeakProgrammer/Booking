<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQueries extends Model
{
    protected $table = 'user_queries';
    protected $primaryKey = 'queryID';
    public $timestamps = false;

    protected $fillable = ['queryID', 'name', 'email', 'subject', 'message', 'date', 'action'];
}
