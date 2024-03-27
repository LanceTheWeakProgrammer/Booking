@extends('user.app')

@section('title', 'Thank you for choosing Starlight')

<style>

    .receipt-row {
        margin-bottom: 0.5rem;
    }

    .receipt-reminder {
        font-size: 0.8em;
    }

</style>

@section('content')

<div class="container mt-5">
    <div class="mb-4">
        <h2 class="text-center fw-bold">Thank you for choosing Starlight Automobile Services</h2>
    </div>

    <div class="col-md-12 mb-3 text-center">
        <button id="downloadReceipt" class="btn custom-bg text-white my-3">Download Receipt</button>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-5 position-relative"> 
            @if($booking)
            <div class="receipt card border-top border-4 pop shadow">
                <h5 class="card-title fw-bold text-center mt-5">STARLIGHT AUTOMOBILE SERVICES</h5>
                <p class="card-text text-center mt-n1" style="font-size: 0.72em;"> {{ $contactInfo->address }}</p>
                <p class="card-text text-center mt-n2" style="font-size: 0.72em;">{{ $contactInfo->tel1 }}</p>
                <p class="card-text text-center mt-n3" style="font-size: 0.72em;"> {{ $contactInfo->tel2 }}</p>
                <p class="card-text text-center mt-n2" style="font-size: 0.8em;">Date: {{ $booking->created_at->format('M d, Y') }}</p>
                <div class="card-body d-flex flex-column justify-content-between receipt-body">
                    <h5 class="card-title">Service:</h5>
                    <div class="d-flex justify-content-between receipt-row">
                        <h6 class="car-titme">{{ $booking->service_type }}</h6>
                        <span class="fw-bold">₱{{ number_format($booking->service_price, 2) }}</span>
                    </div>
                    <div class="r-line bg-dark mt-2"></div>
                    <div class="d-flex justify-content-between receipt-row">
                        <h6 class="card-title mt-4 text-start">TOTAL</h6>
                        <span class="fw-bold fs-3 mt-4" id="totalPrice">₱{{ number_format($booking->service_price, 2) }}</span>
                    </div>
                    <div class="h-line bg-dark mt-2"></div>
                    <div class="text-center mt-3 receipt-reminder">
                        <p>Reminder: Receipt is valid for 3 days from the date of issue. Please keep it safe.
                        Ensure all details are accurate. Any discrepancies should be reported within 24 hours of receipt issuance.
                        Receipts are non-transferable and should not be shared with unauthorized individuals.</p>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-info text-center" role="alert">
                No booking found. Please book a service to generate a receipt.
            </div>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <p class="text-center">If you encounter any issues with your booking, please <a href="{{ route('contact') }}" class="text-dark fw-bold">contact us</a>.</p>
    </div>
</div>

@endsection

@section('footer')

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script>
    function convertToImage() {
        html2canvas(document.querySelector('.receipt'), {
            scrollY: -window.scrollY,
            windowWidth: document.documentElement.offsetWidth,
            windowHeight: document.documentElement.offsetHeight,
            onrendered: function(canvas) {
                var downloadLink = document.createElement('a');
                downloadLink.href = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
                downloadLink.download = 'receipt.jpeg';
                document.body.appendChild(downloadLink);
                setTimeout(function() {
                    downloadLink.click();
                    document.body.removeChild(downloadLink);
                }, 100); 
            }
        });
    }

    document.getElementById('downloadReceipt').addEventListener('click', function() {
        convertToImage();
    });
</script>
@endpush
