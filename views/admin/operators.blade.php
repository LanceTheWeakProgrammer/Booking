@extends('admin.app')

@section('title', 'Admin Panel')

@section('header')

@endsection

@section('content')

<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto overflow-hidden p-3">
            <h3 class="mb-4">Technicians</h3>

            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-body">

                    <div class="text-end mb-4">
                        <button type="button" class="btn btn-secondary shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-operator">
                            <i class="bi bi-plus-square"></i> Add
                        </button>
                    </div>

                    <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
                        <table class="table table-light table-hover border border-0 border-secondary">
                        <thead class="table-secondary">
                            <tr class="bg-dark text-light text-center">
                                <th scope="col">#</th>
                                <th scope="col" width="9%">Name</th>
                                <th scope="col" width="20%">Location</th>
                                <th scope="col" width="13%">Contact Number</th>
                                <th scope="col">Email</th>
                                <th scope="col" width="5%">Experience</th>
                                <th scope="col" width="9%">Hour Rate</th>
                                <th scope="col" width="9%">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>

                        <tbody id="operator-data">

                        </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

    <!-- MODAL FOR ADD OPERATORS -->
    <div class="modal fade" id="add-operator" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"       aria-labelledby="staticBackdropLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <form id="operatorAddForm" autocomplete="off">
                <div class="modal-content">
                    <!-- HEADER -->
                        <div class="modal-header">
                            <h2 class="modal-title">Add a technician</h2>
                        </div>
                    <!-- BODY -->
                        <div class="modal-body">
                            <div class="container-fuild p-0">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Complete Name</label>
                                            <input type="text" name="operatorName" id="operatorName" required class="form-control shadow-none">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Address</label>
                                            <input type="text" name="opAddress" id="opAddress" required class="form-control shadow-none">
                                        </div>
                                            <label class="form-label fw-bold">Contact number</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                                <input type="number" name="operatorTel" id="operatorTel_in" class="form-control shadow-none">
                                            </div>
                                    </div>                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Email</label>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                                                    <input type="email" name="operatorEmail" id="operatorEmail_in" class="form-control shadow-none">
                                                </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Experience (Years)</label>
                                            <input type="text" name="jobAge" id="jobAge_in" required class="form-control shadow-none">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Hourly Rate</label>
                                            <input type="text" name="hRate" id="hRate_in" step="0.01" required class="form-control shadow-none">
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-bold">Car Type Specialty</label>
                                            <div class="row">

                                                @php
                                                    $U = [];
                                                @endphp

                                                @foreach($car as $option)
                                                    @php
                                                        $carType = $option['carType'];
                                                    @endphp

                                                    @if (!in_array($carType, $U))
                                                        @php
                                                            $U[] = $carType;
                                                        @endphp

                                                        <div class="col-md-3 mb-1">
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                <input class="form-check-input shadow-none" type="checkbox" name="car" value="{{ $option['carID'] }}">
                                                                    {{ $option['carType'] }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-bold">Service Offered</label>
                                        <div class="row">
                                             @php
                                                    $U = [];
                                                @endphp

                                                @foreach($service as $option)
                                                    @php
                                                        $serviceType = $option['serviceType'];
                                                    @endphp

                                                    @if (!in_array($carType, $U))
                                                        @php
                                                            $U[] = $serviceType;
                                                        @endphp

                                                        <div class="col-md-3 mb-1">
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                <input class="form-check-input shadow-none" type="checkbox" name="service" value="{{ $option['serviceID'] }}">
                                                                    {{ $option['serviceType'] }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-bold">Description</label>
                                        <textarea class="form-control shadow-none" name="opDescription"rows="4"></textarea>
                                    </div>
                                    <div class="col-12 mb-3">
                                            <label class="form-label fw-bold">Add Image</label>
                                            <input type="file" name="operatorImg" accept="[.jepg, .png, .webp, .jpg]" required class="form-control shadow-none mb-3">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- FOOTER -->
                        <div shadow-none class="modal-footer">
                            <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn custom-bg text-white shadow-none" data-bs-dismiss="modal">Save Changes</button>
                        </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL FOR EDIT OPERATORS -->
    <div class="modal fade" id="edit-operator" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <form id="operatorEditForm" autocomplete="off" enctype="multipart/form-data">
                <div class="modal-content">
                    <!-- HEADER -->
                        <div class="modal-header">
                            <h2 class="modal-title">Edit Technician Profile</h2>
                        </div>
                    <!-- BODY -->
                    <div class="modal-body">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-md-6 mb-3 form-group">
                                    <div class="text-center" id="operatorEditImage">
                                        <h3 class="fw-bold p-3 text-center">Profile</h3>
                                        <div class="position-relative mt-5">
                                            <img src='' alt=' ' class='img-fluid border rounded border-3 mx-auto d-block' id='operator-image-edit' style="width: 250px; height: 250px; object-fit: cover;">
                                            <input type="file" id="operatorImg" name="operatorImg" accept="image/*" class="shadow-none mt-3 ms-5 ps-2">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h5 class="fw-bold">Complete Name</h5>
                                    <input type="text" name="operatorName" id="operatorName" class="form-control shadow-none mb-3">
                                    
                                    <h5 class="fw-bold">Address</h5>
                                    <input type="text" name="opAddress" id="opAddress" required class="form-control shadow-none mb-3">                                    
                                    <h5 class="fw-bold">Contact Number</h5>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                        <input type="number" name="operatorTel" id="operatorTel_in" class="form-control shadow-none">
                                    </div>

                                    <h5 class="fw-bold">Email</h5>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                                        <input type="email" name="operatorEmail" id="operatorEmail_in" class="form-control shadow-none">
                                    </div>
                                    
                                    <h5 class="fw-bold">Experience (Years)</h5>
                                    <input type="text" name="jobAge" id="jobAge_in" required class="form-control shadow-none mb-3">
                                    
                                    <h5 class="fw-bold">Hourly Rate</h5>
                                    <input type="text" name="hRate" id="hRate_in" step="0.01" required class="form-control shadow-none mb-3">
                                </div>
                                <!-- Car Type Specialty -->
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Car Type Specialty</label>
                                        <div class="row">

                                            @php
                                                $U = [];
                                            @endphp

                                            @foreach($car as $option)
                                                @php
                                                    $carType = $option['carType'];
                                                @endphp

                                                @if (!in_array($carType, $U))
                                                    @php
                                                        $U[] = $carType;
                                                    @endphp

                                                    <div class="col-md-3 mb-1">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                            <input class="form-check-input shadow-none" type="checkbox" name="car" value="{{ $option['carID'] }}">
                                                                {{ $option['carType'] }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                </div>
                                <!-- Service Offered -->
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Service Offered</label>
                                    <div class="row">
                                        @php
                                            $U = [];
                                        @endphp

                                        @foreach($service as $option)
                                            @php
                                                $serviceType = $option['serviceType'];
                                            @endphp

                                            @if (!in_array($carType, $U))
                                                @php
                                                    $U[] = $serviceType;
                                                @endphp

                                                <div class="col-md-3 mb-1">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                        <input class="form-check-input shadow-none" type="checkbox" name="service" value="{{ $option['serviceID'] }}">
                                                            {{ $option['serviceType'] }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Operator Description -->
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea class="form-control shadow-none" name="opDescription" rows="4"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="operatorID">
                        </div>
                    </div>
                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none" data-bs-dismiss="modal">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

   <!-- VIEW PROFILE OPERATOR -->
   <div class="modal fade" id="view-operator" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <form id="operatorViewForm" autocomplete="off">
                <div class="modal-content">
                    <!-- HEADER -->
                        <div class="modal-header">
                            <h2 class="modal-title">Technician Profile</h2>
                            <button type="reset" class="btn-close btn-primary" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    <!-- BODY -->
                    <div class="modal-body">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-md-6 mb-3 form-group">
                                    <div class="text-center" id="operatorImageContainer">
                                    <h3 class="fw-bold p-3 text-center">Profile</h3>
                                        <img src='' alt=' ' class='img-fluid border rounded border-3 mx-auto d-block' id='operator-image-view' style="width: 250px; height: 250px; object-fit: cover;">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h5 class="fw-bold">Complete Name</h5>
                                    <input type="text" name="operatorName" id="operatorName" class="form-control shadow-none mb-3" disabled>
                                    
                                    <h5 class="fw-bold">Address</h5>
                                    <input type="text" name="opAddress" id="opAddress" required class="form-control shadow-none mb-3" disabled>                                    
                                    <h5 class="fw-bold">Contact Number</h5>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                        <input type="number" name="operatorTel" id="operatorTel_in" class="form-control shadow-none" disabled>
                                    </div>

                                    <h5 class="fw-bold">Email</h5>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                                        <input type="email" name="operatorEmail" id="operatorEmail_in" class="form-control shadow-none" disabled>
                                    </div>
                                    
                                    <h5 class="fw-bold">Experience (Years)</h5>
                                    <input type="text" name="jobAge" id="jobAge_in" required class="form-control shadow-none mb-3" disabled>
                                    
                                    <h5 class="fw-bold">Hourly Rate</h5>
                                    <input type="text" name="hRate" id="hRate_in" step="0.01" required class="form-control shadow-none mb-3" disabled>
                                </div>
                                <!-- Car Type Specialty -->
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Car Type Specialty</label>
                                    <div class="row">
                                        @php
                                            $U = [];
                                        @endphp
                                        @foreach($car as $option)
                                            @php
                                                $carType = $option['carType'];
                                            @endphp
                                            @if (!in_array($carType, $U))
                                                @php
                                                    $U[] = $carType;
                                                @endphp
                                                <div class="col-md-3 mb-1">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input shadow-none" type="checkbox" name="car" value="{{ $option['carID'] }}" disabled> <!-- added disabled attribute -->
                                                            {{ $option['carType'] }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Service Offered -->
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Service Offered</label>
                                    <div class="row">
                                        @php
                                            $U = [];
                                        @endphp
                                        @foreach($service as $option)
                                            @php
                                                $serviceType = $option['serviceType'];
                                            @endphp
                                            @if (!in_array($carType, $U))
                                                @php
                                                    $U[] = $serviceType;
                                                @endphp
                                                <div class="col-md-3 mb-1">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input shadow-none" type="checkbox" name="service" value="{{ $option['serviceID'] }}" disabled> <!-- added disabled attribute -->
                                                            {{ $option['serviceType'] }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Operator Description -->
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea class="form-control shadow-none" name="opDescription" rows="4" disabled></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="operatorID">
                        </div>
                    </div>
                    <!-- FOOTER -->
                    <div shadow-none class="modal-footer">
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>

let operatorAddForm = document.getElementById('operatorAddForm');
let operatorEditForm = document.getElementById('operatorEditForm');
let operatorViewForm = document.getElementById('operatorViewForm');

operatorAddForm.addEventListener('submit', function (event) {
    event.preventDefault();
    add_operator();
});

operatorEditForm.addEventListener('submit', function (event) {
    event.preventDefault();
    submit_editOperator();
});

function add_operator() 
{
    let formData = new FormData(operatorAddForm);
    formData.append('addOperator', '');

        let car = [];
        let carElements = operatorAddForm.elements['car'];
        for (let i = 0; i < carElements.length; i++) {
            if (carElements[i].checked) {
                car.push(carElements[i].value);
            }
        }
        formData.append('car', JSON.stringify(car));

        let service = [];
        let serviceElements = operatorAddForm.elements['service'];
        for (let i = 0; i < serviceElements.length; i++) {
            if (serviceElements[i].checked) {
                service.push(serviceElements[i].value);
            }
        }
        formData.append('service', JSON.stringify(service));

    $.ajax({
        url: '/add-operator',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        success: function (response) {
            var myModal = document.getElementById('add-operator');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            if (response === '1') {
                alert('error', 'Invalid file format!');
            } else if (response === '10') {
                alert('error', 'Image should be less than 1MB');
            } else if (response === '100') {
                alert('error', 'Upload failed');
            } else {
                alert('success', 'New operator added successfully');
                operatorAddForm.reset();
                getAll_operators();
            }
        },
        error: function (error) {
            console.error('Error:', error);
        }
    });
}

function toggle_status(id, V) {
    let formData = new FormData();
    formData.append('toggleStatus', id);
    formData.append('val', V);

    $.ajax({
        url: '/toggle-active',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (data) {
            console.log('Server Response:', data);

            if (data.success) {
                alert('success', 'Status toggled!');
                getAll_operators();
            } else {
                alert('error', 'Failed to toggle status!');
            }
        },
        error: function (error) {
            console.error('Error:', error);
            alert('error', 'Failed to communicate with the server!');
        }
    });
}

function getAll_operators() {
    let formData = new FormData();

    $.ajax({
        url: '/get-operators',
        type: 'GET',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (data) {
            $('#operator-data').html(data);
        },
        error: function (error) {
            console.error('Error:', error);
            alert('error', 'Failed to fetch operators!');
        }
    });
}

function submit_editOperator() 
{
    let formData = new FormData(operatorEditForm);
    formData.append('editOperator', '');

    let car = [];
    Array.from(operatorEditForm.elements['car']).forEach(element => {
        if (element.checked) {
            car.push(element.value);
        }
    });
    formData.append('car', JSON.stringify(car));

    let service = [];
    Array.from(operatorEditForm.elements['service']).forEach(element => {
        if (element.checked) {
            service.push(element.value);
        }
    });
    formData.append('service', JSON.stringify(service));

    $.ajax({
        url: '/submit-edit',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            var myModal = document.getElementById('edit-operator');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            console.log(response);
            console.log(formData);

            if (response === '1') {
                alert('error', 'Invalid file format!');
            } else if (response === '10') {
                alert('error', 'Image should be less than 1MB');
            } else if (response === '100') {
                alert('error', 'Upload failed');
            } else {
                alert('success', 'Changes saved!');
                operatorEditForm.reset();
                getAll_operators();
            }
        },
        error: function (error) {
            console.error('Error:', error);
            alert('error', 'Error making the request to the server.');
        }
    });
}

function edit_details(id) 
{
    $.ajax({
        url: '/edit-details',
        type: 'GET',
        data: { getOperator: id },
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            data = JSON.parse(data);

            operatorEditForm.elements['operatorName'].value = data.operatorData.operatorName;
            operatorEditForm.elements['opAddress'].value = data.operatorData.opAddress;
            operatorEditForm.elements['operatorTel'].value = data.operatorData.operatorTel;
            operatorEditForm.elements['operatorEmail'].value = data.operatorData.operatorEmail;
            operatorEditForm.elements['jobAge'].value = data.operatorData.jobAge;
            operatorEditForm.elements['hRate'].value = data.operatorData.hRate;
            operatorEditForm.elements['opDescription'].value = data.operatorData.opDescription;
            operatorEditForm.elements['operatorID'].value = data.operatorData.operatorID;

            let operatorImg = document.getElementById('operator-image-edit');
            if (operatorImg) {
                if (data.operatorData.operatorImg) {
                    operatorImg.src = '{{ asset('storage/images/operators') }}/' + data.operatorData.operatorImg;
                    operatorImg.alt = 'Operator Image';
                } else {
                    operatorImg.src = '{{ asset('image/operator/placeholder.png') }}';
                }
            }

            Array.from(operatorEditForm.elements['car']).forEach(element => {
                if (data.car.includes(Number(element.value))) {
                    element.checked = true;
                }
            });

            Array.from(operatorEditForm.elements['service']).forEach(element => {
                if (data.service.includes(Number(element.value))) {
                    element.checked = true;
                }
            });
        },
        error: function (error) {
            console.error('Error:', error);
        }
    });
}

function view_details(id) 
{
    $.ajax({
        url: '/view-details',
        type: 'GET',
        data: { getOperator: id },
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            data = JSON.parse(data);

            operatorViewForm.elements['operatorName'].value = data.operatorData.operatorName;
            operatorViewForm.elements['opAddress'].value = data.operatorData.opAddress;
            operatorViewForm.elements['operatorTel'].value = data.operatorData.operatorTel;
            operatorViewForm.elements['operatorEmail'].value = data.operatorData.operatorEmail;
            operatorViewForm.elements['jobAge'].value = data.operatorData.jobAge;
            operatorViewForm.elements['hRate'].value = data.operatorData.hRate;
            operatorViewForm.elements['opDescription'].value = data.operatorData.opDescription;
            operatorViewForm.elements['operatorID'].value = data.operatorData.operatorID;

            let operatorImg = document.getElementById('operator-image-view');
            if (operatorImg) {
                if (data.operatorData.operatorImg) {
                    operatorImg.src = '{{ asset('storage/images/operators') }}/' + data.operatorData.operatorImg;
                    operatorImg.alt = 'Operator Image';
                } else {
                    operatorImg.src = '{{ asset('image/operator/placeholder.png') }}';
                }
            }

            Array.from(operatorViewForm.elements['car']).forEach(element => {
                if (data.car.includes(Number(element.value))) {
                    element.checked = true;
                }
            });

            Array.from(operatorViewForm.elements['service']).forEach(element => {
                if (data.service.includes(Number(element.value))) {
                    element.checked = true;
                }
            });
        },
        error: function (error) {
            console.error('Error:', error);
        }
    });
}

function delete_operator(operatorID) 
{
    $.ajax({
        url: '/delete-operator',
        type: 'POST',
        data: { deleteOperator: 1, operator_id: operatorID },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
        try {
            if (response.success) {
                alert('success', 'Operator has been removed!');
                operatorEditForm.reset();
                getAll_operators();
            } else if (response.error) {
                alert('error', response.message);
            } else {
                alert('error', 'Server down!');
                console.log('Response object:', response);
            }
        } catch (error) {
            console.error('Error:', error);
            console.log('Response object:', response);
            alert('error', 'Failed to handle server response!');
        }
    },
        error: function (error) {
            console.error('Error:', error);
        }
    });
}

window.onload = function() {
    getAll_operators();
}

</script>
@endpush