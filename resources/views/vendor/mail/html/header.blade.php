<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<!-- <img src="{{ asset('images/avvatta-logo.PNG') }}" class="logo" alt="Avvatta Logo"> -->
Avvatta
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
