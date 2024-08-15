@component('mail::message')
# Maksu vastaanotettu

Hei {{ $request['first_name'] }} {{ $request['last_name'] }},<br><br>

Tilauksen tiedot: <br>
Tilausnumerosi: {{ $request['booking_number'] }}

Määrä: {{ $request['price'] }}€<br>

Tilauksesi on jatkettu {{ $request['date'] }} asti.<br><br>

Kiitos!,<br>
{{ config('app.name') }}
@endcomponent
