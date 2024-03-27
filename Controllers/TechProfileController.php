<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Operator;
use App\Models\AdminInfo;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View; 
use Illuminate\Support\Facades\Response;

class TechProfileController extends Controller
{
    public function __construct()
    {
        $notifications = Notification::where('operator_id', Auth::id())->get();
        
        View::share('notifications', $notifications);
    }

    public function operatorLogin(Request $request)
    {
        $credentials = $request->only('operatorEmail', 'serialNumber');

        $operator = Operator::where('operatorEmail', $credentials['operatorEmail'])
            ->where('serialNumber', $credentials['serialNumber'])
            ->first();

        if ($operator) {
            Auth::guard('operator')->login($operator);
            return redirect()->route('operator.schedules');
        }

        return redirect()->route('operator.login')->with('error', 'Invalid credentials');
    }

    public function operatorLogout()
    {
        Auth::guard('operator')->logout();

        return redirect()->route('operator.login')->with('success', 'Logout successful!');
    }

    public function showOperatorProfile()
    {
        $operator = Auth::guard('operator')->user();
        $ratings = $operator->ratings()->with('user')->get(); 

        $totalRating = $ratings->sum('rating');
        $averageRating = count($ratings) > 0 ? $totalRating / count($ratings) : 0;
    
        return view('operator.profile', compact('operator', 'ratings', 'averageRating'));
    }    

    public function showOperatorLoginForm()
    {
        if (Auth::guard('operator')->check()) {
            return redirect()->route('operator.schedules');
        }

        return view('operator.login');
    }

    public function updateDescription(Request $request)
    {
        $operator = Auth::guard('operator')->user();

        if (!$operator) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $description = $request->input('description');

        $operator->opDescription = $description;
        $operator->save();

        return response()->json(['message' => 'Description updated successfully'], 200);
    }
}
