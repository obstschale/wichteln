@component('mail::message')
# Hallo {{ $user->name }},

für die Gruppe "{{ $group->name }}" wurden die Wichtel gezogen.

@component('mail::panel')
## Dein Wichtel ist:

@component('mail::button', ['url' => $linkToGroup, 'color' => 'success'])
{{ $buddy->name }}
@endcomponent

@endcomponent

@if (is_null($buddy->groups[0]->pivot->wishlist))
Leider hat dein Wichtel keinen Wunschzettle abgegeben. Da musst du wohl kreativ werden.
@else
Dein Wichtel hat einen Wunschzettel abgegeben. Darauf steht:

**{{ $buddy->groups[0]->pivot->wishlist }}**
@endif

Wir wünschen dir viel Spaß beim Wichteln<br>
{{ config('app.name') }}
@endcomponent

