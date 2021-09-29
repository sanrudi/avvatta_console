@component('layout.header')
    @endcomponent
      <!--  BEGIN MAIN CONTAINER  -->
      <div class="main-container" id="container">

<div class="overlay"></div>
<div class="search-overlay"></div>

<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">
    
    <nav id="sidebar">
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu">
                <a href="index.html" data-active="true" data-toggle="dropdown-toggle" aria-expanded="true" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>          
            <li class="menu">
                <a href="#forms" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class=""><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                        <span>Games</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="forms" data-parent="#accordionExample">
                    <li>
                        <a href="{{route('game-report')}}"> All Game Categories </a>
                    </li>
                    <li>
                        <a href="{{ route('repeated-game-by-user') }}"> Top repeat played games by a single user account </a>
                    </li>
                    <li>
                        <a href="{{ route('most-played-games') }}"> Top 10 most played Games </a>
                    </li>
                    <li>
                        <a href="#"> Manage Category </a>
                    </li>
                </ul>
            </li>
            <li class="menu">
                <a href="#forms" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>
                        <span>Kids</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="forms" data-parent="#accordionExample">
                    <li>
                        <a href="{{route('kids-report')}}"> All Kids Categories </a>
                    </li>
                    <li>
                        <a href="{{ route('most-watched-kids-content') }}"> Top 10 watched Kids Category </a>
                    </li>
                    <li>
                        <a href="{{ route('top-kids-content') }}"> Top 10 Kids Content </a>
                    </li>
                    <li>
                        <a href="{{ route('repeated-kidscontent-by-user') }}"> Most Watched Kids Content By Single User</a>
                    </li>
                </ul>
            </li>
            
            
        </ul>
        <!-- <div class="shadow-bottom"></div> -->
        
    </nav>

