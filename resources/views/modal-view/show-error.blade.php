
<div class="container">
  <div class="form-row">
    <div class="form-group col-md-12">
      <label >ID:</label>
      <label >#{{ $data->id }}</p>
    </div>
    <div class="form-group col-md-12">
      <label >Date Time:</label>
      <p class="text-break">{{ $data->date_time }}</p>
    </div>
    <div class="form-group col-md-12">
      <label >Status:</label>
      <p class="text-break">{{ $data->status }}</p>
    </div>
    <div class="form-group col-md-12">
      <label >Status Text:</label>
      <p class="text-break">{{ $data->statusText }}</p>
    </div>
    <div class="form-group col-md-12">
      <label >URL:</label>
      <p class="text-break">{{ $data->url }}</p>
    </div>
    <div class="form-group col-md-12">
      <label >Message:</label>
      <p class="text-break">{{ $data->message }}</p>
    </div>
    <div class="form-group col-md-12">
      <label >Error:</label>
      <p class="text-break">{{ $data->error }}</p>
    </div>
  </div>
</div>