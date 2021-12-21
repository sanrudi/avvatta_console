@extends('layout.template')
@push('css-links')
    <!-- Date Picker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Date Picker -->
@endpush
@section('content')
    <div class="container">
        <h6>User Activity Report</h6><hr>
        <form autocomplete="off" action="" method="GET">
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
                <input id="startDate" name="startDate" type="text" class="form-control" value="" placeholder="yyyy-mm-dd" />
                </div>
                <div class="form-group col-md-2 custom-date">
                <label for="endDate">End Date</label>
                <input id="endDate" name="endDate" type="text" class="form-control"  value="" placeholder="yyyy-mm-dd" />
                </div>
                <div class="form-group col-md-2">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-control">
                        <option value="" selected="selected">Select</option>
                        <option value="erosnow" @if(Request::get('type') == "erosnow") selected="selected" @endif >Erosnow</option>
                        <option value="kids" @if(Request::get('type') == "kids") selected="selected" @endif>Kids</option>
                        <option value="game" @if(Request::get('type') == "game") selected="selected" @endif>Games</option>
                    </select>
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
                <div class="form-group col-md-2">
                    <label for="export">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-success"  name="export" value="Export Excel"  formtarget="_blank">Export Excel</button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered mb-5">
                <thead>
                <tr class="table-success">
                    <th scope="col">User</th>
                    <th scope="col">Content</th>
                    <th scope="col">Activity Type</th>
                    <th scope="col">Activity</th>
                    <th scope="col">Date</th>
                    <th scope="col">Day</th>
                    <th scope="col">Device</th>
                    <th scope="col">OS</th>
                    <th scope="col">Age</th>
                </tr>
                </thead>
                <tbody>
                @foreach($user_contents as $key => $data)
                    <tr>
                        <td>{{ $data['user_name'] }}</td>
                        <td>{{ $data['content_name'] }}</td>
                        <td>{{ ucwords($data['type']) }}</td>
                        <td>{{ $data['action'] }}</td>
                        <td>{{ $data['date_time'] }}</td>
                        <td>{{ date('D', strtotime($data['date_time'])) }}</td>
                        <td>{{ $data['device'] }}</td>
                        <td>{{ $data['os'] }}</td>
                        <td>{{ $data['age'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
        {!! $userPageData->withQueryString()->links() !!}
        </div>
    </div>
@endsection
@push('js-links')
    <!-- Date Picker -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Date Picker -->
@endpush
@section('js-content')
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
