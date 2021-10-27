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
@section('title', '| Top Repeated Watched Videos')
@section('content')
<div class="container">
<h6>Top Repeated Watched Videos - Report @include('report-for-date')</h6><hr>
<form action="" method="GET">
      <div class="form-row">
      <div class="form-group col-md-2">
                    <label for="reportFrom">Report From</label>
                    <select name="reportFrom" id="reportFrom" class="form-control">
                        <option value="7" @if(Request::get('reportFrom') == "" || Request::get('reportFrom') == "7") selected="selected" @endif >Last 7 Days</option>
                        <option value="14" @if(Request::get('reportFrom') == "14") selected="selected" @endif>Last 14 Days</option>
                        <option value="30" @if(Request::get('reportFrom') == "30") selected="selected" @endif>Last 30 Days</option>
                        <option value="90" @if(Request::get('reportFrom') == "90") selected="selected" @endif>Last 90 Days</option>
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
                    <label for="device">Device Type</label>
                    <select name="device" id="device" class="form-control">
                        <option value="" @if(Request::get('device') == "") selected="selected" @endif >All</option>
                        <option value="desktop" @if(Request::get('device') == "desktop") selected="selected" @endif>Desktop</option>
                        <option value="mobile" @if(Request::get('device') == "mobile") selected="selected" @endif>Mobile</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="os">OS</label>
                    <select name="os" id="os" class="form-control">
                        <option value="" @if(Request::get('os') == "") selected="selected" @endif >All</option>
                        <option value="windows" @if(Request::get('os') == "windows") selected="selected" @endif>Windows</option>
                        <option value="ios" @if(Request::get('os') == "ios") selected="selected" @endif>IOS</option>
                        <option value="android" @if(Request::get('os') == "android") selected="selected" @endif>Android</option>
                    </select>
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
        <th>Category</th>
        <th>Sub Category</th>
        <th>Watches</th>
        <th>User</th>
        <th>Provider</th>
        <th>Device Type</th>
        <th>OS</th>
    </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
          <td>
            @if($log->loggable_type == "App\Models\VideoContent")
            {{$log->loggable->content_name}}
            @endif
            @if($log->loggable_type == "App\Models\AvErosNows")
            {!! isset($log->erosnow->title) ? $log->erosnow->title : '' !!}
            @endif
            </td>
            <td>
            @if($log->loggable_type == "App\Models\VideoContent")
            {{$log->loggable->video_category->name}}
            @endif
            @if($log->loggable_type == "App\Models\AvErosNows")
            Erosnow
            @endif
          </td>
            <td>
            @if($log->loggable_type == "App\Models\VideoContent")
            {{$log->loggable->video_sub_category->name}}
            @endif
            @if($log->loggable_type == "App\Models\AvErosNows")
            Erosnow
            @endif</td>
            <td>{{$log->count}}</td>
            <td>
              @if(!empty($log->avvatta_user->firstname) && !empty($log->avvatta_user->lastname))
              {{$log->avvatta_user->firstname}}{{$log->avvatta_user->lastname}}
              @elseif(!empty($log->avvatta_user->email))
              {{$log->avvatta_user->email}}
              @elseif(!empty($log->avvatta_user->mobile))
              {{$log->avvatta_user->mobile}}
              @endif
            </td>
            <td>
            @if($log->loggable_type == "App\Models\VideoContent")
            {{$log->loggable->owner}}
            @endif
            @if($log->loggable_type == "App\Models\AvErosNows")
            Erosnow
            @endif
            </td>
            <td>{{$log->device}}</td>
            <td>{{$log->os}}</td>
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
    <!-- Date Picker -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Date Picker -->
@endpush
@section('js-content')
<script>
$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        info: false, pageLength: 10, "aaSorting": [],language: {search: "Filter records:"},
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
