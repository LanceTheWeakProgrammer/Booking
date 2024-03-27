<div class="container-fluid p-0">
    <div class="row">
        <div class="col-md-6 mb-3 form-group">
            <div class="text-center" id="operatorImageContainer">
                <h3 class="fw-bold p-1 text-center">Profile</h3>
                <img src="{{ asset('storage/' . $user->picture) }}" alt="User Picture" class="img-fluid border rounded border-3 mx-auto d-block" style="width: 250px; height: 250px; object-fit: cover;">
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <h5>Name:</h5>
            <input type="text" class="form-control mb-3" value="{{ $user->firstName }} {{ $user->lastName }}" disabled>
            <h5>Email:</h5>
            <input type="email" class="form-control mb-3" value="{{ $user->email }}" disabled>
            <h5>Contact Number:</h5>
            <input type="text" class="form-control mb-3" value="{{ $user->contactNumber }}" disabled>
            <h5>Gender:</h5>
            <input type="text" class="form-control" value="{{ $user->gender }}" disabled>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h5>Address:</h5>
            <input type="text" class="form-control mb-3" value="{{ $user->address }}" disabled>
            <h5>Zipcode:</h5>
            <input type="text" class="form-control mb-3" value="{{ $user->zipcode }}" disabled>
        </div>
        <div class="col-md-6">
            <h5>Birthdate:</h5>
            <input type="text" class="form-control mb-3" value="{{ \Carbon\Carbon::parse($user->birthdate)->format('F d, Y') }}" disabled>
            <h5>Account Created:</h5>
            <input type="text" class="form-control mb-3" value="{{ $user->datentime->format('F d, Y g:iA') }}" disabled>
        </div>
    </div>
</div>

