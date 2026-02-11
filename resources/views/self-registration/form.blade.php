@extends('layouts.app')

@section('content')
    <section id="landing-page" class="min-h-screen flex flex-col">
        <nav class="py-4">
            <div class="container mx-auto px-4">
                <a class="text-2xl font-bold text-white" href="/">Wichtel.me</a>
            </div>
        </nav>

        <div class="flex-1 flex items-center justify-center">
            <div class="container mx-auto px-4">
                <div class="max-w-xl mx-auto">
                    <h1 class="text-4xl font-bold text-white mb-4 text-center">
                        Gruppe beitreten: {{ $group->name }}
                    </h1>
                    <p class="text-lg text-white/90 mb-8 text-center">
                        Trage deine Daten ein, um der Wichtel-Gruppe beizutreten.
                    </p>

                    @if (session('error'))
                        <div class="bg-red-500/20 border border-red-500 text-red-100 px-4 py-3 rounded mb-6">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('join.register', $joinToken) }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="name" class="block text-white font-medium mb-2">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="Dein Name">
                            @error('name')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-white font-medium mb-2">E-Mail</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="deine@email.de">
                            @error('email')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="wishlist" class="block text-white font-medium mb-2">Wunschliste (optional)</label>
                            <textarea name="wishlist" id="wishlist" rows="4"
                                      class="w-full px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      placeholder="Was wünschst du dir?">{{ old('wishlist') }}</textarea>
                            @error('wishlist')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg text-lg transition-colors">
                            Anmelden
                        </button>
                    </form>

                    <p class="text-white/60 text-sm mt-6 text-center">
                        Du erhältst eine E-Mail zur Bestätigung deiner Anmeldung.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
