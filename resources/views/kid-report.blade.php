@extends('layout.template')
@push('css-links')
    <!-- Data Tables -->
    <link href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!-- Data Tables -->
@endpush
@section('content')
<!--  BEGIN CONTENT AREA  -->
@if (Request::path() == 'kids-report')
<div class="container">
	<h6>All Kids Categories</h6><hr>
	<!-- <a href="{{ route('export-game-content') }}" class="btn btn-info" role="button">Export</a> -->
	<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">	
		<thead>
			<tr>
				<th>User</th>
				<th>Video</th>
				<th>Category</th>
				<th>watched At</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($kids_contents as $kids_content)
			<tr>
				<td>{{$kids_content->firstname.' '.$kids_content->lastname}}</td>
				<td>{{$kids_content->content_name}}</td>
				<td>{{$kids_content->category_name}}</td>
				<td>{{date('D j M Y', strtotime($kids_content->date_time))}}</td>
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
	<h6>Top 10 watched Kids Category</h6><hr>
	<!-- <a href="{{ route('export-most-played-games') }}" class="btn btn-info" role="button">Export</a> -->
	<div class="table-responsive">
	<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
			<thead>
				<tr>
					<th><div >Video</div></th>
					<th><div >Category</div></th>
					<th><div >Provider</div></th>
				</tr>
			</thead>
			<tbody>
			@foreach ($mostWatchedKidsContents as $mostWatchedKidsContent)
				<tr>
					<td><div class="td-content product-brand">{{$mostWatchedKidsContent->content_name}}</div></td>
					<td><div class="td-content">{{$mostWatchedKidsContent->category_name}}</div></td>
					<td>Kids</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	@endif

	@if (Request::path() == 'repeated-kidscontent-by-user')
	<div class="container">
		<h6>Most Watched Kids Content By Single User</h6><hr>
		<!-- <a href="{{ route('export-most-played-games') }}" class="btn btn-info" role="button">Export</a> -->
		<div class="table-responsive">
		<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
				<thead>
					<tr>
						<th><div >User</div></th>
						<th><div >Video</div></th>
						<th><div >Category</div></th>
						<th><div >Provider</div></th>

					</tr>
				</thead>
				<tbody>
				@foreach ($repeatedKidsContents as $repeatedKidsContent)
					<tr>
						<td><div class="td-content customer-name">{{$repeatedKidsContent->firstname.' '.$repeatedKidsContent->lastname}}</div></td>
						<td><div class="td-content product-brand">{{$repeatedKidsContent->content_name}}</div></td>
						<td><div class="td-content">{{$repeatedKidsContent->category_name}}</div></td>
						<td>Kids</td>
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