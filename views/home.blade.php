@extends('user.app')

@section('title', 'Starlight | Home')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

@section('header')

@endsection

@section('content')

<!-- Carousel -->
<div class="container-fluid px-lg-4 mt-4">
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow mb-sm-4" style="max-width: 1000px; height: 1000px; overflow: hidden; border-radius: 0; border: none;">
                <div class="card-body">
                    <div class="swiper swiper-container" style="height: 900px;">
                        <div class="swiper-wrapper">
                            @foreach ($carouselImages as $carousel)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/images/carousel/' . $carousel->cPicture) }}" class="img-fluid" style="width: calc(100% - 4px); height: calc(100% - 4px); object-fit: cover;" />
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            @php
                $carouselCount = 0;
            @endphp
            @foreach ($carouselImages as $carousel)
                @if ($carouselCount < 4)
                    <div class="card shadow border-0 mb-4" style="width: 100%;">
                        <div class="card-body p-3" style="height: 230px;">
                            <img src="{{ asset('storage/images/carousel/' . $carousel->cPicture) }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;" />
                        </div>
                    </div>
                    @php
                        $carouselCount++;
                    @endphp
                @else
                    @break
                @endif
            @endforeach
        </div>
    </div>
</div>

@include('user.partial')
@endsection

@section('footer')

@endsection

@push('scripts')
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script>
      document.addEventListener("DOMContentLoaded", function () {
          var swiperMain = new Swiper(".swiper-container", {
              spaceBetween: 30,
              effect: "fade",
              loop: true,
              autoplay: {
                  delay: 2000,
                  disableOnInteraction: false,
              },
              pagination: {
                  el: ".swiper-pagination",
                  clickable: false,
                  renderBullet: function (index, className) {
                      return '<span class="' + className + '"></span>';
                  },
              },
          });

            var swiperTestimonials = new Swiper(".swiper-testimonials", {
                effect: "coverflow",
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: "auto",
                loop: true,
                coverflowEffect: {
                    rotate: 50,
                    stretch: 0,
                    depth: 100,
                    modifier: 1,
                    slideShadows: false,
                },
                speed: 1550, 
                autoplay: {
                    delay: 0, 
                    disableOnInteraction: false, 
                },
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
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                }
            });
      });
  </script>
@endpush
