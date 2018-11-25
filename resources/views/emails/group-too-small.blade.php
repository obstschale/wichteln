@component('mail::message')
# Hallo {{ $admin->name }},

wir haben die Auslosung gestoppt, da in deiner Gruppe zu wenig Teilnehmer zugesagt haben. Lade doch noch ein paar Teilnehmer ein. Mit einer größeren Gruppe macht es auch gleich viel mehr Spaß.

@component('mail::button', ['url' => $linkToGroup, 'color' => 'success'])
zu Gruppenseite
@endcomponent

Liebe Grüße,<br>
{{ config('app.name') }}
@endcomponent
