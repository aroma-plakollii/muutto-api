<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://www.muuttotarjous.fi/wp-content/uploads/2021/09/logo.png" class="logo" alt="Muuttotarjous">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
