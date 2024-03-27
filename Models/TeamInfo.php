<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamInfo extends Model
{
    protected $table = 'team_info';
    protected $primaryKey = 'teamID';
    public $timestamps = false;
    protected $fillable = ['mName', 'mTitle', 'mPicture'];
}
