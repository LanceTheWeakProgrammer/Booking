@extends('user.app')

@section('title', 'Starlight | Bookings')

@section('header')

@endsection

@section('content')

@auth

<div class="container-fluid">
    <div class="row justify-content-evenly">
        <div class="col-11 my-5 px-3" id="bookingLists">
            <h2 class="fw-bold">BOOKING LISTS</h2>
            <div style="font-size: 15px;">
                <a href="{{ url('/home') }}" class="text-secondary text-decoration-none">{{ strtoupper(auth()->user()->firstName) }}</a>
                <span class="text-secondary"> > </span>
                <a href="{{ url('bookings') }}" class="text-secondary text-decoration-none">BOOKINGS</a>
            </div>
        </div>
        @php
            $currentUser = auth()->user();
        @endphp 

        @if($bookings->isEmpty())
            <div class="col-12 my-5 px-3" id="emptyBooking">
                <p class="text-center fs-1 fw-italic my-3 text-secondary">No bookings found.</p>
            </div>
        @else        
            @foreach($bookings as $booking)
                @if($booking->user_info_id == $currentUser->id && ($booking->status == 'Approved' || $booking->status == 'Cancel Request' || $booking->status == 'Reschedule Request' || $booking->status == 'Reschedule Request by Operator'))
                    <div class="col-lg-3 col-md-4 p-4" id="booking-container">
                        <div class="card mb-4 border-0 border-top border-4 border-dark pop shadow" style="width: 100%; height: 100%; object-fit: cover;">
                            <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                            @if($booking->status == 'Reschedule Request by Operator')
                                    <button class="btn btn-outline-danger btn-reschedule-operator border-0" data-bs-toggle="modal" data-bs-target="#rescheduleOperator{{ $booking->id }}">
                                        <i class="bi bi-exclamation-triangle fs-2"></i>
                                    </button>
                                    <div class="modal fade" id="rescheduleOperator{{ $booking->id }}" tabindex="-1" aria-labelledby="rescheduleOperatorLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rescheduleOperatorLabel">Request Reschedule</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="rescheduleOperatorForm{{ $booking->id }}">
                                                        <p>Are you sure you want to accept or decline the reschedule request for this booking?</p>
                                                        <div class="mb-3">
                                                            <label for="newStartDate" class="col-form-label">New Start Date:</label>
                                                            <input type="text" class="form-control" id="newStartDate" name="newStartDate" value="{{ $booking->resched_start_time }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="newEndDate" class="col-form-label">New End Date:</label>
                                                            <input type="text" class="form-control" id="newEndDate" name="newEndDate" value="{{ $booking->resched_end_time }}" readonly>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="reason" class="col-form-label">Reason:</label>
                                                            <textarea class="form-control" id="reason" name="reason" rows="3" readonly>{{ $booking->resched_info }}</textarea>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" onclick="declineOperatorReschedule({{ $booking->id }})">Decline</button>
                                                    <button type="button" class="btn btn-success" onclick="approveOperatorReschedule({{ $booking->id }})">Accept</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <h4 class="mb-2">#{{ $booking->booking_ticket }}</h4>
                                    <button class="btn btn-outline-white btn-reschedule border-0" data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $booking->id }}" @if($booking->status != 'Approved') disabled @endif">
                                        <i class="bi bi-calendar2-week fs-2"></i>
                                    </button>
                                </div>
                                <h3 class="py-3">{{ $booking->operator->operatorName }}</h3>
                                <h6 class="mb-2">Start: {{ \Carbon\Carbon::parse($booking->start_time)->format('F j, Y') }}
                                </h6>
                                <h6 class="mb-4">End: {{ \Carbon\Carbon::parse($booking->end_time)->format('F j, Y') }}
                                </h6>
                                <div class="car mb-4">
                                    <h6 class="mb-1">Car</h6>
                                    <span
                                        class="badge rounded-pill text-bg-light text-wrap lh-base">{{ $booking->car_type }}</span>
                                </div>

                                <div class="service mb-4">
                                    <h6 class="mb-1">Service</h6>
                                    <span
                                        class="badge rounded-pill text-bg-light text-wrap lh-base me-2 mt-1">{{ $booking->service_type }}</span>
                                </div>

                                <div class="status mb-4">
                                    <h6 class="mb-1">Status</h6>
                                    @php
                                        $statusColor = '';
                                        switch ($booking->status) {
                                            case 'Approved':
                                                $statusColor = 'success';
                                                break;
                                            default:
                                                $statusColor = 'secondary';
                                        }
                                    @endphp
                                    <span class="badge rounded-pill bg-{{ $statusColor }} text-white text-wrap lh-base me-2 mt-1">
                                        {{ $booking->status }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-evenly mb-2">
                                    @if($booking->status != 'Cancel Request')
                                        <button class="btn btn-sm text-white custom-bg shadow-none" data-bs-toggle="modal" data-bs-target="#cancelBookingModal{{ $booking->id }}">
                                            Cancel Booking
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-secondary shadow-none" onclick="showBookingDetails({{ json_encode($booking) }})" data-bs-toggle="modal" data-bs-target="#bookingDetailsModal{{ $booking->id }}">
                                        More details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="rescheduleModal{{ $booking->id }}" tabindex="-1" aria-labelledby="rescheduleModalLabel{{ $booking->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="rescheduleModalLabel">Request Reschedule</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="rescheduleForm{{ $booking->id }}">
                                        <div class="mb-3">
                                            <label for="bookingTicket" class="col-form-label">Ticket Code:</label>
                                            <input type="text" class="form-control" id="bookingTicket" name="bookingTicket" value="{{ $booking->booking_ticket }}" required readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="newStartDate" class="col-form-label">New Start Date:</label>
                                            <input type="date" class="form-control" id="newStartDate" name="newStartDate" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="newEndDate" class="col-form-label">New End Date:</label>
                                            <input type="date" class="form-control" id="newEndDate" name="newEndDate" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="reason" class="col-form-label">Reason:</label>
                                            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn custom-bg text-white" id="submitReschedule"  onclick="submitReschedule({{ $booking->id }})">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="bookingDetailsModal{{ $booking->id }}" tabindex="-1" aria-labelledby="bookingDetailsModalLabel{{ $booking->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="bookingDetailsModalLabel{{ $booking->id }}">Booking Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- First column content -->
                                            <div class="mb-2">
                                                <label for="bookingTicket" class="form-label fw-bold">Booking Ticket</label>
                                                <input type="text" class="form-control fs-1 bg-white fw-bold border-0" id="bookingTicket" value="# {{ $booking->booking_ticket }}" disabled>
                                            </div>
                                            <div class="mb-2 row">
                                                <div class="col-md-6">
                                                    <label for="carTypeBadge" class="form-label fw-bold">Car Type</label>
                                                    <div id="carTypeBadge"></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="serviceTypeBadge" class="form-label fw-bold">Service Type</label>
                                                    <div id="serviceTypeBadge"></div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="bookingSchedule" class="form-label fw-bold">Schedule</label>
                                                <input type="text" class="form-control bg-transparent border-0 shadow-sm fs-5" id="bookingSchedule" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <!-- Second column content -->
                                            <div class="mb-3">
                                                <label for="operatorName" class="form-label fw-bold">Operator Name</label>
                                                <input type="text" class="form-control bg-transparent border-0 shadow-sm fs-5" id="operatorName" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label for="operatorEmail" class="form-label fw-bold">Operator Email</label>
                                                <input type="text" class="form-control bg-transparent border-0 shadow-sm fs-5" id="operatorEmail" disabled>
                                            </div>
                                            <div class="mb-3">
                                                <label for="operatorTel" class="form-label fw-bold">Contact Number</label>
                                                <input type="text" class="form-control bg-transparent border-0 shadow-sm fs-5" id="operatorTel" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="additionalInfo" class="form-label fw-bold">Additional Information</label>
                                                <textarea class="form-control bg-transparent border-0 shadow-sm p-2" id="additionalInfo" rows="4" style="resize: none;" disabled></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div>
                                        <input type="text" class="form-control bg-white fs-1 border-0 fw-bold text-end" id="servicePrice" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal for cancel booking -->
                    <div class="modal fade" id="cancelBookingModal{{ $booking->id }}" tabindex="-1" aria-labelledby="cancelBookingModalLabel{{ $booking->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="cancelBookingModalLabel">Cancel Booking</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to cancel this booking?</p>
                                    <div class="mb-3">
                                        <label for="cancel_reason" class="form-label">Reason for cancellation:</label>
                                        <textarea class="form-control" id="cancel_reason" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn custom-bg text-white" onclick="requestCancelBooking({{ $booking->id }})" data-bs-dismiss="modal" id="cancelButton{{ $booking->id }}">Request Cancel Booking</button>
                                </div>
                            </div>
                        </div>
                    </div>
            @endif
            @endforeach
            <div class="col-11 my-5 px-3" id="bookingHistory">
                <h2 class="fw-bold">BOOKING HISTORY</h2>
            </div>
            <div class="col-lg-12 m-auto overflow-hidden p-3">
                <div class="card mb-4 border-0 border-top border-4 border-dark pop shadow">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col text-end">
                                <button class="btn btn-secondary btn-sm" id="clearHistoryBtn">Clear History</button>
                            </div>
                        </div>
                        <div class="table-responsive-md" style="height: 390px; overflow-y: scroll;">
                            <table class="table table-light table-hover border border-0 border-secondary">
                                <thead class="table-secondary">
                                    <tr class="bg-light text-black">
                                        <th scope="col">#</th>
                                        <th scope="col">Operator Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Contact Number</th>
                                        <th scope="col">Service</th>
                                        <th scope="col">Car</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="align-middle">
                                    @foreach($bookings as $booking)
                                    @if($booking->user_info_id === $currentUser->id && $booking->status !== 'Deleted by user')
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>{{ $booking->operator->operatorName }}</td>
                                        <td>{{ $booking->operator->operatorEmail }}</td>
                                        <td>{{ $booking->user->contactNumber }}</td>
                                        <td>{{ $booking->service_type }}</td>
                                        <td>{{ $booking->car_type }}</td>
                                        <td>Start: {{ \Carbon\Carbon::parse($booking->start_time)->format('F j, Y') }} <br>
                                            End: {{ \Carbon\Carbon::parse($booking->end_time)->format('F j, Y') }}
                                        </td>
                                        <td>
                                            @if($booking->status == 'Pending')
                                            <span class="badge bg-dark" style="font-size: 0.82rem;">Verifying...</span>
                                            @elseif($booking->status == 'Approved')
                                            <span class="badge bg-success" style="font-size: 0.82rem;">{{ $booking->status }}</span>
                                            @elseif($booking->status == 'Declined')
                                            <span class="badge bg-danger" style="font-size: 0.82rem;">{{ $booking->status }}</span>
                                            @elseif($booking->status == 'Cancelled')
                                            <span class="badge bg-secondary" style="font-size: 0.82rem;">{{ $booking->status }}</span>
                                            @elseif($booking->status == 'Verified')
                                            <span class="badge bg-primary" style="font-size: 0.82rem;">{{ $booking->status }} - Pending</span>
                                            @elseif($booking->status == 'Unverified')
                                            <span class="badge bg-secondary" style="font-size: 0.82rem;">Declined by Admin</span>
                                            @elseif($booking->status == 'Cancel Request' || $booking->status == 'Reschedule Request')
                                            <span class="badge bg-secondary" style="font-size: 0.82rem;">Requesting...</span>
                                            @elseif($booking->status == 'Done')
                                            <span class="badge bg-primary" style="font-size: 0.82rem;">Finished</span>
                                            @else
                                            {{ $booking->status }}
                                            @endif
                                        </td>
                                        <td>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="deleteHistory({{ $booking->id }})">
                                            Delete
                                        </button>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @if($bookings->isEmpty())
                                    <tr>
                                        <td colspan="9">No bookings found.</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>     
        @endif
    </div>
</div>

@else
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endauth

@endsection

@section('footer')

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>

function showBookingDetails(booking) {
    const startDate = new Date(booking.start_time).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    const endDate = new Date(booking.end_time).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

    const bookingSchedule = `${startDate} - ${endDate}`;

    document.getElementById('bookingTicket').value = booking.booking_ticket;
    document.getElementById('operatorName').value = booking.operator.operatorName;
    document.getElementById('operatorEmail').value = booking.operator.operatorEmail;
    document.getElementById('operatorTel').value = booking.operator.operatorTel;
    document.getElementById('bookingSchedule').value = bookingSchedule;
    document.getElementById('additionalInfo').value = booking.additional_info;
    document.getElementById('carTypeBadge').innerHTML = `<span class="badge rounded-pill bg-light text-black shadow-sm fs-6">${booking.car_type}</span>`;
    document.getElementById('serviceTypeBadge').innerHTML = `<span class="badge rounded-pill bg-light text-black shadow-sm fs-6">${booking.service_type}</span>`;
    fetchServicePrice(booking.service_type);
    
    const modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
    modal.show();
}

function fetchServicePrice(serviceType) {
    $.ajax({
        url: '/fetch-service-price',
        type: 'GET',
        data: { serviceType: serviceType },
        success: function(response) {
            document.getElementById('servicePrice').value = `₱ ${response.servicePrice}`;
        },
        error: function(error) {
            console.error(error);
        }
    });
}

function requestCancelBooking(bookingId) {
    var cancel_reason = $('#cancel_reason').val();
    $.ajax({
        url: '/request-cancel-booking/' + bookingId,
        type: 'POST',
        data: {
            cancel_reason: cancel_reason
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert('success', 'Cancellation request sent successfully');
            } else {
                alert('danger', response.message);
            }
        },
        error: function(error) {
            console.error(error);
            alert('danger', 'Error requesting cancellation');
        }
    });
}

function deleteHistory(bookingId) {
    $.ajax({
        url: '/delete-history/' + bookingId,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert('success', 'Deleted successfully');
                loadBookingHistory();
            } else {
                alert('danger', response.message);
            }
        },
        error: function(error) {
            console.error(error);
            alert('danger', 'Error deleting booking history');
        }
    });
}

