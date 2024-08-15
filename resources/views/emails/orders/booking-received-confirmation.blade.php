@component('mail::message')
# Varauksesi on vahvistettu

Hei {{ $request['first_name'] }} {{ $request['last_name'] }},<br><br>

Kiitos varauksestasi! Haluamme ilmoittaa, että olemme vastaanottaneet varauksesi ja käsittelemme sitä mahdollisimman pian.<br><br>

Otamme sinuun yhteyttä 24 tunnin kuluessa vain, jos jokin menee pieleen. Muussa tapauksessa älä odota muita vahvistuksia, sillä tämä sähköposti on jo vahvistus.<br><br>

Tilauksen tiedot: <br>
Tilausnumerosi: {{ $request['booking_number'] }}<br>
@if(isset($request['first_name']) && isset($request['last_name']))
    Koko nimi: {{ $request['first_name'] }} {{ $request['last_name'] }}<br>
@endif
Email: {{ $request['email']  }}<br>
@if(isset($request['phone']))
Puhelinnumero: {{ $request['phone'] }},<br><br>
@endif

Muuttopäivä: {{ $request['start_date']}}<br>
@if(isset($request['start_time']) && isset($request['end_time']))
Aloitusaika: {{ $request['start_time']}} - {{ $request['end_time']}}<br>
@endif
@if(isset($request['product_name']))
Tuote: {{ $request['product_name']}}<br>
@endif
@if(isset($request['start_square_meters']))
Asunnon pinta-ala (m2): {{ $request['start_square_meters']}}<br>
@endif
Maksettava summa: {{ $request['price']}}€<br>
@if(isset($request['start_comment']))
Tarjous: {!! $request['start_comment'] !!}<br><br>
@endif


@if(isset($request['start_address']))
Katuosoite mistä muutto alkaa: {{ $request['start_address']}}<br>
@endif
@if(isset($request['start_door_number']))
Talon numero, rappu ja ovinumero: {{ $request['start_door_number']}}<br>
@endif
@if(isset($request['start_door_code']))
Lisätietoa (Esim. ovikoodi): {{ $request['start_door_code']}}<br>
@endif
@if(isset($request['start_floor']))
Valitse kerros: {{ $request['start_floor']}}<br>
@endif
@if(isset($request['start_outdoor_distance']))
Kuinka lähelle muuttoautolla pääsee: {{ $request['start_outdoor_distance']}}<br>
@endif
@if(isset($request['start_elevator']))
Hissin koko: {{ $request['start_elevator']}}<br>
@endif
@if(isset($request['start_storage']))
Varasto: {{ $request['start_storage']}}<br>
@endif
@if(isset($request['start_storage_m2']))
Varaston koko (m2): {{ $request['start_storage_m2']}}<br>
@endif
@if(isset($request['start_storage_floor']))
Varaston kerros: {{ $request['start_storage_floor']}}<br>
@endif

@if(isset($request['end_address']))
Katuosoite mihin muutetaan: {{ $request['end_address']}}<br>
@endif
@if(isset($request['end_door_number']))
Talon numero, rappu ja ovinumero: {{ $request['end_door_number']}}<br>
@endif
@if(isset($request['end_door_code']))
Lisätietoa (Esim. ovikoodi): {{ $request['end_door_code']}}<br>
@endif
@if(isset($request['end_floor']))
Valitse kerros: {{ $request['end_floor']}}<br>
@endif
@if(isset($request['end_outdoor_distance']))
Kuinka lähelle muuttoautolla pääsee: {{ $request['end_outdoor_distance']}}<br>
@endif
@if(isset($request['end_elevator']))
Hissin koko: {{ $request['end_elevator']}}<br>
@endif
@if(isset($request['end_storage']))
Varasto: {{ $request['end_storage']}}<br>
@endif
@if(isset($request['end_storage_m2']))
Varaston koko (m2): {{ $request['end_storage_m2']}}<br>
@endif
@if(isset($request['end_storage_floor']))
    Varaston kerros: {{ $request['end_storage_floor']}}<br>
@endif

@if(isset($request['product_name']))
Muuttotarjouksen tiedot:<br>
Tuote: {{ $request['product_name']}}<br>
@endif
@if(isset($request['unit_description']))
Kuvaus: {!! $request['unit_description'] !!}<br><br>
@endif

Ystävällisin terveisin,<br><br>

Muutto JA Oy <br><br>

info@muuttotarjous.fi <br>

045 645 40 33 <br>

Kiitos!,<br>
{{ config('app.name') }}
@endcomponent
