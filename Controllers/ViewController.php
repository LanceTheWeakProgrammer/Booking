<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactInfo;
use App\Models\TeamInfo;
use App\Models\Carousel;
use App\Models\Service;
use App\Models\Car;
use App\Models\Operator;
use App\Models\Notification;
use App\Models\Booking;
use App\Models\Settings;
use App\Models\Rating;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\WebsiteRating;

class ViewController extends Controller
{
    public function __construct()
    {
        $contactInfo = ContactInfo::where('conID', 1)->first();
        $services = Service::all();
        $operators = Operator::all();
        $notifications = Notification::where('user_id', Auth::id())->get();
        $settings = Settings::find(1);
        
        View::share('contactInfo', $contactInfo);
        View::share('services', $services);
        View::share('operators', $operators);
        View::share('notifications', $notifications);
        View::share('settings', $settings);
    }

    public function contact()
    {
        $notifications = Notification::where('user_id', Auth::id())->get();
        return view('contact', compact('notifications'));
    }
    
    public function about()
    {
        $teamMembers = TeamInfo::all();
        $totalOperators = Operator::count();
        $notifications = Notification::where('user_id', Auth::id())->get();
        $aboutData = Settings::find(1)->siteAbout;
        
        return view('about', compact('teamMembers', 'notifications', 'aboutData', 'totalOperators'));
    }

    public function home()
    {
        $carouselImages = Carousel::all();
        $notifications = Notification::where('user_id', Auth::id())->get(); 
        $ratings = WebsiteRating::where('display', 0)->get();
        $operators = Operator::where('status', 1)->take(3)->get();

        foreach ($operators as $operator) {
            $operator->averageRating = $operator->ratings()->avg('rating');
        }

        return view('home', compact('carouselImages', 'notifications', 'ratings', 'operators'));
    }

    public function service()
    {
        $services = Service::all();
        $notifications = Notification::where('user_id', Auth::id())->get();
        return view('service', compact('services'));
    }

    public function operator()
    {
        $operators = Operator::with('cars', 'services')->where('status', 1)->paginate(3);
        $services = Service::all();
        $cars = Car::all();
        $notifications = Notification::where('user_id', Auth::id())->get();

        return view('operator', compact('operators', 'services', 'cars'));
    }

    public function operator_details($operatorID)
    {
        $operator = Operator::find($operatorID);
        $ratings = Rating::where('operator_id', $operatorID)->get();
        $booking = Booking::where('operator_id', $operatorID)->first(); 
        $notifications = Notification::where('user_id', Auth::id())->get();

        $averageRating = $ratings->isEmpty() ? 0 : $ratings->sum('rating') / $ratings->count();
        
        return view('operator_details', compact('operator', 'ratings', 'booking', 'averageRating'));
    }

    public function booking_details($operatorID)
    {
        $operator = Operator::find($operatorID);
        $booking = Booking::where('operator_id', $operatorID)->first(); 
    
        $notifications = Notification::where('user_id', Auth::id())->get();
        
        return view('booking_details', compact('operator', 'booking'));
    }

    public function receipt($booking_id)
    {
        $booking = Booking::findOrFail($booking_id);
        $contactInfo = ContactInfo::first(); 

        return view('user.receipt', compact('booking', 'contactInfo'));
    }

    public function bookings()
    {
        $user = auth()->user();
        $bookings = $user->bookings;
    
        return view('user_bookings', compact('bookings'));
    }    
        
    public function login()
    {
        return view('login');
    }
}
