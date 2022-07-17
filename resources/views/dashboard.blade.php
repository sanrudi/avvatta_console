@extends('layout.template')
@push('css-links')
<!-- Data Tables -->
<link href="plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
<link href="assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
<!-- Data Tables -->
@endpush
@section('content')
<div class="container">
    <h6>DashBoard</h6><hr>
    <!-- chart -->
    @if(Auth::user()->is_cp == 0 && false)
    <div id="chartArea" class="col-xl-12 layout-spacing">
        <div class="statbox widget box">
            <div class="widget-content widget-content-area">
                <div id="s-line-area" class=""></div>

            </div>
        </div>
    </div>
    @endif
    <!-- Pie chart -->
    @if(Auth::user()->is_cp == 0)
    <div id="pieChartArea" class="col-xl-12 layout-spacing">
        <div class="statbox widget box">
            <div class="widget-content widget-content-area">
                <input type="date" class="chart-date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" />
                <button type="button" class="btn btn-success js-get-day-chart" >Get Data</button> <br/>
                <div id="s-pie-chart" class=""></div>

            </div>
        </div>
    </div>
    @endif
</div>
<div class="row mb-3">
@can('games')
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="{{ route('game-articles-report') }}">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body bg-success">
                            <div class="rotate">
                                <i class="fa fa-gamepad fa-5x"></i>
                            </div>
                            <h6 class="text-uppercase">Game Article</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="{{route('game-report')}}">
                    <div class="card text-white bg-danger h-100">
                        <div class="card-body bg-danger">
                            <div class="rotate">
                                <i class="fa fa-gamepad fa-5x"></i>
                            </div>
                            <h6 class="text-uppercase">All Game Categories</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="{{ route('repeated-game-by-user') }}">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body bg-info">
                            <div class="rotate">
                                <i class="fa fa-gamepad fa-5x"></i>
                            </div>
                            <h6 class="text-uppercase">Top repeat played games by a single user account </h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="{{ route('most-played-games') }}">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body">
                            <div class="rotate">
                                <i class="fa fa-gamepad fa-5x"></i>
                            </div>
                            <h6 class="text-uppercase">Top 10 most played Games</h6>
                        </div>
                    </div>
                    </a>
                </div>
 @endcan
 @can('kids')
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="{{route('kids-report')}}">
                    <div class="card text-white bg-danger h-100">
                        <div class="card-body bg-danger">
                            <div class="rotate">
                                <i class="fa fa-tv fa-5x"></i>
                            </div>
                            <h6 class="text-uppercase">All Kids Categories</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="{{ route('repeated-kidscontent-by-user') }}">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body bg-info">
                            <div class="rotate">
                                <i class="fa fa-tv fa-5x"></i>
                            </div>
                            <h6 class="text-uppercase">Most Watched Kids Content By Single User </h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="{{ route('most-watched-kids-content') }}">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body">
                            <div class="rotate">
                                <i class="fa fa-tv fa-5x"></i>
                            </div>
                            <h6 class="text-uppercase">Top 10 watched Kids Category </h6>
                        </div>
                    </div>
                    </a>
                </div>
 @endcan
 @can('elearn')
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="{{ route('elearn-top-genre-watched-report') }}">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body bg-success">
                            <div class="rotate">
                                <i class="fa fa-book fa-5x"></i>
                            </div>
                            <h6 class="text-uppercase">Top E-Learn Genre watched</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="{{ route('most-watched-elearn-content') }}">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body bg-info">
                            <div class="rotate">
                                <i class="fa fa-book fa-5x"></i>
                            </div>
                            <h6 class="text-uppercase">Most Watched E-learn By Single User</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="{{ route('elearn-top-repeat-user-report') }}">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body">
                            <div class="rotate">
                                <i class="fa fa-book fa-5x"></i>
                            </div>
                            <h6 class="text-uppercase">Top repeat E-Learn watched</h6>
                        </div>
                    </div>
                    </a>
                </div>
 @endcan
 @can('videos')
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="{{ route('video-articles-report') }}">
                    <div class="card bg-success text-white h-100">
                        <div class="card-body bg-success">
                            <div class="rotate">
                                <i class="fa fa-film fa-5x"></i>
                            </div>
                            <h6 class="text-uppercase">Video Article</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="{{ route('video-all-category-user-report') }}">
                    <div class="card text-white bg-danger h-100">
                        <div class="card-body bg-danger">
                            <div class="rotate">
                                <i class="fa fa-film fa-5x"></i>
                            </div>
                            <h6 class="text-uppercase">All Category Users</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="{{ route('video-top-repeat-user-report') }}">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body bg-info">
                            <div class="rotate">
                                <i class="fa fa-film fa-5x"></i>
                            </div>
                            <h6 class="text-uppercase">Top repeat watched</h6>
                        </div>
                    </div>
                    </a>
                </div>
                <div class="col-xl-3 col-sm-6 py-2">
                    <a href="{{ route('video-most-watched-report') }}">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body">
                            <div class="rotate">
                                <i class="fa fa-film fa-5x"></i>
                            </div>
                            <h6 class="text-uppercase">Top 10 most watched </h6>
                        </div>
                    </div>
                    </a>
                </div>
 @endcan
