@extends('layout.template')
@push('css-links')
<!-- Data Tables -->
<link href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<!-- Data Tables -->
<!-- Date Picker -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- Date Picker -->
@endpush
@section('title', 'Revenue - Report')
@section('content')
<div class="container">
    <h6>Revenue - Report - Avvatta Revenue Report  
        @if(Request::get('reportFrom') !="custom" && Request::get('reportFrom') !="")
        for Last {!! Request::get('reportFrom') !!} Days
        @elseif(Request::get('reportFrom') =="custom")
        @if(Request::get('startDate') !="")
        From: {!! Request::get('startDate') !!}
        @endif
        @if(Request::get('endDate') !="")
        till: {!! Request::get('endDate') !!}
        @endif
        @endif
        @if(Request::get('reportFrom') =="" && Request::get('startDate') =="" && Request::get('endDate') =="")
        From: <?php echo $all['fromdate']->format('d-M-Y'); ?>
                   To: <?php echo $all['todate']->format('d-M-Y'); ?>
        @endif
    </h6><hr>
    <form autocomplete="off" action="" method="GET">
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="reportFrom">Report From</label>
                <select name="reportFrom" id="reportFrom" class="form-control">
                    <option value="" @if(Request::get('reportFrom') == "") selected="selected" @endif >Select</option>
                    <option value="custom" @if(Request::get('reportFrom') == "custom") selected="selected" @endif>Custom</option>
                </select>
            </div>
            <div class="form-group col-md-2 custom-date">
                <label for="startDate">Start Date</label>
                <input id="startDate" name="startDate" type="text" class="form-control" value="" placeholder="yyyy-mm-dd" />
            </div>
            <div class="form-group col-md-2 custom-date">
                <label for="endDate">End Date</label>
                <input id="endDate" name="endDate" type="text" class="form-control"  value="" placeholder="yyyy-mm-dd" />
            </div>
            <div class="form-group col-md-2">
                <label for="page">&nbsp;</label>
                <button type="submit" class="form-control btn btn-info" name="page" value="Generate">Generate</button>
            </div>
        </div>
    </form>
    <hr>
      <!-- <form>
          <input name="startDate" class="date form-control" type="text">
          <input name="endDate" class="date form-control" type="text">
          <input type="submit" value = "get report">
      </form> -->

