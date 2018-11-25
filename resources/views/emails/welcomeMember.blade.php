@component('mail::message')
# Herzliche willkommen {{ $user->name }},

wie schön, dass du teilnimmst. Wir wünschen dir und deiner Gruppe viel Spaß beim Wichteln.

Auf der Gruppenseite kannst du deinen Wunschzettel anpassen, den derjenige bekommt, der dich bei der Auslosung zieht.

@component('mail::button', ['url' => $linkToGroup])
Zur Gruppe
@endcomponent

Liebe Grüße,<br>
{{ config('app.name') }}
@endcomponent
