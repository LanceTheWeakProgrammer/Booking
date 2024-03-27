@extends('user.app')

@section('title', 'Automobile Repair Booking System - Technician Details')

<style>

    #starRating .star {
        cursor: pointer;
    }

    #starRating .star:hover {
        color: inherit;
    }

    #starRating .star.text-warning {
        color: inherit;
    }

    @media (max-width: 768px) {
        .col-md-4,
        .col-md-8,
        .col-md-12 {
            width: 100%;
        }
    }

    @media (min-width: 992px) {
        .confirm-button {
            margin-top: -35px; 
        }
        #additionalInfo {
            margin-bottom: 3rem; 
        }
    }
</style>

@section('header')

@endsection

@section('content')
<div class="container-fluid col-11">
    <div class="row">
        <div class="col-12 my-5 px-3">
            <h2 class="fw-bold">{{ $operator->operatorName }}</h2>
            <div style="font-size: 15px;"> 
                <a href="{{ url('/') }}" class="text-secondary text-decoration-none">HOME</a>
                <span class="text-secondary"> > </span>
                <a href="{{ url('operator') }}" class="text-secondary text-decoration-none">OPERATOR</a>
            </div>
        </div>

        <div class="card col-lg-12 col-md-12 px-2 bg-light rounded form border-0 shadow-sm" id="operatorDetails">
            <div class="container-fluid p-4">
                <div class="row">
                    <!-- Booking and Rating Section -->
                    <div class="col-md-5 mb-3 form-group">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body p-0 item-align-middle">
                                <div class="row">
                                    <div class="col-md-4 ms-3 d-flex rating align-items-center">
                                        <h6 class="mt-2">Rating: </h6>
                                        @for ($i = 0; $i < $averageRating; $i++)
                                            <i class="bi bi-star-fill text-warning ms-1"></i>
                                        @endfor
                                    </div>
                                    <div class="col-md-7 mt-2 d-flex justify-content-end px-n2">
                                        <button id="bookNowButton" class="btn text-white w-100 custom-bg btn-sm shadow-none mb-2 mx-2">Book Now</button>
                                        <button id="rateButton" class="btn border-0 w-100 btn-success btn-sm shadow-none mb-2 mx-2" data-bs-toggle="modal" data-bs-target="#rateModal">Rate me!</button>
                                    </div>

                                    <!-- Modal for rating -->
                                    <div class="modal fade" id="rateModal" tabindex="-1" aria-labelledby="rateModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title" id="rateModalLabel">Rate <strong>{{ $operator->operatorName }}</strong>!</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <p class="fw-bold fs-3 text-center" id="ratingText">Rating: Very Bad</p>
                                                    </div>
                                                    <!-- Rating Form -->
                                                    <form id="rateForm">
                                                        <div class="mb-3">
                                                            <label for="rating" class="form-label">Your Rating</label>
                                                            <div id="starRating">
                                                                <i class="fs-5 bi bi-star star" data-rating="1"></i>
                                                                <i class="fs-5 bi bi-star star" data-rating="2"></i>
                                                                <i class="fs-5 bi bi-star star" data-rating="3"></i>
                                                                <i class="fs-5 bi bi-star star" data-rating="4"></i>
                                                                <i class="fs-5 bi bi-star star" data-rating="5"></i>
                                                            </div>
                                                            <input type="hidden" name="rating" id="rating" value="0">
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="mb-3">
                                                                    <label for="review" class="form-label">Your Review</label>
                                                                    <textarea class="form-control" id="review" name="review" rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col">
                                                                <button type="submit" class="btn custom-bg text-white btn-sm">Submit</button>
                                                            </div>
                                                        </div>
                                                        @csrf 
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Operator Image Section -->
                        <div class="text-center mt-5" id="operatorImageContainer">
                            <img src="{{ asset('storage/images/operators/' . $operator->operatorImg) }}" alt="" class="img-fluid border rounded border-3 mx-auto d-block" style="width: 300px; height: 300px; object-fit: cover;"> 
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <!-- Operator Information Fields -->
                        <h5 class="fw-bold">Complete Name</h5>
                        <input type="text" name="operatorName" class="form-control shadow-sm bg-transparent border-0 mb-3 fs-5" value="{{ $operator->operatorName }}" disabled>

                        <h5 class="fw-bold">Address</h5>
                        <input type="text" name="opAddress" required class="form-control shadow-sm bg-transparent border-0 mb-3 fs-5" value="{{ $operator->opAddress }}" disabled>

                        <h5 class="fw-bold">Contact Number</h5>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                            <input type="number" name="operatorTel" class="form-control shadow-sm bg-transparent border-0 fs-5" value="{{ $operator->operatorTel }}" disabled>
                        </div>

                        <h5 class="fw-bold">Email</h5>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                            <input type="email" name="operatorEmail" required class="form-control shadow-sm bg-transparent border-0 fs-5" value="{{ $operator->operatorEmail }}" disabled>
                        </div>

                        <h5 class="fw-bold">Experience (Years)</h5>
                        <input type="text" name="jobAge" required class="form-control shadow-sm bg-transparent border-0 mb-3 fs-5" value="{{ $operator->jobAge }} years" disabled>
                    </div>

                    <div class="col-md-3 mb-2">
                        <h5 class="fw-bold">Car Type Specialty</h5>
                        @foreach ($operator->cars as $car)
                            <span class='badge rounded-pill text-bg-light shadow-sm text-wrap lh-base m-1'>
                                {{ $car->carType }}
                            </span>
                        @endforeach

                        <h5 class="fw-bold mt-3">Service Available</h5>
                        @foreach ($operator->services as $service)
                            <span class='badge rounded-pill text-bg-light shadow-sm text-wrap lh-base m-1'>
                                {{ $service->serviceType}}
                            </span>
                        @endforeach   
                    </div>

                    <div class="col-md-12">
                        <h5 class="fw-bold mt-3">Description</h5>
                        <textarea class="form-control shadow-sm border-0 fs-6" name="opDescription" rows="3" disabled>{{ $operator->opDescription }}</textarea>
                        </div> 

                    <!-- Booking Details -->

                    <div class="col-md-12 mt-4" id="bookingDropdown" style="display: none;">
                        <div class="row">
                            <div class="r-line my-3"></div>
                            <h3 class="mb-3">
                                Booking Details
                            </h3>
                            <div class="col-md-4 mb-3">
                                <label for="checkInDate" class="form-label">Check-In Date</label>
                                <input type="date" class="form-control" id="checkInDate">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="carType" class="form-label">Car Type</label>
                                <select class="form-select" id="carType">
                                    @foreach ($operator->cars as $car)
                                    <option value="{{ $car->carType }}">{{ $car->carType }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="service" class="form-label">Service</label>
                                <select class="form-select" id="service">
                                    <option value="---">---</option>
                                    @foreach ($operator->services as $service)
                                    <option value="{{ $service->serviceType }}">{{ $service->serviceType }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="additionalInfo" class="form-label">Additional Information</label>
                                <textarea id="additionalInfo" class="form-control shadow-none" rows="8" style="resize: none; overflow-y: auto;"></textarea>
                            </div>
                            <div class="col-md-4 mb-3 position-relative"> 
                                <div class="card border-top border-4 pop shadow">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div>
                                            <h5 class="card-title fw-bold">FEE</h5>
                                            <h6 class="card-title">Service: <span class="fw-bold fs-4" id="servicePrice">---</span></h6>
                                            <div class="r-line my-3"></div>
                                            <h6 class="card-title">Total Price: <span class="fw-bold fs-4" id="totalPrice">---</span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                <div class="d-grid gap-2">
                                    <button class="btn custom-bg text-white shadow-none mb-2" id="confirmBooking">Confirm Booking</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<div class="col-lg-12 mt-4">
    <h5>Reviews</h5>
@include('user.rating')
</div>
    </div>
</div>
@endsection

@section('footer')

@endsection

@push('scripts')
<script>
    function updateServicePrice() {
        const selectedServiceType = $('#service').val();
        const selectedService = {!! json_encode($operator->services) !!}.find(service => service.serviceType === selectedServiceType);

        if (selectedService) {
            const servicePrice = selectedService.servicePrice;
            $('#servicePrice').text(`₱${servicePrice}`);
            $('#totalPrice').text(`₱${servicePrice}`);
        } else {
            $('#totalPrice').text('---');
            $('#servicePrice').text('---');
        }
    }

    $(document).ready(function () {
        function updateRatingText(rating) {
            var ratingText = '';
            switch (parseInt(rating)) {
                case 1:
                    ratingText = 'Very Bad';
                    break;
                case 2:
                    ratingText = 'Bad';
                    break;
                case 3:
                    ratingText = 'Fair';
                    break;
                case 4:
                    ratingText = 'Good';
                    break;
                case 5:
                    ratingText = 'Very Good';
                    break;
                default:
                    ratingText = 'Not Rated';
            }
            $('#ratingText').text('Rating: ' + ratingText);
        }

        $('#starRating').rateYo({
            rating: $('#rating').val(),
            fullStar: true,
            spacing: "5px",
            onSet: function (rating, rateYoInstance) {
                $('#rating').val(rating);
                updateRatingText(rating);
            }
        });

        $('#rateForm').submit(function (event) {
            event.preventDefault();

            var formData = {
                rating: $('#rating').val(),
                review: $('#review').val(),
                operator_id: {{ $operator->operatorID }}
            };

            $.ajax({
                url: '/submit-rating',
                type: 'POST',
                data: JSON.stringify(formData),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    $('#rateModal').modal('hide');
                    alert('success', 'Thank you for your feedback!');
                    $('.review-section').html(response.ratings_html);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('error', 'Failed to submit rating. Please try again.');
                }
            });
        });

        $('.like-btn, .dislike-btn').click(function() {
            var $button = $(this);
            var ratingId = $button.data('rating-id');
            var action = $button.hasClass('like-btn') ? 'like' : 'dislike';

            $.ajax({
                type: 'POST',
                url: '/ratings/' + ratingId + '/' + action,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $button.find('i').text(response[action + 's']).removeClass('text-success text-danger text-secondary').addClass(action === 'like' ? 'text-success' : 'text-danger');
                    
                    var oppositeAction = action === 'like' ? 'dislike' : 'like';
                    var $oppositeButton = $button.siblings('.' + oppositeAction + '-btn');
                    if ($oppositeButton.length) {
                        $oppositeButton.find('i').text(response[oppositeAction + 's']).removeClass('text-success text-danger text-secondary').addClass(response[oppositeAction + 's'] === 0 ? 'text-secondary' : oppositeAction === 'like' ? 'text-success' : 'text-danger');
                    }
                }
            });
        });
    });

    updateServicePrice();


    $('#service').change(updateServicePrice);

    document.getElementById('additionalInfo').addEventListener('focus', function() {
            document.getElementById('operatorDetails').style.marginBottom = '3rem';
        });

    document.getElementById('additionalInfo').addEventListener('blur', function() {
        document.getElementById('operatorDetails').style.marginBottom = '0';
    });

    document.addEventListener('DOMContentLoaded', function () {
        const bookNowButton = document.querySelector('#bookNowButton');
        const bookingDropdown = document.querySelector('#bookingDropdown');
        const confirmBookingButton = document.querySelector('#confirmBooking');
        const checkInDateInput = document.querySelector('#checkInDate');
        const carTypeSelect = document.querySelector('#carType');
        const serviceSelect = document.querySelector('#service');
        const additionalInfoInput = document.querySelector('#additionalInfo');

        const userFlag = '{{ auth()->user()->flag }}';

        bookNowButton.addEventListener('click', function () {
            const isHidden = bookingDropdown.style.display === 'none';
            if (isHidden) {
                bookingDropdown.style.transition = 'height 0.5s ease';
                bookingDropdown.style.display = 'block';
                bookingDropdown.style.height = bookingDropdown.scrollHeight + 'px';
                bookingDropdown.scrollIntoView({ behavior: 'smooth' });
            } else {
                bookingDropdown.style.height = '0';
                setTimeout(() => {
                    bookingDropdown.style.display = 'none';
                }, 500);
            }
        });

        confirmBookingButton.addEventListener('click', function () {
            const checkInDate = checkInDateInput.value;
            const carType = carTypeSelect.value;
            const serviceType = serviceSelect.value;
            const servicePrice = document.querySelector('#servicePrice').textContent.replace('₱', '');
            const additionalInfo = additionalInfoInput.value;
            const operatorId = window.location.pathname.split('/').pop();

            if (userFlag === 'Suspended') {
                alert('error', 'Your account is suspended. You cannot make a booking.');
                return;
            }

            $.ajax({
                url: '/create-booking',
                type: 'POST',
                data: {
                    checkInDate: checkInDate,
                    operator_id: operatorId,
                    car_type: carType,
                    service_type: serviceType,
                    service_price: servicePrice,
                    additional_info: additionalInfo,
                    isapproved: 0
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success == true) {
                        alert('success', 'Booking confirmed!');
                        if (response.booking_id !== null) {
                            window.location.href = '/receipt/' + response.booking_id;
                            setTimeout(function () {
                                location.reload();
                            }, 3000);
                        } else {
                            console.error('Booking ID is null.');
                        }
                    } else {
                        alert('danger', 'Booking failed. Please try again.');
                    }
                },
                error: function (error) {
                    alert('danger', 'Booking failed. Please try again.');
                }
            });
        });

    });
</script>
@endpush