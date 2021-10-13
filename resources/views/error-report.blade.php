@extends('layout.template')
@push('css-links')
    <!-- Date Picker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Date Picker -->
@endpush
@section('content')
    <div class="container">
        <h6>Error Report</h6><hr>
        <!-- <form action="" method="GET">
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
                    <label for="page">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-info" name="page" value="Generate">Generate</button>
                </div>
                <div class="form-group col-md-2">
                    <label for="export">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-success"  name="export" value="Export Excel"  formtarget="_blank">Export Excel</button>
                </div>
            </div>
        </form> -->
        <div class="table-responsive">
            <table class="table table-bordered mb-5">
                <thead>
                <tr class="table-success">
                    <th scope="col">Date Time</th>
                    <th scope="col">Status</th>
                    <th scope="col">Status Text</th>
                    <th scope="col">URL</th>
                    <th scope="col">Message</th>
                    <th scope="col">Error</th>
                </tr>
                </thead>
                <tbody>
                @foreach($errorReport as $key => $data)
                    <tr>
                        <td>{{ $data['date_time'] }}</td>
                        <td>{{ $data['status'] }}</td>
                        <td>{{ $data['statusText'] }}</td>
                        <td>{{ $data['url'] }}</td>
                        <td>{{ $data['message'] }}</td>
                        <td>{{ $data['error'] }}</td>
                    </tr>
                @endforeach
                @if(count($errorReport) == 0)
                <tr>
                    <td colspan="6"  style="text-align:center;">No Record Found</td>
                </tr>
                @endif
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
        {!! $errorReport->withQueryString()->links() !!}
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
