@extends('layouts.app')

@section('content')
    <wichtelgroup-view
        :group="{{ $group }}"
    ></wichtelgroup-view>
@endsection
