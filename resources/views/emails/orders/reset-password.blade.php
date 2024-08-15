@component('mail::message')
# Password Reset Request

We received a request to reset your password for your {{ config('app.name') }} account. If you did not make this request, please ignore this email.

To reset your password, please click the following link: <a href="https://dashboard.muuttoja.fi/reset-password/{{ $token }}">Reset Password</a>. 

**Note:** This link will expire after 24 hours or after it has been used to reset your password. 

Kiitos!,<br>
{{ config('app.name') }}
@endcomponent