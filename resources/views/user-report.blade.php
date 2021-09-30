@extends('layout.template')
@section('content')
    <div class="container">
        <h6>User Report</h6><hr>
        <div class="table-responsive">
            <table class="table table-bordered mb-5">
                <thead>
                <tr class="table-success">
                    <th scope="col">#</th>
                    <th scope="col">User</th>
                    <th scope="col">Content</th>
                    <th scope="col">Type</th>
                    <th scope="col">Action</th>
                    <th scope="col">Date & Time</th>
                </tr>
                </thead>
                <tbody>
                @foreach($user_contents as $key => $data)
                    <tr>
                        <td scope="row">{{ $key }}</td>
                        <td>{{ $data['user_name'] }}</td>
                        <td>{{ $data['content_name'] }}</td>
                        <td>{{ ucwords($data['type']) }}</td>
                        <td>{{ $data['action'] }}</td>
                        <td>{{ date('D j M Y', strtotime($data['date_time'])) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('js-links')
@endpush
@section('js-content')
@endsection
