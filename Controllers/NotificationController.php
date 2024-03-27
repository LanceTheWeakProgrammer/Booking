<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{   
    public function fetch()
    {
        $user = Auth::user(); 
        $notifications = Notification::where('user_id', $user->id)
            ->select('id', 'message', 'isRead', 'created_at')
            ->get();

        return response()->json(['notifications' => $notifications]);
    }

    public function markAllRead()
    {
        $user = Auth::user();
        Notification::where('user_id', $user->id)->update(['isRead' => 1]);
    
        return response()->json(['message' => 'All notifications marked as read successfully']);
    }
    
    public function markRead($id)
    {
        $notification = Notification::findOrFail($id);
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $notification->update(['isRead' => 1]);
    
        return response()->json(['message' => 'Notification marked as read successfully']);
    }

    public function delete($id)
    {
        $notification = Notification::findOrFail($id);
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $notification->where('id', $id)->update(['message' => null]);
    
        return response()->json(['message' => 'Notification message deleted successfully']);
    }

    public function fetchOperatorNotif()
    {
        try {
            $operatorId = auth('operator')->id();
            $notifications = Notification::where('operator_id', $operatorId)
                ->select('id', 'operator_message', 'isRead_operator', 'created_at')
                ->get();

            if ($notifications->isEmpty()) {
                return response()->json(['operator_notifications' => []]);
            }

            $notificationsArray = $notifications->toArray();
    
            return response()->json(['operator_notifications' => $notificationsArray]);
        } catch (\Exception $e) {
            Log::error('Error fetching operator notifications: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching operator notifications'], 500);
        }
    }

    public function markAllOperatorRead()
    {
        try {
            $operatorId = auth('operator')->id();
            if (!$operatorId) {
                return response()->json(['error' => 'Operator is not authenticated'], 401);
            }
    
            Notification::where('operator_id', $operatorId)->update(['isRead_operator' => 1]);
    
            return response()->json(['message' => 'All operator notifications marked as read successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error marking all operator notifications as read'], 500);
        }
    }
    
    public function markOperatorRead($id)
    {
        try {
            $operatorId = auth('operator')->id();
            if (!$operatorId) {
                return response()->json(['error' => 'Operator is not authenticated'], 401);
            }
    
            $notification = Notification::where('operator_id', $operatorId)->findOrFail($id);
            $notification->update(['isRead_operator' => 1]);
    
            return response()->json(['message' => 'Operator notification marked as read successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error marking operator notification as read'], 500);
        }
    }

    public function deleteOperatorNotif($id)
    {
        try {
            $operatorId = auth('operator')->id();
            if (!$operatorId) {
                return response()->json(['error' => 'Operator is not authenticated'], 401);
            }
    
            $notification = Notification::where('operator_id', $operatorId)->findOrFail($id);
            $notification->where('id', $id)->update(['operator_message' => null]);
    
            return response()->json(['message' => 'Operator notification message deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting operator notification message'], 500);
        }
    }

    public function fetchAdminNotif()
    {
        try {
            if (!Auth::guard('admin')->check()) {
                return response()->json(['error' => 'Admin is not authenticated'], 401);
            }

            $notifications = Notification::where('admin_id', Auth::guard('admin')->id())
                ->select('id', 'admin_message', 'isRead_admin', 'created_at')
                ->get();

            return response()->json(['admin_notifications' => $notifications]);
        } catch (\Exception $e) {
            Log::error('Error fetching admin notifications: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching admin notifications'], 500);
        }
    }

    public function markAdminRead($id)
    {
        try {
            if (!Auth::guard('admin')->check()) {
                return response()->json(['error' => 'Admin is not authenticated'], 401);
            }
    
            $notification = Notification::where('admin_id', Auth::guard('admin')->id())->findOrFail($id);
            $notification->update(['isRead_admin' => 1]);
    
            return response()->json(['message' => 'Admin notification marked as read successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error marking admin notification as read'], 500);
        }
    }
    
    public function markAllAdminRead()
    {
        try {
            if (!Auth::guard('admin')->check()) {
                return response()->json(['error' => 'Admin is not authenticated'], 401);
            }
    
            Notification::where('admin_id', Auth::guard('admin')->id())->update(['isRead_admin' => 1]);
    
            return response()->json(['message' => 'All admin notifications marked as read successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error marking all admin notifications as read'], 500);
        }
    }

    public function deleteAdminNotif($id)
    {
        try {
            if (!Auth::guard('admin')->check()) {
                return response()->json(['error' => 'Admin is not authenticated'], 401);
            }

            $notification = Notification::where('admin_id', Auth::guard('admin')->id())->findOrFail($id);
            $notification->update(['admin_message' => null]);

            return response()->json(['message' => 'Admin notification message deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting admin notification message'], 500);
        }
    }
}

