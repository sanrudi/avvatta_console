  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laravel Pagination Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.3/css/bootstrapValidator.min.css">
 </head>

  <body>
    <div class="container mt-5">
     <form action="" method="GET">
      <div class="form-row">
        <div class="form-group col-md-2">
          <label for="startDate">Start Date</label>
          <input id="startDate" name="startDate" type="date" class="form-control" value="{!! Request::get('startDate') !!}"/>
        </div>
        <div class="form-group col-md-2">
          <label for="endDate">End Date</label>
          <input id="endDate" name="endDate" type="date" class="form-control" value="{!! Request::get('endDate') !!}" />
        </div>
        <div class="form-group col-md-2">
          <label for="endDate">Type</label>
          <select name="type" id="type" class="form-control">
            <option value="erosnow" @if(Request::get('type') == "erosnow") selected="selected" @endif >Erosnow</option>
            <option value="kids" @if(Request::get('type') == "kids") selected="selected" @endif>Kids</option>
          </select>
        </div>
        <div class="form-group col-md-2">
          <label for="page">&nbsp;</label>
          <button type="submit" class="form-control btn btn-info" name="page" value="Generate">Generate</button>
        </div>
        <div class="form-group col-md-2">
          <label for="export">&nbsp;</label>
          <button type="submit" class="form-control btn btn-success"  name="export" value="Export Excel"  formtarget="_blank">Export Excel</button>
        </div>
      </div>
    </form>
    <div class="table-responsive">
    <table class="table table-bordered mb-5">
      <thead>
        <tr class="table-success">
          <th scope="col">#</th>
          <th scope="col">ContentID</th>
          <th scope="col">Title</th>
          <th scope="col">Category</th>
          <th scope="col">Watches</th>
          <th scope="col">Unique Watches</th>
          <th scope="col">Favourite</th>
          <th scope="col">Average</th>
          <th scope="col">Added Date</th>
          <th scope="col">Duration</th>
        </tr>
      </thead>
      <tbody>
        @foreach($videoArticles as $key => $data)
        <tr>
          <td scope="row">{{ $videoArticles->firstItem() + $key }}</td>
          <td>{{ $data->content_id }}</td>
          <td>{{ $data->title }}</td>
          <td>{{ $data->categories }}</td>
          <td>{{ count($data->erosnow_watches) }}</td>
          <td>{{ count($data->erosnow_unique_watches) }}</td>
          <td>{{ count($data->erosnow_wishlist) }}</td>
          <td>{{ $data->avg }}</td>
          <td>{{ $data->created_date }}</td>
          <td>{{ $data->duration }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>  

    <div class="d-flex justify-content-center">
      {!! $videoArticles->withQueryString()->links() !!}
    </div>
  </div>
</body>

</html>