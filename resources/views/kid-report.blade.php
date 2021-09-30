@extends('layout.template')
@section('content')
<!--  BEGIN CONTENT AREA  -->
@if (Request::path() == 'kids-report')
<div class="container">
	<h6>All Kids Categories</h6>
	<a href="{{ route('export-game-content') }}" class="btn btn-info" role="button">Export</a>
	<table class="table">
		<thead>
			<tr>
				<th><div class="th-content">User</div></th>
				<th><div class="th-content">Video</div></th>
				<th><div class="th-content">Category</div></th>
				<th><div class="th-content th-heading">watched At</div></th>
			</tr>
		</thead>
		@foreach ($kids_contents as $kids_content)
		<tbody>
			<tr>
				<td><div class="td-content customer-name">{{$kids_content->firstname.' '.$kids_content->lastname}}</div></td>
				<td><div class="td-content product-brand">{{$kids_content->content_name}}</div></td>
				<td><div class="td-content">{{$kids_content->category_name}}</div></td>
				<td><div class="td-content pricing"><span class="">{{date('D j M Y', strtotime($kids_content->date_time))}}</span></div></td>
			</tr>
		</tbody>
		@endforeach
	</table>
</div>
</div>
</div>
</div>
@endif

@if (Request::path() == 'most-watched-kids-content')
<div class="container">
	<h6>Top 10 watched Kids Category</h6>
	<a href="{{ route('export-most-played-games') }}" class="btn btn-info" role="button">Export</a>
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th><div class="th-content">Video</div></th>
					<th><div class="th-content">Category</div></th>
				</tr>
			</thead>
			@foreach ($mostWatchedKidsContents as $mostWatchedKidsContent)
			<tbody>
				<tr>
					<td><div class="td-content product-brand">{{$mostWatchedKidsContent->content_name}}</div></td>
					<td><div class="td-content">{{$mostWatchedKidsContent->category_name}}</div></td>
				</tr>
			</tbody>
			@endforeach
		</table>
	</div>
	@endif

	@if (Request::path() == 'repeated-kidscontent-by-user')
	<div class="container">
		<h6>Most Watched Kids Content By Single User</h6>
		<a href="{{ route('export-most-played-games') }}" class="btn btn-info" role="button">Export</a>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th><div class="th-content">User</div></th>
						<th><div class="th-content">Video</div></th>
						<th><div class="th-content">Category</div></th>

					</tr>
				</thead>
				@foreach ($repeatedKidsContents as $repeatedKidsContent)
				<tbody>
					<tr>
						<td><div class="td-content customer-name">{{$repeatedKidsContent->firstname.' '.$repeatedKidsContent->lastname}}</div></td>
						<td><div class="td-content product-brand">{{$repeatedKidsContent->content_name}}</div></td>
						<td><div class="td-content">{{$repeatedKidsContent->category_name}}</div></td>
					</tr>
				</tbody>
				@endforeach
			</table>
		</div>
		@endif
		<!--  END CONTENT AREA  -->
	</div>
	@endsection
	@push('js-links')
	@endpush
	@section('js-content')
	@endsection