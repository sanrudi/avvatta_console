<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Avvatta | Console @yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}"/>
    <link href="{{ asset('assets/css/loader.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/js/loader.js') }}"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('adtemplate/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('adtemplate/assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @stack('css-links')
    @stack('css-content')
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
                        <a href="{{ route('dashboard') }}">
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
                        <a href="{{ route('dashboard') }}" aria-expanded="true" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                <span>Dashboard</span>
                            </div>
                        </a>
                    </li>
                    @can('games')
                    <li class="menu">
                        <a href="#games" data-toggle="collapse" aria-expanded="{{ (request()->is('game-articles-report')||request()->is('game-report')||request()->is('repeated-game-by-user')||request()->is('most-played-games')) ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-play-circle"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg>
                                <span>Games</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled {{ (request()->is('game-articles-report')||request()->is('game-report')||request()->is('repeated-game-by-user')||request()->is('most-played-games')) ? 'show' : '' }}" id="games" data-parent="#accordionExample">
                            <li class="{{ request()->is('game-report') ? 'active' : '' }}">
                                <a href="{{route('game-report')}}"> Most Categories Consumed Users </a>
                            </li>
                            <li class="{{ request()->is('repeated-game-by-user') ? 'active' : '' }}">
                                <a href="{{ route('repeated-game-by-user') }}"> Top Repeat Played Games</a>
                            </li>
                            <li class="{{ request()->is('most-played-games') ? 'active' : '' }}">
                                <a href="{{ route('most-played-games') }}"> Top Most Played Games </a>
                            </li>
                            <li class="{{ request()->is('game-articles-report') ? 'active' : '' }}">
                                <a href="{{ route('game-articles-report') }}"> Game Article </a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    @can('kids')
                    <li class="menu">
                        <a href="#kids" data-toggle="collapse" aria-expanded="{{ (request()->is('kids-report')||request()->is('most-watched-kids-content')||request()->is('repeated-kidscontent-by-user')) ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">   
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tv"><rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect><polyline points="17 2 12 7 7 2"></polyline></svg>
                                <span>Kids</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled {{ (request()->is('kids-report')||request()->is('most-watched-kids-content')||request()->is('repeated-kidscontent-by-user')) ? 'show' : '' }}" id="kids" data-parent="#accordionExample">
                            <li>
                                <a href="{{route('kids-report')}}"> Most Categories Consumed Users </a>
                            </li>
                            <li>
                                <a href="{{ route('most-watched-kids-content') }}"> Top 10 watched Kids Category </a>
                            </li>
                            <li>
                                <a href="{{ route('repeated-kidscontent-by-user') }}"> Most Watched Kids Content By Single User</a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    @can('elearn')
                    <li class="menu">
                        <a href="#elearning" data-toggle="collapse" aria-expanded="{{ (request()->is('elearn-report')||request()->is('top-ten-elearn-content')||request()->is('elearn-top-repeat-user-report')||request()->is('elearn-top-genre-watched-report')||request()->is('most-watched-elearn-content')) ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">   
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                                <span>Elearning</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled {{ (request()->is('elearn-report')||request()->is('top-ten-elearn-content')||request()->is('elearn-top-repeat-user-report')||request()->is('elearn-top-genre-watched-report')||request()->is('most-watched-elearn-content')) ? 'show' : '' }}" id="elearning" data-parent="#accordionExample">
                            <li>
                                <a href="{{route('elearn-report')}}"> Most Categories Consumed Users </a>
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
                                <a href="{{ route('most-watched-elearn-content') }}"> Most Watched E-learn By Single User</a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    @can('videos')
                    <li class="menu">
                        <a href="#video-reports" data-toggle="collapse" aria-expanded="{{ (request()->is('video-articles-report')||request()->is('video-most-watched-report')||request()->is('video-top-repeat-user-report')||request()->is('video-top-genre-watched-report')||request()->is('video-all-category-user-report')) ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">  
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                <span>Video Reports</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled {{ (request()->is('video-articles-report')||request()->is('video-most-watched-report')||request()->is('video-top-repeat-user-report')||request()->is('video-top-genre-watched-report')||request()->is('video-all-category-user-report')) ? 'show' : '' }}" id="video-reports" data-parent="#accordionExample">
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
                                <a href="{{ route('video-all-category-user-report') }}">Most Category Users </a>
                            </li>
                            @can('video-article')
                            <li>
                                <a href="{{ route('video-articles-report') }}"> Video Article </a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan
                    @can('users')
                    <li class="menu">
                        <a href="#user-reports" data-toggle="collapse" aria-expanded="{{ (request()->is('user-report')||request()->is('user-registration-report')||request()->is('user-sub-profile-report')||request()->is('user-login-report')||request()->is('user-idle-subscribers')) ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                                <span>User Reports</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled {{ (request()->is('user-report')||request()->is('user-registration-report')||request()->is('user-sub-profile-report')||request()->is('user-login-report')||request()->is('user-idle-subscribers')) ? 'show' : '' }}" id="user-reports" data-parent="#accordionExample">
                            <li>
                                <a href="{{ route('user-report') }}"> User Activity Report </a>
                            </li>
                            <li>
                                <a href="{{ route('user-registration-report') }}"> Registration Report</a>
                            </li>
                            <li>
                                <a href="{{ route('user-sub-profile-report') }}"> Sub Profile Report</a>
                            </li>
                            <li>
                                <a href="{{ route('user-login-report') }}"> Login Report</a>
                            </li>
                            <li>
                                <a href="{{ route('user-idle-subscribers') }}"> Subscribers activity</a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    @can('subsciprions')
                    <li class="menu">
                        <a href="#subscription-reports" data-toggle="collapse" aria-expanded="{{ (request()->is('subscription-total')||request()->is('subscription-customer')||request()->is('daily-transactions')) ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                <span>Subscription Reports</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled {{ (request()->is('subscription-total')||request()->is('subscription-customer')||request()->is('daily-transactions')) ? 'show' : '' }}" id="subscription-reports" data-parent="#accordionExample">
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
                    </li>
                    @endcan
                    @can('revenue')
                    <li class="menu">
                        <a href="#revenue-reports" data-toggle="collapse" aria-expanded="{{ (request()->is('video-articles-report')||request()->is('video-most-watched-report')||request()->is('video-top-repeat-user-report')||request()->is('video-top-genre-watched-report')||request()->is('video-all-category-user-report')) ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
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
                    @endcan
                    @can('errors')
                    <li class="menu">
                        <a href="#error-reports" data-toggle="collapse" aria-expanded="{{ (request()->is('video-articles-report')||request()->is('video-most-watched-report')||request()->is('video-top-repeat-user-report')||request()->is('video-top-genre-watched-report')||request()->is('video-all-category-user-report')) ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                                <span>Error Report</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled" id="error-reports" data-parent="#accordionExample">
                            <li>
                                <a href="{{ route('error-report') }}"> Report </a>
                            </li>
                        </ul>
                    </li>
                    @endcan
                    @can('manage-user')
                    <li class="menu">
                        <a href="{{ route('users.index') }}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                <span>Manage Users</span>
                            </div>
                        </a>
                    </li>
                    @endcan
                    @can('role-list')
                    <li class="menu">
                        <a href="{{ route('roles.index') }}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                                <span>Manage User Roles</span>
                            </div>
                        </a>
                    </li>
                    @endcan
                    @can('cms-editor')
                    <li class="menu">
                        <a href="#cms" data-toggle="collapse" aria-expanded="{{ (request()->is('cms-editor-erosnow')||request()->is('game-promotion')||request()->is('kid-promotion')||request()->is('elearn-promotion')) ? 'true' : 'false' }}" class="dropdown-toggle">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                <span>CMS Editor</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled {{ (request()->is('cms-editor-erosnow')||request()->is('game-promotion')||request()->is('kid-promotion')||request()->is('elearn-promotion')) ? 'show' : '' }}" id="cms" data-parent="#accordionExample">
                            <li class="{{ request()->is('cms-editor-erosnow') ? 'active' : '' }}">
                                <a href="{{ route('cms-editor-erosnow.index') }}"> Erosnow Promotion</a>
                            </li>
                            <li class="{{ request()->is('game-promotion') ? 'active' : '' }}">
                                <a href="{{ route('game-promotion') }}"> Games Promotion</a>
                            </li>
                            <li class="{{ request()->is('kid-promotion') ? 'active' : '' }}">
                                <a href="{{ route('kid-promotion') }}"> Kids Promotion</a>
                            </li>
                            <li class="{{ request()->is('elearn-promotion') ? 'active' : '' }}">
                                <a href="{{ route('elearn-promotion') }}"> Elearn Promotion</a>
                            </li>
                        </ul>
                    </li>
                    @endcan
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
                @yield('modal')
                <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
                <script src="{{ asset('adtemplate/assets/js/libs/jquery-3.1.1.min.js') }}"></script>
                <script src="{{ asset('adtemplate/bootstrap/js/popper.min.js') }}"></script>
                <script src="{{ asset('adtemplate/bootstrap/js/bootstrap.min.js') }}"></script>
                <script src="{{ asset('adtemplate/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
                <script src="{{ asset('adtemplate/assets/js/app.js') }}"></script>
                <script>
                    $(document).ready(function() {
                        App.init();
                    });
                </script>
            </script>
            <script src="{{ asset('adtemplate/assets/js/custom.js') }}"></script>
            <!-- END GLOBAL MANDATORY SCRIPTS -->
            @stack('js-links')
            @yield('js-content')
            <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
        </body>
        </html>
