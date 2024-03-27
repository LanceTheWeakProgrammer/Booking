    @extends('admin.app')

    @section('title', 'Admin Panel')

    @section('content')
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto overflow-hidden p-3">
                <h3 class="mb-4">Bookings</h3>

                <div class="card border-0 shadow-sm mb-4 bg-light text-dark">
                    <div class="card-body">
                        <div class="table-responsive-lg">
                            <table id="bookings-table" class="table table-light table-hover border border-0 border-secondary">
                                <thead class="table-secondary">
                                    <tr class="bg-light text-dark text-center">
                                        <th scope="col">#</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Technician</th>
                                        <th scope="col">Check-In Date</th>
                                        <th scope="col">Check-Out Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    @if(in_array($booking->status, ['Pending', 'Verified', 'Approved', 'Cancel Request']))
                                    <tr class="text-center">
                                        <th scope="row">{{ $booking->id }}</th>
                                        <td>{{ $booking->user->firstName }} {{ $booking->user->lastName }}</td>
                                        <td>{{ $booking->operator->operatorName }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('F j, Y ') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->end_time)->format('F j, Y ') }}</td>
                                        <td>{{ $booking->status }}</td>
                                        <td>
                                            @if($booking->status == 'Pending')
                                            <button class="btn btn-sm btn-success me-2" onclick="performAction('verify', {{ $booking->id }})">
                                                <i class="bi bi-check2"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="performAction('unverify', {{ $booking->id }})">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                            @elseif($booking->status == 'Cancel Request')
                                            <button class="btn btn-sm btn-danger" onclick="confirmCancelBooking({{ $booking->id }})">
                                                Cancel
                                            </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <div class="modal fade" id="cancelBookingModal" tabindex="-1" aria-labelledby="cancelBookingModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="cancelBookingModalLabel">Confirmation</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">                                                
                                                    Are you sure you want to cancel this booking?
                                                </div>
                                                <label for="reason" class="my-3">Reason:</label>
                                                <textarea class="form-control fs-5" name="reason" rows="6" disabled>{{$booking->cancel_reason}}</textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-danger" onclick="cancelBooking()">Yes, Cancel</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>        
            </div>

            <div class="col-lg-10 ms-auto overflow-hidden p-3">
                <h3 class="mb-4">Records</h3>

                <div class="card border-0 shadow-sm mb-4 bg-light text-dark">
                    <div class="card-body">
                        <div class="table-responsive-lg">
                            <table id="records-table" class="table table-light table-hover border border-0 border-secondary">
                                <thead class="table-secondary">
                                    <tr class="bg-light text-dark text-center">
                                        <th scope="col">#</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Technician</th>
                                        <th scope="col">Book Created</th>
                                        <th scope="col">Check-In Date</th>
                                        <th scope="col">Check-Out Date</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookings as $booking)
                                    <tr class="text-center">
                                        <th scope="row">{{ $booking->id }}</th>
                                        <td>{{ $booking->user->firstName }} {{ $booking->user->lastName }}</td>
                                        <td>{{ $booking->operator->operatorName }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->created_at)->format('F j, Y , g:i A') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('F j, Y ') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->end_time)->format('F j, Y ') }}</td>
                                        <td>{{ $booking->status }}</td>
                                        <td>
                                            @if($booking->status === 'Deleted by user')
                                            <button class="btn btn-sm btn-secondary" onclick="performAction('delete', {{ $booking->id }})">
                                                Delete
                                            </button>
                                            @else
                                            <!-- Add any other actions as needed -->
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>        
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <script>
    $(document).ready(function() {
        $('#bookings-table').DataTable({
        paging: false,
        lengthChange: false,
        searching: true,
        scrollY: 470,
        initComplete: function () {
            $('.dataTables_filter input').addClass('mb-3 mx-2');
        }
    });

    $('#records-table').DataTable({
        paging: false,
        lengthChange: false,
        searching: true,
        scrollY: 470,
        initComplete: function () {
            $('.dataTables_filter input').addClass('mb-3 mx-2');
        }
    });
    });

    function performAction(action, bookingId) {
        if (action === 'verify') {
            verifyBooking(bookingId);
        } else if (action == 'unverify') {
            unverifyBooking(bookingId);
        } else if (action == 'approveCancel') {
            cancelBooking(bookingId);
        } else if (action == 'delete') {
            deleteBooking(bookingId);
        }
    }

    function verifyBooking(bookingId){
        $.ajax({
            url: '/approve-booking/' + bookingId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                alert('success', 'Booking verified successfully');
                disableButtons(bookingId);
                fetchUpdatedData();
            },
            error: function(error) {
                console.error(error);
                alert('danger', 'Error verifying booking');
            }
        });
    }

    function unverifyBooking(bookingId) {
        $.ajax({
            url: '/decline-booking/' + bookingId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                alert('success', 'Booking unverified successfully');
                disableButtons(bookingId);
                fetchUpdatedData();
            },
            error: function(error) {
                console.error(error);
                alert('danger', 'Error unverifying booking');
            }
        });
    }

    function deleteBooking(bookingId) {
        $.ajax({
            url: '/delete-data/' + bookingId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                alert('success', 'Booking deleted successfully');
                fetchUpdatedData();
            },
            error: function(error) {
                console.error(error);
                alert('danger', 'Error deleting booking');
            }
        });
    }

    function confirmCancelBooking(bookingId) {
        $('#cancelBookingModal').modal('show');
        $('#cancelBookingModal').find('.btn-danger').attr('onclick', 'cancelBooking(' + bookingId + ')');
    }

    function cancelBooking(bookingId) {
        $.ajax({
            url: '/cancel-booking/' + bookingId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                $('#cancelBookingModal').modal('hide');
                alert('success', 'Booking canceled successfully');
                fetchUpdatedData();
            },
            error: function(error) {
                console.error(error);
                $('#cancelBookingModal').modal('hide');
                alert('danger', 'Error canceling booking');
            }
        });
    }

    function fetchUpdatedData() {
        $.ajax({
            url: '/fetch-bookings',
            type: 'GET',
            success: function(response) {
                console.log(response);
                updateTable(response);
            },
            error: function(error) {
                console.error(error);
                alert('danger', 'Error fetching updated data');
            }
        });
    }

    function updateTable(data) {
        $('#bookings-table tbody').empty();
        $.each(data, function(index, booking) {
            var row = `<tr class="text-center">
                            <th scope="row">${booking.id}</th>
                            <td>${booking.user.firstName} ${booking.user.lastName}</td>
                            <td>${booking.operator.operatorName}</td>
                            <td>${booking.start_time}</td>
                            <td>${booking.end_time}</td>
                            <td>${booking.status}</td>
                            <td>`;
            if (booking.status === 'Pending') {
                row += `<button class="btn btn-sm btn-success me-2" onclick="performAction('verify', ${booking.id})">
                            <i class="bi bi-check2"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="performAction('unverify', ${booking.id})">
                            <i class="bi bi-x-lg"></i>
                        </button>`;
            } else if (booking.status === 'Deleted by user') {
                row += `<button class="btn btn-sm btn-secondary" onclick="performAction('delete', ${booking.id})">
                            Delete
                        </button>`;
            } else {
                // Add any other actions as needed
            }
            row += `</td></tr>`;
            $('#bookings-table tbody').append(row);
        });
    }
    </script>
    @endpush
