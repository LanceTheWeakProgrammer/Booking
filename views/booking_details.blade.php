@extends('user.app')

@section('title', 'Starlight | Confirm Booking')

<style>
    @media (max-width: 768px) {
        .col-md-4,
        .col-md-8,
        .col-md-12 {
            width: 100%;
        }
    }

    @media (min-width: 992px) {
        .confirm-button {
            margin-top: -45px; 
        }
        #additionalInfo {
            margin-bottom: 3rem; 
        }
    }
</style>

@section('header')

@endsection

@section('content')

<div class="container">
  <div class="row">
    <div class="col-12 my-5 px-3">
        <h2 class="fw-bold">{{ $operator->operatorName }}</h2>
        <div style="font-size: 15px;"> 
            <a href=" {{url ('/') }}" class="text-secondary text-decoration-none">HOME</a>
            <span class="text-secondary"> > </span>
            <a href="{{url ('operator') }}" class="text-secondary text-decoration-none">OPERATOR</a>
            <span class="text-secondary"> > </span>
            <a href="{{url ('booking_details/ . $operator->operatorID)') }}" class="text-secondary text-decoration-none">BOOK</a>
        </div>
    </div>

    <div class="row align-items-end" id="bookingDiv">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="checkInDate" class="form-label">Check-In Date</label>
                <input type="date" class="form-control border-0 shadow-sm" id="checkInDate">
            </div>
            <div class="col-md-4 mb-3">
                <label for="carType" class="form-label">Car Type</label>
                <select class="form-select border-0 shadow-sm" id="carType">
                    @foreach ($operator->cars as $car)
                    <option value="{{ $car->carType }}">{{ $car->carType }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="service" class="form-label">Service</label>
                <select class="form-select border-0 shadow-sm" id="service">
                    <option value="---">---</option>
                    @foreach ($operator->services as $service)
                    <option value="{{ $service->serviceType }}">{{ $service->serviceType }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8 mb-3">
                <label for="additionalInfo" class="form-label">Additional Information</label>
                <textarea id="additionalInfo" class="form-control shadow-sm border-0" rows="7  " style="resize: none; overflow-y: auto;"></textarea>
            </div>
            <div class="col-md-4 mb-3 position-relative"> 
                <div class="card shadow-none border-0 shadow-sm">
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
                    <button class="btn custom-bg text-white shadow-none" id="confirmBooking">Confirm Booking</button>
                </div>
            </div>
        </div>
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

    updateServicePrice();

    $('#service').change(updateServicePrice);

    document.addEventListener('DOMContentLoaded', function () {
        const bookingDiv = document.querySelector('#bookingDiv');
        const confirmBookingButton = document.querySelector('#confirmBooking');
        const checkInDateInput = document.querySelector('#checkInDate');
        const carTypeSelect = document.querySelector('#carType');
        const serviceSelect = document.querySelector('#service');
        const additionalInfoInput = document.querySelector('#additionalInfo');
        
        confirmBookingButton.addEventListener('click', function () {
            const checkInDate = checkInDateInput.value;
            const carType = carTypeSelect.value;
            const serviceType = serviceSelect.value;
            const servicePrice = document.querySelector('#servicePrice').textContent.replace('₱', ''); 
            const additionalInfo = additionalInfoInput.value;
            const operatorId = window.location.pathname.split('/').pop();

            const userFlag = '{{ auth()->user()->flag }}'; 

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
                            setTimeout(function() {
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