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
                        Fast geschafft!
                    </h1>
                    <p class="text-lg text-white/90 mb-8">
                        Wir haben dir eine E-Mail an deine Adresse geschickt.
                        Bitte bestätige deine Anmeldung durch Klick auf den Link in der E-Mail.
                    </p>
                    <p class="text-white/60">
                        Gruppe: <strong class="text-white">{{ $group->name }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
