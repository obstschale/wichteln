@component('mail::message')
# Hallo {{ $user->name }},

du wurdest von {{ $admin->name }} eingeladen zum Wichteln. Wenn du an der Wichtel-Aktion teilnehmen willst bestätige deine Teilnahme durch einen Klick auf den Button.

@component('mail::button', ['url' => $link, 'color' => 'success'])
    Ich nehme teil bei "{{ $group->name }}".
@endcomponent
<small>Falls der Button nicht funktioniert, rufe diesen Link in deinem Browser auf:<br>{{ $link }}</small>

Liebe Grüße von<br>
{{ config('app.name') }}
@endcomponent
