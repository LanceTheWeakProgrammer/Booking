@extends('user.app')

@section('title', 'Starlight | Technicians')

@section('header')

<style>
    .pagination .page-item.active .page-link {
        background-color: grey;
        border-color: grey;
        color: white; /* Optional: Set text color for better visibility */
    }
    .pagination .page-item .page-link {
        color: black;
    }
</style>

@endsection

@section('content')

<div class="my-5 px-4">
  <h2 class="fw-bold h-font text-center">TECHNICIANS</h2>
  <div class="h-line bg-dark"></div>
</div>

<div class="container-fluid col-11">
  <div class="row">

    <!-- Filters   -->
    <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 px-lg-0">
        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
        <div class="container-fluid flex-lg-column align-items-stretch">
                <h4 class="mt-2">FILTERS</h4>
                <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
            <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">
                <div class="border bg-light p-3 rounded mb-3">
                    <h5 class="mb-3" style="font-size: 18px;">Car Type Specialty</h5>
                    @foreach($cars as $car)
                    <div class="mb-2 ms-3">
                        <input type="checkbox" class="form-check-input car-filter shadow-none me-1" data-car="{{ $car->carType }}">
                        <label  class="form-check-label" for="car_{{ $car->carID }}">{{ $car->carType }}</label>
                    </div>
                    @endforeach
                </div>
                <div class="border bg-light p-3 rounded mb-3">
                    <h5 class="mb-3" style="font-size: 18px;">Service Availability</h5>
                    @foreach($services as $service)
                    <div class="mb-2 ms-3">
                        <input type="checkbox" class="form-check-input service-filter shadow-none me-1" data-service="{{ $service->serviceType }}">
                        <label  class="form-check-label" for="service_{{ $service->serviceID }}">{{ $service->serviceType }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        </nav>
    </div>


    <div class="col-lg-1">

    </div>

    <!-- Technicians -->

    <div class="col-lg-8 col-md-12 px-4" id="operators-container">
            @foreach($operators as $operator)
                @if($operator->status == 1) 
                <div class="card mb-4 border-0 shadow operator-card" 
                data-cars="{{ implode(',', $operator->cars->pluck('carType')->toArray()) }}" 
                data-services="{{ implode(',', $operator->services->pluck('serviceType')->toArray()) }}">
                        <div class="row g-0 p-3 align-items-center">
                            <div class="col-md-4 mb-lg-0 mb-md-0 mb-3 text-center">
                                <img src="{{ asset('storage/images/operators/' . $operator->operatorImg) }}" class="img-fluid rounded text-center" style="width: 250px; height: 250px; object-fit: cover;">
                            </div>
                            <div class="col-md-5 px-lg-3 px-md-3 px-0">
                                <h5 class="mb-3">{{ $operator->operatorName }}</h5>
                                <div class="operator mb-4">
                                    <h6 class="mb-2">Car Type Specialty</h6>
                                    @foreach($operator->cars as $car)
                                        <span class='badge rounded-pill text-bg-light text-wrap lh-base'>
                                            {{ $car->carType }}
                                        </span>
                                    @endforeach
                                </div>
                                <div class="service mb-4">
                                    <h6 class="mb-2">Services</h6>
                                    @foreach($operator->services as $service)
                                        <span class='badge rounded-pill text-bg-light text-wrap lh-base'>
                                            {{ $service->serviceType }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-3 align-items-right">
                                <a href="{{ Auth::check() ? url('booking_details/' . $operator->operatorID) : url('login') }}" 
                                    class="btn btn-sm w-100 text-white custom-bg shadow-none mb-2" 
                                    @if(!Auth::check()) onclick="event.preventDefault(); window.location.href='{{ url('login') }}';" @endif>
                                    Book Now
                                </a>
                                <a href="{{ url('operator_details/' . $operator->operatorID) }}" class="btn btn-sm w-100 btn-outline-dark shadow-none">More details</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
    </div> 
  </div>
</div>

<div class="d-flex justify-content-center mt-5">
    {{ $operators->onEachSide(1)->links('pagination::bootstrap-4') }}
</div>


@endsection

@section('footer')

@endsection

@push('scripts')
<script>
function applyFilters() {
    function filterOperators() {
        var selectedCars = [];
        $('.car-filter:checked').each(function() {
            selectedCars.push($(this).data('car'));
        });

        $('.operator-card').each(function() {
            var cars = $(this).data('cars').split(',');
            var show = true;
            $.each(selectedCars, function(index, value) {
                if (!cars.includes(value.trim())) {
                    show = false;
                    return false;
                }
            });

            if (show) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    function filterOperatorsByService() {
        var selectedServices = [];
        $('.service-filter:checked').each(function() {
            selectedServices.push($(this).data('service'));
        });

        $('.operator-card').each(function() {
            var services = $(this).data('services').split(',');
            var show = true;
            $.each(selectedServices, function(index, value) {
                if (!services.includes(value.trim())) {
                    show = false;
                    return false;
                }
            });

            if (show) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $(document).on('change', '.car-filter', function() {
        filterOperators();
        saveFiltersState();
    });

    $(document).on('change', '.service-filter', function() {
        filterOperatorsByService();
        saveFiltersState();
    });

    var savedFilters = JSON.parse(localStorage.getItem('savedFilters'));
    if (savedFilters) {
        $('.car-filter').each(function() {
            var carType = $(this).data('car');
            $(this).prop('checked', savedFilters.selectedCars.includes(carType));
        });

        $('.service-filter').each(function() {
            var serviceType = $(this).data('service');
            $(this).prop('checked', savedFilters.selectedServices.includes(serviceType));
        });

        filterOperators();
        filterOperatorsByService();
    }
}

function saveFiltersState() {
    var selectedCars = $('.car-filter:checked').map(function() {
        return $(this).data('car');
    }).get();

    var selectedServices = $('.service-filter:checked').map(function() {
        return $(this).data('service');
    }).get();

    localStorage.setItem('savedFilters', JSON.stringify({
        selectedCars: selectedCars,
        selectedServices: selectedServices
    }));
}

$(document).ready(function() {
    applyFilters();
});

$(document).ajaxComplete(function() {
    applyFilters();
});

</script>
@endpush


