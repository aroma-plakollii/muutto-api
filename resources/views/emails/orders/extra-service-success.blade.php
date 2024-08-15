@component('mail::message')
# Maksu vastaanotettu

Hei,<br><br>

Olemme vastaanottaneet maksusi seuraavasta lisäpalvelusta:<br>
Määrä: {{ $request['price'] }}€<br>
Kuvaus: {{ $request['description'] }}<br><br>

Ystävällisin terveisin,<br>
{{ config('app.name') }}
@endcomponent
