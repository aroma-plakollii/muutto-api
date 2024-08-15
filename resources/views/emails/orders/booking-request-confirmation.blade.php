@component('mail::message')
# Booking Request


Choose the company for your booking request
@component('mail::button', ['url' => "https://confirm.muuttoja.fi/booking-request-prices/{$request['code']}"])
    Booking Request Prices
@endcomponent

Kiitos!,<br>
{{ config('app.name') }}
@endcomponent