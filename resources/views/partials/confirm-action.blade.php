@extends('layouts.app')

@section('content')
    <section id="landing-page" class="min-h-screen flex flex-col">
        <nav class="py-4">
            <div class="container mx-auto px-4">
                <a class="text-2xl font-bold text-white" href="/">Wichtel.me</a>
            </div>
        </nav>

        <div class="flex-1 flex items-center justify-center">
            <div class="container mx-auto px-4 text-center">
                <div class="max-w-xl mx-auto">
                    @if ($action === 'approve')
                        <h1 class="text-4xl font-bold text-white mb-4">
                            Einladung annehmen?
                        </h1>
                        <p class="text-lg text-white/90 mb-8">
                            Klicke auf den Button, um deine Teilnahme zu bestätigen.
                        </p>
                    @else
                        <h1 class="text-4xl font-bold text-white mb-4">
                            Einladung ablehnen?
                        </h1>
                        <p class="text-lg text-white/90 mb-8">
                            Klicke auf den Button, um die Einladung abzulehnen.
                        </p>
                    @endif

                    <form method="POST" action="{{ route('token.confirm') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="action" value="{{ $action }}">

                        @if ($action === 'approve')
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition-colors">
                                Ja, ich nehme teil!
                            </button>
                        @else
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition-colors">
                                Nein, ich lehne ab
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
