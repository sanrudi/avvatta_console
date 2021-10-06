@extends('layout.template')
@push('css-links')
    <!-- Data Tables -->
    <link href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!-- Data Tables -->
@endpush
@section('content')
<!--  BEGIN CONTENT AREA  -->

@if (Request::path() == 'game-report')

<div class="container">
	<h6>All Game Categories</h6>
	<hr>
	<!-- <a href="{{ route('export-game-content') }}" class="btn btn-info" role="button">Export</a> -->
	<div class="table-responsive">
	<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
			<thead>
				<tr>
					<th><div class="th-content">User</div></th>
					<th><div class="th-content">Game</div></th>
					<th><div class="th-content">Category</div></th>
					<th><div class="th-content th-heading">Played At</div></th>
				</tr>
			</thead>
			<tbody>
			@foreach ($game_contents as $game_content)
				<tr>
					<td><div class="td-content customer-name">{{$game_content->firstname.' '.$game_content->lastname}}</div></td>
					<td><div class="td-content product-brand">{{$game_content->game_name}}</div></td>
					<td><div class="td-content">{{$game_content->category_name}}</div></td>
					<td><div class="td-content pricing"><span class="">{{date('D j M Y', strtotime($game_content->date_time))}}</span></div></td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif


@if (Request::path() == 'repeated-game-by-user')
<div class="container">
	<h6>Top repeat played games by a single user account</h6>
	<hr>
	<!-- <a href="{{ route('export-repeated-game-content') }}" class="btn btn-info" role="button">Export</a> -->
	<div class="table-responsive">
	<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
			<thead>
				<tr>
					<th><div class="th-content">User</div></th>
					<th><div class="th-content">Game</div></th>
					<th><div class="th-content">Category</div></th>
					<th><div class="th-content">Provider</div></th>
				</tr>
			</thead>
			<tbody>
			@foreach ($repeated_games as $repeated_game)
				<tr>
					<td><div class="td-content customer-name">{{$repeated_game->firstname.' '.$repeated_game->lastname}}</div></td>
					<td><div class="td-content product-brand">{{$repeated_game->game_name}}</div></td>
					<td><div class="td-content">{{$repeated_game->category_name}}</div></td>
					<td><div class="td-content">{{$repeated_game->provider}}</div></td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif


@if (Request::path() == 'most-played-games')
<div class="container">
	<h6>Top 10 most played Games</h6>
	<hr>
	<!-- <a href="{{ route('export-most-played-games') }}" class="btn btn-info" role="button">Export</a> -->
	<div class="table-responsive">
	<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
			<thead>
				<tr>
					<th><div class="th-content">Game</div></th>
					<th><div class="th-content">Category</div></th>
					<th><div class="th-content">Provider</div></th>
				</tr>
			</thead>
			<tbody>
			@foreach ($most_played_games as $most_played_game)
				<tr>
					<td><div class="td-content product-brand">{{$most_played_game->game_name}}</div></td>
					<td><div class="td-content">{{$most_played_game->category_name}}</div></td>
					<td><div class="td-content">{{$most_played_game->provider}}</div></td>
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
@endpush
	@section('js-content')
	<script>
$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        searching: false, paging: false, info: false, "aaSorting": [],
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
	@endsection