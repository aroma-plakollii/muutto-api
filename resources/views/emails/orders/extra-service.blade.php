@component('mail::message')
# Lisäpalvelun maksua koskeva tiedote

Hei {{ $request['first_name'] }} {{ $request['last_name'] }},<br><br>

Linkki on voimassa 2 tuntia <br>
@component('mail::button', ['url' => $request['payment_url']])
    Maksa nyt
@endcomponent
<br>
Kiitos tilauksestasi Muutto JA Oy:ltä! Haluamme ilmoittaa, että olemme vastaanottaneet pyyntösi lisäpalvelun saamiseksi muuttopalvelusi yhteydessä. Lisäpalvelun tiedot ovat seuraavat: <br><br>

Palvelun kuvaus: {{ $request['description'] }}<br><br>

Tilausnumerosi: {{ $request['booking_number'] }}<br><br>

Maksettava summa: {{ $request['extraPrice']}}€<br><br>


Muuttopalvelut: <a href="https://muuttotarjous.fi">Muuttotarjous</a><br>

Muuttolaatikot: <a href="https://vuokralaatikot.fi">Vuokralaatikot</a><br>

Ystävällisin terveisin,<br><br>

Muutto JA Oy <br><br>

info@muuttotarjous.fi <br>

045 645 40 33 <br>

Kiitos!,<br>
{{ config('app.name') }}
@endcomponent
