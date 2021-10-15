<span class="text-info">
@if(Request::get('reportFrom') !="custom" && Request::get('reportFrom') !="")
    [ for Last {!! Request::get('reportFrom') !!} Days ]
@elseif(Request::get('reportFrom') =="custom")
    @if(Request::get('startDate') !="" && Request::get('endDate') =="")
    [ From: {!! Request::get('startDate') !!} ]
    @elseif(Request::get('startDate') =="" && Request::get('endDate') !="")
    [ till: {!! Request::get('endDate') !!} ]
    @elseif(Request::get('startDate') !="" && Request::get('endDate') !="")
    [  From: {!! Request::get('startDate') !!} till: {!! Request::get('endDate') !!} ]
    @endif
@elseif(Request::get('reportFrom') =="")
[ for Last 7 Days ]
@endif
</span>