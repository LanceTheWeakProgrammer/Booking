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

class ScheduleController extends Controller
{   
    public function __construct()
    {
        $notifications = Notification::where('operator_id', Auth::id())->get();
        
        View::share('notifications', $notifications);
    }

    public function showOperatorLoginForm()
    {
        if (Auth::guard('operator')->check()) {
            return redirect()->route('operator.dashboard');
        }

        return view('operator.login');
    }

    public function showSchedules()
    {
        $bookings = Booking::where('operator_id', Auth::id())
            ->whereIn('status', ['Approved', 'Reschedule Request', 'Reschedule Request by Operator', 'Done', 'Cancel Request'])
            ->get();
    
        foreach ($bookings as $booking) {
            $conflictingEvents = $this->conflict($booking);
            $booking->conflict = count($conflictingEvents) > 0;
        }

        $bookings = $bookings->sortByDesc(function($booking) {
            return $booking->status == 'Reschedule Request' ? 1 : 0;
        });
    
        return view('operator.schedules', ['bookings' => $bookings]);
    }
    
    private function conflict($booking)
    {
        return Booking::where('operator_id', Auth::id())
            ->where('id', '!=', $booking->id)
            ->whereIn('status', ['Approved', 'Reschedule Request', 'Reschedule Request by Operator', 'Done', 'Cancel Request'])
            ->where('start_time', '<', $booking->end_time)
            ->where('end_time', '>', $booking->start_time)
            ->get();
    }
    
    public function markAsDone($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->status === 'Approved' || Carbon::now() >= Carbon::parse($booking->end_time)) {
            $booking->status = 'Done';
            $booking->save();
    
            Notification::create([
                'user_id' => $booking->user_info_id,
                'operator_id' => $booking->operator_id,
                'admin_id' => AdminInfo::first()->adminID, 
                'message' => 'Job\'s done! Your technician successfully finished the job, don\'t forget to rate!',
                'operator_message' => 'Congratulations, You\'ve successfully finished your task!', 
                'isRead' => 0, 
                'isRead_operator' => 0, 
                'isRead_admin' => 0, 
            ]);
    
            return response()->json(['success' => true, 'message' => 'Task marked as done successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Task cannot be marked as done. Either it is not yet approved or the end time is not met.']);
        }
    }

    public function updateStatus(Request $request)
    {
        $operator = Auth::guard('operator')->user(); 
        if ($operator) {
            $operator->status = $request->input('status');
            $operator->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Operator not authenticated']);
        }
    }
}