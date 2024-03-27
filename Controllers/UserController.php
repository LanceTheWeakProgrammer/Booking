<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('login');
        }
    
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'firstName' => 'required|regex:/^[a-zA-Z\s]+$/',
            'lastName' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:user_info,email',
            'contactNumber' => 'required',
            'password' => 'required|min:8|confirmed',
            'address' => 'required',
            'zipcode' => 'required|regex:/^[0-9]+$/',
            'gender' => 'required',
            'birthdate' => 'required',
            'picture' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
    
        if ($request->hasFile('picture')) {
            $imagePath = $request->file('picture')->store('images/profile_picture', 'public');
        } else {
            $imagePath = null; 
        }
    
        UserInfo::create([
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'email' => $request->input('email'),
            'contactNumber' => $request->input('contactNumber'),
            'password' => bcrypt($request->input('password')),
            'address' => $request->input('address'),
            'zipcode' => $request->input('zipcode'),
            'gender' => $request->input('gender'),
            'birthdate' => $request->input('birthdate'),
            'picture' => $imagePath,
        ]);
    
        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }    

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
    
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
    
        $user = UserInfo::where('email', $request->email)->first();
    
        if ($user) {
            if ($user->flag === 'Banned') {
                return redirect()->route('login')->with('error', 'Your account has been banned. Please contact support.');
            }
    
            if (Auth::attempt($request->only('email', 'password'))) {
                $user->is_online = true;
                $user->save();
                return redirect()->route('home');
            }
        }
    
        return redirect()->route('login')->with('error', 'Invalid credentials')->withInput();
    }  
    
    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->is_online = false;
        $user->save();
    
        $guard = Auth::getDefaultDriver();
    
        Auth::guard($guard)->logout();
    
        $request->session()->forget(Auth::guard($guard)->getName());
        $request->session()->regenerateToken();
    
        if ($guard == 'web') {
            return redirect('home')->with('success', 'Logout successful!');
        } elseif ($guard == 'admin') {
            return redirect()->route('admin.login')->with('success', 'Logout successful!');
        } elseif ($guard == 'operator') {
            return redirect()->route('operator.login')->with('success', 'Logout successful!');
        }
    
        return redirect('home')->with('success', 'Logout successful!');
    }

    public function updateUserStatus(Request $request)
    {
        $userId = Auth::id();

        $user = UserInfo::find($userId);

        if (!$user) {
            return Response::json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $status = $request->input('status');

        $user->is_online = $status;

        $user->save();

        return Response::json(['status' => 'success']);
    }
}
