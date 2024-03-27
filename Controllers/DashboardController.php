<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;
use App\Models\Car;
use App\Models\UserInfo;
use App\Models\Booking;
use App\Models\Operator;
use Carbon\Carbon;
use App\Models\WebsiteRating;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function dashboard()
    {
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            $totalUsers = UserInfo::count();
            $totalBookings = Booking::whereIn('status', ['Approved', 'Done'])->count(); 
            $totalOperators = Operator::count();

            $Sales = Booking::whereIn('status', ['Approved', 'Done'])->sum('service_price') / 1000;
            $totalSales = number_format($Sales, 1) . 'k';
    
            $monthlyBookingsData = Booking::selectRaw('YEAR(start_time) as year, MONTH(start_time) as month, COUNT(*) as total_bookings')
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

                $ratingCounts = WebsiteRating::selectRaw('webRating, COUNT(*) as count')
                ->groupBy('webRating')
                ->pluck('count', 'webRating')
                ->toArray();
            
            $totalRatings = 0;
            $totalRatingCounts = 0;
            
            foreach ($ratingCounts as $rating => $count) {
                $totalRatings += $rating * $count;
                $totalRatingCounts += $count;
            }
            
            $avgRating = $totalRatingCounts > 0 ? $totalRatings / $totalRatingCounts : 0;
            
    
            $allYears = $monthlyBookingsData->pluck('year')->unique()->toArray();
            $months = [
                1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
                9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
            ];
            $monthlyBookingsByYear = [];
            foreach ($allYears as $year) {
                $monthlyBookingsByYear[$year] = [];
                foreach ($months as $monthNum => $monthName) {
                    $monthlyBookingsByYear[$year][$monthName] = $monthlyBookingsData
                        ->where('year', $year)
                        ->where('month', $monthNum)
                        ->first()->total_bookings ?? 0;
                }
            }
    
            $monthlyBookingsData = [
                'years' => $allYears,
                'months' => array_values($months), 
                'bookings' => array_values($monthlyBookingsByYear),
            ];
    
            return view('admin.dashboard', compact('admin', 'totalUsers', 'totalBookings', 'totalOperators', 'totalSales', 'monthlyBookingsData', 'ratingCounts', 'avgRating'));
        } else {
            return redirect()->route('admin.login');
        }
    } 

    public function services()
    {
        return view('admin.services');
    }

    public function carousel()
    {
        return view('admin.carousel');
    }
}
