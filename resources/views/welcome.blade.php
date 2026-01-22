@extends('layouts.app')

@section('content')
    <section id="landing-page" class="min-h-screen flex flex-col relative overflow-hidden">
        <!-- Animated Snowflakes -->
        <div class="snowflakes" aria-hidden="true">
            @for ($i = 0; $i < 12; $i++)
                <div class="snowflake">❄</div>
            @endfor
        </div>

        <!-- Navigation -->
        <nav class="py-6 relative z-10">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <a class="text-2xl font-bold text-white drop-shadow-lg flex items-center gap-2" href="/">
                    <span class="text-3xl">🎁</span>
                    <span>Wichtel.me</span>
                </a>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="flex-1 flex items-center justify-center relative z-10 py-12">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <!-- Main Heading -->
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-white mb-6 drop-shadow-xl leading-tight">
                        <span class="inline-block relative">
                            <img src="{{ asset('images/elf-hat.svg') }}" alt="" class="elf-hat">W</span>ichteln,<br>
                        <span class="bg-gradient-to-r from-sky-200 via-white to-sky-200 bg-clip-text text-transparent">
                            ganz einfach!
                        </span>
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-xl md:text-2xl text-white/90 mb-12 max-w-2xl mx-auto leading-relaxed drop-shadow-md">
                        Erstelle deine Wichtelgruppe in nur 3 einfachen Schritten.
                        Lade Freunde ein, erstellt Wunschlisten und startet die Auslosung!
                    </p>

                    <!-- Steps -->
                    <div class="grid md:grid-cols-3 gap-6 mb-12">
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 transform hover:scale-105 transition-transform duration-300">
                            <div class="text-4xl mb-3">📝</div>
                            <h3 class="text-lg font-semibold text-white mb-2">1. Gruppe erstellen</h3>
                            <p class="text-white/70 text-sm">Gib deiner Wichtelgruppe einen Namen</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 transform hover:scale-105 transition-transform duration-300">
                            <div class="text-4xl mb-3">👥</div>
                            <h3 class="text-lg font-semibold text-white mb-2">2. Freunde einladen</h3>
                            <p class="text-white/70 text-sm">Lade Freunde per Mail ein oder teile den Link zum selber anmelden</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 transform hover:scale-105 transition-transform duration-300">
                            <div class="text-4xl mb-3">🎲</div>
                            <h3 class="text-lg font-semibold text-white mb-2">3. Auslosen</h3>
                            <p class="text-white/70 text-sm">Die geheime Zuordnung wird automatisch erstellt</p>
                        </div>
                    </div>

                    <!-- CTA Form Card -->
                    <div class="bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl p-8 md:p-10 max-w-lg mx-auto border border-white/50 transform hover:shadow-sky-500/20 transition-shadow duration-300">
                        <div class="flex items-center justify-center gap-3 mb-6">
                            <span class="text-3xl">✨</span>
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Jetzt starten</h2>
                            <span class="text-3xl">✨</span>
                        </div>
                        <create-wichtelgroup></create-wichtelgroup>
                    </div>

                    <!-- Trust indicators -->
                    <div class="mt-10 flex flex-wrap justify-center gap-6 text-white/60 text-sm">
                        <div class="flex items-center gap-2">
                            <span>🔒</span>
                            <span>100% kostenlos</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>⚡</span>
                            <span>Keine Registrierung nötig</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>🎄</span>
                            <span>Sofort loslegen</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
