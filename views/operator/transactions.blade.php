@extends('operator.app')

@section('title', 'Technician Panel')

@section('header')

@endsection

@section('content')

<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-11 m-auto overflow-hidden">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="card-title m-0">Transactions</h4>
            </div>
        </div>

        <div class="col-lg-11 m-auto overflow-hidden p-2">
            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-body">
                    <div class="table-responsive-md" style="height: 490px; overflow-y: scroll;">
                        <table id="transactions-table" class="table table-light table-hover border border-0 border-secondary">
                            <thead class="table-secondary">
                                <tr class="bg-light">
                                    <th>#</th>
                                    <th>Client Name</th>
                                    <th>Email</th>
                                    <th>Contact Number</th>
                                    <th>Service</th>
                                    <th>Car</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="align-middle">
                                @foreach($bookings as $booking)
                                    @php
                                        $fullName = $booking->user->firstName . ' ' . $booking->user->lastName;
                                        $status =  $booking->status;
                                    @endphp
                                    <tr>
                                        <td>{{ $booking->id }}</td>
                                        <td>{{ $fullName }}</td>
                                        <td>{{ $booking->user->email }}</td>
                                        <td>{{ $booking->user->contactNumber }}</td>
                                        <td>{{ $booking->service_type }}</td>
                                        <td>{{ $booking->car_type }}</td>
                                        <td>Start: {{ \Carbon\Carbon::parse($booking->start_time)->format('F j, Y') }} <br>
                                              End: {{ \Carbon\Carbon::parse($booking->end_time)->format('F j, Y') }}
                                        </td>
                                        <td class="text-center">
                                            @if($status == 'Pending')
                                                <span class="badge bg-dark" style="font-size: 0.82rem;">Verifiyng ...</span>
                                            @elseif($status == 'Approved')
                                                <span class="badge bg-success" style="font-size: 0.82rem;">{{ $status }}</span>
                                            @elseif($status == 'Declined')
                                                <span class="badge bg-danger" style="font-size: 0.82rem;">{{ $status }}</span>
                                            @elseif($status == 'Cancelled')
                                                <span class="badge bg-secondary" style="font-size: 0.82rem;">{{ $status }}</span>
                                            @elseif($status == 'Verified')
                                                <span class="badge bg-primary" style="font-size: 0.82rem;">{{ $status }} - Pending</span>
                                            @elseif($status == 'Unverified')
                                                <span class="badge bg-secondary" style="font-size: 0.82rem;">Declined by Admin</span>
                                            @elseif($status == 'Reschedule Request')
                                                <span class="badge bg-secondary" style="font-size: 0.82rem;">Requesting...</span>
                                            @elseif($status == 'Reschedule Request by Operator')
                                                <span class="badge bg-secondary" style="font-size: 0.82rem;">Requesting...</span>
                                            @elseif($status == 'Done')
                                                <span class="badge bg-primary" style="font-size: 0.82rem;">Task Done</span>
                                            @else
                                                {{ $status }}
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-success me-2" onclick="confirmApproveModal({{ $booking->id }})" 
                                                {{ $booking->status == 'Approved' || $booking->status == 'Declined' || $booking->status == 'Cancelled' || $booking->status == 'Reschedule Request' || $booking->status == 'Reschedule Request by Operator' || $booking->status == 'Done' ? 'disabled' : '' }}>
                                                <i class="bi bi-check2"></i>
                                            </button>
                                            <button class="btn btn-danger" onclick="confirmDeclineModal({{ $booking->id }})" 
                                                {{ $booking->status == 'Approved' || $booking->status == 'Declined' || $booking->status == 'Cancelled' || $booking->status == 'Reschedule Request' || $booking->status == 'Reschedule Request by Operator' || $booking->status == 'Done' ? 'disabled' : '' }}>
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="approveConfirmationModal{{ $booking->id }}" tabindex="-1" aria-labelledby="approveConfirmationModalLabel{{ $booking->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="approveConfirmationModalLabel">Confirm Approval</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to approve this booking?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn custom-bg text-white" id="confirmApproveBtn{{ $booking->id }}">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="declineConfirmationModal{{ $booking->id }}" tabindex="-1" aria-labelledby="declineConfirmationModalLabel{{ $booking->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="declineConfirmationModalLabel">Confirm Decline</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to decline this booking?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn custom-bg text-white" id="confirmDeclineBtn{{ $booking->id }}">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if($bookings->isEmpty())
                                <tr>
                                    <td colspan="9" class="text-center align-middle fs-5 border-0">Whoosh! No transactions so far!</td>
                                </tr>
                            @endif
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script>

    $(document).ready(function() {
        $('#transactions-table').DataTable({
            paging: false,
            lengthChange: false,
            searching: true,
            scrollY: 470,
            initComplete: function () {
                $('.dataTables_filter input').addClass('mb-3 mx-2');
            }
        });
    });

    function confirmApproveModal(bookingId) {
        $('#approveConfirmationModal' + bookingId).modal('show');
        $('#confirmApproveBtn' + bookingId).off('click').on('click', function() {
            approveBooking(bookingId);
            $('#approveConfirmationModal' + bookingId).modal('hide');
        });
    }

    function confirmDeclineModal(bookingId) {
        $('#declineConfirmationModal' + bookingId).modal('show');
        $('#confirmDeclineBtn' + bookingId).off('click').on('click', function() {
            declineBooking(bookingId);
            $('#declineConfirmationModal' + bookingId).modal('hide');
        });
    }

    function approveBooking(bookingId) {
        $.ajax({
            url: '/approve-transaction/' + bookingId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    alert('success', 'Booking approved successfully');
                    disableButtons(bookingId);
                } else {
                    alert('danger', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr);
                alert('danger', 'Error approving booking: ' + xhr.responseText);
            }
        });
    }

    function declineBooking(bookingId) {
        $.ajax({
            url: '/decline-transaction/' + bookingId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    alert('success', 'Booking declined successfully');
                    disableButtons(bookingId);
                } else {
                    alert('danger', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr);
                alert('danger', 'Error declining booking: ' + xhr.responseText)
            }
        });
    }

    function disableButtons(bookingId) {
        $(`button[data-booking-id='${bookingId}']`).prop('disabled', true);
    }
</script>
@endpush 