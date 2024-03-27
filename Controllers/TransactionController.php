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

class TransactionController extends Controller
{
    public function showTransactions()
    {
        $bookings = Booking::where('operator_id', Auth::id())
            ->whereIn('status', ['Pending', 'Verified', 'Unverified', 'Approved', 'Declined', 'Cancelled', 'Reschedule Request', 'Reschedule Request by Operator', 'Done']) 
            ->get();
    
        return view('operator.transactions', ['bookings' => $bookings]);
    }

    public function approveTransaction($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $status = $booking->status;
    
        if ($status === 'Verified') {
            $booking->status = 'Approved';
            $booking->save();
    
            Log::info('Booking ' . $booking->id . ' approved successfully.');
    
            Notification::create([
                'user_id' => $booking->user_info_id,
                'operator_id' => $booking->operator_id,
                'admin_id' => AdminInfo::first()->adminID, 
                'message' => 'Your booking has been approved.',
                'operator_message' => 'New task! You just approved a booking, good luck!', 
                'admin_message' => 'A booking has been approved by the technician',
                'isRead' => 0, 
                'isRead_operator' => 0, 
                'isRead_admin' => 0, 
            ]);
            
            return response()->json(['success' => true, 'message' => 'Booking approved successfully']);
        } else {
            Log::warning('Booking ' . $booking->id . ' cannot be approved. It is not verified yet.');
    
            return response()->json(['success' => false, 'message' => 'Booking cannot be approved. It is not verified yet.']);
        }
    }
    
    public function declineTransaction($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $status = $booking->status;
    
        if ($status === 'Verified') {
            $booking->status = 'Declined';
            $booking->save();
    
            Notification::create([
                'user_id' => $booking->user_info_id,
                'operator_id' => $booking->operator_id,
                'admin_id' => AdminInfo::first()->adminID, 
                'message' => 'Your booking has been declined.',
                'operator_message' => 'You just declined the booking!.', 
                'admin_message' => 'A booking has been declined by the technician',
                'isRead' => 0, 
                'isRead_operator' => 0, 
                'isRead_admin' => 0, 
            ]);
    
            return response()->json(['success' => true, 'message' => 'Booking declined successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Booking cannot be declined. It is not verified yet.']);
        }
    }
}
