@component('mail::message')
# Hallo {{ $user->name }},

was für ein Spaß! Wichteln ist doch immer wieder eine tolle Sache. Wir hoffen deine Wichtel-Aktion verlieft wie du es geplant hast.

Wir möchten Dich nur darüber informieren, dass wir in einer Woche alle Daten, die zu dieser Gruppen gehören, löschen werden. Wir brauchen diese Daten nicht länger, also warum sollten wir deine Daten weiterhin speichern. Wir werden daher die Gruppe und die Teilnehmer Informationen löschen.

Falls deinen Wunschzettel oder sonstige Daten noch selber sichern möchtest, dann tu das bitte bevor wir die Daten löschen.

Liebe Grüße vom Ober-Wichtel<br>
{{ config('app.name') }}
@endcomponent
