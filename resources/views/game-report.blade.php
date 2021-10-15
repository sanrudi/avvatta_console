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

@if (Request::path() == 'game-report')

<div class="container">
	<h6>Users consuming  All Game Categories @include('report-for-date')</h6>
	<hr>
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
	<div class="table-responsive">
	<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
			<thead>
				<tr>
					<th><div class="th-content">User</div></th>
					<th><div class="th-content">Email</div></th>
					<th><div class="th-content">Mobile</div></th>
					<!-- <th><div class="th-content">Game</div></th>
					<th><div class="th-content">Category</div></th>
					<th><div class="th-content th-heading">Count</div></th> -->
				</tr>
			</thead>
			<tbody>
			@foreach ($game_contents as $game_content)
				<tr>
					<td><div class="td-content customer-name">
					{{$game_content->firstname}}{{$game_content->lastname}}
					</div></td>
					<td><div class="td-content customer-name">
					{{$game_content->email}}
					</div></td>
					<td><div class="td-content customer-name">
					{{$game_content->mobile}}
					</div></td>
					<!-- <td><div class="td-content product-brand">{{$game_content->game_name}}</div></td>
					<td><div class="td-content">{{$game_content->category_name}}</div></td>
					<td><div class="td-content pricing"><span class="">{{$game_content->count}}</span></div></td> -->
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif


@if (Request::path() == 'repeated-game-by-user')
<div class="container">
	<h6>Top repeat played games by a single user account @include('report-for-date')</h6>
	<hr>
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
	<!-- <a href="{{ route('export-repeated-game-content') }}" class="btn btn-info" role="button">Export</a> -->
	<div class="table-responsive">
	<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
			<thead>
				<tr>
					<th><div class="th-content">User</div></th>
					<th><div class="th-content">Game</div></th>
					<th><div class="th-content">Category</div></th>
					<th><div class="th-content">Provider</div></th>
					<th><div class="th-content">Count</div></th>
				</tr>
			</thead>
			<tbody>
			@foreach ($repeated_games as $repeated_game)
				<tr>
					<td><div class="td-content customer-name">
					@if(!empty($repeated_game->firstname) && !empty($repeated_game->lastname))
					{{$repeated_game->firstname}}{{$repeated_game->lastname}}
					@elseif(!empty($repeated_game->email))
					{{$repeated_game->email}}
					@elseif(!empty($repeated_game->mobile))
					{{$repeated_game->mobile}}
					@endif</div></td>
					<td><div class="td-content product-brand">{{$repeated_game->game_name}}</div></td>
					<td><div class="td-content">{{$repeated_game->category_name}}</div></td>
					<td><div class="td-content">{{$repeated_game->provider}}</div></td>
					<td><div class="td-content">{{$repeated_game->count}}</div></td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif


@if (Request::path() == 'most-played-games')
<div class="container">
	<h6>Top 10 most played Games @include('report-for-date')</h6>
	<hr>
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
					<th><div class="th-content">Game</div></th>
					<th><div class="th-content">Category</div></th>
					<th><div class="th-content">Provider</div></th>
					<th><div class="th-content">Count</div></th>
				</tr>
			</thead>
			<tbody>
			@foreach ($most_played_games as $most_played_game)
				<tr>
					<td><div class="td-content product-brand">{{$most_played_game->game_name}}</div></td>
					<td><div class="td-content">{{$most_played_game->category_name}}</div></td>
					<td><div class="td-content">{{$most_played_game->provider}}</div></td>
					<td><div class="td-content">{{$most_played_game->count}}</div></td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
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