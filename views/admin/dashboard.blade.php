@extends('admin.app')

@section('title', 'Admin Panel')

@section('header')

@endsection

@section('content')
<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto overflow-hidden p-3">
            <h3 class="mb-4">Dashboard</h3>

            <div class="row">
                <div class="col-md-8">
                <div class="row">
                        <!-- Total Registered Users -->
                        <div class="col-md-6">
                            <div class="card border-2 shadow border-primary text-primary mb-4">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Total Registered Users</h5>
                                        <p class="card-text display-4">{{ $totalUsers }}</p>
                                    </div>
                                    <i class="display-3 bi bi-people"></i>
                                </div>
                            </div>
                        </div>
                        <!-- Total Bookings) -->
                        <div class="col-md-6">
                            <div class="card border-2 shadow border-primary text-primary mb-4">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Total Bookings</h5>
                                        <p class="card-text display-4">{{ $totalBookings }}</p>
                                    </div>
                                    <i class="display-3 bi bi-calendar"></i>
                                </div>
                            </div>
                        </div>
                        <!-- Total Operators) -->
                        <div class="col-md-6">
                            <div class="card border-2 shadow border-primary text-primary mb-4">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Total Technicians</h5>
                                        <p class="card-text display-4">{{ $totalOperators }}</p>
                                    </div>
                                    <i class="display-3 bi bi-tools"></i>
                                </div>
                            </div>
                        </div>
                        <!-- Revenue -->
                        <div class="col-md-6">
                            <div class="card border-2 shadow border-primary text-primary mb-4">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title">Revenue</h5>
                                        <p class="card-text display-4">{{ $totalSales }}</p>
                                    </div>
                                    <i class="display-3 bi bi-currency-dollar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Annual Bookings Graph -->
                    <div class="card border-2 shadow border-info text-info">
                        <div class="card-body">
                            <h5 class="card-title">Annual Bookings</h5>
                            <div class="row">
                                <div class="col">
                                    <canvas id="annualBookingsChart" width="400" height="280"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Clock -->    
                    <div class="card border-2 shadow border-dark text-dark mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Current Time</h5>
                            <p class="card-text display-4 text-center" id="clock">Loading...</p>
                            <p class="card-text text-end small mb-0" id="date">Loading...</p>
                        </div>
                    </div>
                    <!-- Graphs -->
                    <div class="card border-2 shadow border-dark">
                        <div class="card-body">
                            <h5 class="card-title">Rating Statistics</h5>
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <p class="card-text display-4 text-warning">{{ number_format($avgRating, 1) }}</p>
                                </div>
                                <div class="col">
                                    <i class="bi bi-star-fill display-6 ms-0 text-warning"></i>
                                </div>
                            </div>
                            <canvas id="ratingChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                    <div class="card border-2 shadow border-info text-info mt-4">
                        <div class="card-body">
                            <canvas id="comparisonChart" width="200" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>   
    function updateClock() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds();
        var timeString = hours.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
        document.getElementById('clock').textContent = timeString;

        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        var dateString = now.toLocaleDateString('en-US', options);
        document.getElementById('date').textContent = dateString;
    }

    setInterval(updateClock, 1000);

    var totalUsers = {{ $totalUsers }};
    var totalBookings = {{ $totalBookings }};

    var comparisonChart = new Chart(document.getElementById('comparisonChart'), {
        type: 'bar',
        data: {
            labels: ['Users', 'Bookings'],
            datasets: [{
                label: 'Users',
                data: [totalUsers, 0, 0, 0],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(0, 0, 0, 0)',
                    'rgba(0, 0, 0, 0)',
                    'rgba(0, 0, 0, 0)',
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(0, 0, 0, 0)',
                    'rgba(0, 0, 0, 0)',
                    'rgba(0, 0, 0, 0)',
                ],
                borderWidth: 1
            }, {
                label: 'Bookings',
                data: [0, totalBookings, 0, 0],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(0, 0, 0, 0)',
                    'rgba(0, 0, 0, 0)',
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(0, 0, 0, 0)',
                    'rgba(0, 0, 0, 0)',
                ],
                borderWidth: 1
            },]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var monthlyBookingsData = {
        labels: {!! json_encode($monthlyBookingsData['months']) !!},
        datasets: [
            @foreach($monthlyBookingsData['years'] as $index => $year)
                {
                    label: '{{$year}}',
                    data: {!! json_encode($monthlyBookingsData['bookings'][$index]) !!},
                    fill: false,
                    borderColor: getRandomColor(),
                    borderWidth: 2,
                    lineTension: 0.1
                },
            @endforeach
        ]
    };

    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    var annualBookingsChart = new Chart(document.getElementById('annualBookingsChart'), {
        type: 'line',
        data: monthlyBookingsData,
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var ratingChart = new Chart(document.getElementById('ratingChart'), {
        type: 'bar',
        data: {
            labels: ['5', '4', '3', '2', '1'],
            datasets: [{
                label: 'Rating Counts',
                data: [
                    {{ $ratingCounts[5] ?? 0 }},
                    {{ $ratingCounts[4] ?? 0 }},
                    {{ $ratingCounts[3] ?? 0 }},
                    {{ $ratingCounts[2] ?? 0 }},
                    {{ $ratingCounts[1] ?? 0 }}
                ],
                backgroundColor: [
                    'rgba(255, 255, 0, 0.6)',
                    'rgba(255, 255, 0, 0.6)',
                    'rgba(255, 255, 0, 0.6)',
                    'rgba(255, 255, 0, 0.6)',
                    'rgba(255, 255, 0, 0.6)' 
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1,
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
        }
    });

</script>
@endpush
