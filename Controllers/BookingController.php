<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\AdminInfo;
use App\Models\UserInfo;
use App\Models\Operator;
use App\Models\Notification;
use App\Models\Service;
use App\Models\Promo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function showBookings()
    {
        $bookings = Booking::with(['user', 'operator'])->get();
        $notifications = Notification::all();
        return view('admin.bookings', compact('bookings', 'notifications'));
    }

    public function fetchBookings()
    {
        $bookings = Booking::with(['user', 'operator'])->get();
        return response()->json($bookings);
    }

    public function fetchServicePrice(Request $request)
    {
        $serviceType = $request->input('serviceType');
        $service = Service::where('serviceType', $serviceType)->first();
        if ($service) {
            return response()->json(['servicePrice' => $service->servicePrice]);
        } else {
            return response()->json(['error' => 'Service not found'], 404);
        }
    }

    public function create(Request $request)
    {
        $request->validate([
            'checkInDate' => 'required|date',
            'operator_id' => 'required',
            'car_type' => 'required', 
            'service_type' => 'required',
            'service_price' => 'required',
        ]);

        // Retrieve authenticated user
        $user = auth()->user();

        // Check if user is suspended
        if ($user->flag === 'Suspended') {
            return response()->json(['success' => false, 'message' => 'Your account is suspended. You cannot make bookings.']);
        }

        // Retrieve operator details
        $operatorId = $request->input('operator_id');
        $operator = Operator::findOrFail($operatorId);

        // Check if operator is available for bookings
        if ($operator->status === 0) {
            return response()->json(['success' => false, 'message' => 'Operator is currently unavailable for bookings.']);
        }

        // Check if operator has reached maximum limit of bookings
        if ($operator->bookings->where('status', '!=', 'Cancelled')->where('status', '!=', 'Done')->where('status', '!=', 'Deleted by user')->count() >= 5) {
            return response()->json(['success' => false, 'message' => 'Operator has reached the maximum limit of bookings']);
        }

        // Initialize total price
        $totalPrice = $request->input('service_price');

        // Create booking
        $startDate = Carbon::parse($request->input('checkInDate'));
        $endDate = $startDate->copy()->addDays(5);
        $booking = Booking::create([
            'operator_id' => $operatorId,
            'user_info_id' => $user->id,
            'start_time' => $startDate,
            'end_time' => $endDate,
            'car_type' => $request->input('car_type'),
            'service_type' => $request->input('service_type'),
            'service_price' => $totalPrice, 
            'status' => 'Pending', 
            'isapproved' => 0, 
            'isActive' => 1, 
            'additional_info' => $request->input('additional_info'),
        ]);
        
        // Create notification
        Notification::create([
            'user_id' => $user->id,
            'operator_id' => $operatorId,
            'admin_id' => AdminInfo::first()->adminID,
            'message' => 'Booking created successfully. Please wait for verification.',
            'operator_message' => 'Someone booked you! Check it out!',
            'admin_message' => 'A new booking has been created and is pending verification.',
            'isRead' => 0, 
            'isRead_operator' => 0, 
            'isRead_admin' => 0, 
        ]);

        // Return response
        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'booking_id' => $booking->id, 
        ]);
    }
    
    public function approveBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
    
        if ($booking->status === 'Pending') {
            $booking->status = 'Verified';
            $booking->save();
    
            Notification::create([
                'user_id' => $booking->user_info_id,
                'operator_id' => $booking->operator_id,
                'admin_id' => AdminInfo::first()->adminID, 
                'message' => 'Your booking has been verified by the admin',
                'operator_message' => 'New booking arrived, please check it out.', 
                'admin_message' => 'A booking has been verified.',
                'isRead' => 0,
                'isRead_operator' => 0, 
                'isRead_admin' => 0, 
            ]);
    
            return response()->json(['success' => true, 'message' => 'Booking verified successfully']);
        } elseif ($booking->status == 'Done') {
    
            $operator = Operator::find($booking->operator_id);
            $operator->status = 1; 
            $operator->save();
    
            return response()->json(['success' => true, 'message' => 'Booking marked as done successfully. Operator made active again.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Booking cannot be verified or marked as done. Status is not pending or done.']);
        }
    }    
    
    public function declineBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
    
        if ($booking->status === 'Pending') {
            $booking->status = 'Unverified';
            $booking->save();
    
            Notification::create([
                'user_id' => $booking->user_info_id,
                'operator_id' => $booking->operator_id,
                'admin_id' => AdminInfo::first()->adminID, 
                'message' => 'Your booking has been declined.',
                'operator_message' => 'Admin declined customer\'s booking request.', 
                'admin_message' => 'A booking has been declined.',
                'isRead' => 0,
                'isRead_operator' => 0, 
                'isRead_admin' => 0,  
            ]);
    
            return response()->json(['success' => true, 'message' => 'Booking unverified successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Booking cannot be unverified. Status is not pending.']);
        }
    }
    
    public function requestCancelBooking($bookingId)
    {
        $booking = Booking::where('id', $bookingId)
                        ->where('user_info_id', Auth::id())
                        ->where('status', 'Approved')
                        ->first();
    
        if ($booking) {
            $ticket = $booking->booking_ticket; 
    
            $booking->status = 'Cancel Request';
            $cancelReason = request()->input('cancel_reason'); 
            $booking->cancel_reason = $cancelReason; 
            $booking->save();
            
            Notification::create([
                'user_id' => $booking->user_info_id,
                'operator_id' => $booking->operator_id,
                'admin_id' => AdminInfo::first()->adminID,
                'message' => 'Cancellation requested for booking ticket: <strong>' . $ticket . '</strong>',
                'operator_message' => 'Cancellation requested for booking ticket: <strong>' . $ticket . '</strong>. Reason: <strong>' . $cancelReason . '</strong>',
                'admin_message' => 'A cancellation request has been received for booking ticket: <strong>' . $ticket . '</strong>. Reason: <strong>' . $cancelReason . '</strong>',
                'isRead' => 0,
            ]);
    
            return response()->json(['success' => true, 'message' => 'Cancellation requested successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Booking cannot be cancelled. Either it does not exist, or the status is not approved.']);
        }
    }
    
    public function cancelBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
    
        if ($booking->status === 'Cancel Request') {
            $booking->status = 'Cancelled';
            $booking->isapproved = 0;
            $ticket = $booking->booking_ticket; 
            $booking->save();
    
            Notification::create([
                'user_id' => $booking->user_info_id,
                'operator_id' => $booking->operator_id,
                'admin_id' => AdminInfo::first()->adminID,
                'message' => 'Your cancellation request has been approved. We\'ll process your refund promptly.',
                'operator_message' => 'A user cancelled its booking.',
                'admin_message' => 'You just approved the cancel request for this booking ticket: <strong>' . $ticket . '</strong>',
                'isRead' => 0, 
                'isRead_operator' => 0, 
                'isRead_admin' => 0, 
            ]);
    
            return response()->json(['success' => true, 'message' => 'Cancellation approved successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Cancellation cannot be approved. Status is not "Cancel Request".']);
        }
    }
    
    public function deleteHistory($bookingId)
    {
        $booking = Booking::where('id', $bookingId)
                        ->where('user_info_id', Auth::id())
                        ->whereIn('status', ['Declined', 'Unverified', 'Cancelled', 'Done']) 
                        ->first();
    
        if ($booking) {
            $booking->status = 'Deleted by user';
            $booking->isapproved = 0;
            $booking->save();
    
            return response()->json(['success' => true, 'message' => 'Deleted successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Booking cannot be deleted.']);
        }
    }
    
    public function clearHistory()
    {
        Booking::where('user_info_id', auth()->id())
            ->whereNotIn('status', ['Approved', 'Verified', 'Pending'])
            ->update(['status' => 'Deleted by user']);
    
            return response()->json(['success' => true, 'message' => 'History cleared successfully']);
    }
    
    public function deleteData($bookingId)
    {
        $booking = Booking::where('id', $bookingId)
                        ->where('status', 'Deleted by user') 
                        ->first();
    
        if ($booking) {
            $booking->delete();
    
            return response()->json(['success' => true, 'message' => 'Booking deleted successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Booking cannot be deleted.']);
        }
    }

    public function requestReschedule(Request $request, $bookingId)
    {
        $request->validate([
            'bookingTicket' => 'required|string',
            'newStartDate' => 'required|date',
            'newEndDate' => 'required|date|after:newStartDate',
            'reason' => 'required|string',
        ], [
            'newEndDate.after' => 'Invalid booking format!',
        ]);
    
        $booking = Booking::findOrFail($bookingId);
    
        if ($request->input('bookingTicket') !== $booking->booking_ticket) {
            return response()->json(['success' => false, 'message' => 'Invalid booking ticket']);
        }
    
        $booking->resched_info = $request->input('reason');
        $booking->resched_start_time = $request->input('newStartDate');
        $booking->resched_end_time = $request->input('newEndDate');
        $booking->status = 'Reschedule Request';
        $booking->save();
    
        $userNotificationMessage = 'Your reschedule request has been submitted. Please wait for approval.';
        $operatorNotificationMessage = 'A reschedule request has been submitted by an technician.';
    
        if ($booking->resched_info) {
            $operatorNotificationMessage .=  ' Reason: <i>' . $booking->resched_info . '</i>'; 
        }
    
        Notification::create([
            'user_id' => $booking->user_info_id,
            'operator_id' => $booking->operator_id,
            'admin_id' => AdminInfo::first()->adminID, 
            'message' => $userNotificationMessage,
            'operator_message' => $operatorNotificationMessage,
            'isRead' => 0,
            'isRead_operator' => 0, 
            'isRead_admin' => 0, 
        ]);
    
        return response()->json(['success' => true, 'message' => 'Reschedule request submitted successfully']);
    }
    
    public function approveReschedule($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
    
        if ($booking->status !== 'Reschedule Request') {
            return response()->json(['success' => false, 'message' => 'Booking is not pending reschedule']);
        }
    
        $booking->status = 'Approved';
        $booking->start_time = $booking->resched_start_time;
        $booking->end_time = $booking->resched_end_time;
        $booking->save();
    
        Notification::create([
            'user_id' => $booking->user_info_id,
            'operator_id' => $booking->operator_id,
            'admin_id' => AdminInfo::first()->adminID,
            'message' => 'Your reschedule request has been approved.',
            'operator_message' => 'The reschedule request has been approved.',
            'isRead' => 0,
            'isRead_operator' => 0, 
            'isRead_admin' => 0, 
        ]);
    
        return response()->json(['success' => true, 'message' => 'Reschedule request approved successfully']);
    }
    
    public function requestOperatorReschedule(Request $request, $bookingId)
    {
        $request->validate([
            'bookingTicket' => 'required|string',
            'newStartDate' => 'required|date',
            'newEndDate' => 'required|date|after:newStartDate',
            'reason' => 'required|string',
        ], [
            'newEndDate.after' => 'Invalid booking format!',
        ]);
    
        $booking = Booking::findOrFail($bookingId);
    
        if ($request->input('bookingTicket') !== $booking->booking_ticket) {
            return response()->json(['success' => false, 'message' => 'Invalid booking ticket']);
        }
    
        $booking->resched_info = $request->input('reason');
        $booking->resched_start_time = $request->input('newStartDate');
        $booking->resched_end_time = $request->input('newEndDate');
        $booking->status = 'Reschedule Request by Operator';
        $booking->save();
    
        $operatorNotificationMessage = 'Your reschedule request has been submitted. Please wait for approval.';
        $userNotificationMessage = 'A reschedule request has been submitted by an technician.';
    
        if ($booking->resched_info) {
            $userNotificationMessage .=  ' Reason: <i>' . $booking->resched_info . '</i>'; 
        }
    
        Notification::create([
            'user_id' => $booking->user_info_id,
            'operator_id' => $booking->operator_id,
            'admin_id' => AdminInfo::first()->adminID,
            'message' => $userNotificationMessage,
            'operator_message' => $operatorNotificationMessage,
            'isRead' => 0,
            'isRead_operator' => 0, 
            'isRead_admin' => 0, 
        ]);
    
        return response()->json(['success' => true, 'message' => 'Reschedule request submitted successfully']);
    }    
    
    public function declineReschedule($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
    
        if ($booking->status !== 'Reschedule Request') {
            return response()->json(['success' => false, 'message' => 'Booking is not pending reschedule']);
        }
    
        $booking->status = 'Approved';
        $booking->save();
    
        Notification::create([
            'user_id' => $booking->user_info_id,
            'operator_id' => $booking->operator_id,
            'admin_id' => AdminInfo::first()->adminID,
            'message' => 'Your reschedule request has been declined.',
            'operator_message' => 'The reschedule request has been declined.',
            'isRead' => 0,
            'isRead_operator' => 0, 
            'isRead_admin' => 0, 
        ]);
    
        return response()->json(['success' => true, 'message' => 'Reschedule request declined successfully']);
    }
    
    public function approveOperatorReschedule($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
    
        if ($booking->status !== 'Reschedule Request by Operator') {
            return response()->json(['success' => false, 'message' => 'Booking is not pending reschedule']);
        }
    
        $booking->status = 'Approved';
        $booking->start_time = $booking->resched_start_time;
        $booking->end_time = $booking->resched_end_time;
        $booking->save();
    
        Notification::create([
            'user_id' => $booking->user_info_id,
            'operator_id' => $booking->operator_id,
            'admin_id' => AdminInfo::first()->adminID,
            'message' => 'You just approved the reschedule request.',
            'operator_message' => 'Your reschedule request has been approved.',
            'isRead' => 0,
            'isRead_operator' => 0, 
            'isRead_admin' => 0, 
        ]);
    
        return response()->json(['success' => true, 'message' => 'Reschedule request approved successfully']);
    }
    
    public function declineOperatorReschedule($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
    
        if ($booking->status !== 'Reschedule Request by Operator') {
            return response()->json(['success' => false, 'message' => 'Booking is not pending reschedule']);
        }
    
        $booking->status = 'Approved';
        $booking->save();
    
        Notification::create([
            'user_id' => $booking->user_info_id,
            'operator_id' => $booking->operator_id,
            'admin_id' => AdminInfo::first()->adminID, 
            'message' => 'You declined the reschedule request.',
            'operator_message' => 'The reschedule request has been declined.',
            'isRead' => 0,
            'isRead_operator' => 0, 
            'isRead_admin' => 0, 
        ]);
    
        return response()->json(['success' => true, 'message' => 'Reschedule request declined successfully']);
    }
    
}

