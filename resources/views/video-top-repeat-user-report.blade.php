@extends('layout.template')
@push('css-links')
    <!-- Data Tables -->
    <link href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!-- Data Tables -->
@endpush
@section('content')
<div class="container">
<h6>Top Repeated Watched Videos - Report</h6><hr>
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
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
<thead>
    <tr>
        <th>Title</th>
        <th>Watches</th>
        <th>User</th>
    </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
            @if($log->loggable_type == "App\Models\VideoContent")
            <td>{{$log->loggable->content_name}}</td>
            @endif
            @if($log->loggable_type == "App\Models\AvErosNows")
            <td>{!! isset($log->erosnow->title) ? $log->erosnow->title : '' !!}</td>
            @endif
            <td>{{$log->count}}</td>
            <td>{{$log->avvatta_user->firstname.' '.$log->avvatta_user->lastname}}</td>
        </tr>
        @endforeach 
    </tbody>
</table>
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
