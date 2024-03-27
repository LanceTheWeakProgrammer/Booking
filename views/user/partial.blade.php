<!-- Technicians -->

<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Our Technicians</h2>

<div class="container-fluid col-lg-9 col-md-12">
    <div class="row">
         @foreach($operators as $key => $operator)
            <div class="col-lg-4 offset-lg-0 col-md-8 offset-md-2 p-4">
                <div class="card mb-4 border-0 shadow" style="width: 100%; height: 100%; ">
                    <div style="height: 350px; overflow: hidden;" class="border-4 rounded-border">
                        <img src="{{ asset('storage/images/operators/' . $operator->operatorImg) }}" class="img-fluid" alt="Image" style="width: 100%; height: 450px; object-fit: cover;">
                    </div>
                    <div class="card-body">
                        <h4 class="mb-5">{{ $operator->operatorName }}</h4>

                        <div class="features mb-4">
                            <h6 class="mb-1">Services Offered</h6>
                            @foreach($operator->services as $service)
                                <span class="badge rounded-pill text-bg-light text-wrap lh-base">
                                    {{ $service->serviceType }}
                                </span>
                            @endforeach
                        </div>

                        <div class="facilities mb-4">
                            <h6 class="mb-1">Car Type Specialty</h6>
                            @foreach($operator->cars as $car)
                                <span class="badge rounded-pill text-bg-light text-wrap lh-base me-2 mt-1">
                                    {{ $car->carType }}
                                </span>
                            @endforeach
                        </div>

                        <div class="rating mb-4">
                          <h6 class="mb-1">Rating</h6>
                          <span class="badge round-pill bg-light">
                              @php
                                  $rating = $operator->averageRating;
                                  $wholeStars = floor($rating);
                                  $halfStar = ceil($rating - $wholeStars);
                              @endphp

                              @for ($i = 0; $i < $wholeStars; $i++)
                                  <i class="bi bi-star-fill text-warning"></i>
                              @endfor

                              @if ($halfStar)
                                  <i class="bi bi-star-half text-warning"></i>
                                  @php $wholeStars++; @endphp
                              @endif

                              @for ($i = $wholeStars; $i < 5; $i++)
                                  <i class="bi bi-star text-warning"></i>
                              @endfor
                          </span>
                      </div>

                        <div class="d-flex justify-content-evenly mb-2">
                            <a href="{{ Auth::check() ? url('booking_details/' . $operator->operatorID) : url('login') }}" 
                                class="btn btn-sm text-white custom-bg shadow-none" 
                                    @if(!Auth::check()) onclick="event.preventDefault(); window.location.href='{{ url('login') }}';" @endif>
                                    Book Now
                                </a>
                            <a href="{{ url('operator_details/' . $operator->operatorID) }}" class="btn btn-sm btn-outline-dark shadow-none">More details</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="col-lg-12 text-center mt-5">
        <a href="{{ url('operator') }}" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More technicians>>></a>
    </div>
</div>

<!-- Services -->

<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Our Services</h2>

<div class="container">
    <div class="row justify-content-evenly px-lg-0 px-md-3 px-5">
        @foreach($services->take(5) as $service)
            <div class="col-lg-2 col-md-4 text-center bg-white rounded shadow py-4 my-3 mx-3">
                <img src="{{ asset('storage/images/service/' . $service->serviceIcon) }}" width="80px">
                <h5 class="mt-3">{{ $service->serviceType }}</h5>
            </div>
        @endforeach
        <div class="col-lg-12 text-center mt-5">
            <a href="{{ url('service') }}" class="btn btn-sm btn-outline-dark rounded-o fw-bold shadow-none">More Services>>></a>
        </div>
    </div>
</div>

<!-- Testimonials -->

<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Testimonials</h2>

<div class="container mt-5">
  <div class="swiper swiper-testimonials" style="height: 350px;">
    <div class="swiper-wrapper mb-5">
      @foreach ($ratings as $rating)
      <div class="swiper-slide bg-white p-4" style="height: 100%;">
        <div class="profile d-flex align-items-center mb-3">
          <img src="{{ asset('/images/placeholder.jpg') }}" width="30px">
          <h6 class="ms-3">{{ $rating->name }}</h6>
        </div>
        <p>{{ $rating->comment }}</p>
        <div class="rating">
          @for ($i = 0; $i < $rating->webRating; $i++)
          <i class="bi bi-star-fill text-warning"></i>
          @endfor
        </div>
      </div>
      @endforeach
    </div>
    <div class="swiper-pagination"></div>  
  </div>
</div>


<!-- Contact us -->

<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Reach Us</h2>

<div class="container">
  <div class="row">
    <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white">
      <iframe class="w-100 rounded mb-4" height="320px" src="{{ $contactInfo->iframe }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <div class="col-lg-4 col-md-4">
      <div class="bg-white p-4 rounded mb-4"> 
      <h5 class="mt-4">Call Us</h5>
        <a href="tel:{{ $contactInfo->tel1 }}" class="d-inline-block text-decoration-none text-dark mb-2">
            <i class="bi bi-telephone-fill"></i> {{ $contactInfo->tel1 }}
        </a>
        <br>
        <a href="tel:{{ $contactInfo->tel2 }}" class="d-inline-block text-decoration-none text-dark mb-2">
            <i class="bi bi-telephone-fill"></i> {{ $contactInfo->tel2 }}
        </a>
        <br>
      </div>

      <div class="bg-white p-4 rounded mb-4"> 
        <h5>Follow  Us</h5>
        <a href="{{ $contactInfo->twt }}" class="d-inline-block mb-3">
          <span class="badge bg-light text-dark fs-6 p-2">
            <i class="bi bi-twitter me-1"></i>  Twitter
          </span>
        </a>
        <br>
        <a href="{{ $contactInfo->fb }}" class="d-inline-block mb-3">
          <span class="badge bg-light text-dark fs-6 p-2">
            <i class="bi bi-facebook me-1"></i>  Facebook
          </span>
        </a>
        <br>
        <a href="{{ $contactInfo->ig }}" class="d-inline-block mb-3">
          <span class="badge bg-light text-dark fs-6 p-2">
            <i class="bi bi-instagram me-1"></i>  Instagram
          </span>
        </a>
      </div>
    </div>
  </div>
  <div class="col-lg-12 text-center mt-3">
        <a href="{{ url('about') }}" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More about our team>>></a>
  </div>
</div>


