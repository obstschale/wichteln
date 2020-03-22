@component('mail::message')
# Hallo {{ $admin->name }},

wir möchten dich informieren, dass deine Wichtelgruppe _{{ $group->name }}_ gelöscht wurde. Sowohl die Gruppen-Daten als auch alle Teilnehmer-Daten wurden gelöscht.

Liebe Grüße und bis zum nächsten Mal<br>
dein Ober-Wichtel<br>
{{ config('app.name') }}
@endcomponent
