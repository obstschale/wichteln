@extends('layouts.email')

@section('title', 'You Buddy is...')

@section('content')
    <h2>Hello {{ $user->name }},</h2>

    <p>Secret Santa is coming quickly. You group <strong>{{ $group->name }}</strong> has raffled your buddy.</p>

    <p>Your buddy is:</p>

    <h3>{{ $buddy->name }}</h3>

    @if (is_null($buddy->groups[0]->pivot->wishlist))
        <p>Your buddy haven't provided a wish list. That means you have to get creative.</p>
    @else
        <p>You buddy provided a wish list:</p>
        <p>Wishlist: {{ $buddy->groups[0]->pivot->wishlist }}</p>
    @endif

    <em>Happy Holidays.</em>
@endsection
