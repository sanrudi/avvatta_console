@extends('layout.template')
@push('css-links')
    <!-- Data Tables -->
    <link href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <!-- Data Tables -->
@endpush
@section('content')
<div class="container">
<h6>Most Report</h6><hr>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
<thead>
    <tr>
        <th>Title</th>
        <th>Watches</th>
    </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
            @if($log->loggable_type == "App\Models\VideoContent")
            <td>{{$log->loggable->content_name}}</td>
            @endif
            @if($log->loggable_type == "App\Models\AvErosNows")
            <td>{!! isset($log->erosnow->title) ? $log->erosnow->title : '' !!}</td>
            @endif
            <td>{{$log->count}}</td>
        </tr>
        @endforeach 
    </tbody>
</table>
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
