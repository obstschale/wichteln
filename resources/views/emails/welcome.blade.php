@component('mail::message')
# Hallo {{ $user->name }},

wie schön, dass du eine Wichtel-Aktion startest. Wir wünschen dir und deiner Gruppe viel Spaß beim Wichteln.

@component('mail::panel')
## Einladen

Der nächste Schritt ist weitere Leute zu deiner Wichtel-Aktion einzuladen. Über den folgenden Link gelangst du zu deiner Gruppe. Dort kannst du weitere Teilnehmer einladen und auch sehen wer schon dabei ist und zugesagt hat.

@component('mail::button', ['url' => $linkToGroup, 'color' => 'success'])
    "{{ $group->name }}" ansehen
@endcomponent

<small>Falls der Button nicht funktioniert, rufe diesen Link in deinem Browser auf:<br>{{ $linkToGroup }}</small>

@endcomponent

Nun viel Spaß beim Wichteln<br>
{{ config('app.name') }}
@endcomponent
