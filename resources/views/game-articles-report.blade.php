@extends('layout.template')
@section('content')
<div class="container">
<h6>Game Article Report</h6><hr>
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
          <th scope="col">#</th>
          <!-- <th scope="col">ContentID</th> -->
          <th scope="col">Article</th>
          <th scope="col">Category</th>
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
          <td scope="row">{{ $gameArticles->firstItem() + $key }}</td>
          <!-- <td>{{ $data->content_id }}</td> -->
          <td>{{ $data->article }}</td>
          <td>{{ $data->category }}</td>
          <td>{{ count($data->watches) }}</td>
          <td>{{ count($data->unique_watches) }}</td>
          <td>{{ count($data->wishlist) }}</td>
          <td>{{ $data->avg }}</td>
          <td>{{ $data->created_date }}</td>
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
@endpush
@section('js-content')
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