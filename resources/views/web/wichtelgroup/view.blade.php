@extends('layouts.app')

@section('content')
    <wichtelgroup-view
        :user-id="{{ $userId }}"
        :group="{{ $group }}"
        :buddy="{{ $buddy }}"
        :is-admin="{{ $isAdmin ? 'true' : 'false' }}"
    ></wichtelgroup-view>
@endsection
