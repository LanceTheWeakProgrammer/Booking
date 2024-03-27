@extends('admin.app')

@section('title', 'Admin Panel')
<style>
    .status-circle {
        display: inline-block;
        width: 13px;
        height: 13px;
        border-radius: 50%;
        margin-right: 5px;
    }

    .online {
        background-color: #00FF00;
    }

    .offline {
        background-color: #808080;
    }

    .dataTables_filter input {
        margin-bottom: 10px;
    }
</style>

@section('content')

<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto overflow-hidden p-3">
            <h3 class="mb-4">Manage Users</h3>

            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-body">
                    <table class="table table-light table-hover border border-0 border-secondary" id="userTable">
                    <thead class="table-secondary">
                        <tr class="bg-light">
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Account Created</th>
                            <th scope="col">Status</th>
                            <th scope="col">Flag</th>
                            <th scope="col">Actions</th>
                        </tr> 
                    </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr class="user-row" data-user-id="{{ $user->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->firstName }} {{ $user->lastName }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->datentime->format('F d, Y g:iA') }}</td>
                                    <td>
                                        <span class="status-circle {{ $user->is_online ? 'online' : 'offline' }}"></span>
                                        {{ $user->isOnline() }}
                                    </td>
                                    <td class="user-flag">{{ $user->flag }}</td>
                                    <td>
                                        @if ($user->flag == 'Warning')
                                            <button type="button" class="btn custom-bg2 btn-set-flag btn-sm text-white" data-flag="Suspended" data-user-id="{{ $user->id }}">Suspend</button>
                                            <button type="button" class="btn btn-secondary btn-dismiss btn-sm text-white" data-user-id="{{ $user->id }}">Dismiss</button>
                                        @elseif ($user->flag == 'Suspended')
                                            <button type="button" class="btn btn-danger btn-set-flag btn-sm text-white" data-flag="Banned" data-user-id="{{ $user->id }}">Ban</button>
                                            <button type="button" class="btn btn-secondary btn-dismiss btn-sm text-white" data-user-id="{{ $user->id }}">Dismiss</button>
                                        @elseif ($user->flag == 'Banned')
                                            <button type="button" class="btn btn-secondary btn-set-flag btn-unban btn-sm text-white" data-flag="Good" data-user-id="{{ $user->id }}">Unban</button>
                                        @else
                                            <button type="button" class="btn btn-warning btn-set-flag btn-sm text-white" data-flag="Warning" data-user-id="{{ $user->id }}">Warning</button>
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

<!-- User Details Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<!-- Confirmation modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body testing" id="confirmModalBody">
                Are you sure you want to perform this action?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirm</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function() {

    $(document).ready(function() {
        $('#userTable').DataTable({
            "paging": false, 
            "scrollY": 280 
        });
    });

    $('.user-row').click(function() {
        var userId = $(this).data('user-id');

        $.ajax({
            type: 'GET',
            url: '/user-details/' + userId,
            success: function(response) {
                $('#userDetailsModal .modal-body').html(response);

                $('#userDetailsModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('error', 'An error occurred while fetching user details.');
            }
        });
    });

    $('.btn-set-flag').click(function(event) {
        event.stopPropagation();
        var userId = $(this).data('user-id');
        var flag = $(this).data('flag');
        var flagCell = $(this).closest('tr').find('.user-flag');

        $('#confirmAction').off('click').on('click', function() {
            $('#confirmationModal').modal('hide');
            $.ajax({
                type: 'POST',
                url: '/set-flag/' + userId,
                data: {
                    _token: '{{ csrf_token() }}',
                    flag: flag
                },
                success: function(response) {
                    console.log('Response:', response); 
                    updateUserButton(userId, flag);
                    flagCell.text(flag);
                    alert('success', 'Action performed successfully!');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('error', 'An error occurred while performing the action.');
                }
            });
        });

        $('#confirmationModal').modal('show');

        return false; 
    });

    $('.btn-unban').click(function(event) {
        event.stopPropagation();
        var userId = $(this).data('user-id');
        var button = $(this);
        var flagCell = $(this).closest('tr').find('.user-flag');

        $('#confirmAction').off('click').on('click', function() {
            $('#confirmationModal').modal('hide');
            $.ajax({
                type: 'POST',
                url: '/unban/' + userId,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    updateUserButton(userId, 'Good');
                    flagCell.text('Good'); 
                    alert('success', 'Action performed successfully!');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('error', 'An error occurred while performing the action.');
                }
            });
        });

        $('#confirmationModal').modal('show');

        return false; 
    });

    $('.btn-dismiss').click(function(event) {
        event.stopPropagation();
        var userId = $(this).data('user-id');
        var flagCell = $(this).closest('tr').find('.user-flag');

        $('#confirmAction').off('click').on('click', function() {
            $('#confirmationModal').modal('hide');
            $.ajax({
                type: 'POST',
                url: '/dismiss/' + userId,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    updateUserButton(userId, 'Good');
                    flagCell.text('Good'); 
                    alert('success', 'User dismissed successfully!');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('error', 'An error occurred while dismissing the user.');
                }
            });
        });

        $('#confirmationModal').modal('show');

        return false; 
    });

    function updateUserButton(userId, flag) {
        var button = $('.btn-set-flag[data-user-id="' + userId + '"]');
        button.removeClass('btn-warning btn-danger btn-secondary custom-bg2');
        switch (flag) {
            case 'Warning':
                button.addClass('custom-bg2');
                button.text('Suspend');
                break;
            case 'Suspended':
                button.addClass('bg-danger');
                button.text('Ban');
                break;
            case 'Banned':
                button.addClass('btn-secondary');
                button.text('Unban');
                break;
            case 'Good':
            default:
                button.addClass('btn-warning');
                button.text('Warning');
                break;
        }
    }
});
    
</script>
@endpush
