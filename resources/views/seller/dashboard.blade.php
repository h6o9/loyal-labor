@extends('seller.layouts.master')
@section('title')
    <title>{{ __('Dashboard') }}</title>
@endsection
@section('seller-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Dashbaord') }}</h1>
            </div>

            @php
                $monthsArray = [
                    1 => __('January'),
                    2 => __('February'),
                    3 => __('March'),
                    4 => __('April'),
                    5 => __('May'),
                    6 => __('June'),
                    7 => __('July'),
                    8 => __('August'),
                    9 => __('September'),
                    10 => __('October'),
                    11 => __('November'),
                    12 => __('December'),
                ];
            @endphp

            <div class="section-body">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-stats">
                                <div class="card-stats-title">{{ __('Order Statistics') }} -
                                    <div class="dropdown d-inline">
                                        <a class="font-weight-600 dropdown-toggle" id="orders-month"
                                            data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"
                                            rel="nofollow">{{ $monthsArray[request()->get('month', date('n'))] }}</a>
                                        <ul class="dropdown-menu dropdown-menu-sm"
                                            style="position: absolute; transform: translate3d(0px, 18px, 0px); top: 0px; left: 0px; will-change: transform;"
                                            x-placement="bottom-start">
                                            <li class="dropdown-title">{{ __('Select Month') }}</li>
                                            @foreach ($monthsArray as $month => $monthName)
                                                <li><a class="dropdown-item {{ request()->get('month', date('n')) == $month ? 'active' : '' }}"
                                                        href="{{ route('seller.dashboard', ['month' => $month, 'year' => request()->get('year', date('Y'))]) }}">{{ $monthName }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @if (count($cardData['availableYears']) > 0)
                                        <div class="dropdown d-inline">
                                            <a class="font-weight-600 dropdown-toggle" id="orders-year"
                                                data-bs-toggle="dropdown" href="#" role="button"
                                                aria-expanded="false"
                                                rel="nofollow">{{ request()->get('year', date('Y')) }}</a>
                                            <ul class="dropdown-menu dropdown-menu-sm"
                                                style="position: absolute; transform: translate3d(0px, 18px, 0px); top: 0px; left: 0px; will-change: transform;"
                                                x-placement="bottom-start">
                                                <li class="dropdown-title">{{ __('Select Month') }}</li>
                                                @foreach ($cardData['availableYears'] as $year)
                                                    <li><a class="dropdown-item {{ request()->get('year', date('Y')) == $year ? 'active' : '' }}"
                                                            href="{{ route('seller.dashboard', ['year' => $year, 'month' => request()->get('month', date('n'))]) }}">{{ $year }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-stats-items">
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">
                                            {{ $cardData['totalMonthlyPendingOrders'] ?? 0 }}
                                        </div>
                                        <div class="card-stats-item-label">{{ __('Pending') }}</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">
                                            {{ $cardData['totalMonthlyShippedOrders'] ?? 0 }}</div>
                                        <div class="card-stats-item-label">{{ __('Shipped') }}</div>
                                    </div>
                                    <div class="card-stats-item">
                                        <div class="card-stats-item-count">
                                            {{ $cardData['totalMonthlyDeliveredOrders'] ?? 0 }}</div>
                                        <div class="card-stats-item-label">{{ __('Delivered') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-archive"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Orders') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $cardData['totalMonthlyOrders'] ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-chart">
                                <canvas id="balance-chart" height="80"></canvas>
                            </div>
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Sales') }} ({{ $monthsArray[request()->get('month', date('n'))] }},
                                        {{ request()->get('year', date('Y')) }})</h4>
                                </div>
                                <div class="card-body">
                                    {{ currency($cardData['sellChartAmount'] ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card card-statistic-2">
                            <div class="card-chart">
                                <canvas id="sales-chart" height="80"></canvas>
                            </div>
                            <div class="card-icon shadow-primary bg-primary">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Product Sales') }} ({{ $monthsArray[request()->get('month', date('n'))] }},
                                        {{ request()->get('year', date('Y')) }})</h4>
                                </div>
                                <div class="card-body">
                                    {{ $cardData['salesChartCount'] ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="dashboard_card_seperator">
                            <h4>{{ __('Total') }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="far fa-shopping-basket"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Order') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalOrders->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-list-ul"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Product') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $products->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $totalEarning = 0;
                        $totalProductSale = 0;
                        foreach ($totalOrders as $key => $totalOrder) {
                            $orderProducts = $totalOrder->items;
                            foreach ($orderProducts as $key => $orderProduct) {
                                $price = $orderProduct->price * $orderProduct->qty + $orderProduct->tax_amount;
                                $totalEarning = $totalEarning + $price;
                                $totalProductSale = $totalProductSale + $orderProduct->qty;
                            }
                        }
                    @endphp
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="far fa-truck-container"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Product Sale') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalProductSale }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="far fa-check-circle"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Complete Order') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalOrders->where('order_status', 3)->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fad fa-spinner"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Pending Order') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalOrders->where('order_status', 0)->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="far fa-times"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Declined Order') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalOrders->where('order_status', 4)->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Product Review') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $reviews->count() ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fad fa-spinner"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Pending Review') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $reviews->where('status', 0)->count() ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="far fa-newspaper"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Earning') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ currency($totalEarning ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="far fa-newspaper"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Withdraw') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ currency($totalWithdraw) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fad fa-spinner"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Pending Withraw') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalPendingWithdraw }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="dashboard_card_seperator">
                            <h4>{{ __('Today') }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="far fa-shopping-basket"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Today Order') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $todayOrders->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="far fa-newspaper"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Today Earning') }}</h4>
                                </div>
                                <div class="card-body">
                                    @php
                                        $todayEarning = 0;
                                        $todayProductSale = 0;
                                        foreach ($todayOrders as $key => $todayOrder) {
                                            $orderProducts = $todayOrder->items;
                                            foreach ($orderProducts as $key => $orderProduct) {
                                                $price =
                                                    $orderProduct->price * $orderProduct->qty +
                                                    $orderProduct->tax_amount;
                                                $todayEarning = $todayEarning + $price;
                                                $todayProductSale = $todayProductSale + $orderProduct->qty;
                                            }
                                        }
                                    @endphp
                                    {{ currency($todayEarning) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fad fa-spinner"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Today Pending Order') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $todayOrders->where('order_status', 0)->count() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="far fa-truck-container"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Today Product Sale') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $todayProductSale }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fad fa-spinner"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Today Pending Earning') }}</h4>
                                </div>
                                <div class="card-body">
                                    @php
                                        $todayPendingEarning = 0;
                                        foreach (
                                            $todayOrders->where('order_status', 'pending')
                                            as $key => $todayOrder
                                        ) {
                                            $orderProducts = $todayOrder->items;
                                            foreach ($orderProducts as $key => $orderProduct) {
                                                $price =
                                                    $orderProduct->price * $orderProduct->qty +
                                                    $orderProduct->tax_amount;
                                                $todayPendingEarning = $todayPendingEarning + $price;
                                            }
                                        }
                                    @endphp

                                    {{ currency($todayPendingEarning) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="dashboard_card_seperator">
                            <h4>{{ __('This Month') }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="far fa-newspaper"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('This Month Earning') }}</h4>
                                </div>
                                <div class="card-body">
                                    @php
                                        $thisMonthEarning = 0;
                                        $thisMonthProductSale = 0;
                                        foreach ($monthlyOrders as $key => $monthlyOrder) {
                                            $orderProducts = $monthlyOrder->items;
                                            foreach ($orderProducts as $key => $orderProduct) {
                                                $price =
                                                    $orderProduct->price * $orderProduct->qty +
                                                    $orderProduct->tax_amount;
                                                $thisMonthEarning = $thisMonthEarning + $price;
                                                $thisMonthProductSale = $thisMonthProductSale + $orderProduct->qty;
                                            }
                                        }
                                    @endphp
                                    {{ currency($thisMonthEarning) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="far fa-truck-container"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('This Month Product Sale') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $thisMonthProductSale }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="dashboard_card_seperator">
                            <h4>{{ __('This Years') }}</h4>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="far fa-newspaper"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('This Year Earning') }}</h4>
                                </div>
                                <div class="card-body">
                                    @php
                                        $thisYearEarning = 0;
                                        $thisYearProductSale = 0;
                                        foreach ($yearlyOrders as $key => $yearlyOrder) {
                                            $orderProducts = $yearlyOrder->items;
                                            foreach ($orderProducts as $key => $orderProduct) {
                                                $price =
                                                    $orderProduct->price * $orderProduct->qty +
                                                    $orderProduct->tax_amount;
                                                $thisYearEarning = $thisYearEarning + $price;
                                                $thisYearProductSale = $thisYearProductSale + $orderProduct->qty;
                                            }
                                        }
                                    @endphp
                                    {{ currency($thisYearEarning ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="far fa-truck-container"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('This Year Product Sale') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $thisYearProductSale }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <h3>{{ __('Today New Order') }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="10%">{{ __('Customer') }}</th>
                                                <th width="10%">{{ __('Order Id') }}</th>
                                                <th width="15%">{{ __('Date') }}</th>
                                                <th width="10%">{{ __('Quantity') }}</th>
                                                <th width="10%">{{ __('Amount') }}</th>
                                                <th width="10%">{{ __('Order Status') }}</th>
                                                <th width="10%">{{ __('Payment') }}</th>
                                                <th width="5%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($todayOrders as $index => $order)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $order->user?->name ?? '' }}</td>
                                                    <td>#{{ $order->order_id }}</td>
                                                    <td>{{ formattedDate($order->created_at) }}</td>
                                                    <td>{{ $order->items->sum('qty') ?? 0 }}</td>
                                                    <td>{{ $order->payable_amount }} {{ $order->payable_currency }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $order->order_status->class() }}">{{ $order->order_status->getLabel() }}
                                                        </span>

                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-{{ $order->paymentDetails->payment_status->class() }}">
                                                            {{ $order->paymentDetails->payment_status->getLabel() }}
                                                        </span>
                                                    </td>

                                                    <td>

                                                        <a class="btn btn-primary btn-sm"
                                                            href="{{ route('seller.orders.show', $order->order_id) }}"><i
                                                                class="fa fa-eye" aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script src="{{ asset('website/js/chart.min.js') }}"></script>

    <script>
        var balance_chart = document.getElementById("balance-chart").getContext('2d');

        var balance_chart_bg_color = balance_chart.createLinearGradient(0, 0, 0, 70);
        balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
        balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

        var myChart = new Chart(balance_chart, {
            type: 'line',
            data: {
                labels: @json($cardData['sellChartLabels']),
                datasets: [{
                    label: '{{ __('Balance') }}',
                    data: @json($cardData['sellChartValues']),
                    backgroundColor: balance_chart_bg_color,
                    borderWidth: 3,
                    borderColor: 'rgba(63,82,227,1)',
                    pointBorderWidth: 0,
                    pointBorderColor: 'transparent',
                    pointRadius: 3,
                    pointBackgroundColor: 'transparent',
                    pointHoverBackgroundColor: 'rgba(63,82,227,1)',
                }]
            },
            options: {
                layout: {
                    padding: {
                        bottom: -1,
                        left: -1
                    }
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            beginAtZero: true,
                            display: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            drawBorder: false,
                            display: false,
                        },
                        ticks: {
                            display: false
                        }
                    }]
                },
            }
        });

        var sales_chart = document.getElementById("sales-chart").getContext('2d');

        var sales_chart_bg_color = sales_chart.createLinearGradient(0, 0, 0, 80);
        balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
        balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

        var myChart = new Chart(sales_chart, {
            type: 'line',
            data: {
                labels: @json($cardData['salesChartLabels']),
                datasets: [{
                    label: 'Sales',
                    data: @json($cardData['salesChartValues']),
                    borderWidth: 2,
                    backgroundColor: balance_chart_bg_color,
                    borderWidth: 3,
                    borderColor: 'rgba(63,82,227,1)',
                    pointBorderWidth: 0,
                    pointBorderColor: 'transparent',
                    pointRadius: 3,
                    pointBackgroundColor: 'transparent',
                    pointHoverBackgroundColor: 'rgba(63,82,227,1)',
                }]
            },
            options: {
                layout: {
                    padding: {
                        bottom: -1,
                        left: -1
                    }
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            beginAtZero: true,
                            display: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            drawBorder: false,
                            display: false,
                        },
                        ticks: {
                            display: false
                        }
                    }]
                },
            }
        });
    </script>
@endpush
