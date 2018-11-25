@extends('layouts.app')

@section('content')
    <section id="landing-page" class="hero is-info is-fullheight">
        <div class="hero-head">
            <nav class="navbar">
                <div class="container">
                    <div class="navbar-brand">
                        <a class="navbar-item title" href="/">Wichtel.me</a>
                    </div>
                </div>
            </nav>
        </div>

        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="column is-6 is-offset-3">
                    <h1 class="title">
                        Wichteln, ganz einfach!
                    </h1>
                    <h2 class="subtitle">
                        Mit Wichtel.me kannst du deine ganz private Wichtel-Aktion in nur 3 Schritten starten. Erstelle eine neue Wichtelgruppe, lade deine Freunde ein und starte die Auslosung.
                    </h2>
                    <div class="box">
                        <div class="field">
                            <h3 class="title has-text-black">Neue Wichtelgruppe erstellen</h3>
                            <create-wichtelgroup></create-wichtelgroup>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
