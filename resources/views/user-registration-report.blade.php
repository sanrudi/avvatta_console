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
@section('content')
    <div class="container">
        <h6>Registration Report @include('report-for-date-no-default')</h6><hr>
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
            </div>
        </form>
        <!-- registerUsersMonthly -->
        <hr>
        <h6>Registered Users - Monthly</h6>
        <div class="table-responsive">
            <table id="registerUsersMonthly" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%"> 
                <thead>
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>No of Registration</th>
                </tr>
                </thead>
                <tbody>
                @php $total = 0; @endphp
                @foreach($registerUsersMonthly as $key => $data)
                @php $total = $total + $data->count; @endphp
                    <tr>
                        <td>{{ $data->year }}</td>
                        <td>{{ $data->month }}</td>
                        <td>{{ $data->count }}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td>Total</td>
                        <td></td>
                        <td>{{ $total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- registerUsersDaily -->
        <hr>
        <h6>Registered Users - Daily</h6>
        <div class="table-responsive">
            <table id="registerUsersDaily" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%"> 
                <thead>
                <tr>
                    <th>Date</th>
                    <th>No of Registration</th>
                </tr>
                </thead>
                <tbody>
                @php $total = 0; @endphp
                @foreach($registerUsersDaily as $key => $data)
                @php $total = $total + $data->count; @endphp
                    <tr>
                        <td>{{ $data->date }}</td>
                        <td>{{ $data->count }}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td>Total</td>
                        <td>{{ $total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- signedUpOnWeb -->
        <hr>
        <h6>Signed Up On Web</h6>
        <div class="table-responsive">
            <table id="signedUpOnWeb" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%"> 
                <thead>
                <tr>
                    <th>Date</th>
                    <th>No of Registration</th>
                </tr>
                </thead>
                <tbody>
                @php $total = 0; @endphp
                @foreach($registerUsersDaily as $key => $data)
                @php $total = $total + $data->count; @endphp
                    <tr>
                        <td>{{ $data->date }}</td>
                        <td>{{ $data->count }}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td>Total</td>
                        <td>{{ $total }}</td>
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
    <!-- Date Picker -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Date Picker -->
@endpush
@section('js-content')
<script>
$(document).ready(function() {
   
    $('#registerUsersMonthly').DataTable( {
        dom: 'Bfrtip',
        searching: false, paging: false, info: false, ordering: false, "aaSorting": [],
        buttons: [
            {
            extend: 'excel',
            text: 'Export Results',
            className: 'btn btn-default',
            title: 'registerUsersMonthly'
        }
        ]
    } );
    
    $('#registerUsersDaily').DataTable( {
        dom: 'Bfrtip',
        searching: false, paging: true, info: false, ordering: false, pageLength: 10, "aaSorting": [],
        buttons: [
            {
            extend: 'excel',
            text: 'Export Results',
            className: 'btn btn-default',
            title: 'registerUsersDaily'
        }
        ]
    } );

    $('#signedUpOnWeb').DataTable( {
        dom: 'Bfrtip',
        searching: false, paging: true, info: false, ordering: false, pageLength: 10, "aaSorting": [],
        buttons: [
            {
            extend: 'excel',
            text: 'Export Results',
            className: 'btn btn-default',
            title: 'signedUpOnWeb'
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
