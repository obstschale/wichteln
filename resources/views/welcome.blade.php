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
                        Wichteln, ganz einfach!
                    </h1>
                    <p class="text-lg text-white/90 mb-8 leading-relaxed">
                        Mit Wichtel.me kannst du deine ganz private Wichtel-Aktion in nur 3 Schritten starten. Erstelle eine neue Wichtelgruppe, lade deine Freunde ein und starte die Auslosung.
                    </p>
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Neue Wichtelgruppe erstellen</h3>
                        <create-wichtelgroup></create-wichtelgroup>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
