@extends('layout.template')
@push('css-links')
    <!-- Date Picker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Date Picker -->
@endpush
@section('content')
<div class="container">
<h6>Daily Transaction Report @include('report-for-date-no-default')</h6><hr>
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
          <label for="search">Search</label>
          <input id="search" name="search" type="text" class="form-control"  value="{!! Request::get('search') !!}" placeholder="type customer name .. " />
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
    <hr>
    <div class="table-responsive">
    <table class="table table-bordered mb-5">
      <thead>
        <tr class="table-success">
          <th scope="col">Date</th>
          <th scope="col">Customer</th>
          <th scope="col">Title</th>
          <th scope="col">Amount</th>
          <th scope="col">Is Renewal</th>
          <th scope="col">Mode</th>
        </tr>
      </thead>
      <tbody>
        @foreach($transactions as $key => $data)
        <tr>
          <td>{{ $data->created_at }}</td>
          <td>
            @if(!empty($data->user_payments_avvatta_users->firstname) && !empty($data->user_payments_avvatta_users->lastname))
            {{$data->user_payments_avvatta_users->firstname}}&nbsp;{{$data->user_payments_avvatta_users->lastname}}
            @elseif(!empty($data->user_payments_avvatta_users->email))
            {{$data->user_payments_avvatta_users->email}}
            @elseif(!empty($data->user_payments_avvatta_users->mobile))
            {{$data->user_payments_avvatta_users->mobile}}
            @endif
          </td>
          <td>{{ $data->user_payments_subscriptions->title ?? ''}}</td>
          <td>{{ $data->amount }}</td>
          <td>Is Renewal</td>
          <td>{{ $data->payment_mode }}</td>          
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