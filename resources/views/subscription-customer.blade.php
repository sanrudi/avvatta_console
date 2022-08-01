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
@push('css-content')
<style>
.responsive-tabs {margin-bottom:10px;}
</style>
@endpush
@section('title', '| Subscription Report')
@section('content')

<div class="container">
  <h6>Subscription Report @include('report-for-date-no-default')</h6><hr>
  <form autocomplete="off" action="" method="GET">
    <div class="form-row">
      <div class="form-group col-md-2">
        <label for="reportFrom">Report From </label>
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
                    <label for="page">&nbsp;</label>
        <button type="submit" class="form-control btn btn-info" name="page" value="Generate">Generate</button>
      </div>
    </div>
  </form>
  <!-- Nav tabs -->
  <ul class="nav nav-tabs responsive-tabs" id="myTab" role="tablist">
    <li class="nav-item active">
      <a class="nav-link active" id="report-tab" data-toggle="tab" href="#report" role="tab" aria-controls="home" aria-selected="true">Report</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="RegisteredCustomers-tab" data-toggle="tab" href="#RegisteredCustomers" role="tab" aria-controls="profile" aria-selected="false">Registered Customers</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="RegisteredSubscribedCustomers-tab" data-toggle="tab" href="#RegisteredSubscribedCustomers" role="tab" aria-controls="profile" aria-selected="false">Registered & Subscribed Customers</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="RegisteredNonsubscribedCustomers-tab" data-toggle="tab" href="#RegisteredNonsubscribedCustomers" role="tab" aria-controls="profile" aria-selected="false">Registered & Non subscribed Customers</a>
    </li>
    
    <li class="nav-item">
      <a class="nav-link" id="NumberofSubscriptions-tab" data-toggle="tab" href="#NumberofSubscriptions" role="tab" aria-controls="profile" aria-selected="false">List of Subscriptions</a>
    </li>
    
    <li class="nav-item">
      <a class="nav-link" id="CancelledSubscriptions-tab" data-toggle="tab" href="#CancelledSubscriptions" role="tab" aria-controls="profile" aria-selected="false">Cancelled Subscriptions</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="NewSubscriptions-tab" data-toggle="tab" href="#NewSubscriptions" role="tab" aria-controls="profile" aria-selected="false">New Subscriptions</a>
    </li>
  </ul>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="report" role="tabpanel" aria-labelledby="report-tab">
      <!-- tab -->
      <table id="dataTableId" class="table table-striped table-bordered " style="width:100%">
        <thead>
          <tr>
            <th>Metrics</th>
            <th>Count</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Registered Customers</td>
            <td>{{$registerCustomerCount}}</td>
          </tr>
          <tr>
            <td>Registered & Subscribed Customers</td>
            <td>{{$registerSubscriptionCustomerCount}}</td>
          </tr>
          <tr>
            <td>Registered & Non subscribed Customers</td>
            <td>{{$registerNotSubscriptionCustomerCount}}</td>
          </tr>
          <tr>
            <td>Number of Subscriptions</td>
            <td>{{$noOfSubscriptionsCount}}</td>
          </tr>
          <tr>
            <td>Cancelled in last 7 Days</td>
            <td>{{$cancelledSevenDaysCount}}</td>
          </tr>
          <tr>
            <td>Cancelled in last 14 Days</td>
            <td>{{$cancelledfourteenDaysCount}}</td>
          </tr>
          <tr>
            <td>Cancelled in last 30+ Days</td>
            <td>{{$cancelledthirtyDaysCount}}</td>
          </tr>
        </tbody>
      </table>
      <!-- tab -->
    </div>
    <div class="tab-pane fade" id="RegisteredCustomers" role="tabpanel" aria-labelledby="RegisteredCustomers-tab">
      <!-- tab -->
      <table id="registeredUsers" class="table table-striped table-bordered " style="width:100%">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Registered At</th>
          </tr>
        </thead>
        <tbody>
         
        </tbody>
      </table>
      <!-- tab -->
    </div>
    <div class="tab-pane fade" id="RegisteredSubscribedCustomers" role="tabpanel" aria-labelledby="contact-tab">
      <!-- tab -->
      <table id="subscribedUsers" class="table table-striped table-bordered " style="width:100%">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Registered At</th>
          </tr>
        </thead>
        <tbody>
         
        </tbody>
      </table>
      <!-- tab -->
    </div>
    <div class="tab-pane fade" id="RegisteredNonsubscribedCustomers" role="tabpanel" aria-labelledby="RegisteredNonsubscribedCustomers-tab">
      <!-- tab -->
      <table id="avvattaNSUsers" class="table table-striped table-bordered " style="width:100%">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Registered At</th>
          </tr>
        </thead>
        <tbody>
         
        </tbody>
      </table>
      <!-- tab -->
    </div>
      
    <div class="tab-pane fade" id="NumberofSubscriptions" role="tabpanel" aria-labelledby="NumberofSubscriptions-tab">
     
      <table id="noOfSubscriptions" class="table table-striped table-bordered " style="width:100%">
        <thead>
          <tr>
            <th>Subscription</th>
            <th>Customer</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
      
    </div>
      
      
      
    <div class="tab-pane fade" id="CancelledSubscriptions" role="tabpanel" aria-labelledby="CancelledSubscriptions-tab">
      <!-- tab -->
      <table id="cancelled" class="table table-striped table-bordered " style="width:100%">
        <thead>
          <tr>
            <th>Subscription</th>
            <th>Customer</th>
            <th>Days</th>
            <th>Date</th>
            <th>Reason</th>
          </tr>
        </thead>
        <tbody>
         
        </tbody>
      </table>
      <!-- tab -->
    </div>
    <div class="tab-pane fade" id="NewSubscriptions" role="tabpanel" aria-labelledby="NewSubscriptions-tab">
      <!-- tab -->
      <table id="newSubscriptions" class="table table-striped table-bordered " style="width:100%">
        <thead>
          <tr>
            <th>Customer</th>
            <th>Subscription</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
      <!-- tab -->
    </div>
  </div>
  <!-- Tab panes -->
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

    $('#dataTableId').DataTable( {
      dom: 'Bfrtip',
      paging: false, info: false, "aaSorting": [],
      buttons: [
      {
        extend: 'excel',
        text: 'Export Results',
        className: 'btn btn-default',
        title: 'Subscription Report'
      }
      ]
    } );
    $(document).on('click', '#RegisteredCustomers-tab', function() {
      var href = $(this).attr('href').substring(1);
      if ( ! $.fn.DataTable.isDataTable( '#registeredUsers' ) ) {
        $('#registeredUsers').DataTable( {
          processing: true,
          serverSide: true,
          ajax: "{{route('getRegisteredCustomer')}}",
          columns: [
            { data: 'name' },
            { data: 'email' },
            { data: 'mobile' },
            { data: 'created_at'},
          ],
          order: [[3, 'desc']],
          dom: 'Bfrtip',
          //paging: true, info: false, ordering: false, pageLength: 10, "aaSorting": [],
          buttons: [
          {
            extend: 'excel',
            text: 'Export Results',
            className: 'btn btn-default',
            title: 'Registered Customers'
          }
          ]
        } );
      }
    });

    $(document).on('click', '#RegisteredSubscribedCustomers-tab', function() {
      var href = $(this).attr('href').substring(1);
      if ( ! $.fn.DataTable.isDataTable( '#subscribedUsers' ) ) {
        $('#subscribedUsers').DataTable( {
          processing: true,
          serverSide: true,
          ajax: "{{route('getRegisteredSubscribedCustomer')}}",
          columns: [
            { data: 'firstname' },
            { data: 'email' },
            { data: 'mobile' },
            { data: 'created_at'},
          ],
          order: [[3, 'desc']],
          dom: 'Bfrtip',
          //paging: true, info: false, ordering: false, pageLength: 10, "aaSorting": [],
          buttons: [
          {
            extend: 'excel',
            text: 'Export Results',
            className: 'btn btn-default',
            title: 'Registered & Subscribed Customers'
          }
          ]
        } );
      }
    });

    $(document).on('click', '#RegisteredNonsubscribedCustomers-tab', function() {
      var href = $(this).attr('href').substring(1);
      if ( ! $.fn.DataTable.isDataTable( '#avvattaNSUsers' ) ) {
        $('#avvattaNSUsers').DataTable( {
          processing: true,
          serverSide: true,
          ajax: "{{route('getRegisteredNotSubscribedCustomer')}}",
          columns: [
            { data: 'firstname' },
            { data: 'email' },
            { data: 'mobile' },
            { data: 'created_at'},
          ],
          order: [[3, 'desc']],
          dom: 'Bfrtip',
          //paging: true, info: false, ordering: false, pageLength: 10, "aaSorting": [],
          buttons: [
          {
            extend: 'excel',
            text: 'Export Results',
            className: 'btn btn-default',
            title: 'Registered & Not Subscribed Customers'
          }
          ]
        } );
      }
    });

    $(document).on('click', '#NumberofSubscriptions-tab', function() {
      var href = $(this).attr('href').substring(1);
      if ( ! $.fn.DataTable.isDataTable( '#noOfSubscriptions' ) ) {
        $('#noOfSubscriptions').DataTable( {
          processing: true,
          serverSide: true,
          ajax: "{{route('getSubscriberList')}}",
          columns: [
            { data: 'title' },
            { data: 'name' },
            { data: 'created_at'},
          ],
          order: [[2, 'desc']],
          dom: 'Bfrtip',
          //paging: true, info: false, ordering: false, pageLength: 10, "aaSorting": [],
          buttons: [
          {
            extend: 'excel',
            text: 'Export Results',
            className: 'btn btn-default',
            title: 'Subscriptions'
          }
          ]
        } );
      }
    });

    $(document).on('click', '#CancelledSubscriptions-tab', function() {
      var href = $(this).attr('href').substring(1);
      if ( ! $.fn.DataTable.isDataTable( '#cancelled' ) ) {
        $('#cancelled').DataTable( {
          processing: true,
          serverSide: true,
          ajax: "{{route('getSubscriberCancelledList')}}",
          columns: [
            { data: 'title' },
            { data: 'name' },
            { data: 'days' },
            { data: 'created_at'},
            { data: 'reason' },
          ],
          order: [[3, 'desc']],
          dom: 'Bfrtip',
          //paging: true, info: false, ordering: false, pageLength: 10, "aaSorting": [],
          buttons: [
          {
            extend: 'excel',
            text: 'Export Results',
            className: 'btn btn-default',
            title: 'Cancelled Subscriptions'
          }
          ]
        } );
      }
    });   
    
    $(document).on('click', '#NewSubscriptions-tab', function() {
      var href = $(this).attr('href').substring(1);
      if ( ! $.fn.DataTable.isDataTable( '#newSubscriptions' ) ) {
        $('#newSubscriptions').DataTable( {
          processing: true,
          serverSide: true,
          ajax: "{{route('getNewSubscriberList')}}",
          columns: [
            { data: 'name' },
            { data: 'title' },
            { data: 'created_at'},
          ],
          order: [[2, 'desc']],
          dom: 'Bfrtip',
          //paging: true, info: false, ordering: false, pageLength: 10, "aaSorting": [],
          buttons: [
          {
            extend: 'excel',
            text: 'Export Results',
            className: 'btn btn-default',
            title: 'New Subscriptions'
          }
          ]
        } );
      }
    });

    
  } );
</script>
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
@endsection
