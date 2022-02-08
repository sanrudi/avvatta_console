@extends('layout.template')
@section('content')
    <h6>Create New Role</h6><hr>
    <a class="btn btn-primary" href="{{ route('roles.index') }}"> Back</a><hr>
@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
{!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="form-group">
            <strong>Name:</strong> [For CP, use cp name as role name as per records.]
            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
        </div>
    </div>
    <strong>Permission:</strong>
    <div class="row">

       @foreach($permission as $value)  
        <div class="col-md-3">        
          <div class="form-group">
           
            <br/>
            <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                {{ $value->name }}</label>
                <br/>
            </div>
         </div>
        @endforeach
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
    {!! Form::close() !!}
    @endsection