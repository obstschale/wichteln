@component('mail::message')
# Hallo {{ $user->name }},

du hast dich für die Wichtel-Gruppe **{{ $group->name }}** angemeldet. Bitte bestätige deine E-Mail-Adresse, um der Gruppe beizutreten.

@component('mail::button', ['url' => $approveLink, 'color' => 'success'])
    E-Mail bestätigen
@endcomponent
@component('mail::button', ['url' => $declineLink, 'color' => 'error'])
    Anmeldung abbrechen
@endcomponent

<small>Falls der Button nicht funktioniert, rufe diesen Link in deinem Browser auf:<br>{{ $approveLink }}</small>

Liebe Grüße von<br>
{{ config('app.name') }}
@endcomponent
