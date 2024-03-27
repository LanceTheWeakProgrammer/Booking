<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['user_id', 'rating_id', 'vote_type'];

    public function rating()
    {
        return $this->belongsTo(Rating::class);
    }
}