<?php
$taxPercent = 15;
$host =  $_SERVER['HTTP_HOST'];
if($host == "avvatta.com:8100"){
    $taxPercent = 15;
}
if($host == "ng.avvatta.com:8100"){
    $taxPercent = 17.5;
}
if($host == "gh.avvatta.com:8100"){
    $taxPercent = 15;
}
$excludeTax = (100 - $taxPercent)/100;
$revData = array();
$vodData = array();
$eroData = array();
$gamData = array();
$kidData = array();
$funData = array();
$higData = array();
$codData = array();
$siyData = array();
$i = 0;
// Sub Count
$vod_sub_count = 0;
$ero_sub_count = 0;
$gam_sub_count = 0;
$kid_sub_count = 0;
$fun_sub_count = 0;
$hig_sub_count = 0;
$cod_sub_count = 0;
$siy_sub_count = 0;
$total_sub_count = 0;
// Sub Rev
$vod_sub_rev = 0;
$ero_sub_rev = 0;
$gam_sub_rev = 0;
$kid_sub_rev = 0;
$fun_sub_rev = 0;
$hig_sub_rev = 0;
$cod_sub_rev = 0;
$siy_sub_rev = 0;
$total_sub_rev = 0;
// Sub Rev Ex Vat
$vod_sub_rev_ex_vat = 0;
$ero_sub_rev_ex_vat = 0;
$gam_sub_rev_ex_vat = 0;
$kid_sub_rev_ex_vat = 0;
$fun_sub_rev_ex_vat = 0;
$hig_sub_rev_ex_vat = 0;
$cod_sub_rev_ex_vat = 0;
$siy_sub_rev_ex_vat = 0;
$total_sub_rev_ex_vat = 0;
// Ope Rev
$vod_op_rev = 0;
$ero_op_rev = 0;
$gam_op_rev = 0;
$kid_op_rev = 0;
$fun_op_rev = 0;
$hig_op_rev = 0;
$cod_op_rev = 0;
$siy_op_rev = 0;
$total_op_rev = 0;
// Agg Rev
$vod_agg_rev = 0;
$ero_agg_rev = 0;
$gam_agg_rev = 0;
$kid_agg_rev = 0;
$fun_agg_rev = 0;
$hig_agg_rev = 0;
$cod_agg_rev = 0;
$siy_agg_rev = 0;
$total_agg_rev = 0;
// Cp Rev
$vod_cp_rev = 0;
$ero_cp_rev = 0;
$gam_cp_rev = 0;
$kid_cp_rev = 0;
$fun_cp_rev = 0;
$hig_cp_rev = 0;
$cod_cp_rev = 0;
$siy_cp_rev = 0;
$total_cp_rev = 0;
// Over all Cp Payment
$vod_cpp = 0;
$ero_cpp = 0;
$gam_cpp = 0;
$kid_cpp = 0;
$fun_cpp = 0;
$hig_cpp = 0;
$cod_cpp = 0;
$siy_cpp = 0;
$currentCPPayment = [];
$category ="";
foreach ($subs as $value) {  
    //echo "title |".$value->title."|<br>";
if($value->title == "vod daily" || $value->title == "vod weekly" || $value->title == "vod monthly"){
    $category="vod";
    $vod_sub_count = $vod_sub_count + $subcount[$value->id];
    $vod_sub_rev = $vod_sub_rev + $all[$value->id];
    $vod_sub_rev_ex_vat = $vod_sub_rev_ex_vat + ($all[$value->id] * $excludeTax);
    $vod_op_rev = $vod_op_rev + ($all[$value->id] * $excludeTax * .4 );
    $vod_agg_rev = $vod_agg_rev + ($all[$value->id] * $excludeTax * .1);
    $vod_cp_rev = $vod_cp_rev + ($all[$value->id] * $excludeTax * .25 );
} 
if($value->title == "Eros Now Daily" || $value->title == "Eros Now weekly" || $value->title == "Eros Now Monthly"){
    $category="eros";
    $ero_sub_count =  $ero_sub_count + $subcount[$value->id];
    $ero_sub_rev = $ero_sub_rev + $all[$value->id];
    $ero_sub_rev_ex_vat = $ero_sub_rev_ex_vat + ($all[$value->id] * $excludeTax);
    $ero_op_rev = $ero_op_rev + ($all[$value->id] * $excludeTax * .4 );
    $ero_agg_rev = $ero_agg_rev + ($all[$value->id] * $excludeTax * .1);
    $ero_cp_rev = $ero_cp_rev + ($all[$value->id] * $excludeTax * .25 );
}
if($value->title == "Games Daily" || $value->title == "Games weekly" || $value->title == "Games Monthly") {
    $category="games";
    // echo "title |".$value->title."|<br>";
    // echo "gam_sub_rev |".$all[$value->id]."|<br>";
    // echo "gam_sub_rev_ex_vat |".($all[$value->id] * 0.85)."|<br>";
    // echo "gam_op_rev |".($all[$value->id] * 0.85 * .4 )."|<br>";
    $gam_sub_count =  $gam_sub_count + $subcount[$value->id];
    $gam_sub_rev = $gam_sub_rev + $all[$value->id];
    $gam_sub_rev_ex_vat = $gam_sub_rev_ex_vat + ($all[$value->id] * $excludeTax);
    $gam_op_rev = $gam_op_rev + ($all[$value->id] * $excludeTax * .4 );
    $gam_agg_rev = $gam_agg_rev + ($all[$value->id] * $excludeTax * .1);
    $gam_cp_rev = $gam_cp_rev + ($all[$value->id] * $excludeTax * .25 );
}
if($value->title == "Kids Daily" || $value->title == "Kids weekly" || $value->title == "Kids Monthly") {
    $category="kids";
    $kid_sub_count =  $kid_sub_count + $subcount[$value->id];
    $kid_sub_rev = $kid_sub_rev + $all[$value->id];
    $kid_sub_rev_ex_vat = $kid_sub_rev_ex_vat + ($all[$value->id] * $excludeTax);
    $kid_op_rev = $kid_op_rev + ($all[$value->id] * $excludeTax * .4 );
    $kid_agg_rev = $kid_agg_rev + ($all[$value->id] * $excludeTax * .1);
    $kid_cp_rev = $kid_cp_rev + ($all[$value->id] * $excludeTax * .25 );
}
if($value->title == "Fun & Learning Daily" || $value->title == "Fun & Learning Weekly" || $value->title == "Fun & Learning Monthly") {
$category="Fun & Learning";
    $fun_sub_count =  $fun_sub_count + $subcount[$value->id];
    $fun_sub_rev = $fun_sub_rev + $all[$value->id];
    $fun_sub_rev_ex_vat = $fun_sub_rev_ex_vat + ($all[$value->id] * $excludeTax);
    $fun_op_rev = $fun_op_rev + ($all[$value->id] * $excludeTax * .4 );
    $fun_agg_rev = $fun_agg_rev + ($all[$value->id] * $excludeTax * .1);
    $fun_cp_rev = $fun_cp_rev + ($all[$value->id] * $excludeTax * .25 );
}
if($value->title == "Higher Learning Daily" || $value->title == "Higher Learning Weekly" || $value->title == "Higher Learning Monthly") {
$category="Higher Learning";
    $hig_sub_count =  $hig_sub_count + $subcount[$value->id];
    $hig_sub_rev = $hig_sub_rev + $all[$value->id];
    $hig_sub_rev_ex_vat = $hig_sub_rev_ex_vat + ($all[$value->id] * $excludeTax);
    $hig_op_rev = $hig_op_rev + ($all[$value->id] * $excludeTax * .4 );
    $hig_agg_rev = $hig_agg_rev + ($all[$value->id] * $excludeTax * .1);
    $hig_cp_rev = $hig_cp_rev + ($all[$value->id] * $excludeTax * .25 );
}
if($value->title == "Coding Daily" || $value->title == "Coding Weekly" || $value->title == "Coding Monthly") {
$category="Coding";
    $cod_sub_count =  $cod_sub_count + $subcount[$value->id];
    $cod_sub_rev = $cod_sub_rev + $all[$value->id];
    $cod_sub_rev_ex_vat = $cod_sub_rev_ex_vat + ($all[$value->id] * $excludeTax);
    $cod_op_rev = $cod_op_rev + ($all[$value->id] * $excludeTax * .4 );
    $cod_agg_rev = $cod_agg_rev + ($all[$value->id] * $excludeTax * .1);
    $cod_cp_rev = $cod_cp_rev + ($all[$value->id] * $excludeTax * .25 );
}
if($value->title == "Siyavula Daily" || $value->title == "Siyavula Weekly" || $value->title == "Siyavula Monthly") {
$category="Siyavula";
    $siy_sub_count =  $siy_sub_count + $subcount[$value->id];
    $siy_sub_rev = $siy_sub_rev + $all[$value->id];
    $siy_sub_rev_ex_vat = $siy_sub_rev_ex_vat + ($all[$value->id] * $excludeTax);
    $siy_op_rev = $siy_op_rev + ($all[$value->id] * $excludeTax * .4 );
    $siy_agg_rev = $siy_agg_rev + ($all[$value->id] * $excludeTax * .1);
    $siy_cp_rev = $siy_cp_rev + ($all[$value->id] * $excludeTax * .25 );
}  
if($category != ""){
$total_sub_count =  $total_sub_count + $subcount[$value->id];
$total_sub_rev = $total_sub_rev + $all[$value->id];
$total_sub_rev_ex_vat = $total_sub_rev_ex_vat + ($all[$value->id] * $excludeTax);
$total_op_rev = $total_op_rev + ($all[$value->id] * $excludeTax * .4 );
$total_agg_rev = $total_agg_rev + ($all[$value->id] * $excludeTax * .1);
$total_cp_rev = $total_cp_rev + ($all[$value->id] * $excludeTax * .25 );
//echo "category-".$category."-";  
//echo "total_sub_rev_ex_vat".$total_sub_rev_ex_vat."|";
}
$category = ""; 
//  
$i++;

 }  ?>

