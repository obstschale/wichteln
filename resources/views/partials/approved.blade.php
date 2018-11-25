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
                        Super, du nimmst Teil!
                    </h1>
                    <h2 class="subtitle">
                        Wir haben dir eine E-Mail mit weiteren Informationen geschickt.
                    </h2>
                </div>
            </div>
        </div>
    </section>
@endsection
