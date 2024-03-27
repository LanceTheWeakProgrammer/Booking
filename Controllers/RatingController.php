<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\UserInfo;
use App\Models\Vote;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    public function submitRating(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            $validatedData = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:255',
            ]);
    
            $rating = (int) $validatedData['rating'];
    
            $ratingModel = new Rating();
            $ratingModel->rating = $rating;
            $ratingModel->review = $validatedData['review'];
            $ratingModel->user_id = Auth::id();
            $ratingModel->operator_id = $request->input('operator_id');

            $operatorId = $request->input('operator_id');
    
            $ratingModel->save();

            Notification::create([
                'operator_id' => $operatorId,
                'operator_message' => 'Someone rated you! Check it out!',
                'isRead_operator' => 0, 
            ]);
    
            $ratings = Rating::with('user')->where('operator_id', $request->input('operator_id'))->get();
    
            $ratingsHtml = View::make('user.rating', compact('ratings'))->render();
    
            return response()->json(['success' => true, 'ratings_html' => $ratingsHtml]);
        } catch (\Exception $e) {
            Log::error('Failed to submit rating: ' . $e->getMessage());
    
            return response()->json(['error' => 'Failed to submit rating. Please try again.'], 500);
        }
    }
    
    public function getRatings($operatorId)
    {
        try {
            $ratings = Rating::with('user')->where('operator_id', $operatorId)->get();
            $ratingsHtml = View::make('user.rating', compact('ratings'))->render();
            return response()->json(['success' => true, 'ratings_html' => $ratingsHtml]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch ratings: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch ratings.'], 500);
        }
    }
    
    public function likeDislike(Request $request, $id, $action)
    {
        $rating = Rating::findOrFail($id);
        $user_id = auth()->id();
        
        $existingVote = $rating->votes()->where('user_id', $user_id)->first();
        
        if ($existingVote) {
            if ($existingVote->vote_type === $action) {
                $existingVote->delete();
                if ($action === 'like') {
                    $rating->likes--;
                } else {
                    $rating->dislikes--;
                }
            } else {
                if ($existingVote->vote_type === 'like') {
                    $rating->likes--;
                    $rating->dislikes++;
                } else {
                    $rating->dislikes--;
                    $rating->likes++;
                }
                $existingVote->vote_type = $action;
                $existingVote->save();
            }
        } else {
            $vote = new Vote();
            $vote->user_id = $user_id;
            $vote->rating_id = $id;
            $vote->vote_type = $action;
            $vote->save();
            if ($action === 'like') {
                $rating->likes++;
            } else {
                $rating->dislikes++;
            }
        }
        
        $rating->save();
        
        return response()->json([
            'likes' => $rating->likes,
            'dislikes' => $rating->dislikes
        ]);
    }           
}
