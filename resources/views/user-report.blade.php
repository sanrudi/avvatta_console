@extends('layout.template')
@section('content')
    <div class="container">
        <h6>User Report</h6><hr>
        <form action="" method="GET">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="reportFrom">Report From</label>
                    <select name="reportFrom" id="reportFrom" class="form-control">
                        <option value="" @if(Request::get('reportFrom') == "") selected="selected" @endif >Select</option>
                        <option value="7" @if(Request::get('reportFrom') == "7") selected="selected" @endif >Last 7 Days</option>
                        <option value="14" @if(Request::get('reportFrom') == "14") selected="selected" @endif>Last 14 Days</option>
                        <option value="30" @if(Request::get('reportFrom') == "30") selected="selected" @endif>Last 30 Days</option>
                        <option value="90" @if(Request::get('reportFrom') == "90") selected="selected" @endif>Last 90 Days</option>
                        <option value="custom" @if(Request::get('reportFrom') == "custom") selected="selected" @endif>Custom</option>
                    </select>
                </div>
                <div class="form-group col-md-2 custom-date">
                    <label for="startDate">Start Date</label>
                    <input id="startDate" name="startDate" type="date" class="form-control" value="{!! Request::get('startDate') !!}"/>
                </div>
                <div class="form-group col-md-2 custom-date">
                    <label for="endDate">End Date</label>
                    <input id="endDate" name="endDate" type="date" class="form-control" value="{!! Request::get('endDate') !!}" />
                </div>
                <div class="form-group col-md-2">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-control">
                        <option value="" selected="selected">Select</option>
                        <option value="erosnow" @if(Request::get('type') == "erosnow") selected="selected" @endif >Erosnow</option>
                        <option value="kids" @if(Request::get('type') == "kids") selected="selected" @endif>Kids</option>
                        <option value="game" @if(Request::get('type') == "game") selected="selected" @endif>Games</option>
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
    <script>
        $(document).ready(function(){
            $(".custom-date").hide();
            $("#reportFrom").change(function(){
                $(this).find("option:selected").each(function(){
                    var optionValue = $(this).attr("value");
                    console.log(optionValue);
                    $("#startDate").val("");
                    $("#endDate").val("");
                    if(optionValue == "custom"){
                        $(".custom-date").show();
                    } else{
                        $(".custom-date").hide();
                    }
                });
            }).change();
        });
    </script>
@endsection
