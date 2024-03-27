<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Technician Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:ital,wght@0,400;0,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/operator.css') }}">

    <style>
        .my-custom-dropdown {
            min-width: 250px; 
        }
        .my-notification-dropdown {
            min-width: 445px;
            max-height: 500px; 
            overflow-y: auto; 
        }
    </style> 

    @stack('head')
</head>
<body class="custom-bg2">

    @yield('header')
    
    <nav class="navbar custom-bg text-light p-3 align-items-center justify-content-between d-flex fixed-top">
        <div class="container-fluid">
            <button class="btn d-flex justify-content-between align-items-center border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <i class="bi bi-wrench fs-2 me-2 text-white"></i>
                <h3 class="mb-0 f-font me-2 text-white">STARLIGHT</h3>
            </button>
            <div class="d-flex align-items-center">
                @include('operator.notification')
                <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#logoutConfirmationModal">Logout</button>
            </div>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h4 class="offcanvas-title mt-2" id="offcanvasNavbarLabel">Welcome, {{ Auth::guard('operator')->user()->operatorName }}!</h4>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-start flex-grow-1 ms-3">
                        <li class="nav-item">
                            <a class="nav-link fs-5" href="{{ route('operator.schedules') }}">Schedules</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-5" href="{{ route('operator.transactions') }}">Transactions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fs-5" href="{{ route('operator.profile') }}">Profile</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

<div class="modal fade" id="logoutConfirmationModal" tabindex="-1" aria-labelledby="logoutConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutConfirmationModalLabel">Confirm Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to logout?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn custom-bg text-white" onclick="logout()">Logout</button>
            </div>
        </div>
    </div>
</div>

@yield('content')
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
    function alert(type, msg) {
        let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
        let element = document.createElement('div');
        element.innerHTML = `
            <div class="position-fixed top-1 end-0 pt-5 pe-3 mt-5" style="z-index: 9999; top: 20px;">
                <div class="alert ${bs_class} alert-dismissible fade show custom-alert" role="alert">
                    <strong class="me-3">${msg}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>`;
        document.body.append(element);
        setTimeout(remAlert, 2000);
    }

    function remAlert(){
        document.getElementsByClassName('alert')[0].remove();
    }

    function logout() {
        window.location.href = "{{ route('operator.logout') }}";
    }

$(document).ready(function() {
    function fetchOPNotif() {
        $.ajax({
            url: "/operator-notifications", 
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function(response) {
                let notifications = response.operator_notifications.filter(notification => notification.operator_message !== null && notification.operator_message !== undefined); 

                let unreadCount = notifications.filter(notification => notification.isRead_operator === 0).length;

                $('#unreadNotificationCount').text(unreadCount);
                if (unreadCount > 0) {
                    $('#unreadNotificationCount').show();
                } else {
                    $('#unreadNotificationCount').hide();
                }

                let notificationListHtml = '';
                if (notifications.length === 0) {
                    notificationListHtml = '<p class="text-center fs-6 my-3">No notifications.</p>';
                } else {
                    notifications.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                    notifications.forEach(notification => {
                        let timeDiff = Math.floor((new Date() - new Date(notification.created_at)) / 1000);
                        let timeAgo;
                        if (timeDiff < 60) {
                            timeAgo = 'Just now';
                        } else if (timeDiff < 3600) {
                            timeAgo = Math.floor(timeDiff / 60) + ' minutes ago';
                        } else if (timeDiff < 86400) {
                            let hours = Math.floor(timeDiff / 3600);
                            timeAgo = hours + (hours === 1 ? ' hour ago' : ' hours ago');
                        } else if (timeDiff < 604800) {
                            let days = Math.floor(timeDiff / 86400);
                            timeAgo = days + (days === 1 ? ' day ago' : ' days ago');
                        } else if (timeDiff < 2592000) {
                            let weeks = Math.floor(timeDiff / 604800);
                            timeAgo = weeks + (weeks === 1 ? ' week ago' : ' weeks ago');
                        } else if (timeDiff < 31536000) {
                            let months = Math.floor(timeDiff / 2592000);
                            timeAgo = months + (months === 1 ? ' month ago' : ' months ago');
                        } else {
                            let years = Math.floor(timeDiff / 31536000);
                            timeAgo = years + (years === 1 ? ' year ago' : ' years ago');
                        }

                        let message = notification.operator_message;

                        if (message.length > 39) {
                            let substrings = [];
                            let currentSubstring = '';
                            let currentLength = 0;
                            for (let i = 0; i < message.length; i++) {
                                if (message[i] === ' ' && currentLength > 45) {
                                    substrings.push(currentSubstring);
                                    currentSubstring = '';
                                    currentLength = 0;
                                } else {
                                    currentSubstring += message[i];
                                    currentLength++;
                                }
                            }
                            substrings.push(currentSubstring);
                            message = substrings.join('<br>');
                        }

                        notificationListHtml += `
                            <li class="${notification.isRead_operator ? '' : 'bg-light'}">
                                <form method="POST" action="/operator-notifications/${notification.id}/mark-read" class="notification-form">
                                    @csrf
                                    <input type="hidden" name="_method" value="POST">
                                    <div class="d-flex align-items-center">
                                        <button type="submit" class="dropdown-item notification-link p-3 flex-grow-1 btn btn-light">
                                            <span>${message}</span>
                                            <small class="text-muted d-block ms-3" style="font-size: 0.8rem;">${timeAgo}</small>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-white me-4 border-0 delete-notification" data-id="${notification.id}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </form>
                            </li>`;
                    });
                }

                $('#notificationList').html(notificationListHtml);

            },
            error: function(xhr, status, error) {
                console.error('Error fetching notifications:', error);
            }
        });
    }
        fetchOPNotif();

        $('.mark-all-read').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: "/operator-notifications/mark-all-read",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    fetchOPNotif();
                },
                error: function(xhr, status, error) {
                    console.error('Error marking all notifications as read:', error);
                }
            });
        });

        $(document).on('click', '.notification-link', function(e) {
            e.preventDefault();
            var notificationId = $(this).closest('form').find('.delete-notification').data('id');
            var notificationMessage = $(this).find('span').text();
            $.ajax({
                url: `/operator-notifications/${notificationId}/mark-read`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (notificationMessage.includes("There's a conflict in your schedule")) {
                        window.location.href = "{{ route('operator.schedules') }}";
                    } else {
                        window.location.href = "{{ route('operator.transactions') }}";
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error marking notification as read:', error);
                }
            });
        });

        $(document).on('click', '.delete-notification', function(e) {
            e.preventDefault();
            var notificationId = $(this).data('id');
            $.ajax({
                url: "/operator-notifications/" + notificationId + "/delete",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    fetchOPNotif();
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting notification:', error);
                }
            });
    });
});

</script>
@stack('scripts')
</body>
</html>