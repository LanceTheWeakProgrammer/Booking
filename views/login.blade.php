<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:ital,wght@0,400;0,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">

    <title>Automobile Service Booking System</title>
</head>

<style> 
    div.login-form{
        position: absolute;
        top: 60%;   
        left: 50%;
        transform: translate(-50%, -50%);
        width: 500px;
    }
</style>

<body>

    <div class="container">

        <div class="header">
            <div class="row text-center">
                <div class="col-md-12 mt-2">
                    <h2 class="fw-bold"> <img src="{{asset('images\logo.png')}}" class="me-2" width="45" height="auto">Starlight Automobile Service</h2>
                    <p class="text-body-gray mt-3 fs-6">Login to schedule your automobile service conveniently. </p>
                </div> 
            </div>
        </div>

        <div class="row align-items-center justify-content-center">
            <div class="col-md-4">
            @if(session('error'))
            @if(session('error') === 'Your account has been banned. Please contact support.')
            <div class="position-fixed top-1 end-0 pe-3 mt-5" style="z-index: 9999; top: 5px;">
                <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                    <strong class="me-3">{{ session('error') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @else
            <div class="position-fixed top-1 end-0 pe-3 mt-5" style="z-index: 9999; top: 5px;">
                <div class="alert alert-danger alert-dismissible fade show custom-alert" role="alert">
                    <strong class="me-3">Invalid credentials</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif
            @endif
            <div class="login-form col-md-5 shadow overflow-hidden">
                <form id="loginForm" action="{{ route('login') }}" method="post" class="bg-light bg-opacity-75 p-5 form-control border border-4 border-light" onsubmit="return validateLoginForm()">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-lg-12">
                            <label for="email" class="form-label fs-5 mb-2">Email Address</label>
                            <input type="email" class="form-control mb-2" id="email" name="email" placeholder="Enter your email address" required>
                            <small id="emailError" class="text-danger"></small>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 position-relative">
                                <label for="password" class="text-dark bg-transparent">Password</label>
                                <input type="password" class="form-control text-dark" id="password" name="password" placeholder="Enter your password" required>
                                <div class="input-group-append position-absolute top-50 pt-4 translate-middle-y end-0">
                                    <span class="input-group-text bg-transparent border-0" style="cursor: pointer;" onclick="togglePassword('password')">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn custom-bg text-white mb-3 mt-2">Submit</button>
                        <p class="text-dark mt-2 fs-6">Don't have an account? <a href="{{ url('register') }}" class="text-decoration-none fw-bold text-dark">Sign up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId) {
            var passwordInput = document.getElementById(inputId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }

        function validateLoginForm() {
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;

            if (email.trim() === "") {
                document.getElementById("emailError").textContent = "Email is required";
                return false;
            } else {
                document.getElementById("emailError").textContent = "";
            }

            if (password.trim() === "") {
                document.getElementById("passwordError").textContent = "Password is required";
                return false;
            } else {
                document.getElementById("passwordError").textContent = "";
            }

            return true;
        }
    </script>
</body>
</html>
