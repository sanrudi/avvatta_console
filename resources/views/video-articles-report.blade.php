<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laravel Pagination Demo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
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

        <div class="d-flex justify-content-center">
            {!! $videoArticles->links() !!}
        </div>
    </div>
</body>

</html>