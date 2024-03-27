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
use App\Models\UserInfo;
use App\Models\Settings;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
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

    public function profile()
    {   
        $user = auth()->user();
        $addressParts = explode(', ', $user->address);
        $country = $addressParts[0] ?? '';
        $province = $addressParts[1] ?? '';
        $city = $addressParts[2] ?? '';
        return view('profile', compact('user', 'country', 'province', 'city'));
    }

    public function profileData(Request $request)
    {
        $user = auth()->user();

        $addressParts = explode(', ', $user->address);
        $country = $addressParts[0] ?? '';
        $province = $addressParts[1] ?? '';
        $city = $addressParts[2] ?? '';
        $userData = [
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'gender' => $user->gender,
            'birthdate' => $user->birthdate,
            'email' => $user->email,
            'contactNumber' => $user->contactNumber,
            'country' => $country,
            'province' => $province,
            'city' => $city,
            'zipcode' => $user->zipcode,
            'picture' => $user->picture,
        ];
        return response()->json($userData);
    }
    
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
    
        $user->firstName = $request->input('firstName');
        $user->lastName = $request->input('lastName');
        $user->gender = $request->input('gender');
        $user->birthdate = $request->input('birthdate');
        $user->email = $request->input('email');
        $user->contactNumber = $request->input('contactNumber');
    
        if ($request->has('country') && $request->has('province') && $request->has('city')) {
            $address = $request->input('country') . ', ' . $request->input('province') . ', ' . $request->input('city');
            $user->address = $address;
        }
    
        $user->zipcode = $request->input('zipcode');
    
        if ($request->hasFile('profile_picture')) {
            if ($user->picture) {
                Storage::disk('public')->delete($user->picture);
            }
    
            $image = $request->file('profile_picture');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $path = $image->storeAs('profile', $imageName, 'public');
            $user->picture = $path;
        }
    
        $user->save();
    
        return response()->json(['message' => 'Profile updated successfully']);
    }
}    
