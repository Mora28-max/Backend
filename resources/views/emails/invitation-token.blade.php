@component('mail::message')
# Código de Invitación

Tu token de invitación es:

@component('mail::panel')
{{ $token }}
@endcomponent

Usa este código para completar tu registro en el sistema.
@component('mail::button', ['url' => $url])
Crear mi cuenta
@endcomponent

Gracias,<br>
SOAPAMZ
@endcomponent
