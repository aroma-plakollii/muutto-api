@component('mail::message')
# Maksu vastaanotettu

Hei {{ $request['first_name'] }} {{ $request['last_name'] }},<br><br>
Tilausnumerosi: {{ $request['booking_number'] }}

Puhelin: {{ $request['phone'] }},<br>
Sähköposti: {{ $request['email']  }}<br><br>

Uusi lopetuspäivä:{{ $request['date'] }}<br>

Muuttolaatikoiden lukumäärä: {{ $request['quantity'] }}<br><br>

Maksettava summa yhteensä: {{ $request['price']  }} €<br><br>

@component('mail::button', ['url' => $request['payment_url']])
    Maksa nyt
@endcomponent

Kiitos!,<br>
{{ config('app.name') }}
@endcomponent
