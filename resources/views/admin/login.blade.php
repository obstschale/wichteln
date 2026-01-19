@extends('layouts.app')

@section('content')
<div class="flex min-h-[60vh] items-center justify-center">
    <div class="w-full max-w-md rounded-lg bg-white p-8 shadow-md">
        <h1 class="mb-6 text-2xl font-bold text-gray-800">Admin Login</h1>

        @isset($error)
            <div class="mb-4 rounded bg-red-100 p-3 text-red-700">{{ $error }}</div>
        @endisset

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-4">
                <label for="password" class="mb-2 block text-sm font-medium text-gray-700">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"
                    required
                    autofocus
                >
            </div>
            <button
                type="submit"
                class="w-full rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
            >
                Login
            </button>
        </form>
    </div>
</div>
@endsection
