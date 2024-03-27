@extends('admin.app')

@section('title', 'Admin Panel')

@section('header')
    
@endsection

@section('content')
<div class="container-fluid" id="main-content">
<div class="row">
    <div class="col-lg-10 ms-auto p-3 overflow-hidden">
        <h3 class="mb-4">Settings</h3>

        <!-- GENERAL -->
        <div class="card bg-light text-dark border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="card-title m-0">General Settings</h5>
                    <button type="button" class="btn btn-secondary shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#general-s">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                </div>
                @csrf
                <h6 class="card-subtitle mb-1 fw-bold">Site Title</h6>
                <p class="card-text" id="siteTitle"></p>
                <h6 class="card-subtitle mb-1 fw-bold">About us</h6>
                <p class="card-text" id="siteAbout"></p>
            </div>
        </div>
        <!-- GENERAL SETTINGS -->
        <div class="modal fade" id="general-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id=generalSettForm>
                 @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title">General Settings</h1>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Site Title</label>
                                <input type="text" name="siteTitle" id="siteTitle_in" required class="form-control shadow-none">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">About us</label>
                                <textarea class="form-control shadow-none" name="siteAbout" id="siteAbout_in" rows="6" required></textarea>
                            </div>
                        </div>

                        <div shadow-none class="modal-footer">
                            <button type="button" onclick="siteTitle.value = generalData.siteTitle, siteAbout.value = generalData.siteAbout" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn custom-bg text-white shadow-none" data-bs-dismiss="modal">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- SHUTDOWN -->
        <div class="card bg-light text-dark border-0 shadow-sm mb-4">
            <div class="card-body">
               @csrf
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="card-title m-0">Shutdown Website</h5>
                    <div class="form-check form-switch">
                        <form>
                            <input onchange="updateShutDown(this.value)" class="form-check-input" type="checkbox" role="switch" id="shutdown-toggle">
                        </form>
                    </div>
                </div>
                <p class="card-text">
                    During the shutdown mode, automobile repair booking services are temporarily unavailable on the website.
                </p>
            </div>
        </div>
        <!-- CONTACT -->
        <div class="card bg-light text-dark border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="card-title m-0">Contacts Settings</h5>
                    <button type="button" class="btn btn-secondary shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#contacts-s">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                </div>
                <div class="row">
                    @csrf
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <h6 class="card-subtitle mb-1 fw-bold">Address</h6>
                            <p class="card-text" id="address"></p>
                        </div>
                        <div class="mb-4">
                            <h6 class="card-subtitle mb-1 fw-bold">Google Map</h6>
                            <p class="card-text" id="gmap"></p>
                        </div>
                        <div class="mb-4">
                            <h6 class="card-subtitle mb-1 fw-bold">Phone Numbers</h6>
                            <p class="card-text mb-l">
                                <i class="bi bi-telephone-fill"></i>
                                <span id="tel1"></span>
                            </p>
                            <p class="card-text">
                                <i class="bi bi-telephone-fill"></i>
                                <span id="tel2"></span>
                            </p>
                        </div>
                        <div class="mb-4">
                            <h6 class="card-subtitle mb-1 fw-bold">Email</h6>
                            <p class="card-text" id="email"></p>
                        </div>
                    </div>    
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <h6 class="card-subtitle mb-1 fw-bold">Social Links</h6>
                                <p class="card-text mb-l">
                                    <i class="bi bi-twitter me-1"></i>
                                    <span id="twt"></span>
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-facebook me-1"></i>
                                    <span id="fb"></span>
                                </p>
                                <p class="card-text">
                                    <i class="bi bi-instagram me-1"></i>
                                    <span id="ig"></span>
                                </p>
                            </div>
                            <div class="mb-4">
                                <h6 class="card-subtitle mb-1 fw-bold">iFrame</h6>
                                    <iframe id="iframe" class="border p-2 w-100" loading="lazy"></iframe>
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
        <!-- CONTACT SETTINGS -->
        <div class="modal fade" id="contacts-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id=contactsSettForm>
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title">Contact Settings</h1>
                        </div>
                        <div class="modal-body">
                            <div class="container-fuild p-0">
                                <div class="row">
                                    <div class="col-md-6">
                                            <div class="mb-3">
                                            <label class="form-label fw-bold">Address</label>
                                            <input type="text" name="address" id="address_in" reqiured class="form-control shadow-none">
                                            </div>
                                            <div class="mb-3">
                                            <label class="form-label fw-bold">Google Map Link</label>
                                            <input type="text" name="gmap" id="gmap_in" reqiured class="form-control shadow-none">
                                            </div>
                                            <div class="mb-3">
                                            <label class="form-label fw-bold">Phone numbers</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text "><i class="bi bi-telephone-fill"></i></span>
                                                <input type="text" name="tel1" id="tel1_in" class="form-control shadow-none" required >
                                            </div>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                                                <input type="text" name="tel2" id="tel2_in" class="form-control shadow-none">
                                            </div>
                                            </div>
                                            <div class="mb-3">
                                            <label class="form-label fw-bold">Email</label>
                                            <input type="email" name="email" id="email_in" reqiured class="form-control shadow-none">
                                            </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Social Links</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text"><i class="bi bi-twitter"></i></span>
                                                <input type="text" name="twt" id="twt_in" class="form-control shadow-none" required >
                                            </div>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text "><i class="bi bi-facebook"></i></span>
                                                <input type="text" name="fb" id="fb_in" class="form-control shadow-none" required >
                                            </div>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text "><i class="bi bi-instagram"></i></span>
                                                <input type="text" name="ig" id="ig_in" class="form-control shadow-none" required >
                                            </div>
                                        </div>  
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">iFrame source</label>
                                            <input type="text" name="iframe" id="iframe_in" reqiured class="form-control shadow-none">
                                        </div>
                                    </div>  
                                </div>
                            </div>

                        </div>
                        <div shadow-none class="modal-footer">
                            <button type="button" onclick="contactsIn(contactsData)" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn custom-bg text-white shadow-none" data-bs-dismiss="modal">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- MANAGEMENT -->
        <div class="card bg-light text-dark border-0 shadow-sm mb-4">
            <div class="card-body">
                @csrf
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="card-title m-0">Management Team</h5>
                    <button type="button" class="btn btn-secondary shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#team-s">
                        <i class="bi bi-plus-square"></i> Add
                    </button>
                </div>
                <div class="row" id="team-data">

                </div>
            </div>
        </div>
        <!-- MANAGEMENT SETTINGS -->
        <div class="modal fade" id="team-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="teamSettForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title">Add a team member</h2>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" name="mName" id="mName_in" required class="form-control shadow-none">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Title</label>
                                <input type="text" name="mTitle" id="mTitle_in" required class="form-control shadow-none">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Picture</label>
                                <input type="file" name="mPicture" id="mPicture_in" accept="[.jepg, .png, .webp, .jpg]" required class="form-control shadow-none">
                            </div>
                        </div>

                        <div shadow-none class="modal-footer">
                            <button type="button" onclick="mName.value='', mTitle.value='', mPicture.value=''" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn custom-bg text-white shadow-none" data-bs-dismiss="modal">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>
