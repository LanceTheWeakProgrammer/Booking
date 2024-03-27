@extends('admin.app')

@section('title', 'Admin Panel')

@section('header')

@endsection

@section('content')
<!-- CAROUSEL -->
<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto p-3 overflow-hidden">
            <h3 class="mb-4">Assets</h3>
            <div class="card bg-light border-0 shadow-sm mb-2">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="card-title m-0">Carousel</h5>
                        <button type="button" class="btn btn-secondary shadow-none btn-sm ms-auto" data-bs-toggle="modal" data-bs-target="#carousel-s">
                            <i class="bi bi-plus-square"></i> Add
                        </button>
                    </div>
                    <div class="row" id="carousel-data">
                        <!-- Your content goes here -->
                    </div>
                </div>   
            </div>
        </div>
    </div>
</div>

<!-- ADD CAROUSEL -->
<div class="modal fade" id="carousel-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="CarouselSettForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Add Image</h2>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Image</label>
                        <input type="file" name="cPicture" id="cPicture_in" accept="[.jepg, .png, .webp, .jpg]" required class="form-control shadow-none">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="cPicture.value=''" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
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
    let CarouselSettForm = document.getElementById('CarouselSettForm');
    let cPicture_in = document.getElementById('cPicture_in');

    CarouselSettForm.addEventListener('submit', function (event) {
        event.preventDefault();
        addImage();
    });

    function addImage() {
        let formData = new FormData();
        formData.append('cPicture', cPicture_in.files[0]);
        formData.append('addImage', '');

        $.ajax({
            type: 'POST',
            url: '/add-image', 
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                var myModal = document.getElementById('carousel-s');
                var modal = new bootstrap.Modal(myModal);
                modal.hide();

                console.log(response);

                if (response === '1') {
                    alert('error', 'Invalid file format: only JPEG or PNG are allowed');
                } else if (response === '10') {
                    alert('error', 'Image should be less than 2MB');
                } else if (response === '100') {
                    alert('error', 'Upload failed');
                } else {
                    alert('success', 'New image added successfully');
                    cPicture_in.value = '';
                    getCarousel();
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }

    function getCarousel() {
        $.ajax({
            type: 'GET',
            url: '/get-carousel',
            data: 'getCarousel',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.length > 0) {
                    let htmlContent = '';
                    response.forEach(row => {
                        htmlContent += `
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card" style="max-width: 380px; height: 200px;">
                                    <img src="/storage/images/carousel/${row.cPicture}" class="card-img" style="width: 100%; height: 100%; object-fit: cover;">
                                    <div class="card-img-overlay text-end">
                                        <button type="button" onclick="removeCarousel(${row.entryID})" class="btn btn-secondary btn-sm shadow-none mb-2">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>`;
                    });
                    document.getElementById('carousel-data').innerHTML = htmlContent;
                } else {
                    document.getElementById('carousel-data').innerHTML = '<div class="col-md-12"><p>No carousel images found.</p></div>';
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }

    function removeCarousel(val) {
        $.ajax({
            type: 'POST',
            url: '/remove-carousel',
            data: 'removeImage=' + val,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert('success', 'Image removed!');
                    getCarousel();
                } else {
                    alert('error', response.error || 'Server error!');
                }
            },
            error: function(error) {
                console.error('Error:', error.responseText || 'Server error');
            }
        });
    }

    window.onload = function () {
        getCarousel();
    };
</script>
@endpush
  