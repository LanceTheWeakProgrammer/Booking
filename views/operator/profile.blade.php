@extends('operator.app')

@section('title', 'Technician Panel')

@section('header')

@endsection

@section('content')
<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-11 m-auto overflow-hidden">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="m-0">Profile</h4>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="card col-lg-12 col-md-12 px-2 bg-light rounded form border-5 border-secondary shadow-sm mb-4" id="operatorDetails">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-md-12 form-group" id="frontView">
                    <button class="btn custom-bg text-white flip-button" type="button" id="flipButton"><i class="fs-5 bi bi-person-fill"></i></button>
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <div class="text-center mt-5" id="operatorImageContainer">
                                <img src="{{ asset('storage/images/operators/' . $operator->operatorImg) }}" alt="" class="img-fluid border rounded border-3 mx-auto d-block" style="width: 300px; height: 300px; object-fit: cover;"> 
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-4 mb-3">
                            <h5 class="fw-bold">Complete Name</h5>
                            <input type="text" name="operatorName" class="form-control shadow-sm bg-transparent border-0 mb-3 fs-5" value="{{ $operator->operatorName }}" disabled>

                            <h5 class="fw-bold">Address</h5>
                            <input type="text" name="opAddress" required class="form-control shadow-sm bg-transparent border-0 mb-3 fs-5" value="{{ $operator->opAddress }}" disabled>

                            <h5 class="fw-bold">Contact Number</h5>
                            <div class="input-group mb-3">
                                <span class="input-group-text border-0 shadow-sm"><i class="bi bi-telephone-fill"></i></span>
                                <input type="number" name="operatorTel" class="form-control shadow-sm bg-transparent border-0 fs-5" value="{{ $operator->operatorTel }}" disabled>
                            </div>

                            <h5 class="fw-bold">Email</h5>
                            <div class="input-group mb-3">
                                <span class="input-group-text border-0 shadow-sm"><i class="bi bi-envelope-at"></i></span>
                                <input type="email" name="operatorEmail" required class="form-control shadow-sm bg-transparent border-0 fs-5" value="{{ $operator->operatorEmail }}" disabled>
                            </div>

                            <h5 class="fw-bold">Experience (Years)</h5>
                            <input type="text" name="jobAge" required class="form-control shadow-sm bg-transparent border-0 mb-3 fs-5" value="{{ $operator->jobAge }} years" disabled>
                        </div>

                        <div class="col-lg-3 col-md-12 mb-2">
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
                    </div>
                </div>

                <div class="col-md-12 mb-3 form-group d-none" id="backView">
                    <button class="btn custom-bg text-white flip-button" type="button" id="unflipButton"><i class="fs-5 bi bi-person"></i></button>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <span class="badge round-pill bg-white py-3 round">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $averageRating)
                                        <i class="bi bi-star-fill display-3 text-warning me-3"></i>
                                    @elseif ($i - $averageRating < 1)
                                        <i class="bi bi-star-half display-3 text-warning me-3"></i>
                                    @else
                                        <i class="bi bi-star display-3 text-warning me-3"></i>
                                    @endif
                                @endfor
                            </span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5 class="fw-bold mt-3">Description</h5>
                            <div class="position-relative">
                                <textarea class="form-control shadow-sm border-0 fs-6" name="opDescription" rows="12" disabled>{{ $operator->opDescription }}</textarea>
                                <button class="btn bg-transparent position-absolute top-0 end-0 mt-2 me-2 btn-sm border-0" type="button" id="editDescriptionButton">
                                    <i class="bi bi-pencil-fill text-secondary fs-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-11 m-auto overflow-hidden">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="m-0">Reviews</h4>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-10 m-auto overflow-hidden">
    <div class="card col-lg-12 col-md-12 px-2 bg-light rounded mb-3">
        <div class="card-body" style="max-height: 500px; overflow-y: auto;">
            @foreach ($ratings as $rating)
                <div class="card bg-light border-0 mb-3">
                    <div class="card-body">
                    <div class="d-flex align-items-center">
                        @if ($rating->user->picture)
                            <img src="{{ asset('storage/' . $rating->user->picture) }}" width="30px" height="30px" style="cover: fit;" class="me-2">
                        @else
                            <img src="{{ asset('/images/placeholder.jpg') }}" width="30px" class="me-2">
                        @endif
                        <h6 class="mt-2">{{ $rating->user->firstName }} {{ $rating->user->lastName }}</h6>
                    </div>          
                             @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $rating->rating)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @else
                                    <i class="bi bi-star text-warning"></i>
                                @endif
                            @endfor</p>
                        <p class="card-text">"<i> {{ $rating->review }} </i>"</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.getElementById('flipButton').addEventListener('click', function() {
        document.getElementById('frontView').classList.add('d-none');
        document.getElementById('backView').classList.remove('d-none');
        document.querySelectorAll('.flip-button').forEach(button => button.classList.add('flip'));
    });

    document.getElementById('unflipButton').addEventListener('click', function() {
        document.getElementById('backView').classList.add('d-none');
        document.getElementById('frontView').classList.remove('d-none');
        document.querySelectorAll('.flip-button').forEach(button => button.classList.remove('flip'));
    });

    $(document).ready(function() {
        $('#editDescriptionButton').click(function() {
            var editButton = $(this);
            var textarea = $('textarea[name="opDescription"]');
            var operatorId = '{{ $operator->id }}'; 

            if (textarea.prop('disabled')) {
                textarea.prop('disabled', false);
                editButton.html('<i class="bi bi-check-circle-fill text-secondary fs-4"></i>');
                editButton.on('click', saveDescription);
            } else {
                textarea.prop('disabled', true);
                editButton.html('<i class="bi bi-pencil-fill text-secondary fs-4"></i>');
                editButton.off('click', saveDescription);
            }

            function saveDescription() {
                var description = textarea.val();

                $.ajax({
                    url: '/update-description',
                    type: 'POST',
                    headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        operatorId: operatorId,
                        description: description
                    },
                    success: function(response) {
                        alert('success', 'Description saved successfully');
                        console.log('Description saved successfully');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error occurred while saving description:', error);
                    }
                });
            }
        });
    });
</script>

@endpush
