<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Mobile Repair Booking System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:ital,wght@0,400;0,500;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">    
            <div class="row text-center">
                <div class="col-md-12 mt-2">
                    <h2 class="fw-bold"> <img src="{{asset('images\logo.png')}}" class="me-2" width="45" height="auto">Starlight Automobile Service</h2>
                    <p class="text-body-gray mt-3 fs-6">Register an account to schedule your automobile service conveniently. Please take your time to fill in all information</p>
                </div> 
            </div>
        </div>

    <!-- Registration Form Section -->
    <div class="row align-items-center justify-content-center">
        <div class="col-md-12">
            <form id="registerForm" class="active register-form col-md-12 p-5 shadow overflow-hidden bg-light bg-opacity-75 border border-4 border-light" action="{{ url('/register') }}" method="post" enctype="multipart/form-data">
                @csrf                    
                <div class="row">
                    <!-- Personal Information Section -->
                    <div class="col-md-12 col-lg-6">
                        <h2 class="text-dark fs-3 mb-4">Personal Information</h2>
                        <div class="row">
                            <div class="col-12">
                                <div class="card text-center" style="width: 250px; height: 250px; margin: 0 auto;">
                                    <div class="card-body d-flex justify-content-center align-items-center">
                                        <img id="picturePreview" src="#" alt=" " class="img-fluid" style="width: 250px; height: 215px; margin: 0 auto;">
                                    </div>
                                </div>
                                <label for="picture" class="form-label text-dark fs-5 mt-3 mb-2"></label>
                                <input type="file" name="picture" id="picture" accept=".jpeg, .png, .webp, .jpg" class="form-control mb-2 text-dark @error('picture') border-danger @enderror">
                                @error('picture')
                                    <small class="text-danger error-message">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-6 col-md-12"> 
                                <div class="mb-3">
                                    <label for="firstName" class="text-dark bg-transparent">First Name</label>
                                    <input type="text" class="form-control text-dark @error('firstName') border-danger @enderror" id="firstName" name="firstName" placeholder="Enter your first name">
                                    @error('firstName')
                                        <small class="text-danger error-message">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="mb-3">
                                    <label for="lastName" class="text-dark bg-transparent">Last Name</label>
                                    <input type="text" class="form-control text-dark @error('lastName') border-danger @enderror" id="lastName" name="lastName" placeholder="Enter your last name">
                                    @error('lastName')
                                        <small class="text-danger error-message">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="position-relative">
                                    <label for="password" class="text-dark bg-transparent">Password</label>
                                    <input type="password" class="form-control text-dark @error('password') border-danger @enderror" id="password" name="password" placeholder="Enter your password">
                                    <div class="input-group-append position-absolute top-50 pt-4 translate-middle-y end-0">
                                        <span class="input-group-text bg-transparent border-0" style="cursor: pointer;" onclick="togglePassword('password')">
                                            <i class="bi bi-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('password')
                                    <small class="text-danger error-message">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-12">
                                <div class="mt-3 position-relative">
                                    <label for="confirmPassword" class="text-dark bg-transparent">Confirm Password</label>
                                    <input type="password" class="form-control text-dark @error('password_confirmation') border-danger @enderror" id="confirmPassword" name="password_confirmation" placeholder="Confirm your password">
                                    <div class="input-group-append position-absolute top-50 pt-4 translate-middle-y end-0">
                                        <span class="input-group-text bg-transparent border-0" style="cursor: pointer;" onclick="togglePassword('confirmPassword')">
                                            <i class="bi bi-eye"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('password_confirmation')
                                    <small class="text-danger error-message">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="gender" class="bg-transparent text-dark">Gender</label>
                                    <select class="form-control text-dark @error('gender') border-danger @enderror" id="gender" name="gender">
                                        <option value="" class="text-dark">---</option>
                                        <option value="Male" class="text-dark">Male</option>
                                        <option value="Female" class="text-dark">Female</option>
                                        <option value="Other" class="text-dark">Other</option>
                                    </select>
                                    @error('gender')
                                        <small class="text-danger error-message">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mt-3">
                                    <label for="birthdate" class="bg-transparent text-dark">Birthdate</label>
                                    <input type="date" class="form-control text-dark @error('birthdate') border-danger @enderror" id="birthdate" name="birthdate">
                                    @error('birthdate')
                                        <small class="text-danger error-message">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Contact Information Section -->
                    <div class="col-lg-6 col-md-12">
                        <h2 class="text-dark fs-3 mb-4">Contact Information</h2>
                        <div class="row mb-4">
                            <div class="col-lg-12 mt-3">
                                <div class="mb-3">
                                    <label for="email" class="text-dark bg-transparent">Email Address</label>
                                    <input type="email" class="form-control text-dark @error('email') border-danger @enderror" id="email" name="email" placeholder="Enter your email address">
                                    @error('email')
                                        <small class="text-danger error-message">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="contactNumber" class="text-dark bg-transparent">Contact Number</label>
                                    <input type="tel" class="form-control text-dark @error('contactNumber') border-danger @enderror" id="contactNumber" name="contactNumber" placeholder="Enter your contact number">
                                    @error('contactNumber')
                                        <small class="text-danger error-message">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>  
                            <div class="col-lg-12 col-md-6">
                                <div class="mb-3">
                                    <label for="country" class="text-dark bg-transparent">Country</label>
                                    <input type="text" class="form-control text-dark @error('address') border-danger @enderror" id="country" name="country" placeholder="Enter your country">
                                    @error('address')
                                        <small class="text-danger error-message">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6">
                                <div class="mb-3">
                                    <label for="province" class="text-dark bg-transparent">Province</label>
                                    <input type="text" class="form-control text-dark @error('address') border-danger @enderror" id="province" name="province" placeholder="Enter your province">
                                    @error('address')
                                        <small class="text-danger error-message">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="text-dark bg-transparent">City/Municipality</label>
                                    <input type="text" class="form-control text-dark @error('address') border-danger @enderror" id="city" name="city" placeholder="Enter your city/municipality">
                                    @error('address')
                                        <small class="text-danger error-message">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-6">
                                <div class="mb-3">
                                    <label for="zipcode" class="text-dark bg-transparent">Zipcode</label>
                                    <input type="text" class="form-control text-dark @error('zipcode') border-danger @enderror" id="zipcode" name="zipcode" placeholder="Enter your zipcode">
                                    @error('zipcode')
                                        <small class="text-danger error-message">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <input type="hidden" id="address" name="address">
                            <div class="col-md-12 mb-4 mt-4 me-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="termsCheck" required>
                                    <label class="form-check-label text-dark" for="termsCheck">
                                        By registering, I agree to the <a href="#" data-bs-toggle="modal" class="text-dark fw-bold text-decoration-none" data-bs-target="#termsModal">Terms and Conditions of Service</a>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="text-dark fs-6 mt-3">Already have an account? <a href="{{ url('login') }}" class="text-decoration-none text-dark fw-bold">Sign in</a></p>
                                    <button type="submit" class="btn custom-bg text-white">Register an account!</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


        <div class="mt-4">

        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="termsModalLabel">Terms and Conditions of Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-black fw-bold">
                        These terms and conditions ("Terms") govern your use of the automobile service booking platform ("Service") provided by STARLIGHT  ("Company", "we", "us", or "our"). By accessing or using the Service, you agree to be bound by these Terms. If you disagree with any part of these Terms, you may not access the Service.
                    </p>
                    <ol class="text-black">
                        <li>
                            <h6>Service Description</h6>
                            <p>
                                The Service provided by the Company allows users to book automobile services, including but not limited to maintenance, repairs, and inspections, with authorized service providers.
                            </p>
                        </li>
                        <li>
                            <h6>User Responsibilities</h6>
                            <p>
                                <ul>
                                    <li>Users must provide accurate and up-to-date information when using the Service.</li>
                                    <li>Users are responsible for any actions taken under their account, including the booking of services.</li>
                                    <li>Users must comply with all applicable laws and regulations while using the Service.</li>
                                </ul>
                            </p>
                        </li>
                        <li>
                            <h6>Service Providers</h6>
                            <p>
                                <ul>
                                    <li>Service providers listed on the platform are independent entities and not employees or agents of the Company.</li>
                                    <li>The Company does not guarantee the quality or availability of services provided by service providers.</li>
                                    <li>Users are solely responsible for their interactions with service providers.</li>
                                </ul>
                            </p>
                        </li>
                        <li>
                            <h6>Booking and Payment</h6>
                            <p>
                                <ul>
                                    <li>Users may book services through the Service by providing necessary details and selecting a service provider.</li>
                                    <li>Payment for services may be processed through the Service. Users are responsible for any fees associated with the services booked.</li>
                                    <li>Prices listed on the platform are subject to change without notice.</li>
                                </ul>
                            </p>
                        </li>
                        <li>
                            <h6>Cancellation and Refunds</h6>
                            <p>
                                <ul>
                                    <li>Users may cancel bookings subject to the cancellation policy of the selected service provider.</li>
                                    <li>Refunds, if applicable, will be processed in accordance with the refund policy of the selected service provider.</li>
                                </ul>
                            </p>
                        </li>
                        <li>
                            <h6>Intellectual Property</h6>
                            <p>
                                All content and materials provided through the Service are the property of the Company or its licensors and are protected by intellectual property laws.
                            </p>
                        </li>
                        <li>
                            <h6>Limitation of Liability</h6>
                            <p>
                                <ul>
                                    <li>The Company shall not be liable for any indirect, incidental, special, or consequential damages arising out of or in connection with the use of the Service.</li>
                                    <li>In no event shall the Company's total liability exceed the amount paid by the user for the services booked through the Service.</li>
                                </ul>
                            </p>
                        </li>
                        <li>
                            <h6>Indemnification</h6>
                            <p>
                                Users agree to indemnify and hold the Company harmless from any claims, damages, or losses arising out of or in connection with their use of the Service.
                            </p>
                        </li>
                        <li>
                            <h6>Modification of Terms</h6>
                            <p>
                                The Company reserves the right to modify these Terms at any time without prior notice. Continued use of the Service after such modifications constitutes acceptance of the revised Terms.
                            </p>
                        </li>
                        <li>
                            <h6>Governing Law</h6>
                            <p>
                                These Terms shall be governed by and construed in accordance with the laws of the Republic of the Philippines.
                            </p>
                        </li>
                        <li>
                            <h6>Contact Information</h6>
                            <p>
                                For questions or concerns regarding these Terms, please contact us at <a  class="text-secondary" href="{{ route ('contact') }}">our User Queries</a>.
                            </p>
                        </li>
                    </ol>
                    <p class="text-black">
                        By using the Service, you acknowledge that you have read, understood, and agree to be bound by these Terms.
                    </p>
                    <p class="text-black">
                        Last Updated: February 19, 2024
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
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

    document.getElementById("picture").addEventListener("change", function(event){
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('picturePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    });

    document.getElementById("country").addEventListener("input", updateAddress);
    document.getElementById("province").addEventListener("input", updateAddress);
    document.getElementById("city").addEventListener("input", updateAddress);

    function updateAddress() {
        var country = document.getElementById("country").value;
        var province = document.getElementById("province").value;
        var city = document.getElementById("city").value;

        var address = country + ", " + province + ", " + city;

        document.getElementById("address").value = address;
    }

</script>

</body>
</html>

