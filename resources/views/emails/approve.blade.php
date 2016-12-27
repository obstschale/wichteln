@extends('layouts.email')

@section('title', 'You have been invited')

@section('content')
    <h2>Hello {{ $user->name }},</h2>

    <p>you have been invited to join the group "{{ $group->name }}" for Secrete Santa. Please approve your participation by clicking the following link.</p>

    <p><a href="{{ $link }}" title="Approve Wichtel.me">{{ $link }}</a></p>
@endsection
