@extends('user.app')

@section('title', 'Automobile Repair Booking System - Contact Us')

@section('header')

@endsection

@section('content')

<div class="my-5 px-4">
  <h2 class="fw-bold h-font text-center">CONTACT US</h2>
  <div class="h-line bg-dark"></div>
  <p class="text-center mt-3">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit.
    Exercitationem dolores excepturi corporis sapiente ex non,<br> 
    neque laudantium eius expedita omnis similique.
    Quos exercitationem soluta fuga eos dolores illum pariatur 
    quaerat?
  </p>
</div>

<!-- Contact Info -->

<div class="container-fluid col-11">
    <div class="row">
        <div class="col-lg-6 col-md-6 mb-5 px-4">
            <div class="bg-white rounded shadow p-4">
                <iframe class="w-100 rounded mb-4" height="320px" src="{{ $contactInfo->iframe }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <h5>Address</h5>
                <a href="{{ $contactInfo->gmap }}" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2">
                    <i class="bi bi-geo-alt-fill"></i> {{ $contactInfo->address }}
                </a>
                <h5 class="mt-4">Call Us</h5>
                <a href="tel:{{ $contactInfo->tel1 }}" class="d-inline-block text-decoration-none text-dark mb-2">
                    <i class="bi bi-telephone-fill"></i> {{ $contactInfo->tel1 }}
                </a>
                <br>
                <a href="tel:{{ $contactInfo->tel2 }}" class="d-inline-block text-decoration-none text-dark mb-2">
                    <i class="bi bi-telephone-fill"></i> {{ $contactInfo->tel2 }}
                </a>
                <br>
                <h5 class="mt-4">Email</h5>
                <a href="mailto:{{ $contactInfo->email }}" class="d-inline-block mb-2 text-decoration-none text-dark">
                    <i class="bi bi-envelope-fill"></i> {{ $contactInfo->email }}
                </a>
                <h5 class="mt-4">Follow Us</h5>
                <a href="{{ $contactInfo->twt }}" class="d-inline-block text-dark fs-5 me-2">
                    <i class="bi bi-twitter me-1"></i>
                </a>
                <a href="{{ $contactInfo->fb }}" class="d-inline-block text-dark fs-5 me-2">
                    <i class="bi bi-facebook me-1"></i>
                </a>
                <a href="{{ $contactInfo->ig }}" class="d-inline-block text-dark fs-5 me-2">
                    <i class="bi bi-instagram me-1"></i>
                </a>
            </div>
        </div>

<!-- Send a message -->

<div class="col-lg-6 col-md-6 px-4">
    <div class="bg-white rounded shadow p-4">
        <form method="POST" action="{{ route('admin.user_queries.store') }}">
            @csrf
            <h5>
                Send a message
            </h5>
            <div class="mb-3">
                <label class="form-label" style="font-weight: 500;">Name</label>
                <input name="name" required type="text" class="form-control shadow-none">
            </div>
            <div class="mb-3">
                <label class="form-label" style="font-weight: 500;">Email</label>
                <input name="email" required type="email" class="form-control shadow-none">
            </div>
            <div class="mb-3">
                <label class="form-label" style="font-weight: 500;">Subject</label>
                <input name="subject" required type="text" class="form-control shadow-none">
            </div>
            <div class="mb-3">
                <label class="form-label" style="font-weight: 500;">Message</label>
                <textarea name="message" required class="form-control shadow-none" rows="5" style="resize: 50;"></textarea>
            </div>
            <button type="submit" name="send" class="btn text-white custom-bg mt-3">Send</button>
        </form>
    </div>
</div>

</div>  
</div>
 
@endsection

@section('footer')

@endsection

@push('scripts')
<script>
  @if(session('success'))
      alert('success', '{{ session('success') }}');
  @endif 
</script>
@endpush

