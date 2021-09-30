@extends('layout.template')
@section('content')
<!--  BEGIN CONTENT AREA  -->

@if (Request::path() == 'game-report')

<div class="container">
	<h6>All Game Categories</h6>
	<a href="{{ route('export-game-content') }}" class="btn btn-info" role="button">Export</a>
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th><div class="th-content">User</div></th>
					<th><div class="th-content">Game</div></th>
					<th><div class="th-content">Category</div></th>
					<th><div class="th-content th-heading">Played At</div></th>
				</tr>
			</thead>
			@foreach ($game_contents as $game_content)
			<tbody>
				<tr>
					<td><div class="td-content customer-name">{{$game_content->firstname.' '.$game_content->lastname}}</div></td>
					<td><div class="td-content product-brand">{{$game_content->game_name}}</div></td>
					<td><div class="td-content">{{$game_content->category_name}}</div></td>
					<td><div class="td-content pricing"><span class="">{{date('D j M Y', strtotime($game_content->date_time))}}</span></div></td>
				</tr>
			</tbody>
			@endforeach
		</table>
	</div>
</div>
@endif


@if (Request::path() == 'repeated-game-by-user')
<div class="container">
	<h6>Top repeat played games by a single user account</h6>
	<a href="{{ route('export-repeated-game-content') }}" class="btn btn-info" role="button">Export</a>
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th><div class="th-content">User</div></th>
					<th><div class="th-content">Game</div></th>
					<th><div class="th-content">Category</div></th>

				</tr>
			</thead>
			@foreach ($repeated_games as $repeated_game)
			<tbody>
				<tr>
					<td><div class="td-content customer-name">{{$repeated_game->firstname.' '.$repeated_game->lastname}}</div></td>
					<td><div class="td-content product-brand">{{$repeated_game->game_name}}</div></td>
					<td><div class="td-content">{{$repeated_game->category_name}}</div></td>

				</tr>
			</tbody>
			@endforeach
		</table>
	</div>
</div>
@endif


@if (Request::path() == 'most-played-games')
<div class="container">
	<h6>Top 10 most played Games</h6>
	<a href="{{ route('export-most-played-games') }}" class="btn btn-info" role="button">Export</a>
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th><div class="th-content">Game</div></th>
					<th><div class="th-content">Category</div></th>

				</tr>
			</thead>
			@foreach ($most_played_games as $most_played_game)
			<tbody>
				<tr>
					<td><div class="td-content product-brand">{{$most_played_game->game_name}}</div></td>
					<td><div class="td-content">{{$most_played_game->category_name}}</div></td>

				</tr>
			</tbody>
			@endforeach
		</table>
	</div>
</div>
@endif
<!--  END CONTENT AREA  -->
</div>
@endsection
@push('js-links')
@endpush
@section('js-content')
@endsection