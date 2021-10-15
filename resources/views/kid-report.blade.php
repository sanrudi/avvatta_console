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
<!--  BEGIN CONTENT AREA  -->
@if (Request::path() == 'kids-report')
<div class="container">
	<h6>Users consuming  All Kids Categories @include('report-for-date')</h6><hr>
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
                    <label for="page">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-info" name="page" value="Generate">Generate</button>
                </div>
                
            </div>
        </form>
	<!-- <a href="{{ route('export-game-content') }}" class="btn btn-info" role="button">Export</a> -->
	<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">	
		<thead>
			<tr>
				<th>User</th>
				<th><div class="th-content">Email</div></th>
				<th><div class="th-content">Mobile</div></th>
			</tr>
		</thead>
		<tbody>
		@foreach ($kids_contents as $kids_content)
			<tr>
				<td><div class="td-content customer-name">
				{{$kids_content->firstname}}{{$kids_content->lastname}}
				</div></td>
				<td><div class="td-content customer-name">
				{{$kids_content->email}}
				</div></td>
				<td><div class="td-content customer-name">
				{{$kids_content->mobile}}
				</div></td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>
</div>
</div>
</div>
@endif

@if (Request::path() == 'most-watched-kids-content')
<div class="container">
	<h6>Top 10 watched Kids Category @include('report-for-date')</h6><hr>
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
                    <label for="page">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-info" name="page" value="Generate">Generate</button>
                </div>
                
            </div>
        </form>
	<!-- <a href="{{ route('export-most-played-games') }}" class="btn btn-info" role="button">Export</a> -->
	<div class="table-responsive">
	<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
			<thead>
				<tr>
					<th><div >Video</div></th>
					<th><div >Category</div></th>
					<th><div >Sub Category</div></th>
					<th><div >Provider</div></th>
					<th><div >Watches</div></th>
				</tr>
			</thead>
			<tbody>
			@foreach ($mostWatchedKidsContents as $mostWatchedKidsContent)
				<tr>
					<td><div class="td-content product-brand">{{$mostWatchedKidsContent->content_name}}</div></td>
					<td><div class="td-content">{{$mostWatchedKidsContent->category_name}}</div></td>
					<td><div class="td-content">{{$mostWatchedKidsContent->sub_cat_name}}</div></td>
					<td>{{$mostWatchedKidsContent->provider}}</td>
					<td>{{$mostWatchedKidsContent->count}}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	@endif

	@if (Request::path() == 'repeated-kidscontent-by-user')
	<div class="container">
		<h6>Most Watched Kids Content By Single User @include('report-for-date')</h6><hr>
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
                    <label for="page">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-info" name="page" value="Generate">Generate</button>
                </div>
                
            </div>
        </form>
		<!-- <a href="{{ route('export-most-played-games') }}" class="btn btn-info" role="button">Export</a> -->
		<div class="table-responsive">
		<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th><div >User</div></th>
						<th><div >Video</div></th>
						<th><div >Category</div></th>
						<th><div >Sub Category</div></th>
						<th><div >Provider</div></th>
						<th><div >Watches</div></th>

					</tr>
				</thead>
				<tbody>
				@foreach ($repeatedKidsContents as $repeatedKidsContent)
					<tr>
						<td><div class="td-content customer-name">
							@if(!empty($repeatedKidsContent->firstname) && !empty($repeatedKidsContent->lastname))
							{{$repeatedKidsContent->firstname}}{{$repeatedKidsContent->lastname}}
							@elseif(!empty($repeatedKidsContent->email))
							{{$repeatedKidsContent->email}}
							@elseif(!empty($repeatedKidsContent->mobile))
							{{$repeatedKidsContent->mobile}}
							@endif
						</div></td>
						<td><div class="td-content product-brand">{{$repeatedKidsContent->content_name}}</div></td>
						<td><div class="td-content">{{$repeatedKidsContent->category_name}}</div></td>
						<td><div class="td-content">{{$repeatedKidsContent->sub_cat_name}}</div></td>
						<td>{{$repeatedKidsContent->provider}}</td>
						<td>{{$repeatedKidsContent->count}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
		@endif
		<!--  END CONTENT AREA  -->
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