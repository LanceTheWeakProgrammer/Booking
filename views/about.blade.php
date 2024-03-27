@extends('user.app')

@section('title', 'Starlight | About')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

@section('header')

@endsection

@section('content')

  <div class="my-5 px-4">
      <h2 class="fw-bold h-font text-center">ABOUT US</h2>
      <div class="h-line bg-dark"></div>
      <p class="text-center mt-3">
        {{ $aboutData }}
      </p>
  </div>

  <div class="container-fluid col-11">
    <div class="row justify-content-between align-items-center">
      <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2" >
        <h3 class="mb-3">Lorem ipsum dolor sit.</h3>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elit. 
          Facilis, magni fugiat ex laboriosam a necessitatibus 
          labore?
        </p>
      </div>
      <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
        <img src="{{asset ('images\about\1.jpg')}}" class="w-100" >
      </div>
    </div>
  </div>

  <div class="container-fluid col-11 mt-5">
    <div class="row">
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="{{asset ('images\about\1.jpg')}}" width="70px">
          <h4 class="mt-3">{{ $totalOperators }} TECHNICIANS</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="{{asset ('images\about\1.jpg')}}" width="70px">
          <h4 class="mt-3">200+ CUSTOM</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="{{asset ('images\about\1.jpg')}}" width="70px">
          <h4 class="mt-3">150+ REVIEWS</h4>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-4 px-4">
        <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
          <img src="{{asset ('images\about\1.jpg')}}" width="70px">
          <h4 class="mt-3">100+ STAFF</h4>
        </div>
      </div>
    </div>
  </div>

  <h3 class="my-5 fw-bold h-font text-center">MANAGEMENT TEAM</h3>

  <div class="container-fluid col-11 px-4">
      <div class="swiper mySwiper" style="height: 620px;">
          <div class="swiper-wrapper mb-5">

              @forelse ($teamMembers as $teamMember)
                  <div class="swiper-slide bg-white text-center overflow-hidden rounded" style="height: 620px;">
                      <div style="height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                          <img src="{{ asset('storage/images/about/' . $teamMember->mPicture) }}" class="w-100" style="height: 100%; object-fit: cover;">
                          <h5 class="mt-2">{{ $teamMember->mName }}</h5>
                          <h6 class="mt-2">{{ $teamMember->mTitle }}</h6>
                      </div>
                  </div>
              @empty
                  <div class="swiper-slide bg-white text-center overflow-hidden rounded" style="height: 620px;">
                      <p>No team members found.</p>
                  </div>
              @endforelse

          </div>
          <div class="swiper-pagination"></div>
      </div>
  </div>


@endsection

@section('footer')

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".mySwiper", {
      spaceBetween: 40,
      pagination: {
        el: ".swiper-pagination",
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
        },
        640: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 3,
        },
        1024: {
          slidesPerView: 3,
        },
      }
    });
</script>
@endpush



