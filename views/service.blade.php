@extends('user.app')

@section('title', 'Starlight | Services')

@section('header')

@endsection

@section('content')

<div class="my-5 px-4">
  <h2 class="fw-bold h-font text-center">OUR SERVICES</h2>
  <div class="h-line bg-dark"></div>
  <p class="text-center mt-3">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit.
    Exercitationem dolores excepturi corporis sapiente ex non,<br> 
    neque laudantium eius expedita omnis similique.
    Quos exercitationem soluta fuga eos dolores illum pariatur 
    quaerat?
  </p>
</div>

<div class="container-fluid col-11">
    <div class="row">
        @foreach($services as $service)
            <div class="col-lg-4 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop" style="height: 250px;"> 
                    <div class="d-flex align-items-center mb-2">
                        <img src="{{ asset('storage/images/service/' . $service->serviceIcon) }}" class="me-2" width="40px" style="height: 40px; width: auto;">
                        <h5 class="m-0 ms-3">{{ $service->serviceType }}</h5>
                    </div>
                    <p>{{ $service->sDescription }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
      
@endsection

@section('footer')

@endsection