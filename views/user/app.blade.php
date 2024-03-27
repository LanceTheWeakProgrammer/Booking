<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Welcome!')</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:ital,wght@0,400;0,500;1,600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <style>
        .my-custom-dropdown {
            min-width: 250px; 
        }
        .my-notification-dropdown {
            min-width: 380px;
            max-height: 500px; 
            overflow-y: auto; 
        }

        @media (max-width: 767.98px) {
            .site-title {
                display: none;
            }
            .site-logo {
                display: none;
            }
            .mobile-dropdown {
                margin-left: 220px;
                top: 10px; 
            }
            .my-notification-dropdown {
                min-width: 340px;
                max-height: 450px; 
                overflow-y: auto; 
            }
        }

        @media (max-width: 992px) {
            .site-logo {
                display: none;
                margin-right: 30px;
            }
        }

        .navbar-nav .nav-link {
            position: relative;
            color: white;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link.active {
            color: purple;
        }
    </style> 

    @stack('head')
</head>
<body>
    @yield('header')

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
                    <form id="logoutForm" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn custom-bg text-white">Logout</button>
                    </form>    
                </div>
            </div>
        </div>
    </div>

    <nav id="nav-bar" class="navbar navbar-expand-lg navbar-light custom-bg px-lg-3 py-lg-2 shadow-sm sticky-top">
        <div class="container-fluid col-sm-12">
            <a class="navbar-brand me-lg-5 me-md-n1 fw-bold fs-3 f-font" href="{{ url('home') }}">
                <img src="{{ asset('images/logo.png') }}" class="site-logo" width="30" height="auto" alt="Logo">
                <span class="site-title text-white px-md-3">{{ $settings->siteTitle }}</span>
            </a>
            <button class="navbar-toggler shadow-none border-0 order-first me-xs-3 px-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <img src="{{ asset('images/logo.png') }}" width="40" height="40" alt="Logo">
            </button>
            <!-- Collapse div -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-white me-2 {{ request()->is('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white me-2 {{ request()->is('operator') ? 'active' : '' }}" href="{{ route('operator') }}">Technicians</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white me-2 {{ request()->is('service') ? 'active' : '' }}" href="{{ route('service') }}">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white me-2 {{ request()->is('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white me-2 {{ request()->is('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                    </li>
                </ul>
            </div>
            <div class="d-flex mobile-dropdown">
                @guest
                    <a href="{{ url('login') }}" class="btn btn-outline-light shadow-none me-lg-3 me-2 mb-2">
                        <span class="glyphicon glyphicon-log-in bg-white"></span> Login
                    </a>
                    <a href="{{ url('register') }}" class="btn btn-outline-light shadow-none me-lg-3 me-2 mb-2">
                        <span class="glyphicon glyphicon-register"></span> Register
                    </a>
                @endguest
                @auth
                <!-- Notification Dropdown -->
                @include('user.notification')
                <!-- User Dropdown -->
                <div class="dropdown position-relative ms-xs-3">
                <button class="btn btn-outline-light bg-transparent shadow-none dropdown-toggle me-0 mt-1 order-last" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('storage/' . Auth::user()->picture) }}" alt="Profile Picture" class="rounded-circle me-lg-2 border-dark" width="32" height="32">
                    <span id="userIcon" class="fs-5">{{ Auth::user()->firstName }}</span> 
                </button>
                    <ul class="dropdown-menu my-custom-dropdown dropdown-menu-end slide p-2 border-0 shadow" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item p-2" href="{{ url('/profile') }}">Profile</a></li>
                        <li>
                            <a class="dropdown-item p-2" href="{{ url('/bookings') }}">My Bookings</a>
                        </li>
                        <div class="ms-3">
                            <li>
                                <a class="dropdown-item fw-bold p-2" href="{{ url('/bookings#bookingLists') }}">Booking Lists</a>
                            </li>
                            <li>
                                <a class="dropdown-item fw-bold p-2" href="{{ url('/bookings#bookingHistory') }}">Booking History</a>
                            </li>
                        </div>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="button" class="dropdown-item p-2 mb-0" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth
            </div>    
        </div>
    </nav>

    <!-- Content -->
    @if($settings->shutdown == 1)
        <h6 class="text-center bg-danger text-white p-3 m-0 sticky-top">This website is currently undergoing maintenance. We apologize for any inconvenience. Please check back later.</h6>
    @yield('content')
    @else

    @yield('content')

    @endif
    <!-- Footer -->
    @yield('footer')

    <!-- Footer Links -->
    <div class="container-fluid bg-white mt-5">
        <div class="row">
            <div class="col-lg-4 p-4">
                <h3 class="h-font fw-bold fs-3 mb-2">
                    Automobile Service Shop
                </h3>
                <p> 
                    {{ $settings->siteAbout}}
                </p>
            </div>
            <div class="col-lg-3 p-4">
                <h5 class="mb-3">Links</h5>
                <a href="{{ url('home') }}" class="d-inline-block mb-2 text-dark text-decoration-none">Home</a> <br>
                <a href="{{ url('operator') }}" class="d-inline-block mb-2 text-dark text-decoration-none">Technicians</a> <br>
                <a href="{{ url('service') }}" class="d-inline-block mb-2 text-dark text-decoration-none">Services</a> <br>
                <a href="{{ url('contact') }}" class="d-inline-block mb-2 text-dark text-decoration-none">Contact Us</a> <br>
                <a href="{{ url('about') }}" class="d-inline-block mb-2 text-dark text-decoration-none">About</a>
            </div>
            <div class="col-lg-2 p-4">
                <h5 class="mb-3">Follow Us</h5>
                <a href="{{ $contactInfo->twt }}" class="d-inline-block text-dark text-decoration-none mb-2">
                    <i class="bi bi-twitter me-1"></i> Twitter
                </a><br>
                <a href="{{ $contactInfo->fb }}" class="d-inline-block text-dark text-decoration-none mb-2">
                    <i class="bi bi-facebook me-1"></i> Facebook
                </a><br>
                <a href="{{ $contactInfo->ig }}" class="d-inline-block text-dark text-decoration-none mb-2">
                    <i class="bi bi-instagram me-1"></i> Instagram
                </a><br>
            </div>
            <div class="col-lg-3 p-4 text-center mt-4">
                <h5>Rate Our Website</h5>
                <button type="button" class="btn custom-bg text-white bt-sm w-100" data-bs-toggle="modal" data-bs-target="#webRateModal">
                    Rate Now!
                </button>
            </div>
        </div>
    </div>

<!-- Rating Modal -->
<div class="modal fade" id="webRateModal" tabindex="-1" aria-labelledby="webRateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 order-lg-1 order-md-2 order-2">
                            <h3>
                                Rate Our Website
                            </h3>
                            <h6 class="mt-3 text-secondary">
                                Let us know what you think!
                            </h6>
                            <form id="webRatingForm">
                                <div id="webRating" class="my-3"></div>        
                                <div class="mb-3">
                                    <label class="form-label" style="font-weight: 500;">Name</label>
                                    <input name="name" type="text" class="form-control shadow-none">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="font-weight: 500;">Email</label>
                                    <input name="email" type="email" class="form-control shadow-none">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" style="font-weight: 500;">Comment</label>
                                    <textarea name="message" required class="form-control shadow-none" rows="5" style="resize: 50;"></textarea>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary" id="submitRating">Submit</button>
                                </div>
                                @csrf 
                            </form>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-4 order-lg-2 order-md-1 order-1">
                            <img src="{{asset ('images\about\1.jpg')}}" class="w-100" >
                        </div>
                    </div>    
                </div>
            </div>
            <div id="thankYouContent" class="p-5" style="display: none;">
                <h3>Thank You for Your Feedback!</h3>
                <p>Your rating has been submitted successfully.</p>
            </div>
        </div>
    </div>
</div>


    <!-- Footer Text -->
    <h6 class="text-center bg-dark text-white p-3 m-0">Team Starlight 2023</h6>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
    <script>

    $(document).ready(function() {
        $("#webRating").rateYo({
            rating: 0,
            spacing: "50px",
            starWidth: "60px",
            fullStar: true
        });

        $("#submitRating").click(function(e) {
            
            var webRating = $("#webRating").rateYo("rating");
            var name = $("input[name='name']").val();
            var email = $("input[name='email']").val();
            var comment = $("textarea[name='message']").val();

            e.preventDefault();

            $.ajax({
                url: "/submit-web-rating",
                type: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    name: name,
                    email: email,
                    webRating: webRating,
                    comment: comment
                },
                success: function(response) {
                    console.log("Rating submitted successfully!");
                    // Hide the rating form
                    $('#webRateModal .modal-body').hide();
                    // Show the thank you message
                    $('#thankYouContent').show();
                },
                error: function(xhr, status, error) {
                    console.error('Error submitting rating:', error);
                    alert('error', 'Error submitting rating. Please try again later.');
                }
            });
        });
    }); 

    function shutdownMode() {
        var shutdown = {{ $settings->shutdown }};
        if (shutdown === 1) {
            var buttons = document.querySelectorAll('button');
            buttons.forEach(function(button) {
                button.disabled = true;
            });

            var links = document.querySelectorAll('a');
            links.forEach(function(link) {
                link.style.pointerEvents = 'none';
            });
        }
    }

    window.onload = function() {
        shutdownMode();
    };

    function updateUserStatus(status) {
        $.ajax({
            url: '/update-user-status',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function(response) {
                console.log('User status updated successfully.');
            },
            error: function(xhr, status, error) {
                console.error('Error updating user status:', error);
            }
        });
    }

    $(document).ready(function() {
        updateUserStatus(1); 
    });

    $(window).on('beforeunload', function() {
        updateUserStatus(0); 
    });

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
        function fetchNotifications() {
            $.ajax({
                url: "/notifications", 
                type: 'GET',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    let notifications = response.notifications.filter(notification => notification.message !== null && notification.message !== undefined);

                    let unreadCount = notifications.filter(notification => notification.isRead === 0).length;

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

                            let message = notification.message;

                            if (message.length > 39) {
                                let substrings = [];
                                let currentSubstring = '';
                                let currentLength = 0;
                                for (let i = 0; i < message.length; i++) {
                                    if (message[i] === ' ' && currentLength > 39) {
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
                                <li class="${notification.isRead ? '' : 'bg-light'}">
                                    <form method="POST" action="/notifications/${notification.id}/mark-read" class="notification-form">
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
        fetchNotifications();

        $('.navbar-toggler').click(function(){
            $('#notificationDropdown, #userDropdown').toggle();
        });

        if (/Mobi/.test(navigator.userAgent)) {
            $('#userIcon').addClass('visually-hidden');
        } else {
            $('#userIcon').removeClass('visually-hidden');
        }

        $('.mark-all-read').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: "/notifications/mark-all-read",
                type: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    fetchNotifications();
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
                url: `/notifications/${notificationId}/mark-read`,
                type: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                
                success: function(response) {
                    if (notificationMessage.includes("Your account has been")) {
                        window.location.href = "{{ route('profile') }}";
                    } else if (notificationMessage.includes( "A warning")) {
                        window.location.href = "{{ route('profile') }}";
                    }else {
                    window.location.href = "{{ route('bookings') }}";
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
                url: "/notifications/" + notificationId + "/delete",
                type: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    fetchNotifications();
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