@if(Auth::user()->is_cp)
<div class="table-responsive">
    <table id="cp-table" class="table table-bordered mb-5">
        <thead>
            <tr >
                <th >Category</th>
                <th >Provider</th>
                <th >Subscriptions</th>
                <th >Watches</th>
                <th >Watches (%)</th>
                <th >Subs Revenue (Incl VAT)</th>
                <th >Subs Revenue (Excl VAT)</th>
                <th >Billing ID</th>
                <th >Operator</th>
                <th >Quantity Successfully Billed</th>
                <th >Quantity Failed</th>
                <th >Gross Revenue (VAT EX)</th>
                <th >Operator Revenue</th>
                <th >Aggregator Revenue</th>
                <th >CP Revenue</th>
            </tr>   
        </thead>
        <tbody>
                @if(Auth::user()->roles->pluck('name')[0] == 'vod' && Auth::user()->is_cp)
                <tr >
                    <td >Video on Demand</td>
                    <td ></td>
                    <td >{{$vod_sub_count}}</td>
                    <td >0</td>
                    <td >0</td>
                    <td >{{ $vod_sub_rev }}</td>
                    <td >{{ $vod_sub_rev_ex_vat }}</td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >{{ $vod_op_rev }}</td>
                    <td >{{ $vod_agg_rev }}</td>
                    <td >(0){{ $vod_cp_rev }}
                        <?php 
                        //currentCPPayment
                        $currentCPPayment[] = array('category' => 'Video on Demand',
                        'provider' => 'vod',
                        'cprevenue' => $vod_cp_rev);

                        ?>
                    </td>
                </tr>
                @endif
                @if(Auth::user()->roles->pluck('name')[0] == 'erosnow' && Auth::user()->is_cp)
                <tr >
                    <td >Eros Now</td>
                    <td >Erosnow</td>
                    <td >{{$vod_sub_count}}</td>
                    <td >{{$erosnowWatches}}</td>
                    <td >
                    </td>
                    <td >
                    <?php $eroWatchep = 100; //echo round($eroWatchep,2); ?>
                        <?php 
                        $ero_sub_rev_ForCp = $ero_sub_rev * $eroWatchep/100; 
                        echo "(".round($ero_sub_rev_ForCp,2).")"; 
                        echo $ero_sub_rev; 
                        ?></td>
                    <td >
                    <?php 
                    $ero_sub_rev_ex_vat_ForCp = $ero_sub_rev_ex_vat * $eroWatchep/100; 
                    echo "(".round($ero_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $ero_sub_rev_ex_vat; 
                    ?></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $ero_op_rev_ForCp = $ero_op_rev * $eroWatchep/100; 
                    echo "(".round($ero_op_rev_ForCp,2).")"; 
                    echo $ero_op_rev; 
                    ?></td>
                    <td >
                    <?php 
                    $ero_agg_rev_ForCp = $ero_agg_rev * $eroWatchep/100; 
                    echo "(".round($ero_agg_rev_ForCp,2).")"; 
                    echo $ero_agg_rev; 
                    ?></td>
                    <td >
                    <?php 
                    $ero_cp_rev_ForCp = $ero_cp_rev * $eroWatchep/100; 
                    echo "(".round($ero_cp_rev_ForCp,2).")"; 
                    echo $ero_cp_rev; 
                    //currentCPPayment
                    $currentCPPayment[] = array('category' => 'Eros Now',
                    'provider' => 'Erosnow',
                    'cprevenue' => $ero_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                @endif
                <!-- Games With Watches  -->
                <?php 
                if(count($gameWatches)>0){
                $gameWatchesTotal =0;
                foreach ($gameWatches as $gameWatche) {$gameWatchesTotal = $gameWatchesTotal + $gameWatche['count'];}
                foreach ($gameWatches as $gameWatche) {  ?>
                @if(Auth::user()->roles->pluck('name')[0] == $gameWatche['provider'] && Auth::user()->is_cp)
                <tr >
                    <td >Games</td>
                    <td >{{$gameWatche['provider']}}</td>
                    <td >{{$gam_sub_count}}</td>
                    <td >{{$gameWatche['count']}}</td>
                    <td >
                    <?php $gameWatchep = ($gameWatche['count']/$gameWatchesTotal)*100; echo round($gameWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $gam_sub_rev_ForCp = $gam_sub_rev * $gameWatchep/100; 
                        echo "(".round($gam_sub_rev_ForCp,2).")"; 
                        echo $gam_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $gam_sub_rev_ex_vat_ForCp = $gam_sub_rev_ex_vat * $gameWatchep/100; 
                    echo "(".round($gam_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $gam_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $gam_op_rev_ForCp = $gam_op_rev * $gameWatchep/100; 
                    echo "(".round($gam_op_rev_ForCp,2).")"; 
                    echo $gam_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $gam_agg_rev_ForCp = $gam_agg_rev * $gameWatchep/100; 
                    echo "(".round($gam_agg_rev_ForCp,2).")"; 
                    echo $gam_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $gam_cp_rev_ForCp = $gam_cp_rev * $gameWatchep/100; 
                    echo "(".round($gam_cp_rev_ForCp,2).")"; 
                    echo $gam_cp_rev; 
                    //currentCPPayment
                    $currentCPPayment[] = array('category' => 'Games',
                    'provider' => $gameWatche['provider'],
                    'cprevenue' => $gam_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                @endif
                <?php 
                }  
                } ?>
                <!-- Games No Watches  -->
                <?php 
                if(count($gameWatches) == 0){
                $gameWatchesTotal =0;
                foreach ($gameWatches as $gameWatche) {$gameWatchesTotal = $gameWatchesTotal + $gameWatche['count'];}
                foreach ($gameWatches as $gameWatche) {  ?>
                @if(Auth::user()->roles->pluck('name')[0] == $gameWatche['provider'] && Auth::user()->is_cp)
                <tr >
                    <td >Games</td>
                    <td ></td>
                    <td >{{$gam_sub_count}}</td>
                    <td ></td>
                    <td >
                    <?php $gameWatchep = 0; echo round($gameWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $gam_sub_rev_ForCp = $gam_sub_rev * $gameWatchep/100; 
                        echo "(".round($gam_sub_rev_ForCp,2).")"; 
                        echo $gam_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $gam_sub_rev_ex_vat_ForCp = $gam_sub_rev_ex_vat * $gameWatchep/100; 
                    echo "(".round($gam_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $gam_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $gam_op_rev_ForCp = $gam_op_rev * $gameWatchep/100; 
                    echo "(".round($gam_op_rev_ForCp,2).")"; 
                    echo $gam_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $gam_agg_rev_ForCp = $gam_agg_rev * $gameWatchep/100; 
                    echo "(".round($gam_agg_rev_ForCp,2).")"; 
                    echo $gam_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $gam_cp_rev_ForCp = $gam_cp_rev * $gameWatchep/100; 
                    echo "(".round($gam_cp_rev_ForCp,2).")"; 
                    echo $gam_cp_rev; 
                    //currentCPPayment
                    // $currentCPPayment[] = array('category' => 'Games',
                    // 'provider' => $gameWatche['provider'],
                    // 'cprevenue' => $gam_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                @endif
                <?php 
                }  
                } ?>

                <!-- Kids -->
                <?php if(count($kidWatches)>0){ ?>
                <?php 
                $kidWatchesTotal =0;
                foreach ($kidWatches as $kidWatche) {$kidWatchesTotal = $kidWatchesTotal + $kidWatche['count'];}
                foreach ($kidWatches as $kidWatche) {  
                   ?>
                @if(Auth::user()->roles->pluck('name')[0] == $kidWatche['provider'] && Auth::user()->is_cp)
                <tr >
                    <td >Kids</td>
                    <td >{{$kidWatche['provider']}}</td>
                    <td >{{$kid_sub_count}}</td>
                    <td >{{$kidWatche['count']}}</td>
                    <td >
                    <?php $kidWatchep = ($kidWatche['count']/$kidWatchesTotal)*100; echo round($kidWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $kid_sub_rev_ForCp = $kid_sub_rev * $kidWatchep/100; 
                        echo "(".round($kid_sub_rev_ForCp,2).")"; 
                        echo $kid_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $kid_sub_rev_ex_vat_ForCp = $kid_sub_rev_ex_vat * $kidWatchep/100; 
                    echo "(".round($kid_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $kid_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $kid_op_rev_ForCp = $kid_op_rev * $kidWatchep/100; 
                    echo "(".round($kid_op_rev_ForCp,2).")"; 
                    echo $kid_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $kid_agg_rev_ForCp = $kid_agg_rev * $kidWatchep/100; 
                    echo "(".round($kid_agg_rev_ForCp,2).")"; 
                    echo $kid_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $kid_cp_rev_ForCp = $kid_cp_rev * $kidWatchep/100; 
                    echo "(".round($kid_cp_rev_ForCp,2).")"; 
                    echo $kid_cp_rev; 
                    //currentCPPayment
                    $currentCPPayment[] = array('category' => 'Kids',
                    'provider' => $kidWatche['provider'],
                    'cprevenue' => $kid_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                @endif
                <?php }   ?>
                <?php }   ?>
                <!-- Kids -->
                <?php if(count($kidWatches) == 0){ ?>
                <?php 
                $kidWatchesTotal =0;
                foreach ($kidWatches as $kidWatche) {$kidWatchesTotal = $kidWatchesTotal + $kidWatche['count'];}
                foreach ($kidWatches as $kidWatche) {  
                   ?>
                   @if(Auth::user()->roles->pluck('name')[0] == $kidWatche['provider'] && Auth::user()->is_cp)
                <tr >
                    <td >Kids</td>
                    <td ></td>
                    <td >{{$kid_sub_count}}</td>
                    <td ></td>
                    <td >
                    <?php $kidWatchep = 0; echo round($kidWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $kid_sub_rev_ForCp = $kid_sub_rev * $kidWatchep/100; 
                        echo "(".round($kid_sub_rev_ForCp,2).")"; 
                        echo $kid_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $kid_sub_rev_ex_vat_ForCp = $kid_sub_rev_ex_vat * $kidWatchep/100; 
                    echo "(".round($kid_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $kid_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $kid_op_rev_ForCp = $kid_op_rev * $kidWatchep/100; 
                    echo "(".round($kid_op_rev_ForCp,2).")"; 
                    echo $kid_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $kid_agg_rev_ForCp = $kid_agg_rev * $kidWatchep/100; 
                    echo "(".round($kid_agg_rev_ForCp,2).")"; 
                    echo $kid_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $kid_cp_rev_ForCp = $kid_cp_rev * $kidWatchep/100; 
                    echo "(".round($kid_cp_rev_ForCp,2).")"; 
                    echo $kid_cp_rev; 
                    //currentCPPayment
                    // $currentCPPayment[] = array('category' => 'Kids',
                    // 'provider' => $kidWatche['provider'],
                    // 'cprevenue' => $kid_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                @endif
                <?php }   ?>
                <?php }   ?>
                <!-- Fun -->
                <?php if(count($funWatches)>0){ ?>
                <?php 
                $funWatchesTotal =0;
                foreach ($funWatches as $funWatche) {$funWatchesTotal = $funWatchesTotal + $funWatche['count'];}
                foreach ($funWatches as $funWatche) {  ?>
                    @if(Auth::user()->roles->pluck('name')[0] == $funWatche['provider'] && Auth::user()->is_cp)
                <tr >
                    <td >Fun & Learning</td>
                    <td >{{$funWatche['provider']}}</td>
                    <td >{{$fun_sub_count}}</td>
                    <td >{{$funWatche['count']}}</td>
                    <td >
                    <?php $funWatchep = ($funWatche['count']/$funWatchesTotal)*100; echo round($funWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $fun_sub_rev_ForCp = $fun_sub_rev * $funWatchep/100; 
                        echo "(".round($fun_sub_rev_ForCp,2).")"; 
                        echo $fun_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $fun_sub_rev_ex_vat_ForCp = $fun_sub_rev_ex_vat * $funWatchep/100; 
                    echo "(".round($fun_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $fun_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $fun_op_rev_ForCp = $fun_op_rev * $funWatchep/100; 
                    echo "(".round($fun_op_rev_ForCp,2).")"; 
                    echo $fun_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $fun_agg_rev_ForCp = $fun_agg_rev * $funWatchep/100; 
                    echo "(".round($fun_agg_rev_ForCp,2).")"; 
                    echo $fun_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $fun_cp_rev_ForCp = $fun_cp_rev * $funWatchep/100; 
                    echo "(".round($fun_cp_rev_ForCp,2).")"; 
                    echo $fun_cp_rev; 
                    //currentCPPayment
                    $currentCPPayment[] = array('category' => 'Fun & Learning',
                    'provider' => 'Netsport',
                    'cprevenue' => $fun_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                @endif
                <?php }   ?>
                <?php }   ?>
                
                <!-- Fun -->
                <?php if(count($funWatches) == 0){ ?>
                <?php 
                $funWatchesTotal =0;
                foreach ($funWatches as $funWatche) {$funWatchesTotal = $funWatchesTotal + $funWatche['count'];}
                //foreach ($funWatches as $funWatche) {  ?>
                @if(Auth::user()->roles->pluck('name')[0] == "fun" && Auth::user()->is_cp)
                <tr >
                    <td >Fun & Learning</td>
                    <td ></td>
                    <td >{{$fun_sub_count}}</td>
                    <td ></td>
                    <td >
                    <?php $funWatchep = 0; echo round($funWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $fun_sub_rev_ForCp = $fun_sub_rev * $funWatchep/100; 
                        echo "(".round($fun_sub_rev_ForCp,2).")"; 
                        echo $fun_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $fun_sub_rev_ex_vat_ForCp = $fun_sub_rev_ex_vat * $funWatchep/100; 
                    echo "(".round($fun_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $fun_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $fun_op_rev_ForCp = $fun_op_rev * $funWatchep/100; 
                    echo "(".round($fun_op_rev_ForCp,2).")"; 
                    echo $fun_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $fun_agg_rev_ForCp = $fun_agg_rev * $funWatchep/100; 
                    echo "(".round($fun_agg_rev_ForCp,2).")"; 
                    echo $fun_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $fun_cp_rev_ForCp = $fun_cp_rev * $funWatchep/100; 
                    echo "(".round($fun_cp_rev_ForCp,2).")"; 
                    echo $fun_cp_rev; 
                    ?>
                    </td>
                </tr>
                @endif
                <?php //}   ?>
                <?php }   ?>
                <?php if(count($higWatches)>0){ 
                    ?>
                <?php 
                $higWatchesTotal =0;
                foreach ($higWatches as $higWatche) {$higWatchesTotal = $higWatchesTotal + $higWatche['count'];}
                foreach ($higWatches as $higWatche) {  ?>
                    @if(Auth::user()->roles->pluck('name')[0] == $higWatche['provider'] && Auth::user()->is_cp)
                <tr >
                    <td >Higher Learning</td>
                    <td >{{$higWatche['provider']}}</td>
                    <td >{{$hig_sub_count}}</td>
                    <td >{{$higWatche['count']}}</td>
                    <td >
                    </td>
                    <td >
                    <?php $higWatchep = ($higWatche['count'] / $higWatchesTotal) * 100; //echo round($higWatchep,2); ?>
                        <?php 
                        $hig_sub_rev_ForCp = $hig_sub_rev * $higWatchep/100; 
                        echo "(".round($hig_sub_rev_ForCp,2).")"; 
                        echo $hig_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $hig_sub_rev_ex_vat_ForCp = $hig_sub_rev_ex_vat * $higWatchep/100; 
                    echo "(".round($hig_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $hig_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $hig_op_rev_ForCp = $hig_op_rev * $higWatchep/100; 
                    echo "(".round($hig_op_rev_ForCp,2).")"; 
                    echo $hig_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $hig_agg_rev_ForCp = $hig_agg_rev * $higWatchep/100; 
                    echo "(".round($hig_agg_rev_ForCp,2).")"; 
                    echo $hig_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $hig_cp_rev_ForCp = $hig_cp_rev * $higWatchep/100; 
                    echo "(".round($hig_cp_rev_ForCp,2).")"; 
                    echo $hig_cp_rev; 
                    ?>
                    </td>
                </tr>
                @endif
                <?php }   ?>
                <?php }   ?>
                <?php if(count($codWatches)>0){  ?>
                <?php 
                $codWatchesTotal =0;
                foreach ($codWatches as $codWatche) {$codWatchesTotal = $codWatchesTotal + $codWatche['count'];}
                foreach ($codWatches as $codWatche) {  ?>
                    @if(Auth::user()->roles->pluck('name')[0] == $codWatche['provider'] && Auth::user()->is_cp)
                <tr >
                    <td >Coding</td>
                    <td >{{$codWatche['provider']}}</td>
                    <td >{{$cod_sub_count}}</td>
                    <td >{{$codWatche['count']}}</td>
                    <td >
                    <?php $codWatchep = 100; echo round($codWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $cod_sub_rev_ForCp = $cod_sub_rev * $codWatchep/100; 
                        echo "(".round($cod_sub_rev_ForCp,2).")"; 
                        echo $cod_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $cod_sub_rev_ex_vat_ForCp = $cod_sub_rev_ex_vat * $codWatchep/100; 
                    echo "(".round($cod_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $cod_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $cod_op_rev_ForCp = $cod_op_rev * $codWatchep/100; 
                    echo "(".round($cod_op_rev_ForCp,2).")"; 
                    echo $cod_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $cod_agg_rev_ForCp = $cod_agg_rev * $codWatchep/100; 
                    echo "(".round($cod_agg_rev_ForCp,2).")"; 
                    echo $cod_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $cod_cp_rev_ForCp = $cod_cp_rev * $codWatchep/100; 
                    echo "(".round($cod_cp_rev_ForCp,2).")"; 
                    echo $cod_cp_rev; 
                    ?>
                    </td>
                </tr>
                @endif
                <?php }   ?>
                <?php }   ?>
                <?php
                // if(count($siyWatches)>0){
                    ?>
                <?php 
                $siyWatchesTotal =0;
                foreach ($siyWatches as $siyWatche) {$siyWatchesTotal = $siyWatchesTotal + $siyWatche['count'];}
              //  foreach ($siyWatches as $siyWatche) {  
                  ?>
                  @if(Auth::user()->roles->pluck('name')[0] == "Siyavula" && Auth::user()->is_cp)
                <tr >
                    <td >Siyavula</td>
                    <td >Siyavula</td>
                    <td >{{$siy_sub_count}}</td>
                    <td >{{$siyWatchesTotal}}</td>
                    <td >
                    <?php $siyWatchep = ($siyWatchesTotal>0)?100:0; echo round($siyWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $siy_sub_rev_ForCp = $siy_sub_rev * $siyWatchep/100; 
                        echo "(".round($siy_sub_rev_ForCp,2).")"; 
                        echo $siy_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $siy_sub_rev_ex_vat_ForCp = $siy_sub_rev_ex_vat * $siyWatchep/100; 
                    echo "(".round($siy_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $siy_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $siy_op_rev_ForCp = $siy_op_rev * $siyWatchep/100; 
                    echo "(".round($siy_op_rev_ForCp,2).")"; 
                    echo $siy_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $siy_agg_rev_ForCp = $siy_agg_rev * $siyWatchep/100; 
                    echo "(".round($siy_agg_rev_ForCp,2).")"; 
                    echo $siy_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $siy_cp_rev_ForCp = $siy_cp_rev * $siyWatchep/100; 
                    echo "(".round($siy_cp_rev_ForCp,2).")"; 
                    echo $siy_cp_rev; 
                    //currentCPPayment
                    $currentCPPayment[] = array('category' => 'Siyavula',
                    'provider' => 'Siyavula',
                    'cprevenue' => $siy_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                @endif
                <?php //}   ?>
                <?php //}   ?>
                
        </tbody>
    </table>
  </div>  
@endif

    @if(!Auth::user()->is_cp)
  <div class="table-responsive">
    <table id="example" class="table table-bordered mb-5">
        <thead>
            <tr >
                <th >Category</th>
                <th >Provider</th>
                <th >Subscription</th>
                <th >Watches</th>
                <th >Watches (%)</th>
                <th >Subs Revenue (Incl VAT)</th>
                <th >Subs Revenue (Excl VAT)</th>
                <th >Billing ID</th>
                <th >Operator</th>
                <th >Quantity Successfully Billed</th>
                <th >Quantity Failed</th>
                <th >Gross Revenue (VAT EX)</th>
                <th >Operator Revenue</th>
                <th >Aggregator Revenue</th>
                <th >CP Revenue</th>
            </tr>   
        </thead>
        <tbody>
                <tr >
                    <td >Video on Demand</td>
                    <td ></td>
                    <td >{{$vod_sub_count}}</td>
                    <td >0</td>
                    <td >0</td>
                    <td >{{ $vod_sub_rev }}</td>
                    <td >{{ $vod_sub_rev_ex_vat }}</td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >{{ $vod_op_rev }}</td>
                    <td >{{ $vod_agg_rev }}</td>
                    <td >(0){{ $vod_cp_rev }}
                        <?php 
                        //currentCPPayment
                        $currentCPPayment[] = array('category' => 'Video on Demand',
                        'provider' => 'vod',
                        'cprevenue' => $vod_cp_rev);

                        ?>
                    </td>
                </tr>
                <tr >
                    <td >Eros Now</td>
                    <td >Erosnow</td>
                    <td >{{$ero_sub_count}}</td>
                    <td >{{$erosnowWatches}}</td>
                    <td >
                    </td>
                    <td >
                    <?php $eroWatchep = 100; //echo round($eroWatchep,2); ?>
                        <?php 
                        $ero_sub_rev_ForCp = $ero_sub_rev * $eroWatchep/100; 
                        echo "(".round($ero_sub_rev_ForCp,2).")"; 
                        echo $ero_sub_rev; 
                        ?></td>
                    <td >
                    <?php 
                    $ero_sub_rev_ex_vat_ForCp = $ero_sub_rev_ex_vat * $eroWatchep/100; 
                    echo "(".round($ero_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $ero_sub_rev_ex_vat; 
                    ?></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $ero_op_rev_ForCp = $ero_op_rev * $eroWatchep/100; 
                    echo "(".round($ero_op_rev_ForCp,2).")"; 
                    echo $ero_op_rev; 
                    ?></td>
                    <td >
                    <?php 
                    $ero_agg_rev_ForCp = $ero_agg_rev * $eroWatchep/100; 
                    echo "(".round($ero_agg_rev_ForCp,2).")"; 
                    echo $ero_agg_rev; 
                    ?></td>
                    <td >
                    <?php 
                    $ero_cp_rev_ForCp = $ero_cp_rev * $eroWatchep/100; 
                    echo "(".round($ero_cp_rev_ForCp,2).")"; 
                    echo $ero_cp_rev; 
                    //currentCPPayment
                    $currentCPPayment[] = array('category' => 'Eros Now',
                    'provider' => 'Erosnow',
                    'cprevenue' => $ero_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                <!-- Games With Watches  -->
                <?php 
                if(count($gameWatches)>0){
                $gameWatchesTotal =0;
                foreach ($gameWatches as $gameWatche) {$gameWatchesTotal = $gameWatchesTotal + $gameWatche['count'];}
                foreach ($gameWatches as $gameWatche) {  ?>
                <tr >
                    <td >Games</td>
                    <td >{{$gameWatche['provider']}}</td>
                    <td >{{$gam_sub_count}}</td>
                    <td >{{$gameWatche['count']}}</td>
                    <td >
                    <?php $gameWatchep = ($gameWatche['count']/$gameWatchesTotal)*100; echo round($gameWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $gam_sub_rev_ForCp = $gam_sub_rev * $gameWatchep/100; 
                        echo "(".round($gam_sub_rev_ForCp,2).")"; 
                        echo $gam_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $gam_sub_rev_ex_vat_ForCp = $gam_sub_rev_ex_vat * $gameWatchep/100; 
                    echo "(".round($gam_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $gam_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $gam_op_rev_ForCp = $gam_op_rev * $gameWatchep/100; 
                    echo "(".round($gam_op_rev_ForCp,2).")"; 
                    echo $gam_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $gam_agg_rev_ForCp = $gam_agg_rev * $gameWatchep/100; 
                    echo "(".round($gam_agg_rev_ForCp,2).")"; 
                    echo $gam_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $gam_cp_rev_ForCp = $gam_cp_rev * $gameWatchep/100; 
                    echo "(".round($gam_cp_rev_ForCp,2).")"; 
                    echo $gam_cp_rev; 
                    //currentCPPayment
                    $currentCPPayment[] = array('category' => 'Games',
                    'provider' => $gameWatche['provider'],
                    'cprevenue' => $gam_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                <?php 
                }  
                } ?>
                <!-- Games No Watches  -->
                <?php 
                if(count($gameWatches) == 0){
                $gameWatchesTotal =0;
                foreach ($gameWatches as $gameWatche) {$gameWatchesTotal = $gameWatchesTotal + $gameWatche['count'];}
               // foreach ($gameWatches as $gameWatche) {  ?>
                <tr >
                    <td >Games</td>
                    <td ></td>
                    <td >{{$gam_sub_count}}</td>
                    <td ></td>
                    <td >
                    <?php $gameWatchep = 0; echo round($gameWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $gam_sub_rev_ForCp = $gam_sub_rev * $gameWatchep/100; 
                        echo "(".round($gam_sub_rev_ForCp,2).")"; 
                        echo $gam_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $gam_sub_rev_ex_vat_ForCp = $gam_sub_rev_ex_vat * $gameWatchep/100; 
                    echo "(".round($gam_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $gam_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $gam_op_rev_ForCp = $gam_op_rev * $gameWatchep/100; 
                    echo "(".round($gam_op_rev_ForCp,2).")"; 
                    echo $gam_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $gam_agg_rev_ForCp = $gam_agg_rev * $gameWatchep/100; 
                    echo "(".round($gam_agg_rev_ForCp,2).")"; 
                    echo $gam_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $gam_cp_rev_ForCp = $gam_cp_rev * $gameWatchep/100; 
                    echo "(".round($gam_cp_rev_ForCp,2).")"; 
                    echo $gam_cp_rev; 
                    //currentCPPayment
                    // $currentCPPayment[] = array('category' => 'Games',
                    // 'provider' => $gameWatche['provider'],
                    // 'cprevenue' => $gam_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                <?php 
               // }  
                } ?>

                <!-- Kids -->
                <?php if(count($kidWatches)>0){ ?>
                <?php 
                $kidWatchesTotal =0;
                foreach ($kidWatches as $kidWatche) {$kidWatchesTotal = $kidWatchesTotal + $kidWatche['count'];}
                foreach ($kidWatches as $kidWatche) {  
                   ?>
                <tr >
                    <td >Kids</td>
                    <td >{{$kidWatche['provider']}}</td>
                    <td >{{$kid_sub_count}}</td>
                    <td >{{$kidWatche['count']}}</td>
                    <td >
                    <?php $kidWatchep = ($kidWatche['count']/$kidWatchesTotal)*100; echo round($kidWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $kid_sub_rev_ForCp = $kid_sub_rev * $kidWatchep/100; 
                        echo "(".round($kid_sub_rev_ForCp,2).")"; 
                        echo $kid_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $kid_sub_rev_ex_vat_ForCp = $kid_sub_rev_ex_vat * $kidWatchep/100; 
                    echo "(".round($kid_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $kid_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $kid_op_rev_ForCp = $kid_op_rev * $kidWatchep/100; 
                    echo "(".round($kid_op_rev_ForCp,2).")"; 
                    echo $kid_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $kid_agg_rev_ForCp = $kid_agg_rev * $kidWatchep/100; 
                    echo "(".round($kid_agg_rev_ForCp,2).")"; 
                    echo $kid_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $kid_cp_rev_ForCp = $kid_cp_rev * $kidWatchep/100; 
                    echo "(".round($kid_cp_rev_ForCp,2).")"; 
                    echo $kid_cp_rev; 
                    //currentCPPayment
                    $currentCPPayment[] = array('category' => 'Kids',
                    'provider' => $kidWatche['provider'],
                    'cprevenue' => $kid_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                <?php }   ?>
                <?php }   ?>
                <!-- Kids -->
                <?php if(count($kidWatches) == 0){ ?>
                <?php 
                $kidWatchesTotal =0;
                foreach ($kidWatches as $kidWatche) {$kidWatchesTotal = $kidWatchesTotal + $kidWatche['count'];}
               // foreach ($kidWatches as $kidWatche) {  
                   ?>
                <tr >
                    <td >Kids</td>
                    <td ></td>
                    <td >{{$kid_sub_count}}</td>
                    <td ></td>
                    <td >
                    <?php $kidWatchep = 0; echo round($kidWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $kid_sub_rev_ForCp = $kid_sub_rev * $kidWatchep/100; 
                        echo "(".round($kid_sub_rev_ForCp,2).")"; 
                        echo $kid_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $kid_sub_rev_ex_vat_ForCp = $kid_sub_rev_ex_vat * $kidWatchep/100; 
                    echo "(".round($kid_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $kid_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $kid_op_rev_ForCp = $kid_op_rev * $kidWatchep/100; 
                    echo "(".round($kid_op_rev_ForCp,2).")"; 
                    echo $kid_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $kid_agg_rev_ForCp = $kid_agg_rev * $kidWatchep/100; 
                    echo "(".round($kid_agg_rev_ForCp,2).")"; 
                    echo $kid_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $kid_cp_rev_ForCp = $kid_cp_rev * $kidWatchep/100; 
                    echo "(".round($kid_cp_rev_ForCp,2).")"; 
                    echo $kid_cp_rev; 
                    //currentCPPayment
                    // $currentCPPayment[] = array('category' => 'Kids',
                    // 'provider' => $kidWatche['provider'],
                    // 'cprevenue' => $kid_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                <?php //}   ?>
                <?php }   ?>
                <!-- Fun -->
                <?php if(count($funWatches)>0){ ?>
                <?php 
                $funWatchesTotal =0;
                foreach ($funWatches as $funWatche) {$funWatchesTotal = $funWatchesTotal + $funWatche['count'];}
                foreach ($funWatches as $funWatche) {  ?>
                <tr >
                    <td >Fun & Learning</td>
                    <td >{{$funWatche['provider']}}</td>
                    <td >{{$fun_sub_count}}</td>
                    <td >{{$funWatche['count']}}</td>
                    <td >
                    <?php $funWatchep = ($funWatche['count']/$funWatchesTotal)*100; echo round($funWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $fun_sub_rev_ForCp = $fun_sub_rev * $funWatchep/100; 
                        echo "(".round($fun_sub_rev_ForCp,2).")"; 
                        echo $fun_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $fun_sub_rev_ex_vat_ForCp = $fun_sub_rev_ex_vat * $funWatchep/100; 
                    echo "(".round($fun_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $fun_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $fun_op_rev_ForCp = $fun_op_rev * $funWatchep/100; 
                    echo "(".round($fun_op_rev_ForCp,2).")"; 
                    echo $fun_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $fun_agg_rev_ForCp = $fun_agg_rev * $funWatchep/100; 
                    echo "(".round($fun_agg_rev_ForCp,2).")"; 
                    echo $fun_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $fun_cp_rev_ForCp = $fun_cp_rev * $funWatchep/100; 
                    echo "(".round($fun_cp_rev_ForCp,2).")"; 
                    echo $fun_cp_rev; 
                    //currentCPPayment
                    $currentCPPayment[] = array('category' => 'Fun & Learning',
                    'provider' => 'Netsport',
                    'cprevenue' => $fun_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                <?php }   ?>
                <?php }   ?>
                
                <!-- Fun -->
                <?php if(count($funWatches) == 0){ ?>
                <?php 
                $funWatchesTotal =0;
                foreach ($funWatches as $funWatche) {$funWatchesTotal = $funWatchesTotal + $funWatche['count'];}
                //foreach ($funWatches as $funWatche) {  ?>
                <tr >
                    <td >Fun & Learning</td>
                    <td ></td>
                    <td >{{$fun_sub_count}}</td>
                    <td ></td>
                    <td >
                    <?php $funWatchep = 0; echo round($funWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $fun_sub_rev_ForCp = $fun_sub_rev * $funWatchep/100; 
                        echo "(".round($fun_sub_rev_ForCp,2).")"; 
                        echo $fun_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $fun_sub_rev_ex_vat_ForCp = $fun_sub_rev_ex_vat * $funWatchep/100; 
                    echo "(".round($fun_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $fun_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $fun_op_rev_ForCp = $fun_op_rev * $funWatchep/100; 
                    echo "(".round($fun_op_rev_ForCp,2).")"; 
                    echo $fun_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $fun_agg_rev_ForCp = $fun_agg_rev * $funWatchep/100; 
                    echo "(".round($fun_agg_rev_ForCp,2).")"; 
                    echo $fun_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $fun_cp_rev_ForCp = $fun_cp_rev * $funWatchep/100; 
                    echo "(".round($fun_cp_rev_ForCp,2).")"; 
                    echo $fun_cp_rev; 
                    //currentCPPayment
                    // $currentCPPayment[] = array('category' => 'Fun & Learning',
                    // 'provider' => 'Netsport',
                    // 'cprevenue' => $fun_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                <?php //}   ?>
                <?php }   ?>
                <?php if(count($higWatches)>0){ 
                    ?>
                <?php 
                $higWatchesTotal =0;
                foreach ($higWatches as $higWatche) {$higWatchesTotal = $higWatchesTotal + $higWatche['count'];}
                foreach ($higWatches as $higWatche) {  ?>
                <tr >
                    <td >Higher Learning</td>
                    <td >{{$higWatche['provider']}}</td>
                    <td >{{$hig_sub_count}}</td>
                    <td >{{$higWatche['count']}}</td>
                    <td >
                    </td>
                    <td >
                    <?php $higWatchep = ($higWatche['count'] / $higWatchesTotal) * 100; //echo round($higWatchep,2); ?>
                        <?php 
                        $hig_sub_rev_ForCp = $hig_sub_rev * $higWatchep/100; 
                        echo "(".round($hig_sub_rev_ForCp,2).")"; 
                        echo $hig_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $hig_sub_rev_ex_vat_ForCp = $hig_sub_rev_ex_vat * $higWatchep/100; 
                    echo "(".round($hig_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $hig_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $hig_op_rev_ForCp = $hig_op_rev * $higWatchep/100; 
                    echo "(".round($hig_op_rev_ForCp,2).")"; 
                    echo $hig_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $hig_agg_rev_ForCp = $hig_agg_rev * $higWatchep/100; 
                    echo "(".round($hig_agg_rev_ForCp,2).")"; 
                    echo $hig_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $hig_cp_rev_ForCp = $hig_cp_rev * $higWatchep/100; 
                    echo "(".round($hig_cp_rev_ForCp,2).")"; 
                    echo $hig_cp_rev; 
                    ?>
                    </td>
                </tr>
                <?php }   ?>
                <?php }   ?>
                <?php if(count($codWatches)>0){  ?>
                <?php 
                $codWatchesTotal =0;
                foreach ($codWatches as $codWatche) {$codWatchesTotal = $codWatchesTotal + $codWatche['count'];}
                foreach ($codWatches as $codWatche) {  ?>
                <tr >
                    <td >Coding</td>
                    <td >{{$codWatche['provider']}}</td>
                    <td >{{$cod_sub_count}}</td>
                    <td >{{$codWatche['count']}}</td>
                    <td >
                    <?php $codWatchep = 100; echo round($codWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $cod_sub_rev_ForCp = $cod_sub_rev * $codWatchep/100; 
                        echo "(".round($cod_sub_rev_ForCp,2).")"; 
                        echo $cod_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $cod_sub_rev_ex_vat_ForCp = $cod_sub_rev_ex_vat * $codWatchep/100; 
                    echo "(".round($cod_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $cod_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $cod_op_rev_ForCp = $cod_op_rev * $codWatchep/100; 
                    echo "(".round($cod_op_rev_ForCp,2).")"; 
                    echo $cod_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $cod_agg_rev_ForCp = $cod_agg_rev * $codWatchep/100; 
                    echo "(".round($cod_agg_rev_ForCp,2).")"; 
                    echo $cod_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $cod_cp_rev_ForCp = $cod_cp_rev * $codWatchep/100; 
                    echo "(".round($cod_cp_rev_ForCp,2).")"; 
                    echo $cod_cp_rev; 
                    ?>
                    </td>
                </tr>
                <?php }   ?>
                <?php }   ?>
                <?php
                // if(count($siyWatches)>0){
                    ?>
                <?php 
                $siyWatchesTotal =0;
                foreach ($siyWatches as $siyWatche) {$siyWatchesTotal = $siyWatchesTotal + $siyWatche['count'];}
              //  foreach ($siyWatches as $siyWatche) {  
                  ?>
                <tr >
                    <td >Siyavula</td>
                    <td >Siyavula</td>
                    <td >{{$siy_sub_count}}</td>
                    <td >{{$siyWatchesTotal}}</td>
                    <td >
                    <?php $siyWatchep = ($siyWatchesTotal>0)?100:0; echo round($siyWatchep,2); ?>
                    </td>
                    <td >
                        <?php 
                        $siy_sub_rev_ForCp = $siy_sub_rev * $siyWatchep/100; 
                        echo "(".round($siy_sub_rev_ForCp,2).")"; 
                        echo $siy_sub_rev; 
                        ?>
                    </td>
                    <td >
                    <?php 
                    $siy_sub_rev_ex_vat_ForCp = $siy_sub_rev_ex_vat * $siyWatchep/100; 
                    echo "(".round($siy_sub_rev_ex_vat_ForCp,2).")"; 
                    echo $siy_sub_rev_ex_vat; 
                    ?>
                    </td>
                    </td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >
                    <?php 
                    $siy_op_rev_ForCp = $siy_op_rev * $siyWatchep/100; 
                    echo "(".round($siy_op_rev_ForCp,2).")"; 
                    echo $siy_op_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $siy_agg_rev_ForCp = $siy_agg_rev * $siyWatchep/100; 
                    echo "(".round($siy_agg_rev_ForCp,2).")"; 
                    echo $siy_agg_rev; 
                    ?>
                    </td>
                    <td >
                    <?php 
                    $siy_cp_rev_ForCp = $siy_cp_rev * $siyWatchep/100; 
                    echo "(".round($siy_cp_rev_ForCp,2).")"; 
                    echo $siy_cp_rev; 
                    //currentCPPayment
                    $currentCPPayment[] = array('category' => 'Siyavula',
                    'provider' => 'Siyavula',
                    'cprevenue' => $siy_cp_rev_ForCp);
                    ?>
                    </td>
                </tr>
                <?php //}   ?>
                <?php //}   ?>
                
            <tr >
                <td >Total</td>   
                <td ></td>
                <td >{{$total_sub_count}}</td>
                <td ></td> 
                <td ></td>
                <td >{{ $total_sub_rev }}</td>
                <td >{{ $total_sub_rev_ex_vat }}</td>
                <td ></td> 
                <td ></td> 
                <td ></td>   
                <td ></td>  
                <td ></td> 
                <td >{{ $total_op_rev }}</td> 
                <td >{{ $total_agg_rev }}</td> 
                <td >{{ $total_cp_rev }}</td> 
            </tr><!-- comment -->
        </tbody>
    </table>
  </div>  
    <hr>
    <br>
    <br>
    <br>
    <h6>GROSS MARGINS BREAKDOWN/REVENUE SHARE</h6>
    <div class="table-responsive">
    <table id="example2" class="table table-bordered mb-5">
        <thead>        
            <tr >
                <th >Current Rev Share</th>
                <th ></th>
                <th >%</th>
            </tr>  
        </thead>
        <tbody>
            <tr>
                <td >Subscription Revenue ex VAT</td>
                <td ></td>
                <td >{{ $total_sub_rev_ex_vat }} </td>
            </tr>
            <tr >
                <td >Operator Share</td>
                <td >40 %</td>
                <td > {{ $total_op_rev }} </td>
            </tr>
            <tr>
                <td >DM 333 Net Rev</td>
                <td ><?php $dn333netrev = 0; $dn333netrev = $total_sub_rev_ex_vat-$total_op_rev; ?></td>
                <td >  {{ $dn333netrev }} </td>
            </tr>
            <tr >
                <td >Aggregator</td>
                <td >10%</td>
                <td > {{ $dn333netrev * .1 }} </td>
            </tr>
            <tr>
                <td >Marketing </td>
                <td >16%</td>
                <td > {{ $dn333netrev *.16 }}  </td>
            </tr>
            <tr >
                <td >DM333 Margin 1</td>
                <td ></td>
                <td > {{ $total_cp_rev }} </td>
            </tr>
            <tr >
                <td >Current CP payment</td>
                <td >50%</td>
                <td > {{ $total_cp_rev *.5 }} </td>
            </tr>
            @foreach($currentCPPayment as $currentPayment)
            <tr >
                <td >{{$currentPayment['category']}}-{{$currentPayment['provider']}}</td>
                <td ></td>
                <td >{{round($currentPayment['cprevenue'],2)}}</td>
            </tr>
            @endforeach
            <tr >
                <td >DM333 Margin 2</td>
                <td >50%</td>
                <td > {{ $total_cp_rev *.5 }} </td>
            </tr>
        </tbody>
    </table>
</div>
@endif
</div>
@endsection
@push('js-links')
<!-- Data Tables -->
<script src="https://cdn.datatables.net/1.11.2/js/jquery.dataTables.min.js"></script>
<!-- <script src="https://cdn.datatables.net/1.11.2/js/dataTables.bootstrap4.min.js"></script> -->
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script>
<!-- Data Tables -->
<!-- Date Picker -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- Date Picker -->
@endpush
@section('js-content')
<script>
    $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            paging: false, info: false, "aaSorting": [],
            buttons: [
            {
                extend: 'excel',
                text: 'Export Results',
                className: 'btn btn-default',
                title: ''
            }
            ]
        } );
    } );
</script>
<script>
    $(document).ready(function() {
        $('#example2').DataTable( {
            dom: 'Bfrtip',
            paging: false, info: false, "aaSorting": [],
            buttons: [
            {
                extend: 'excel',
                text: 'Export Results',
                className: 'btn btn-default',
                title: ''
            }
            ]
        } );
    } );
</script>
<script>
    $(document).ready(function() {
        $('#cp-table').DataTable( {
            dom: 'Bfrtip',
            paging: false, info: false, "aaSorting": [],
            buttons: [
            {
                extend: 'excel',
                text: 'Export Results',
                className: 'btn btn-default',
                title: ''
            }
            ]
        } );
    } );
</script>
<script>
$(document).ready(function(){
    $(".custom-date").hide();
    $("#reportFrom").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            $("#startDate").val("");
            $("#endDate").val("");
            if(optionValue == "custom"){
                $(".custom-date").show();
            } else{
                $(".custom-date").hide();
            }
        });
    }).change();
});
  $( function() {
    $( "#startDate" ).datepicker({
      dateFormat: "yy-mm-dd",
        onSelect: function(selected) {
          $("#endDate").datepicker("option","minDate", selected)
        }
    });
    $( "#startDate" ).datepicker("setDate","{!! Request::get('startDate') !!}");
  } );
  $( function() {
    $( "#endDate" ).datepicker({
      dateFormat: "yy-mm-dd",
        onSelect: function(selected) {
           $("#startDate").datepicker("option","maxDate", selected)
        }
    });
    $( "#endDate" ).datepicker("setDate","{!! Request::get('endDate') !!}");
  } );
</script>
@endsection
