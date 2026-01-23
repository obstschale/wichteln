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
                    <h1 class="text-4xl font-bold text-white mb-4">
                        Super, du nimmst Teil!
                    </h1>
                    <p class="text-lg text-white/90 mb-8">
                        Wir haben dir eine E-Mail mit weiteren Informationen geschickt.
                    </p>
                    <a href="{{ route('wichtelgroup', ['group' => $group, 'token' => $userToken]) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition-colors inline-block">
                        Zur Gruppe
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
