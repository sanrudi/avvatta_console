@extends('layout.template')
@push('css-links')
<!-- Data Tables -->
<link href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<!-- Data Tables -->
@endpush
@section('content')
<div class="container">
    <h6>Role Management</h6><hr>
    @can('role-create')
            <a class="btn  btn-sm btn-success" href="{{ route('roles.create') }}"> Create New Role</a><hr>
    @endcan
@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif
    <div class="table-responsive">
    <table id="example" class="table table-striped table-bordered " style="width:100%">
        <thead>
    <tr>
        <th>Name</th>
        <th>Permissions</th>
        <th>Action</th>
    </tr>
        </thead>
        <tbody>
    @foreach ($roles as $key => $role)
    <tr>
        <td>{{ $role->name }}</td>
        <td>
        <div class="text-wrap width-100">
        @foreach ($role->permissions as $keyVal => $permission)
        <label class="badge badge-info">{{ $permission->name }}</label>
        @endforeach
        </div>
        </td>
        <td>
            <a class="btn  btn-sm btn-info" href="{{ route('roles.show',$role->id) }}">Show</a>
            @if($role->name !="Super Admin")
            @can('role-edit')
            <a class="btn  btn-sm btn-primary" href="{{ route('roles.edit',$role->id) }}">Edit</a>
            @endcan
            @can('role-delete')
            {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
            {!! Form::submit('Delete', ['class' => 'btn  btn-sm btn-danger']) !!}
            {!! Form::close() !!}
            @endcan
            @endif
        </td>
    </tr>
    @endforeach
        </tbody>
    </table>
</div>
</div>
{!! $roles->render() !!}
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
            info: false,paging: false, pageLength: 10, "aaSorting": [],language: {search: "Filter records:"}
        } );
    } );
</script>
@endsection