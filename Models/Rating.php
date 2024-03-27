<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserInfo; 
use App\Models\Vote;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'operator_id',
        'rating',
        'review',
    ];

    public function user()
    {
        return $this->belongsTo(UserInfo::class, 'user_id');
    }
    

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function countVote()
    {
        $this->likes = $this->votes()->where('vote_type', 'like')->count();
        $this->dislikes = $this->votes()->where('vote_type', 'dislike')->count();
        $this->save();
    }
}