</div>


@endsection
@push('js-links')
@if(Auth::user()->is_cp == 0)
<script src="plugins/apex/apexcharts.min.js"></script>
@endif
@endpush
@section('js-content')
@if(Auth::user()->is_cp == 0)
<script>
// Simple Line Area
var lastdays
var sLineArea = {
    chart: {
        height: 400,
        type: 'area',
        toolbar: {
            show: false,
        }
    },
colors: ['#99e5fa', '#fff001', '#32fe00', '#00fe74', '#00ffff', '#0074ff', '#90de66', '#8303fc', '#f006fe', '#ff001c', '#f2c451', '#89d145', '#45d15e', '#4591d1', '#c045d1', '#d1458b', '#1e5c7a', '#1e7a22', '#9c7909', '#579c09', '#099c4a', '#e86514', '#0ee5cb', '#47b1cb', '#66dd72', '#ff84f9', '#99faa6', '#db99fa'],
dataLabels: {
    enabled: false
},
stroke: {
    curve: 'smooth'
},
series: [
@foreach($subscriptions as $subscription)
        {
          name:"{{ ucfirst(trans($subscription['title'])) }}",
          data: [{!! $subscription['subscription_count'] !!}]
        },
@endforeach 

],

xaxis: {
    type: 'datetime',
    tickPlacement: 'between',
    categories: [
      @foreach($period as $dayData)
          "{{ $dayData->format('Y-m-d') }}",
      @endforeach 
    ],                
},
tooltip: {
    x: {
        format: 'dd/MM/yyyy'
    },
},
title: {
    text: "Subscriptions",
    align: 'left',
    margin: 10,
    offsetX: 0,
    offsetY: 0,
    floating: false,
    style: {
      fontSize:  '14px',
      fontWeight:  'bold',
      fontFamily:  undefined,
      color:  '#263238'
    },
}
}

var chart = new ApexCharts(
    document.querySelector("#s-line-area"),
    sLineArea
    );

chart.render();

// Start - Day wise subscription count
var PieChart;
function loadPieCart(dayWiseSubscriptions, dayWiseTitles, isRefresh = false) {
    var options = {
        series: dayWiseSubscriptions,
        chart: {
        width: 800,
        type: 'pie',
    },
    labels: dayWiseTitles,
    responsive: [{
        breakpoint: 480,
        options: {
        chart: {
            width: 200
        },
        legend: {
            position: 'bottom'
        }
        }
    }],
    title: {
        text: "Subscriptions",
        align: 'left',
        margin: 10,
        offsetX: 0,
        offsetY: 0,
        floating: false,
        style: {
        fontSize:  '14px',
        fontWeight:  'bold',
        fontFamily:  undefined,
        color:  '#263238'
        },
    }
    };

    if(!isRefresh) {
        PieChart = new ApexCharts(document.querySelector("#s-pie-chart"), options);
        PieChart.render();
    } else {
        PieChart.updateOptions(options);
    }
}
var dayWiseSubscriptions = @json($dayWiseSubscriptions);
var dayWiseTitles = @json($dayWiseTitles);
loadPieCart(dayWiseSubscriptions, dayWiseTitles);
$(document).on('click', '.js-get-day-chart', function(e) {
    var chartDate = $('.chart-date').val();
    var $btnGetData = $(this);
    $btnGetData.html("Loading...").attr('disabled', true);
    $.ajax({
        url: "{{ route('home') }}",
        context: document.body,
        dataType: 'json',
        data: {chartDate : chartDate}
    }).done(function(result) {
        console.log(result);
        loadPieCart(result['dayWiseSubscriptions'], result['dayWiseTitles'], true);
        $btnGetData.html("Get Data").attr('disabled', false);
    });
});
// End - Day wise subscription count

</script>
@endif
@endsection