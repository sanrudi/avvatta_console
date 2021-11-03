@extends('layout.template')
@push('css-links')
    <!-- Date Picker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Date Picker -->
@endpush
@section('content')
<div class="container">
<h6>Game Article Report @include('report-for-date')</h6><hr>
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
        @if(Auth::user()->is_cp == 0)
        <div class="form-group col-md-2">
            <label for="provider">Provider</label>
            <select name="provider" id="provider" class="form-control">
                <option value="" @if(Request::get('provider') == "") selected="selected" @endif >All</option>
                <option value="gogames" @if(Request::get('provider') == "gogames") selected="selected" @endif>gogames</option>
                <option value="gamepix" @if(Request::get('provider') == "gamepix") selected="selected" @endif>gamepix</option>
            </select>
        </div>
        @endif
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
          <!-- <th scope="col">ContentID</th> -->
          <th scope="col">Article</th>
          <th scope="col">Category</th>
          <th scope="col">Provider</th>
          <th scope="col">Watches</th>
          <th scope="col">Unique Watches</th>
          <th scope="col">Favourite</th>
          <th scope="col">Average</th>
          <th scope="col">Added Date</th>
          <th scope="col">Duration</th>
        </tr>
      </thead>
      <tbody>
        @foreach($gameArticles as $key => $data)
        <tr>
          <!-- <td>{{ $data->content_id }}</td> -->
          <td>{{ $data->article }}</td>
          <td>{{ $data->category }}</td>
          <td>{{ $data->provider }}</td>
          <td>{{ count($data->watches) }}</td>
          <td>{{ count($data->unique_watches) }}</td>
          <td>{{ count($data->wishlist) }}</td>
          <td>{{ $data->avg }}</td>
          <td>{{ $data->added_at }}</td>
          <td>{{ $data->duration }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>  
    <div class="d-flex justify-content-center mt-4">
      {!! $gameArticles->withQueryString()->links() !!}
    </div>
  </div>
@endsection
@push('js-links')
<!-- Date Picker -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
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