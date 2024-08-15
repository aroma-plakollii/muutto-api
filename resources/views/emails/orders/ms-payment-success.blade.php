@component('mail::message')
# Tilaus on maksettu, Kiitos.

Hei {{ $request['first_name'] }} {{ $request['last_name'] }},<br><br>

Tilauksen tiedot: <br>
Tilausnumerosi: {{ $request['booking_number'] }}<br>
Koko nimi: {{ $request['first_name'] }} {{ $request['last_name'] }}<br>
Email: {{ $request['email']  }}<br>
Puhelinnumero: {{ $request['phone'] }},<br><br>

Muuttopäivä: {{ $request['start_date']}}<br>
Aloitusaika: {{ $request['start_time']}} - {{ $request['end_time']}}<br>
Tuote: {{ $request['product_name']}}<br>
Asunnon pinta-ala (m2): {{ $request['start_square_meters']}}<br>
Maksettava summa: {{ $request['price']}}€<br><br>

Katuosoite mistä muutto alkaa: {{ $request['start_address']}}<br>
Talon numero, rappu ja ovinumero: {{ $request['start_door_number']}}<br>
Lisätietoa (Esim. ovikoodi): {{ $request['start_door_code']}}<br>
Valitse kerros: {{ $request['start_floor']}}<br>
Kuinka lähelle muuttoautolla pääsee: {{ $request['start_outdoor_distance']}}<br>
Hissin koko: {{ $request['start_elevator']}}<br>
Varasto: {{ $request['start_storage']}}<br>
@if(isset($request['start_storage_m2']))
Varaston koko (m2): {{ $request['start_storage_m2']}}<br>
@endif
@if(isset($request['start_storage_floor']))
Varaston kerros: {{ $request['start_storage_floor']}}<br>
@endif


Katuosoite mihin muutetaan: {{ $request['end_address']}}<br>
Talon numero, rappu ja ovinumero: {{ $request['end_door_number']}}<br>
Lisätietoa (Esim. ovikoodi): {{ $request['end_door_code']}}<br>
Valitse kerros: {{ $request['end_floor']}}<br>
Kuinka lähelle muuttoautolla pääsee: {{ $request['end_outdoor_distance']}}<br>
Hissin koko: {{ $request['end_elevator']}}<br>
Varasto: {{ $request['end_storage']}}<br>
@if(isset($request['end_storage_m2']))
Varaston koko (m2): {{ $request['end_storage_m2']}}<br>
@endif
@if(isset($request['end_storage_floor']))
    Varaston kerros: {{ $request['end_storage_floor']}}<br>
@endif



Muuttopalvelut: <a href="https://muuttotarjous.fi">Muuttotarjous</a><br>

Muuttolaatikot: <a href="https://vuokralaatikot.fi">Vuokralaatikot</a><br>

Ystävällisin terveisin,<br><br>

Muutto JA Oy <br><br>

info@muuttotarjous.fi <br>

045 645 40 33 <br>

Kiitos!,<br>
{{ config('app.name') }}
@endcomponent
