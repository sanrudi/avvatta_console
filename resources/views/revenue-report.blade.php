@extends('layout.template')
@push('css-links')
<!-- Data Tables -->
<link href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<!-- Data Tables -->
@endpush
@section('content')
<div class="container">
    <h6>Revenue - Report - Avvatta Revenue Report  
        From: <?php echo $all['fromdate']->format('d-M-Y'); ?>
        To: <?php echo $all['todate']->format('d-M-Y'); ?>
    </h6><hr>
    <form action="" method="GET">
        <div class="form-row">
            <div class="form-group col-md-2">
                <label for="reportFrom">Report From</label>
                <select name="reportFrom" id="reportFrom" class="form-control">
                    <option value="" @if(Request::get('reportFrom') == "") selected="selected" @endif >Select</option>
                    <option value="7" @if(Request::get('reportFrom') == "7") selected="selected" @endif >Last 7 Days</option>
                    <option value="14" @if(Request::get('reportFrom') == "14") selected="selected" @endif>Last 14 Days</option>
                    <option value="30" @if(Request::get('reportFrom') == "30") selected="selected" @endif>Last 30 Days</option>
                    <option value="90" @if(Request::get('reportFrom') == "90") selected="selected" @endif>Last 90 Days</option>
                    <option value="custom" @if(Request::get('reportFrom') == "custom") selected="selected" @endif>Custom</option>
                </select>
            </div>
            <div class="form-group col-md-2 custom-date">
                <label for="startDate">Start Date</label>
                <input id="startDate" name="startDate" type="date" class="form-control" value="{!! Request::get('startDate') !!}"/>
            </div>
            <div class="form-group col-md-2 custom-date">
                <label for="endDate">End Date</label>
                <input id="endDate" name="endDate" type="date" class="form-control" value="{!! Request::get('endDate') !!}" />
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
    <div class="table-responsive">
    <table id="example" class="table table-bordered mb-5">
        <thead>
            <tr >
                <th >Category</th>
                <th >Billing Frequency</th>
                <th >Consumer Price</th>
                <th >No. of Subscriptions</th>
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
            <?php
            foreach ($subs as $value) {  
                ?>
                <tr >
                    <td >{{ $value->name }}  {{ $value->title }}  </td>
                    <td ></td>
                    <td >{{ $value->amount }}</td>
                    <td >{{ $subcount[$value->id] }}</td>
                    <td >{{ $all[$value->id] }}</td>
                    <td >{{ $all[$value->id] * 0.85 }}</td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >{{ $all[$value->id] * 0.85 * .4 }}</td>
                    <td >{{ $all[$value->id] * 0.85 * .1 }}</td>
                    <td >{{  $all[$value->id] * 0.85 * .1 * .5 }}</td>
                </tr>
            <?php   } ?>
            <tr >
                <td >Total</td>   
                <td ></td>
                <td ></td> 
                <td >{{ $all['count'] }}</td>
                <td >{{ $all['total'] }}</td>
                <td >{{ $all['total'] * .85 }}</td>
                <td ></td> 
                <td ></td> 
                <td ></td>   
                <td ></td>  
                <td ></td> 
                <td >{{ $all['total'] * .85 *.4 }}</td> 
                <td >{{ $all['total'] * .85 *.1 }}</td> 
                <td >{{ $all['total'] * .85 *.9 *.5 }}</td> 
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
                <td >   {{ $all['total'] * .85 }} </td>
            </tr>
            <tr >
                <td >Operator Share</td>
                <td >40 %</td>
                <td > {{ $all['total'] * .85 *.4 }} </td>
            </tr>
            <tr>
                <td >DM 333 Net Rev</td>
                <td ></td>
                <td >  {{ $all['total'] * .85 *.6 }} </td>
            </tr>
            <tr >
                <td >Aggregator</td>
                <td >10%</td>
                <td > {{ $all['total'] * .85 *.6 *.1 }} </td>
            </tr>
            <tr>
                <td >Marketing </td>
                <td >16%</td>
                <td > {{ $all['total'] * .85 *.6 *.16 }}  </td>
            </tr>
            <tr >
                <td >DM333 Margin 1</td>
                <td ></td>
                <td > {{ $all['total'] * .85 *.6 *.74 }} </td>
            </tr>
            <tr >
                <td >Current CP payment</td>
                <td >50%</td>
                <td > {{ $all['total'] * .85 *.6 *.74 *.5 }} </td>
            </tr>
            <tr >
                <td >DM333 Margin 2</td>
                <td >50%</td>
                <td > {{ $all['total'] * .85 *.6 *.74 *.5 }} </td>
            </tr>
        </tbody>
    </table>
</div>

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
@endpush
@section('js-content')
<script>
    $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            searching: false, paging: false, info: false, "aaSorting": [],
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
            searching: false, paging: false, info: false, "aaSorting": [],
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
                console.log(optionValue);
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
</script>
@endsection
