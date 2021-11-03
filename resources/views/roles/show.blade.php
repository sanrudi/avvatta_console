@extends('layout.template')
@section('content')
    <h6>Show Role</h6><hr>
    <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back</a><hr>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {{ $role->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Permissions:</strong>
            @if(!empty($rolePermissions))
            @foreach($rolePermissions as $v)
            <label class="label label-success">{{ $v->name }},</label>
            @endforeach
            @endif
        </div>
    </div>
</div>
@endsection