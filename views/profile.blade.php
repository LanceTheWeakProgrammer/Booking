@extends('user.app')

@section('title', 'Starlight | Profile')

@section('header')

@endsection

@section('content')
<div class="container-fluid col-11">
    <div class="row align-items-center justify-content-center overflow-hidden p-4">
        <div class="col-md-12">

        @if($user->flag === 'Warning')
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i> 
                Attention: This user has been warned due to violating our community guidelines. Further violations may result in suspension or banning of the account. Please review our terms of service for more information.
            </div>
        @endif

        @if($user->flag === 'Suspended')
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-2"></i> 
                This user has been suspended and cannot make bookings at the moment. Please contact support for further assistance.
            </div>
        @endif

        <!-- Display User Information -->
        <div class="card border-0">
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
                <p class="text-lg mb-0 h-font fw-bold">User Information</p>
                <div>
                    <button id="editModeButton" class="btn custom-bg text-white"><i class="bi bi-pencil-fill me-2"></i>Edit</button>
                    <div id="editModeButtons" style="display: none;">
                        <button id="saveChangesButton" class="btn custom-bg text-white">Save Changes</button>
                        <button id="cancelButton" class="btn btn-secondary ms-lg-2">Cancel</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="profileForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-img text-center mb-4">
                                <label for="profilePicture">
                                    <img id="profilePicture" class="img-fluid border-0 shadow" alt="User Picture" style="height: 300px; width: 300px; object-fit: cover;">
                                    <input type="file" id="profilePictureInput" class="form-control form-control-sm border-0 mt-2 rounded-border" style="display: none;">
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-4 p-4">
                            <h4>Personal Information</h4>
                            <label for="firstName">First Name:</label>
                            <input type="text" id="firstName" class="form-control border-0 shadow mb-2 fs-5 bg-transparent" disabled>
                            <label for="lastName">Last Name:</label>
                            <input type="text" id="lastName" class="form-control border-0 shadow mb-2 fs-5 bg-transparent" disabled>
                            <label for="gender">Gender:</label>
                            <select id="gender" class="form-select border-0 shadow mb-2 fs-5 bg-transparent" disabled>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                            <label for="birthdate">Birthday:</label>
                            <input type="date" id="birthdate" class="form-control border-0 shadow mb-2 fs-5 bg-transparent" disabled>
                        </div>
                        <div class="col-md-12 col-lg-4 p-4">
                            <h4>Contact Information</h4>
                            <label for="email">Email:</label>
                            <input type="email" id="email" class="form-control border-0 shadow mb-2 fs-5 bg-transparent" disabled>
                            <label for="contactNumber">Contact Number:</label>
                            <input type="tel" id="contactNumber" class="form-control border-0 shadow mb-2 fs-5 bg-transparent" disabled>
                        </div>
                        <div class="col-md-12 col-lg-4 p-4">
                            <h4>Address</h4>
                            <label for="country">Country:</label>
                            <input type="text" id="country" class="form-control border-0 shadow mb-2 fs-5 bg-transparent" disabled>
                            <label for="province">Province:</label>
                            <input type="text" id="province" class="form-control border-0 shadow mb-2 fs-5 bg-transparent" disabled>
                            <label for="city">City:</label>
                            <input type="text" id="city" class="form-control border-0 shadow mb-2 fs-5 bg-transparent" disabled>
                            <label for="zipcode">Zipcode:</label>
                            <input type="text" id="zipcode" class="form-control border-0 shadow mb-2 fs-5 bg-transparent" disabled>
                        </div>
                    </div>
                </form>
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
    function getProfile() {
        $.ajax({
            url: '/profile-data',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $('#firstName').val(data.firstName);
                $('#lastName').val(data.lastName);
                $('#gender').val(data.gender);
                $('#birthdate').val(data.birthdate);
                $('#email').val(data.email);
                $('#contactNumber').val(data.contactNumber);
                $('#country').val(data.country);
                $('#province').val(data.province);
                $('#city').val(data.city);
                $('#zipcode').val(data.zipcode);

                if (data.picture) {
                    $('#profilePicture').attr('src', '{{ asset('storage/') }}/' + data.picture);
                } else {
                    $('#profilePicture').attr('src', '{{ asset('image/user/placeholder.png') }}');
                }
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    };

    function enterEditMode() {
        $('#profileForm input').prop('disabled', false);
        $('#profilePictureInput').show();
        $('#gender').prop('disabled', false);
        $('#editModeButtons').show();
        $('#editModeButton').hide();
    }

    function cancelEditMode() {
        $('#profileForm input').prop('disabled', true);
        $('#profilePictureInput').hide();
        $('#editModeButtons').hide();
        $('#gender').prop('disabled', true);
        $('#editModeButton').show();
        getProfile(); 
    }

    function saveChanges() {
        var formData = new FormData();
        formData.append('firstName', $('#firstName').val());
        formData.append('lastName', $('#lastName').val());
        formData.append('gender', $('#gender').val());
        formData.append('birthdate', $('#birthdate').val());
        formData.append('email', $('#email').val());
        formData.append('contactNumber', $('#contactNumber').val());
        formData.append('country', $('#country').val());
        formData.append('province', $('#province').val());
        formData.append('city', $('#city').val());
        formData.append('zipcode', $('#zipcode').val());
        formData.append('profile_picture', $('#profilePictureInput')[0].files[0]);

        $.ajax({
            url: '/update-profile',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log(response);
                alert('success', 'Changes saved!');
                getProfile();
                $('#profileForm input').prop('disabled', true);
                $('#gender').prop('disabled', true);
                $('#profilePictureInput').hide();
                $('#editModeButton').show();
                $('#editModeButtons').hide();
            },
            error: function (error) {
                console.error('Error:', error);
            }
        });
    }

    $(document).ready(function() {
        getProfile();
        $('#editModeButton').click(enterEditMode);
        $('#saveChangesButton').click(saveChanges);
        $('#cancelButton').click(cancelEditMode);
        $('#profilePictureInput').change(function () {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#profilePicture').attr('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        });
    });
</script>
@endpush
