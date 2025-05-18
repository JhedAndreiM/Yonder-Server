@extends('Front_layouts.org')

@section('head')
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            background-image: url("{{ asset('img/background.svg') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: top center;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Report Page</title>
    @vite('resources/css/orgReport.css')
@endsection
@section('maincontent')
    <div class="container">

        <div class="container-top"></div>
        <div class="container-bottom">
            <div class="container-bottom-left">
                <div class="left-container">
                    <div class="left-one">
                        <h3>PBEN Organization</h3>
                    </div>
                    <div class="left-two">
                        <hr>
                    </div>
                    <div class="left-three"><i class="fa-solid fa-basket-shopping left-icon"></i><a href="{{ route('organization.dashboard') }}">Products</a></div>
                    <div class="left-four"><i class="fa-solid fa-list-check left-icon"></i><a href="{{ route('order.page') }}">Orders</a></div>
                    <div class="left-five"><i class="fa-solid fa-star-half-stroke left-icon"></i><a href="{{ route('review.page') }}">Reviews</a></div>
                    <div class="left-six"><i class="fa-solid fa-money-check-dollar left-icon"></i><span class="currentPage">Dashboard</span></div>
                </div>
            </div>
            <div class="container-bottom-right">
                <div class="container-bottom-right-top">
                    <h1>Dashboard</h1>
                    <h4>Here’s your sales report!</h4>
                </div>
                <div class="container-bottom-right-bottom">
                    <div class="graph-container">
                        <div class="graph-container-items">
                        <!-- LEFT SIDE NG GRAPH CONTAINER-->
                        <div class="graph-container-left">
                            <!-- CONTAINER NG 4 na cards -->
                            <div class="graph-container-minicards">
                                <div class="minicards-top">
                                    <div class="cards-totalSales firstCard">
                                        <div class="firstCard-top">Total Sales</div>
                                        <div class="firstCard-middle">
                                            <h1>PHP </h1>
                                            <pre>{{ print_r($totalAmount, true) }}</pre>
                                        </div>
                                    </div>
                                    <div class="cards-totalSales thirdCard">
                                        <div class="firstCard-top">Wishlist Count</div>
                                        <div class="firstCard-middle">
                                            <h1>
                                                <pre>{{ print_r($totalWishlistItems, true) }}</pre>
                                            </h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="minicards-bottom">
                                    <div class="cards-totalSales secondCard">
                                        <div class="firstCard-top">Top Seller</div>
                                        <div class="firstCard-middle">
                                            <h1>
                                                <pre> 
                                            @if ($topSellerProduct)
                                            {{ $topSellerProduct->product_name }}
                                            @else
                                            No top-selling product yet.
                                            @endif
                                        </pre>
                                            </h1>
                                        </div>
                                    </div>
                                    <div class="cards-totalSales fourthCard">
                                        <div class="firstCard-top">Low Stock</div>
                                        <div class="firstCard-middle">
                                            <h1>
                                                <pre>{{ print_r($lowStockCount, true) }}</pre>
                                            </h1>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- Container ng Recents-->
                            <div class="graph-container-recents">
                                <div class="recents-content">
                                    <h3 class="recents-title">Recent Activity</h3>
                                    <div class="tableDiv">
                                        <table class="recents-table">
                                            <thead>
                                                <tr>
                                                    <th>Customer</th>
                                                    <th>Customer ID</th>
                                                    <th>Time</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cartData->take(5) as $item)
                                                <tr>
                                                    <td>{{ $item->buyer_name }}</td>
                                                    <td>#{{ $item->buyer_id }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->updated_at)->format('F j, Y \a\t g:i A') }}</td>
                                                    <td>PHP {{ $item->unit_price * $item->quantity }}</td>
                                                </tr>
                                                @endforeach
                                                
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- RIGHT SIDE NG GRAPH CONTAINER-->
                        <div class="graph-container-right">
                            <div class="graph-one">
                                <div class="graph-one-top">
                                    <h3>Status of Items</h3>
                                </div>
                                <div class="graph-one-bottom">
                                    <canvas id="statusChart" width="600" height="200"></canvas>
                                </div>

                            </div>
                            <div class="graph-two">
                                <div class="graph-two-top">
                                    <h3>Sales per month</h3>
                                </div>
                                <div class="graph-two-bottom">
                                    <canvas id="monthlySalesChart" width="500" height="200"></canvas>
                                </div>

                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const ctx = document.getElementById('statusChart').getContext('2d');

        const statusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Completed', 'Cancelled', 'In Cart', 'To Receive'],
                datasets: [{
                    label: 'Listings by Status',
                    data: {!! json_encode(array_values($statusCounts)) !!},
                    backgroundColor: [
                        '#f6c23e',
                        '#1cc88a',
                        '#e74a3b',
                        '#6c757d',
                        '#4e73df',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        const salesCtx = document.getElementById('monthlySalesChart').getContext('2d');

        const monthlySalesChart = new Chart(salesCtx, {
            type: 'bar',
            data: {
                labels: [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ],
                datasets: [{
                    label: 'Completed Sales ({{ now()->year }})',
                    data: {!! json_encode($monthlySalesData) !!},
                    backgroundColor: '#1cc88a',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    </script>
@endsection
