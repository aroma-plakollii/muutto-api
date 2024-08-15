@component('mail::message')
# Tarvetta lisãà aikaa?

Hei {{ $request['first_name'] }} {{ $request['last_name'] }},<br><br>

<!-- Muuttolaatikoiden vuokraus päättyy huomenna {{ $request['end_date'] }}. Jos tarvetta lisäà aikaa jatkaa vuokran <a href="https://confirm.muuttoja.fi/continue/{$request['id']}">tästà</a> linkistà.<br> -->
Muuttolaatikoiden vuokraus päättyy huomenna {{ $request['end_date'] }}. Jos tarvetta lisäà aikaa jatkaa vuokran tästä linkistà.<br>
@component('mail::button', ['url' => "https://confirm.muuttoja.fi/continue/{$request['id']}"])
    Tästä
@endcomponent

Kiitos!,<br>
{{ config('app.name') }}
@endcomponent
