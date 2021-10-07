<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Avvatta | Console @yield('title')</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.png"/>
    <link href="assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="assets/js/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="adtemplate/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="adtemplate/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link href="adtemplate/plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="adtemplate/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="adtemplate/plugins/select2/select2.min.css"> -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <!-- <link href="adtemplate/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css"> -->
    <!-- <link href="adtemplate/assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" /> -->
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    @stack('css-links')
</head>
<body>
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    @include('layout.navbar')

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme">

            <nav id="sidebar">

                <ul class="navbar-nav theme-brand flex-row  text-center">
                    <li class="nav-item theme-logo">
                        <a href="index.html">
                            <img src="{{ asset('images/avvatta-logo.PNG') }}" class="navbar-logo" alt="logo">
                        </a>
                    </li>
                    <li class="nav-item toggle-sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left sidebarCollapse"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    </li>
                </ul>
                <div class="shadow-bottom"></div>
                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <li class="menu active">
                        <a href="" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                <span>Dashboard</span>
                            </div>
                        </a>
                    </li>

                    <li class="menu">
                        <a href="#games" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-target"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                                <span>Games</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="games" data-parent="#accordionExample">
                            <li>
                                <a href="{{ route('game-articles-report') }}"> Game Article </a>
                            </li>
                            <li>
                                <a href="{{route('game-report')}}"> All Game Categories </a>
                            </li>
                            <li>
                                <a href="{{ route('repeated-game-by-user') }}"> Top repeat played games by a single user account </a>
                            </li>
                            <li>
                                <a href="{{ route('most-played-games') }}"> Top 10 most played Games </a>
                            </li>
                        </ul>
                        
                        <a href="#kids" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">                                  
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-target"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                                <span>Kids</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="kids" data-parent="#accordionExample">
                            <li>
                            <a href="{{route('kids-report')}}"> All Kids Categories </a>
                            </li>
                            <li>
                            <a href="{{ route('most-watched-kids-content') }}"> Top 10 watched Kids Category </a>
                            </li>
                            <!-- <li>
                            <a href="{{ route('top-kids-content') }}"> Top 10 Kids Content </a>
                            </li> -->
                            <li>
                            <a href="{{ route('repeated-kidscontent-by-user') }}"> Most Watched Kids Content By Single User</a>
                            </li>
                        </ul>

                        <a href="#elearning" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">                                  
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-target"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                                <span>Elearning</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="elearning" data-parent="#accordionExample">
                            <li>
                            <a href="{{route('elearn-report')}}"> All Elearning Categories </a>
                            </li>
                            <li>
                            <a href="{{ route('top-ten-elearn-content') }}"> Top 10 watched Category </a>
                            </li>
                            <li>
                                <a href="{{ route('elearn-top-repeat-user-report') }}">Top repeat watched </a>
                            </li>
                            <li>
                                <a href="{{ route('elearn-top-genre-watched-report') }}">Top Genre watched </a>
                            </li>
                            <li>
                            <a href="{{ route('most-watched-elearn-content') }}"> Most Watched Kids Content By Single User</a>
                            </li>
                        </ul>
                        
                        <a href="#video-reports" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-target"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                                <span>Video Reports</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="video-reports" data-parent="#accordionExample">
                            <li>
                                <a href="{{ route('video-articles-report') }}"> Video Article </a>
                            </li>
                            <li>
                                <a href="{{ route('video-most-watched-report') }}"> Top 10 most watched  </a>
                            </li>
                            <li>
                                <a href="{{ route('video-top-repeat-user-report') }}">Top repeat watched </a>
                            </li>
                            <li>
                                <a href="{{ route('video-top-genre-watched-report') }}">Top Genre watched </a>
                            </li>
                            <li>
                                <a href="{{ route('video-all-category-user-report') }}">All Category Users </a>
                            </li>
                        </ul>
                        <a href="#user-reports" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-target"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                                <span>User Reports</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="user-reports" data-parent="#accordionExample">
                            <li>
                                <a href="{{ route('user-report') }}"> User Report </a>
                            </li>
                        </ul>
                        <a href="#subscription-reports" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-target"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                                <span>Subscription Reports</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="subscription-reports" data-parent="#accordionExample">
                            <li>
                                <a href="{{ route('subscription-total') }}"> Received Subscriptions </a>
                            </li>
                            <li>
                                <a href="{{ route('subscription-customer') }}">Customer Report </a>
                            </li>
                            <li>
                                <a href="{{ route('daily-transactions') }}">Daily Transactions </a>
                            </li>
                        </ul>
                        <a href="#revenue-reports" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-target"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
                                <span>Revenue Reports</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="revenue-reports" data-parent="#accordionExample">
                            <li>
                                <a href="{{ route('revenue-report') }}"> Revenue </a>
                            </li>
                        </ul>
                    </li>
                </ul>

            </nav>

        </div>
        <!--  END SIDEBAR  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing layout-spacing">
                    <div class="col-lg-12">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-content widget-content-area">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                    <!--  END CONTENT AREA  -->


                </div>
                <!-- END MAIN CONTAINER -->

                <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
                <script src="adtemplate/assets/js/libs/jquery-3.1.1.min.js"></script>
                <script src="adtemplate/bootstrap/js/popper.min.js"></script>
                <script src="adtemplate/bootstrap/js/bootstrap.min.js"></script>
                <script src="adtemplate/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
                <script src="adtemplate/assets/js/app.js"></script>
                <script>
                    $(document).ready(function() {
                        App.init();
                    });

                </script>
            </script>
            <script src="adtemplate/assets/js/custom.js"></script>
            <!-- END GLOBAL MANDATORY SCRIPTS -->
            @stack('js-links')
            @yield('js-content')
            <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->

        </body>
        </html>
