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
                        Schade!
                    </h1>
                    <p class="text-lg text-white/90">
                        Du hast die Einladung abgelehnt.
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