</div>
@endsection

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    let generalData, contactsData;

    let generalSettForm = document.getElementById('generalSettForm');
    let siteTitle_in = document.getElementById('siteTitle_in');
    let siteAbout_in = document.getElementById('siteAbout_in');

    let contactsSettForm = document.getElementById('contactsSettForm');
    let teamSettForm = document.getElementById('teamSettForm');

    let mName_in = document.getElementById('mName_in');
    let mTitle_in = document.getElementById('mTitle_in');
    let mPicture_in = document.getElementById('mPicture_in');

    function get_general() {
        let siteTitle = document.getElementById('siteTitle');
        let siteAbout = document.getElementById('siteAbout');
        let SDToggle = document.getElementById('shutdown-toggle');

        let request = new XMLHttpRequest();
        request.open('GET', '/general', true);
        request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

        request.onload = function () {
            generalData = JSON.parse(this.responseText);

            siteTitle.innerText = generalData.siteTitle;
            siteAbout.innerText = generalData.siteAbout;
            siteTitle_in.value = generalData.siteTitle;
            siteAbout_in.value = generalData.siteAbout;

            SDToggle.checked = generalData.shutdown === 1;
            SDToggle.value = generalData.shutdown;
        };

        request.send();
    }

    teamSettForm.addEventListener('submit', function (event) {
        event.preventDefault();
        add_member();
    });

    generalSettForm.addEventListener('submit', function (event) {
        event.preventDefault();

        let siteTitleValue = siteTitle_in.value;
        let siteAboutValue = siteAbout_in.value;

        let data = new FormData();
        data.append('siteTitle', siteTitleValue);
        data.append('siteAbout', siteAboutValue);

        $.ajax({
            url: '/update-general',
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            success: function(data) {
                console.log(data); 
                if (data.success) {
                    alert('success', 'Changed successfully!');
                    get_general();
                } else {
                    alert('error', 'No changes made');
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });

    function updateShutDown(V) {
        $.ajax({
            url: '/shutdown',
            type: 'POST',
            data: `updateShutDown=${V}`,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            success: function(response) {
                try {
                    let message = (response.success === true && V == 1) ? 'Site is back online!' : 'Site has been shutdown!';
                    alert('success', message);
                    get_general();
                } catch (error) {
                    console.error('Error parsing JSON response:', error);
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }

    function contactsIn(data) {
        let contacts_id = ['address_in', 'gmap_in', 'tel1_in', 'tel2_in', 'email_in', 'twt_in', 'fb_in', 'ig_in', 'iframe_in'];

        for (let i = 0; i < contacts_id.length; i++) {
            document.getElementById(contacts_id[i]).value = data[i + 1];
        }
    }

    function get_contacts() {
        let contacts_id = ['address', 'gmap', 'tel1', 'tel2', 'email', 'twt', 'fb', 'ig'];
        let iframe = document.getElementById('iframe');

        $.ajax({
            url: '/contacts',
            type: 'GET',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                contactsData = Object.values(data);

                for (let i = 0; i < contacts_id.length; i++) {
                    document.getElementById(contacts_id[i]).innerText = contactsData[i + 1];
                }

                iframe.src = contactsData[9];
                contactsIn(contactsData);
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function update_contacts() {
        let indexes = ['address', 'gmap', 'tel1', 'tel2', 'email', 'twt', 'fb', 'ig', 'iframe'];
        let contacts_id = ['address_in', 'gmap_in', 'tel1_in', 'tel2_in', 'email_in', 'twt_in', 'fb_in', 'ig_in', 'iframe_in'];

        let data_s = new URLSearchParams();

        for (let i = 0; i < indexes.length; i++) {
            data_s.append(indexes[i], document.getElementById(contacts_id[i]).value);
        }

        $.ajax({
            url: '/update-contacts',
            type: 'POST',
            data: data_s.toString(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                var myModal = document.getElementById('contacts-s');
                var modal = new bootstrap.Modal(myModal);
                modal.hide();

                if (response.success == 1) {
                    alert('success', 'Changes saved');
                    get_contacts();
                } else {
                    alert('error', 'No changes made');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    contactsSettForm.addEventListener('submit', function (event) {
        event.preventDefault();
        update_contacts();
    });

    function add_member() {
        let formData = new FormData();
        formData.append('mName', mName_in.value);
        formData.append('mTitle', mTitle_in.value);
        formData.append('mPicture', mPicture_in.files[0]);
        formData.append('addMember', 'true');

        $.ajax({
            url: '/add-member',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            success: function(response) {

                var myModal = document.getElementById('team-s');
                var modal = new bootstrap.Modal(myModal);
                modal.hide();

                try {
                    let data;
                    if (typeof response === 'string') {
                        data = JSON.parse(response);
                    } else {
                        data = response;
                    }

                    if (data.error) {
                        alert('error', data.error);
                    } else if (data.image) {
                        document.getElementById('mPicture_in').src = data.image;
                        alert('success', 'New member added successfully');
                        get_members();
                        mName_in.value = '';
                        mTitle_in.value = '';
                        mPicture_in.value = '';
                    } else {
                        alert('error', 'Unexpected response from the server');
                    }
                } catch (error) {
                    console.error('Error parsing JSON response:', error);
                    alert('error', 'Error processing server response');
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }

    function get_members() {
        $.ajax({
            url: '/members',
            type: 'GET',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            success: function(response) {
                if (response.length > 0) {
                    let htmlContent = '';
                    response.forEach(teamMember => {
                        htmlContent += `
                            <div class="col-md-3">
                                <div class="card" style="max-width: 360px; height: 500px;">
                                    <img src="/storage/images/about/${teamMember.mPicture}" class="card-img" style="width: 100%; height: 100%; object-fit: cover;">
                                    <div class="card-img-overlay text-end">
                                        <button type="button" onclick="remove_member(${teamMember.teamID})" class="btn btn-secondary btn-sm shadow-none mb-2">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                    <p class="card-text text-white text-center position-absolute bottom-0 w-100 p-2">
                                        ${teamMember.mName} <br>
                                        ${teamMember.mTitle}
                                    </p>
                                </div>
                            </div>`;
                    });
                    document.getElementById('team-data').innerHTML = htmlContent;
                } else {
                    document.getElementById('team-data').innerHTML = '<div class="col-md-12"><p>No team members found.</p></div>';
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }

    function remove_member(val) {
        let data = 'removeMember=' + val;

        $.ajax({
            url: '/remove',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            success: function(response) {
                alert('success', 'Member removed!');
                get_members();
            },
            error: function(xhr, status, error) {
                alert('error', 'Server down!');
                console.log(xhr.responseText);
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        get_general();
        get_contacts();
        get_members();
    });
</script>

@endpush        





 
