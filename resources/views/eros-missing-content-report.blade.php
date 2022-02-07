@extends('layout.template')
@section('content')
<div class="container">
<h6>Erosnow Report</h6><hr>
     <form autocomplete="off" action="" method="GET">
      <div class="form-row">
        <div class="form-group col-md-2">
          <label for="export">&nbsp;</label>
          <button type="submit" class="form-control btn btn-success"  name="export" value="Export Excel"  formtarget="_blank">Export Excel</button>
        </div>
    </form>
    <hr>
  </div>  
  </div>
@endsection