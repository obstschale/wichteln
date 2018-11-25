@extends('layouts.app')

@section('content')
    <wichtelgroup-view
        :group="{{ $group }}"
        :is-admin="{{ $isAdmin ? 'true' : 'false' }}"
    ></wichtelgroup-view>
@endsection