</div>
<!--  END SIDEBAR  -->

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">

        <div class="row layout-top-spacing">

            @if (Request::path() == 'game-report')
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-table-two pb-5">

                    <div class="widget-heading">
                        <div class="row">
                            <div class="game-content-header"><h5 class="">All Game Categories</h5></div>
                        </div>
                        <div class="game-content-header">
                            <a href="{{ route('export-game-content') }}" class="btn btn-info" role="button">Export</a>
                        </div>
                    </div>

                    <div class="widget-content">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><div class="th-content">User</div></th>
                                        <th><div class="th-content">Game</div></th>
                                        <th><div class="th-content">Category</div></th>
                                        <th><div class="th-content th-heading">Played At</div></th>
                                    </tr>
                                </thead>
                                @foreach ($game_contents as $game_content)
                                <tbody>
                                    <tr>
                                        <td><div class="td-content customer-name"><img src="assets/img/90x90.jpg" alt="avatar">{{$game_content->firstname.' '.$game_content->lastname}}</div></td>
                                        <td><div class="td-content product-brand">{{$game_content->game_name}}</div></td>
                                        <td><div class="td-content">{{$game_content->category_name}}</div></td>
                                        <td><div class="td-content pricing"><span class="">{{date('D j M Y', strtotime($game_content->date_time))}}</span></div></td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
        <div class="row layout-top-spacing">

            @if (Request::path() == 'repeated-game-by-user')
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-table-two pb-5">

                    <div class="widget-heading">
                        <div class="row">
                            <div class="game-content-header"><h5 class="">Top repeat played games by a single user account</h5></div>
                        </div>
                        <div class="game-content-header">
                            <a href="{{ route('export-repeated-game-content') }}" class="btn btn-info" role="button">Export</a>
                        </div>
                    </div>

                    <div class="widget-content">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><div class="th-content">User</div></th>
                                        <th><div class="th-content">Game</div></th>
                                        <th><div class="th-content">Category</div></th>
                                        
                                    </tr>
                                </thead>
                                @foreach ($repeated_games as $repeated_game)
                                <tbody>
                                    <tr>
                                        <td><div class="td-content customer-name"><img src="assets/img/90x90.jpg" alt="avatar">{{$repeated_game->firstname.' '.$repeated_game->lastname}}</div></td>
                                        <td><div class="td-content product-brand">{{$repeated_game->game_name}}</div></td>
                                        <td><div class="td-content">{{$repeated_game->category_name}}</div></td>
                            
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
        <div class="row layout-top-spacing">

            @if (Request::path() == 'most-played-games')
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-table-two pb-5">

                    <div class="widget-heading">
                        <div class="row">
                            <div class="game-content-header"><h5 class="">Top 10 most played Games</h5></div>
                        </div>
                        <div class="game-content-header">
                            <a href="{{ route('export-most-played-games') }}" class="btn btn-info" role="button">Export</a>
                        </div>
                    </div>

                    <div class="widget-content">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><div class="th-content">Game</div></th>
                                        <th><div class="th-content">Category</div></th>
                                        
                                    </tr>
                                </thead>
                                @foreach ($most_played_games as $most_played_game)
                                <tbody>
                                    <tr>
                                        <td><div class="td-content product-brand">{{$most_played_game->game_name}}</div></td>
                                        <td><div class="td-content">{{$most_played_game->category_name}}</div></td>
                            
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if (Request::path() == 'kids-report')
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-table-two pb-5">

                    <div class="widget-heading">
                        <div class="row">
                            <div class="game-content-header"><h5 class="">All Kids Categories</h5></div>
                        </div>
                        <div class="game-content-header">
                            <a href="{{ route('export-game-content') }}" class="btn btn-info" role="button">Export</a>
                        </div>
                    </div>

                    <div class="widget-content">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><div class="th-content">User</div></th>
                                        <th><div class="th-content">Video</div></th>
                                        <th><div class="th-content">Category</div></th>
                                        <th><div class="th-content th-heading">watched At</div></th>
                                    </tr>
                                </thead>
                                @foreach ($kids_contents as $kids_content)
                                <tbody>
                                    <tr>
                                        <td><div class="td-content customer-name"><img src="assets/img/90x90.jpg" alt="avatar">{{$kids_content->firstname.' '.$kids_content->lastname}}</div></td>
                                        <td><div class="td-content product-brand">{{$kids_content->content_name}}</div></td>
                                        <td><div class="td-content">{{$kids_content->category_name}}</div></td>
                                        <td><div class="td-content pricing"><span class="">{{date('D j M Y', strtotime($kids_content->date_time))}}</span></div></td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if (Request::path() == 'most-watched-kids-content')
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-table-two pb-5">

                    <div class="widget-heading">
                        <div class="row">
                            <div class="game-content-header"><h5 class="">Top 10 watched Kids Category</h5></div>
                        </div>
                        <div class="game-content-header">
                            <a href="{{ route('export-most-played-games') }}" class="btn btn-info" role="button">Export</a>
                        </div>
                    </div>

                    <div class="widget-content">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><div class="th-content">Video</div></th>
                                        <th><div class="th-content">Category</div></th>
                                        
                                    </tr>
                                </thead>
                                @foreach ($mostWatchedKidsContents as $mostWatchedKidsContent)
                                <tbody>
                                    <tr>
                                        <td><div class="td-content product-brand">{{$mostWatchedKidsContent->content_name}}</div></td>
                                        <td><div class="td-content">{{$mostWatchedKidsContent->category_name}}</div></td>
                            
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if (Request::path() == 'repeated-kidscontent-by-user')
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-table-two pb-5">

                    <div class="widget-heading">
                        <div class="row">
                            <div class="game-content-header"><h5 class="">Most Watched Kids Content By Single User</h5></div>
                        </div>
                        <div class="game-content-header">
                            <a href="{{ route('export-most-played-games') }}" class="btn btn-info" role="button">Export</a>
                        </div>
                    </div>

                    <div class="widget-content">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><div class="th-content">User</div></th>
                                        <th><div class="th-content">Video</div></th>
                                        <th><div class="th-content">Category</div></th>
                                        
                                    </tr>
                                </thead>
                                @foreach ($repeatedKidsContents as $repeatedKidsContent)
                                <tbody>
                                    <tr>
                                        <td><div class="td-content customer-name"><img src="assets/img/90x90.jpg" alt="avatar">{{$repeatedKidsContent->firstname.' '.$repeatedKidsContent->lastname}}</div></td>
                                        <td><div class="td-content product-brand">{{$repeatedKidsContent->content_name}}</div></td>
                                        <td><div class="td-content">{{$repeatedKidsContent->category_name}}</div></td>
                            
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>

    </div>
    <div class="footer-wrapper">
        <div class="footer-section f-section-1">
            <p class="">Copyright © <script>document.write(new Date().getFullYear())</script>  All rights reserved.</p>
        </div>
    </div>
</div>
<!--  END CONTENT AREA  -->

</div>
<script src="assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="bootstrap/js/popper.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="assets/js/custom.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->
    <script src="plugins/apex/apexcharts.min.js"></script>
    <script src="assets/js/dashboard/dash_1.js"></script>
<!-- END MAIN CONTAINER -->
@component('layout.header')
    @endcomponent