function approveOperatorReschedule(bookingId) {
    fetch(`/approve-operator-reschedule/${bookingId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to approve reschedule');
        }
        $('#rescheduleOperator' + bookingId).modal('hide'); 
        alert('success', 'Reschedule request approved successfully');
    })
    .catch(error => {
        console.error('Error approving reschedule:', error);
        alert('danger', 'Error approving reschedule: ' + error.message);
    });
}

function declineOperatorReschedule(bookingId) {
    fetch(`/decline-operator-reschedule/${bookingId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to decline reschedule');
        }
        $('#rescheduleOperator' + bookingId).modal('hide'); 
        alert('success', 'Reschedule request declined successfully');
    })
    .catch(error => {
        console.error('Error declining reschedule:', error);
        alert('danger', 'Error declining reschedule: ' + error.message);
    });
}

function submitReschedule(bookingId) {
    $('#submitReschedule').prop('disabled', true);

    var formData = $('#rescheduleForm' + bookingId).serialize();

    $.ajax({
        url: '/reschedule-booking/' + bookingId,
        type: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert('success', 'Reschedule request submitted successfully');
                $('#rescheduleModal' + bookingId).modal('hide');
            } else {
                alert('danger', 'Error submitting reschedule request: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            var errorMessage = 'Error submitting reschedule request';
            if (xhr.status == 422) {
                errorMessage = xhr.responseJSON.message || errorMessage;
            } else {
                errorMessage = error || errorMessage;
            }
            alert('danger', errorMessage);
        },
        complete: function() {
            $('#submitReschedule').prop('disabled', false);
        }
    });
}

$(document).ready(function() {
    $('#clearHistoryBtn').click(function() {
        clearHistory();
    });

    function clearHistory() {
        $.ajax({
            url: '/clear-history',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('.booking-row').remove();
                    alert('success', 'Booking history cleared successfully');
                    loadBookingHistory();
                } else {
                    console.error(response);
                    alert('danger', response.message);
                }
            },
            error: function(error) {
                console.error(error);
                alert('danger', 'Error clearing booking history');
            }
        });
    }

    function loadBookingHistory() {
        $('#bookingHistory').load(window.location.href + ' #bookingHistory');
    }
});
</script>
@endpush
