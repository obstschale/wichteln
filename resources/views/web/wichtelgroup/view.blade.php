@extends('layouts.app')

@section('content')
    <wichtelgroup-view
        :user-id="{{ $userId }}"
        :group="{{ $group }}"
        :is-admin="{{ $isAdmin ? 'true' : 'false' }}"
    ></wichtelgroup-view>
@endsection
