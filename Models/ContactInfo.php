<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $table = 'contact_info';
    protected $primaryKey = 'conID';
    public $timestamps = false;

    protected $fillable = [
        'address',
        'gmap',
        'tel1',
        'tel2',
        'email',
        'twt',
        'fb',
        'ig',
        'iframe',
    ];
}