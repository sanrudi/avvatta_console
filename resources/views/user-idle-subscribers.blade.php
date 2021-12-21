@extends('layout.template')
@push('css-links')
    <!-- Date Picker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Date Picker -->
@endpush
@section('content')
<div class="container">
<h6>Subscribers activity @include('report-for-date-no-default')</h6><hr>
     <form autocomplete="off" action="" method="GET">
      <div class="form-row">
        <div class="form-group col-md-2">
          <label>Report From</label>
          <select name="reportFrom" id="reportFrom" class="form-control">
            <option value="" @if(Request::get('reportFrom') == "") selected="selected" @endif >Select</option>
            <option value="7" @if(Request::get('reportFrom') == "7") selected="selected" @endif >Last 7 Days</option>
            <option value="14" @if(Request::get('reportFrom') == "14") selected="selected" @endif>Last 14 Days</option>
            <option value="30" @if(Request::get('reportFrom') == "30") selected="selected" @endif>Last 30 Days</option>
            <option value="90" @if(Request::get('reportFrom') == "90") selected="selected" @endif>Last 90 Days</option>
            <!-- <option value="custom" @if(Request::get('reportFrom') == "custom") selected="selected" @endif>Custom</option> -->
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
        <!-- <div class="form-group col-md-2">
          <label for="export">&nbsp;</label>
          <button type="submit" class="form-control btn btn-success"  name="export" value="Export Excel"  formtarget="_blank">Export Excel</button>
        </div> -->
      </div>
    </form>
    <hr>
    <div class="table-responsive">
    <table class="table table-bordered mb-5">
      <thead>
        <tr class="table-success">
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Mobile</th>
          <th scope="col">No Active Days</th>
          <th scope="col">Last Active</th>
        </tr>
      </thead>
      <tbody>
        @foreach($transactions as $key => $log)
        <tr>
          <td>{{isset($log->user_payments_avvatta_users->firstname)?$log->user_payments_avvatta_users->firstname:''}} {{isset($log->user_payments_avvatta_users->lastname)?$log->user_payments_avvatta_users->lastname:''}}</td>
          <td>{{isset($log->user_payments_avvatta_users->email)?$log->user_payments_avvatta_users->email:''}}</td>
          <td>{{isset($log->user_payments_avvatta_users->mobile)?$log->user_payments_avvatta_users->mobile:''}}</td>
          <td>{{ $log->idleDays ?? '-' }}</td>   
          <td>{{ $log->date_time ?? 'No record' }}</td>  
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>  
    <div class="d-flex justify-content-center mt-4">
      {!! $transactions->withQueryString()->links() !!}
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
</script>
<script>
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