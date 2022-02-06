@extends('layout.template')
@push('css-links')
<!-- Date Picker -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- Date Picker -->
<!-- Multi Date Picker -->
<link rel="stylesheet" href="{{ asset('assets/plugins/jquery-ui.multidatespicker.css') }}">
<!-- Multi Date Picker -->
@endpush
@section('content')
<div class="container">
  <h6>Eros Now Feed Report</h6><hr>
  <form autocomplete="off" action="" method="GET">
    <div class="form-row">
      <div class="form-group col-md-2">
        <label for="reportFrom">Report From</label>
        <select name="reportFrom" id="reportFrom" class="form-control">
          <option value="7" @if(Request::get('reportFrom') == "" || Request::get('reportFrom') == "7") selected="selected" @endif >Last 7 Days</option>
          <option value="14" @if(Request::get('reportFrom') == "14") selected="selected" @endif>Last 14 Days</option>
          <option value="30" @if(Request::get('reportFrom') == "30") selected="selected" @endif>Last 30 Days</option>
          <option value="90" @if(Request::get('reportFrom') == "90") selected="selected" @endif>Last 90 Days</option>
          <option value="custom" @if(Request::get('reportFrom') == "custom") selected="selected" @endif>Custom</option>
          <option value="multiple" @if(Request::get('reportFrom') == "multiple") selected="selected" @endif>Multiple</option>
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
      <div class="form-group col-md-2 multiple-date">
        <label for="multiDate">Multiple Date</label>
        <input id="multiDate" name="multiDate" type="text" class="form-control"  value="{{ Request::get('multiDate') }}" placeholder="yyyy-mm-dd" />
      </div>
      <div class="form-group col-md-2">
        <label for="page">&nbsp;</label>
        <button type="submit" class="form-control btn btn-info" name="page" value="Generate">Generate</button>
      </div>
    </div>
  </form>
  <div class="table-responsive">
    <table class="table table-bordered mb-5">
      <thead>
        <tr class="table-success">
          <th scope="col">ID</th>
          <th scope="col">Date Time</th>
          <th scope="col">Status</th>
          <th scope="col">URL</th>
          <th scope="col">Message</th>
        </tr>
      </thead>
      <tbody>
        @foreach($errorReport as $key => $data)
        <tr>
          <td><a href="#" onclick="show_error({{ $data['id'] }});">#{{ $data['id'] }}</a></td>
          <td>{{ $data['date_time'] }}</td>
          <td>{{ $data['status'] }}</td>
          <td>{{ $data['url'] }}</td>
          <td>{{ $data['message'] }}</td>
        </tr>
        @endforeach
        @if(count($errorReport) == 0)
        <tr>
          <td colspan="7"  style="text-align:center;">No Record Found</td>
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
@section('modal')
<!-- Modal -->
<div class="modal fade bd-example-modal-lg"  id="showError" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="showErrorModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="showErrorContent"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
@endsection
@push('js-links')
<!-- Date Picker -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- Date Picker -->
<!-- Multi Date Picker -->
<script src="{{ asset('assets/plugins/jquery-ui.multidatespicker.js') }}"></script>
<!-- Multi Date Picker -->
@endpush
@section('js-content')
<script>
  $(document).ready(function(){
    $(".custom-date").hide();
    $(".multiple-date").hide();
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
        if(optionValue == "multiple"){
          $(".multiple-date").show();
        } else{
          $(".multiple-date").hide();
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

  $(document).ready(function(){
    $('#multiDate').multiDatesPicker({
      showAnim:"",                            
      dateFormat: "yy-mm-dd",
      maxDate: "+1m",
      minDate: "-12m",
      multidate: true,
      defaultDate:"0d",						
      onSelect: function(){
        var datepickerObj = $(this).data("datepicker");
        var datepickerSettings = datepickerObj.settings;
        var tempDay = datepickerObj.selectedDay;
        var tempMonth = datepickerObj.selectedMonth+1;
        var tempYear = datepickerObj.selectedYear;
        var pickedDate = tempYear+"-"+tempMonth+"-"+tempDay;
        delete datepickerSettings["defaultDate"];
        datepickerSettings.defaultDate=pickedDate;
        $("#multiDate").blur();
        setTimeout(function(){
          $("#multiDate").focus();
        },1);								
      }
    });
  });
</script>
<script>
  function show_error(id){
    $('#showError').modal('show');
    $('#showErrorModalLabel').html("#"+id);
    $('#showErrorContent').html("loading...");
    var url = "{{ route('show-error') }}";   
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $.ajax({
      type: "GET",
      url: url,
      data: {id:id},
      success: function(data)
      {
        $('#showErrorContent').html(data);
      }
    });
  }
</script>