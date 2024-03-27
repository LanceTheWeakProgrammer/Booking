<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'siteID';
    public $timestamps = false;

    protected $fillable = ['siteID', 'siteTitle', 'siteAbout'];
}
