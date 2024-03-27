<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use App\Models\Notification;
use App\Models\AdminInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserManageController extends Controller
{
    public function index()
    {
        $users = UserInfo::all();

        return view('admin.manage_users', ['users' => $users]);
    }

    public function getUserDetails($userId)
    {
        $user = UserInfo::find($userId);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        return view('admin.user_details')->with('user', $user);
    }
      
    public function setFlag(Request $request, $userId)
    {
        $user = UserInfo::find($userId);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $flag = $request->input('flag');

        if (!in_array($flag, ['Good', 'Warning', 'Suspended', 'Banned'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid flag'], 400);
        }

        $previousFlag = $user->flag; 
        $user->flag = $flag;
        $user->save();

        $message = '';
        switch ($flag) {
            case 'Good':
                $message = 'Your account is in good standing.';
                break;
            case 'Warning':
                $message = 'A warning has been issued to your account.';
                break;
            case 'Suspended':
                $message = 'Your account has been suspended.';
                break;
            case 'Banned':
                $message = 'Your account has been banned.';
                break;
            default:
                $message = 'Your account flag has been updated.';
                break;
        }

        Notification::create([
            'user_id' => $userId,
            'admin_id' => AdminInfo::first()->adminID, 
            'message' => $message,
            'isRead' => 0,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function dismissUser(Request $request, $userId)
    {
        $user = UserInfo::find($userId);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $user->flag = 'Good';
        $user->save();

        Notification::create([
            'user_id' => $userId,
            'admin_id' => AdminInfo::first()->adminID, 
            'message' => 'Your account has been dismissed.', 
            'isRead' => 0,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function unban(Request $request, $userId)
    {
        $user = UserInfo::find($userId);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $user->flag = 'Good';
        $user->save();

        return response()->json(['status' => 'success']);
    }
}
