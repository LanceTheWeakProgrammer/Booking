@extends('operator.app')

@section('title', 'Technician Panel')

@section('header')

@endsection

@section('content')
<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-12 col-md-12 px-4" id="costumer-container">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <h4 class="card-title m-0 p-3">Schedules</h4>
                </div>
                <div class="form-check form-switch d-flex align-items-center fs-5">
                    <input class="form-check-input me-2" type="checkbox" id="modeSwitch">
                    <label class="form-check-label" for="modeSwitch">Calendar View</label>
                </div>
            </div>
        </div>
            <!-- Normal Mode Display -->
            <div id="normal-mode">
                <div class="row">
                    <!-- Displaying Schedule Data Cards -->
                    <div class="col-lg-8 col-md-12 px-4" id="costumer-container">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                        </div>
                        @foreach($bookings as $booking)
                            @if ($booking->status == 'Reschedule Request')
                                <div class="modal fade" id="rescheduleModal{{ $booking->id }}" tabindex="-1" role="dialog" aria-labelledby="rescheduleModalLabel{{ $booking->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="rescheduleModalLabel{{ $booking->id }}">Reschedule Request</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to approve or decline the reschedule request for this booking?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" onclick="approveReschedule({{ $booking->id }})">Approve</button>
                                                <button type="button" class="btn btn-danger" onclick="declineReschedule({{ $booking->id }})">Decline</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif      
                        <div class="modal fade" id="rescheduleOperatorModal{{ $booking->id }}" tabindex="-1" aria-labelledby="rescheduleOperatorModalLabel{{ $booking->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="rescheduleOperatorModalLabel({{ $booking->id }})">Request Reschedule</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="rescheduleOperatorForm{{ $booking->id }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="bookingTicket" class="col-form-label">Booking Ticket:</label>
                                                <input type="text" class="form-control" id="bookingTicket" name="bookingTicket" value="{{ $booking->booking_ticket }}" readonly>
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
                                        <button type="button" class="btn custom-bg text-white" id="submitRescheduleOperator{{ $booking->id }}" data-booking-id="{{ $booking->id }}">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal for confirming mark as done -->
                        <div class="modal fade" id="markAsDoneModal" tabindex="-1" role="dialog" aria-labelledby="markAsDoneModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="markAsDoneModalLabel">Mark Task as Done</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to mark this task as done?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn custom-bg text-white" id="confirmMarkAsDone">Confirm</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4 border-0 shadow schedule-card position-relative">
                            <button class="btn btn-outline-white border-0 btn-sm position-absolute top-0 end-0 mt-2 me-3" data-bs-toggle="modal" data-bs-target="#rescheduleOperatorModal{{$booking->id}}">
                                <i class="bi bi-calendar2-week fs-3"></i>
                            </button>                             
                            <div class="row g-0 p-3 align-items-center">
                                <div class="col-md-4 px-lg-3 px-md-3 px-0">
                                    <div class="d-flex align-items-center mb-3">
                                        <h3 class="mb-0 fw-bold">#{{ $booking->booking_ticket }}</h3>
                                        @if ($booking->status == 'Reschedule Request')
                                            <button class="btn border-0 btn-sm bg-transparent shake-animation" data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $booking->id }}">
                                                <i class="bi bi-exclamation-circle fs-3 text-danger"></i>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="mb-4">
                                        <h6 class="mb-2">Client Name:</h6>
                                        <h6 class="fw-bold">{{ $booking->user->firstName }} {{ $booking->user->lastName }}</h6>
                                    </div>
                                    <div class="mb-4">
                                        <h6 class="mb-2">Car</h6>
                                        <h6 class="fw-bold">{{ $booking->car_type }}</h6>
                                    </div>
                                    <div class="mb-4">
                                        <h6 class="mb-2">Task</h6>
                                        <h6 class="fw-bold">{{ $booking->service_type }}</h6>
                                    </div>
                                </div>                    
                                <div class="col-md-5 px-lg-3 px-md-3 px-0 mt-5 pt-4">
                                    <div class="mb-4">
                                        <h6 class="mb-2">Contact Number:</h6>
                                        <h6 class="fw-bold">{{ $booking->user->contactNumber }}</h6>
                                    </div>
                                    <div class="mb-4">
                                        <h6 class="mb-2">Email:</h6>
                                        <h6 class="fw-bold">{{ $booking->user->email }}</h6>
                                    </div>
                                    <div>
                                        <h6 class="mb-2">Schedule Date:</h6>
                                        <h6 class="fw-bold">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('F j, Y') }}
                                            -
                                            {{ \Carbon\Carbon::parse($booking->end_time)->format('F j, Y') }}
                                        </h6>
                                    </div>
                                </div>
                                <div class="col-md-3 align-items-right mt-5">
                                    <button class="btn btn-sm w-100 btn-outline-dark shadow-none mb-2">More Details</button>
                                    <button class="btn btn-sm w-100 custom-bg text-white shadow-none" onclick="markAsDone({{ $booking->id }})">Mark as done</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <ul class="pagination justify-content-center" id="pagination-container">
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous" id="previousPage">
                                <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <li>
                                <p class="page-link disabled">
                                <span class="text-light">---</span>
                                </p>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Next" id="nextPage">
                                <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-lg-4 mt-3 col-md-12 px-4">
                        <div class="card mb-4 border-0 shadow">
                            <div class="card-body">
                                <!-- Calendar Section -->
                                <div id="small-calendar"></div>
                            </div>
                        </div>
                        <div class="bg-white rounded shadow">
                            <div class="container-fluid flex-lg-column align-items-stretch p-3">
                                <h4 class="mt-2 mb-3">PANEL</h4>
                                <div class="border bg-light p-3 rounded m-2">
                                    <div class="d-flex justify-content-between form-check form-switch mb-3">
                                        <h5 style="margin-left: -40px; font-size: 18px;">Tasks</h5>
                                        <label class="form-check-label mt-0 fw-bold" for="toggleDoNotDisturb" style="margin-left: 150px; font-size: 15px;">WORK MODE</label>
                                        <input class="form-check-input" type="checkbox" role="switch" id="toggleDoNotDisturb" onchange="toggleStatus(this.checked ? 2 : 1)">
                                    </div>

                                    @php
                                        $taskCounts = [];
                                    @endphp
                                    @foreach($bookings as $booking)
                                        @php
                                            if ($booking->status !== 'Done') {
                                                $serviceType = $booking->service_type;
                                                if(isset($taskCounts[$serviceType])){
                                                    $taskCounts[$serviceType]++;
                                                } else {
                                                    $taskCounts[$serviceType] = 1;
                                                }
                                            }
                                        @endphp
                                    @endforeach
                                    @if(empty($taskCounts))
                                        <p class="text-center mt-2">No tasks available.</p>
                                    @else
                                        <ul>
                                            @foreach($taskCounts as $task => $count)
                                                <li class="fs-5 mb-2">
                                                    @if($count > 1)
                                                        {{ $task }} ({{ $count }})
                                                    @else
                                                        {{ $task }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <div class="border bg-light rounded mt-4 m-2" style="max-height: 300px; overflow-y: auto;">
                                    <h5 class="mb-3 sticky-top bg-light p-3 top-0 d-flex fixed-top" style="font-size: 18px;">Completed Tasks</h5>
                                    <ul>
                                        @foreach($bookings as $booking)
                                            @if($booking->status == 'Done')
                                                <li class="fs-5 mb-2">
                                                    <span class="fw-bold">{{ $booking->service_type }}</span><br>
                                                    <span style="font-size: 13px; font-style: italic;">{{ \Carbon\Carbon::parse($booking->start_time)->format('F j, Y') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('F j, Y') }}</span>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Calendar Mode -->
            <div id="calendar-mode" style="display: none;">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-4 border-0 shadow border-top border-5 border-white">
                                <div class="card-header bg-light py-4">
                                    <h3 class="card-title text-dark">Calendar</h3>
                                </div>
                                <div class="card-body">
                                    <div id="calendar"></div>
                                </div>
                                <div class="card-footer">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-4 border-0 shadow border-top border-5 border-white">
                                <div class="card-header bg-light">
                                    <h4 class="mt-2 mb-3">PANEL</h4>
                                </div>
                                <div class="card-body">
                                    <div class="container-fluid flex-lg-column align-items-stretch p-3">
                                            <div class="mb-3 px-3">
                                                <h3>Tasks</h3>
                                            </div>
                                            @php
                                                $taskCounts = [];
                                            @endphp
                                            @foreach($bookings as $booking)
                                                @php
                                                    if ($booking->status !== 'Done') {
                                                        $serviceType = $booking->service_type;
                                                        if(isset($taskCounts[$serviceType])){
                                                            $taskCounts[$serviceType]++;
                                                        } else {
                                                            $taskCounts[$serviceType] = 1;
                                                        }
                                                    }
                                                @endphp
                                            @endforeach
                                            @if(empty($taskCounts))
                                                <p class="text-center mt-2">No tasks available.</p>
                                            @else
                                                <ul>
                                                    @foreach($taskCounts as $task => $count)
                                                        <li class="fs-5 mb-2">
                                                            @if($count > 1)
                                                                {{ $task }} ({{ $count }})
                                                            @else
                                                                {{ $task }}
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        <div style="max-height: 300px; overflow-y: auto;">
                                            <h3 class="mb-3 sticky-top top-0 d-flex p-3 bg-white fixed-top">Completed Tasks</h3>
                                            <ul>
                                                @foreach($bookings as $booking)
                                                    @if($booking->status == 'Done')
                                                        <li class="fs-5 mb-2">
                                                            <span class="fw-bold">{{ $booking->service_type }}</span><br>
                                                            <span style="font-size: 13px; font-style: italic;">{{ \Carbon\Carbon::parse($booking->start_time)->format('F j, Y') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('F j, Y') }}</span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <!-- Footer content here if needed -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<!-- Modal for Booking Details -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailsModalLabel">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-modal-body">
                <!-- Booking details will be displayed here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- Include FullCalendar core library -->
    <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet'/>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.simplePagination.js/1.6/jquery.simplePagination.min.js"></script>

    <script>
    function markAsDone(bookingId) {
        $('#markAsDoneModal').modal('show'); 

        $('#confirmMarkAsDone').off('click').on('click', function() {
            fetch(`/mark-as-done/${bookingId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to mark task as done');
                }
                $('#markAsDoneModal').modal('hide'); 
                alert('success', 'Task marked as done successfully');
            })
            .catch(error => {
                console.error('Error marking task as done:', error);
                alert('danger', 'Error marking task as done: ' + error.message);
            });
        });
    }

    function approveReschedule(bookingId) {
        fetch(`/approve-reschedule/${bookingId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to approve reschedule');
            }
            $('#rescheduleModal' + bookingId).modal('hide'); 
            alert('success', 'Reschedule request approved successfully');
        })
        .catch(error => {
            console.error('Error approving reschedule:', error);
            alert('danger', 'Error approving reschedule: ' + error.message);
        });
    }

    function declineReschedule(bookingId) {
        fetch(`/decline-reschedule/${bookingId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to decline reschedule');
            }
            $('#rescheduleModal' + bookingId).modal('hide'); 
            getData();
            alert('success', 'Reschedule request declined successfully');
        })
        .catch(error => {
            console.error('Error declining reschedule:', error);
            alert('danger', 'Error declining reschedule: ' + error.message);
        });
    }

    function toggleStatus() {
        const toggleDoNotDisturb = document.getElementById('toggleDoNotDisturb');
        const status = toggleDoNotDisturb.checked ? 2 : 1; 

        localStorage.setItem('toggleStatus', status);

        $.ajax({
            url: '/update-status',
            type: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({ status: status }),
            success: function(data) {
                if (data.success) {
                    alert('success', 'Status updated successfully');
                    getData();
                    getCalendar();
                } else {
                    alert('danger', 'Failed to update status');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating status:', error);
                alert('danger', 'Error updating status: ' + error.message);
            }
        });
    }

    $(document).ready(function() {
        $('[id^="submitRescheduleOperator"]').click(function() {
            var bookingId = $(this).data('booking-id');
            if (bookingId === undefined) {
                console.error('No booking data available.');
                return;
            }

            var formData = new FormData($('#rescheduleOperatorForm' + bookingId)[0]);

            $.ajax({
                url: '/reschedule-operator-booking/' + bookingId,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data.success) {
                        alert('success','Reschedule request submitted successfully');
                        getData();
                        getCalendar();
                        $('#rescheduleOperatorModal' + bookingId).modal('hide');
                    } else {
                        alert('danger','Error submitting reschedule request: ' + data.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('There was a problem with the fetch operation:', error.message);
                    alert('danger','Error submitting reschedule request');
                }
            });
        });
    });

    $(document).ready(function() {
        $('#calendar').fullCalendar({
            defaultView: 'month',
            events: [
                @foreach($bookings as $booking)
                {
                    title: '#{{ $booking->booking_ticket }} - {{ $booking->service_type }}',
                    start: '{{ \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d') }}',
                    end: '{{ \Carbon\Carbon::parse($booking->end_time)->addDay()->format('Y-m-d') }}',
                    allDay: true,
                    bookingId: '{{ $booking->id }}',
                    bookingDetails: {!! json_encode($booking) !!},
                    @if($booking->conflict)
                        conflict: true,
                    @endif
                    status: '{{ $booking->status }}', 
                },
                @endforeach
            ],
            eventRender: function(event, element) {
                if (event.conflict) {
                    $(element).find('.fc-content').addClass('fc-conflicting-event');
                    $(element).find('.fc-event').addClass('fc-conflicting-event');
                }

                if (event.status === 'Reschedule Request') {
                    $(element).find('.fc-content').addClass('fc-reschedule-request-event shake-animation').prepend('<i class="bi bi-exclamation-circle-fill text-white fs-4 mx-1"></i> ');
                    $(element).find('.fc-event').addClass('fc-reschedule-request-event').prepend('<i class="bi bi-exclamation-circle-fill text-white fs-4 mx-1"></i> ');
                }
            },
            eventClick: function(event) {
                console.log("Event clicked:", event);
                var booking = event.bookingDetails;
                var startDate = new Date(booking.start_time);
                var endDate = new Date(booking.end_time);

                var startFormattedDate = startDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                var endFormattedDate = endDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

                var modalBody = '<div class="row">' +
                    '<div class="col-md-6">' +
                    '<label for="ticket" class="form-label">Ticket:</label>' +
                    '<input type="text" class="form-control" id="ticket" value="' + booking.booking_ticket + '" disabled>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                    '<label for="carType" class="form-label">Car Type:</label>' +
                    '<input type="text" class="form-control" id="carType" value="' + booking.car_type + '" disabled>' +
                    '</div>' +
                    '</div>' +
                    '<div class="row">' +
                    '<div class="col-md-6">' +
                    '<label for="serviceType" class="form-label">Service Type:</label>' +
                    '<input type="text" class="form-control" id="serviceType" value="' + booking.service_type + '" disabled>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                    '<label for="clientName" class="form-label">Client Name:</label>' +
                    '<input type="text" class="form-control" id="clientName" value="' + booking.user.firstName + ' ' + booking.user.lastName + '" disabled>' +
                    '</div>' +
                    '</div>' +
                    '<div class="row">' +
                    '<div class="col-md-6">' +
                    '<label for="startEndDate" class="form-label">Booking Schedule</label>' +
                    '<input type="text" class="form-control" id="startEndDate" value="' + startFormattedDate + ' - ' + endFormattedDate + '" disabled>' +
                    '</div>';

                if (booking.status === 'Reschedule Request') {
                    var reschedStartDate = new Date(booking.resched_start_time);
                    var reschedEndDate = new Date(booking.resched_end_time);
                    var reschedStartFormattedDate = reschedStartDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                    var reschedEndFormattedDate = reschedEndDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

                    modalBody += '<div class="col-md-12">' +
                        '<h4 class="mt-4">Requested for a reschedule:</h4>' +
                        '</div>' +
                        '<div class="row">' +
                        '<div class="col-md-6">' +
                        '<label for="reschedSchedule" class="form-label">Reschedule Start Date:</label>' +
                        '<input type="text" class="form-control" id="reschedSchedule" value="' + reschedStartFormattedDate + '" disabled>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                        '<label for="reschedSchedule" class="form-label">Reschedule End Date:</label>' +
                        '<input type="text" class="form-control" id="reschedSchedule" value="' + reschedEndFormattedDate + '" disabled>' +
                        '</div>' +
                        '</div>' +
                        '<div class="row">' +
                        '<div class="col-md-12">' +
                        '<label for="reschedInfo" class="form-label">Reason:</label>' +
                        '<textarea class="form-control" id="reschedInfo" disabled>' + booking.resched_info + '</textarea>' +
                        '</div>' +
                        '</div>';
                }

                modalBody += '</div>';

                console.log("Modal body:", modalBody);
                $('.custom-modal-body').html(modalBody);
                $('#bookingDetailsModal').modal('show');
            }
        });

        $('#small-calendar').fullCalendar({
            defaultView: 'month',
            events: [
                @foreach($bookings as $booking)
                {
                    title: '#{{ $booking->booking_ticket }} - {{ $booking->service_type }}',
                    start: '{{ \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d') }}',
                    end: '{{ \Carbon\Carbon::parse($booking->end_time)->addDay()->format('Y-m-d') }}',
                    allDay: true,
                    bookingId: '{{ $booking->id }}',
                    bookingDetails: {!! json_encode($booking) !!},
                    @if($booking->conflict)
                        conflict: true,
                    @endif
                    status: '{{ $booking->status }}', 
                },
                @endforeach
            ],
            eventRender: function(event, element) {
                if (event.conflict) {
                    $(element).find('.fc-content').addClass('fc-conflicting-event');
                    $(element).find('.fc-event').addClass('fc-conflicting-event');
                }

                if (event.status === 'Reschedule Request') {
                    $(element).find('.fc-content').addClass('fc-reschedule-request-event').prepend('<i class="bi bi-exclamation-circle-fill text-light fs-6 mx-1"></i> ');
                    $(element).find('.fc-event').addClass('fc-reschedule-request-event').prepend('<i class="bi bi-exclamation-circle-fill text-light fs-6 mx-1"></i> ');
                }
            },
            eventClick: function(event) {
                console.log("Event clicked:", event);
                var booking = event.bookingDetails;
                var startDate = new Date(booking.start_time);
                var endDate = new Date(booking.end_time);

                var startFormattedDate = startDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                var endFormattedDate = endDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

                var modalBody = '<div class="row">' +
                    '<div class="col-md-6">' +
                    '<label for="ticket" class="form-label">Ticket:</label>' +
                    '<input type="text" class="form-control" id="ticket" value="' + booking.booking_ticket + '" disabled>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                    '<label for="carType" class="form-label">Car Type:</label>' +
                    '<input type="text" class="form-control" id="carType" value="' + booking.car_type + '" disabled>' +
                    '</div>' +
                    '</div>' +
                    '<div class="row">' +
                    '<div class="col-md-6">' +
                    '<label for="serviceType" class="form-label">Service Type:</label>' +
                    '<input type="text" class="form-control" id="serviceType" value="' + booking.service_type + '" disabled>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                    '<label for="clientName" class="form-label">Client Name:</label>' +
                    '<input type="text" class="form-control" id="clientName" value="' + booking.user.firstName + ' ' + booking.user.lastName + '" disabled>' +
                    '</div>' +
                    '</div>' +
                    '<div class="row">' +
                    '<div class="col-md-6">' +
                    '<label for="startEndDate" class="form-label">Booking Schedule</label>' +
                    '<input type="text" class="form-control" id="startEndDate" value="' + startFormattedDate + ' - ' + endFormattedDate + '" disabled>' +
                    '</div>';

                if (booking.status === 'Reschedule Request') {
                    var reschedStartDate = new Date(booking.resched_start_time);
                    var reschedEndDate = new Date(booking.resched_end_time);
                    var reschedStartFormattedDate = reschedStartDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                    var reschedEndFormattedDate = reschedEndDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });

                    modalBody += '<div class="col-md-12">' +
                        '<h4 class="mt-4">Requested for a reschedule:</h4>' +
                        '</div>' +
                        '<div class="row">' +
                        '<div class="col-md-6">' +
                        '<label for="reschedSchedule" class="form-label">Reschedule Start Date:</label>' +
                        '<input type="text" class="form-control" id="reschedSchedule" value="' + reschedStartFormattedDate + '" disabled>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                        '<label for="reschedSchedule" class="form-label">Reschedule End Date:</label>' +
                        '<input type="text" class="form-control" id="reschedSchedule" value="' + reschedEndFormattedDate + '" disabled>' +
                        '</div>' +
                        '</div>' +
                        '<div class="row">' +
                        '<div class="col-md-12">' +
                        '<label for="reschedInfo" class="form-label">Reason:</label>' +
                        '<textarea class="form-control" id="reschedInfo" disabled>' + booking.resched_info + '</textarea>' +
                        '</div>' +
                        '</div>';
                }

                modalBody += '</div>';

                console.log("Modal body:", modalBody);
                $('.custom-modal-body').html(modalBody);
                $('#bookingDetailsModal').modal('show');
            }
        });

        $('#modeSwitch').change(function() {
            if ($(this).is(':checked')) {
                $('#normal-mode').hide();
                $('#normal-calendar').show();
                $('#calendar-mode').show();
                $('#calendar').fullCalendar('render');
            } else {
                $('#calendar-mode').hide();
                $('#normal-calendar').hide();
                $('#normal-mode').show();
            }

            const calendarViewStatus = $(this).is(':checked');
            localStorage.setItem('calendarViewStatus', calendarViewStatus);
        });
    });

    $(document).ready(function() {
        var itemsPerPage = 3; 
        var numItems = $('#costumer-container .card').length;
        var totalPages = Math.ceil(numItems / itemsPerPage); 
        var currentPage = 1;

        showPage(currentPage);

        $('#nextPage').on('click', function(e) {
            e.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                showPage(currentPage);
            }
        });

        $('#previousPage').on('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
            }
        });

        function showPage(page) {
            var startItem = (page - 1) * itemsPerPage;
            var endItem = startItem + itemsPerPage;

            $('#costumer-container .card').hide();
            $('#costumer-container .card').slice(startItem, endItem).show();

            if (currentPage === 1) {
                $('#previousPage').parent().addClass('disabled');
            } else {
                $('#previousPage').parent().removeClass('disabled');
            }
            if (currentPage === totalPages) {
                $('#nextPage').parent().addClass('disabled');
            } else {
                $('#nextPage').parent().removeClass('disabled');
            }
        }
    });

    window.onload = function() {
        const storedStatus = localStorage.getItem('toggleStatus');
        const calendarStatus = localStorage.getItem('calendarViewStatus');

        if (storedStatus !== null) {
            const toggleDoNotDisturb = document.getElementById('toggleDoNotDisturb');
            toggleDoNotDisturb.checked = storedStatus == 2;

        }

        if (calendarStatus !== null) {
            const modeSwitch = document.getElementById('modeSwitch');
            modeSwitch.checked = JSON.parse(calendarStatus);
            modeSwitch.dispatchEvent(new Event('change'));
        }
    }
    </script>
@endpush