@component('mail::message')
# Hallo {{ $user->name }},

du wurdest von **{{ $admin->name }}** eingeladen zum Wichteln in der Gruppe **{{ $group->name }}**. Wenn du an der Wichtel-Aktion teilnehmen willst bestätige deine Teilnahme durch einen Klick auf den Button.

@component('mail::button', ['url' => $approveLink, 'color' => 'success'])
    Ich nehme teil.
@endcomponent
@component('mail::button', ['url' => $declineLink, 'color' => 'error'])
    Ich lehne ab.
@endcomponent

<small>Falls der Button nicht funktioniert, rufe diesen Link in deinem Browser auf:<br>{{ $approveLink }}</small>

Liebe Grüße von<br>
{{ config('app.name') }}
@endcomponent
