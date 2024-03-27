<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebsiteRating;

class WebRatingController extends Controller
{
    public function submitRating(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'webRating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $rating = new WebsiteRating();
        $rating->fill($validatedData);

        $rating->save();

        return response()->json(['message' => 'Rating submitted successfully'], 200);
    }
}
