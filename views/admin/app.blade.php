<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:ital,wght@0,400;0,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

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
<body class="bg-white">

    @yield('header')
    
<div class="container-fluid custom-bg text-light p-4 d-flex align-items-center justify-content-between sticky-top">
    <h3 class="mb-0 f-font me-2">STARLIGHT</h3>
    <div class="d-flex align-items-center">
        @include('admin.notification')
        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
    </div>
</div>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to logout?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="logout()">Logout</button>
            </div>
        </div>
    </div>
</div>
    
<div class="col-lg-2 bg-light border-top border-3 border-light" id="dashboard-menu" style="z-index: 2;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-light">
        <div class="container-fluid flex-lg-column align-items-stretch">
            <h4 class="mt-2 text-dark">Admin Panel</h4> 
            <button class="navbar-toggler shadow-none custom-bg" type="button" data-bs-toggle="collapse" data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="adminDropdown">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-dark {{ request()->is('admin/dashboard') ? 'active text-white' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark {{ request()->is('admin/bookings') ? 'active text-white' : '' }}" href="{{ route('admin.bookings') }}">Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark {{ request()->is('admin/operators') ? 'active text-white' : '' }}" href="{{ route('admin.operators') }}">Technicians</a>
                    </li>   
                    <li class="nav-item">
                        <a class="nav-link text-dark {{ request()->is('admin/services') ? 'active text-white' : '' }}" href="{{ route('admin.services') }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark {{ request()->is('admin/settings') ? 'active text-white' : '' }}" href="{{ route('admin.settings') }}">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark {{ request()->is('admin/assets') ? 'active text-white' : '' }}" href="{{ route('admin.assets') }}">Assets</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark {{ request()->is('admin/userqueries') ? 'active text-white' : '' }}" href="{{ route('admin.user_queries') }}">User Queries</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark {{ request()->is('admin/manage_users') ? 'active text-white' : '' }}" href="{{ route('admin.manage_users') }}">Users</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
    
@yield('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</script>
<script>
    function logout() {
        setTimeout(function() {
            window.location.href = "{{ route('admin.logout') }}"; 
        }, 500); 
    }
    
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

    $(document).ready(function() {
        function fetchAdminNotif() {
            $.ajax({
                url: "/admin-notifications", 
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    let notifications = response.admin_notifications.filter(notification => notification.admin_message !== null && notification.admin_message !== undefined); 

                    let unreadCount = notifications.filter(notification => notification.isRead_admin === 0).length;

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

                            let message = notification.admin_message;

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
                                <li class="${notification.isRead_admin ? '' : 'bg-light'}">
                                    <form method="POST" action="/admin-notifications/${notification.id}/mark-read" class="notification-form">
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
        fetchAdminNotif();

        $('.mark-all-read').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: "/admin-notifications/mark-all-read",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    fetchAdminNotif();
                },
                error: function(xhr, status, error) {
                    console.error('Error marking all notifications as read:', error);
                }
            });
        });

        $(document).on('click', '.notification-link', function(e) {
            e.preventDefault();
            var notificationId = $(this).closest('form').find('.delete-notification').data('id');
            $.ajax({
                url: `/admin-notifications/${notificationId}/mark-read`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    window.location.href = "{{ route('admin.bookings') }}";
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
                url: "/admin-notifications/" + notificationId + "/delete",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    fetchAdminNotif();
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
    