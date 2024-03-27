@extends('admin.app')

@section('title', 'Admin Panel')

@section('header')

@endsection

@section('content')
<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto overflow-hidden p-3">
            <h3 class="mb-4">Services & Car Models</h3>

            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title m-0">Car Models</h5>
                        <button type="button" class="btn btn-secondary shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#car-s">
                            <i class="bi bi-plus-square"></i> Add
                        </button>
                    </div>

                    <div class="table-responsive-md" style="height: 375px; overflow-y: scroll;">
                        <table class="table table-light table-hover border border-0 border-secondary">
                        <thead class=" table-secondary">
                            <tr class="bg-light text-dark">
                                <th scope="col">#</th>
                                <th scope="col">Make</th>
                                <th scope="col">Model</th>
                                <th scope="col">Type</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="car-data">

                        </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title m-0">Services</h5>
                        <button type="button" class="btn btn-secondary shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#service-s">
                            <i class="bi bi-plus-square"></i> Add
                        </button>
                    </div>

                    <div class="table-responsive-md" style="height: 375px; overflow-y: scroll;">
                        <table class="table table-light table-hover border border-0 border-secondary">
                        <thead class=" table-secondary">
                            <tr class="bg-light">
                                <th scope="col" width="5%">#</th>
                                <th scope="col" width="15%">Type of Service</th>
                                <th scope="col" width="10%">Price</th>
                                <th scope="col" width="10%"></th> <!-- Added Price column -->
                                <th scope="col" width="35%">Description</th>
                                <th scope="col" width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody id="service-data">

                        </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

    <!-- MODAL FOR ADD CAR MODELS -->
<div class="modal fade" id="car-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="carSettForm">
        @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Add a car</h2>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Car make</label>
                        <input type="text" name="carName" required class="form-control shadow-none">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Car model</label>
                        <input type="text" name="carModel" required class="form-control shadow-none">
                    </div>
                    <div class="mb-3">
                    <label class="form-label fw-bold">Car type</label>
                    <select name="carType" class="form-select shadow-none">
                        <option value="Sedan">Sedan</option>
                        <option value="SUV">SUV</option>
                        <option value="Truck">Truck</option>
                        <option value="Hatchback">Hatchback</option>
                        <option value="Crossover">Crossover</option>
                        <option value="Minivan">Minivan</option>
                    </select>
                    </div>
                </div>

                <div shadow-none class="modal-footer">
                    <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn custom-bg text-white shadow-none" data-bs-dismiss="modal">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <!-- MODAL FOR ADD SERVICES -->
<div class="modal fade" id="service-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="serviceSettForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Add a service</h2>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Type of Service</label>
                        <input type="text" name="serviceType" required class="form-control shadow-none">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Icon</label>
                        <input type="file" name="serviceIcon" accept="[.svg]" required class="form-control shadow-none">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Price</label>
                        <input type="number" name="servicePrice" required class="form-control shadow-none" min="0" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control shadow-none" name="sDescription" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn custom-bg text-white shadow-none" data-bs-dismiss="modal">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>

    let carSettForm = document.getElementById('carSettForm');
    let serviceSettForm = document.getElementById('serviceSettForm');

    carSettForm.addEventListener('submit', function (event) {
        event.preventDefault();
        add_car();
    });

    serviceSettForm.addEventListener('submit', function (event) {
        event.preventDefault();
        add_service();
    });

    function add_car() {
        let formData = new FormData(carSettForm);
        formData.append('addCar', '');

        $.ajax({
            url: '/add-car',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                try {
                    let data;
                    if (typeof response === 'string') {
                        data = JSON.parse(response);
                    } else {
                        data = response;
                    }

                    if (data.message) {
                        alert('success', data.message);
                        get_car();
                    } else if (data.error) {
                        alert('error', data.error);
                    } else {
                        console.error('Unexpected response from the server:', response);
                    }
                } catch (error) {
                    console.error('Error parsing JSON response:', error);
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }
    
    function get_car() {
        $.ajax({
            url: '/get-car',
            type: 'GET',
            data: { getCar: true },
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                let carDataHtml = '';
                $.each(response, function (index, car) {
                    carDataHtml += `
                        <tr>
                            <th scope="row">${index + 1}</th>
                            <td>${car.carName}</td>
                            <td>${car.carModel}</td>
                            <td>${car.carType}</td>
                            <td>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="remove_car(${car.carID})">
                                    Remove
                                </button>
                            </td>
                        </tr>`;
                });
                $('#car-data').html(carDataHtml);
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    function remove_car(val) {
        $.ajax({
            url: '/remove-car',
            type: 'POST',
            data: { removeCar: val },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                try {
                    let data;
                    if (typeof response === 'string') {
                        data = JSON.parse(response);
                    } else {
                        data = response;
                    }

                    if (data.message) {
                        alert('success', data.message);
                        get_car();
                    } else if (data.error) {
                        alert('error', data.error);
                    } else {
                        console.error('Unexpected response from the server:', response);
                    }
                } catch (error) {
                    console.error('Error parsing JSON response:', error);
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    function add_service() {
        let formData = new FormData(serviceSettForm);
        formData.append('addService', '');

        $.ajax({
            url: '/add-service',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response == '1') {
                    alert('error', 'Invalid file format: only SVG is allowed');
                } else if (response == '10') {
                    alert('error', 'Image should be less than 1MB');
                } else if (response == '100') {
                    alert('error', 'Upload failed');
                } else {
                    alert('success', 'New service added successfully');
                    serviceSettForm.reset();
                    get_service();
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    function get_service() {

        $.ajax({
            url: '/get-service',
            type: 'GET',
            data: { getService: true },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                let serviceDataHtml = '';
                $.each(response, function (index, service) {
                    serviceDataHtml += `
                        <tr class="align-middle">
                            <th scope="row">${index + 1}</th>
                            <td>${service.serviceType}</td>
                            <td>₱${service.servicePrice}</td>
                            <td><img src="/storage/images/service/${service.serviceIcon}" alt="Service Icon" width="70px"></td>
                            <td>${service.sDescription}</td>
                            <td>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="remove_service(${service.serviceID})">
                                    Remove
                                </button>
                            </td>
                        </tr>`;
                });
                $('#service-data').html(serviceDataHtml);
            },
            error: function (error) {
                console.error('Error:',error);
            }
        });
    }

    function remove_service(val) {
        $.ajax({
            url: '/remove-service',
            type: 'POST',
            data: { removeService: val },
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                let trimmedResponse = response.trim();
                if (trimmedResponse === '1') {
                    alert('error', 'Invalid file format: only SVG is allowed');
                } else if (trimmedResponse === '10') {
                    alert('error', 'Image should be less than 1MB');
                } else if (trimmedResponse === '100') {
                    alert('error', 'Upload failed');
                } else if (trimmedResponse === 'operator_added') {
                    alert('error', 'Service already added in operator!');
                } else {
                    alert('success', 'Service removed!');
                    get_service();
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    window.onload = function () {
        get_car();
        get_service();
    };
</script>
@endpush
