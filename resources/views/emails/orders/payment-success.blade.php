@component('mail::message')
# Tilaus on maksettu, Kiitos.

Hei {{ $request['first_name'] }} {{ $request['last_name'] }},<br><br>

Tilauksen tiedot: <br>
Tilausnumerosi: {{ $request['booking_number'] }}

Puhelin: {{ $request['phone'] }},<br>
Sähköposti: {{ $request['email']  }}<br><br>

Vuokra-aika:
{{ $request['start_date']}} @if(!isset($request['start_address']) or $request['start_address'] == '') Avoinna Ma-Pe klo 7.30 – 17.00 (heinäkuussa klo 7.30–16.00), Hämeentie 155, Helsinki @endif - {{ $request['end_date'] }}@if(!isset($request['end_address']) or $request['end_address'] == '') Avoinna Ma-Pe klo 7.30 – 17.00 (heinäkuussa klo 7.30–16.00), Hämeentie 155, Helsinki @endif <br><br>

Kuljetuspalvelu: Kuljettaja lähettää reitin tiedot tekstiviestillä aamulla, kuljettaja myös soittaa 30 min ennen tuloa. <br><br>

Osoite, johon muuttolaatikot toimitetaan: {{ $request['start_address'] }}<br>
Toimitusosoitteen talonumero: {{ $request['start_door_number']  }} Lisätiedot: {{ $request['start_door_code']  }}<br><br>

Osoite, josta muuttolaatikot noudetaan: {{ $request['end_address'] }}<br>
Nouto-osoitteen talonumero: {{ $request['end_door_number']  }} Lisätiedot: {{ $request['end_door_code']  }}<br><br>

Muuttolaatikoiden lukumäärä: {{ $request['quantity'] }}<br><br>

Muuttolaatikot vuokra: {{ $request['rent_price']  }} €<br>
Muuttolaatikoiden tuonti: {{ $request['start_price']  }} €<br>
Muuttolaatikoiden nouto: {{ $request['end_price']  }} €<br>
Maksettava summa yhteensä: {{ $request['price']  }} €<br><br>

Muuttopalvelut: <a href="https://muuttotarjous.fi">Muuttotarjous</a><br>

Muuttolaatikot: <a href="https://vuokralaatikot.fi">Vuokralaatikot</a><br>

Ystävällisin terveisin,<br><br>

Muutto JA Oy <br><br>

info@muuttotarjous.fi <br>

045 645 40 33 <br>

Kiitos!,<br>
{{ config('app.name') }}
@endcomponent
