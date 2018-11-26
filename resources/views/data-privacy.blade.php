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
                    <div class="box">
                        <div class="field">
                            <h3 class="title has-text-black">Datenschutzerklärung</h3>
                            <p>Hallo,</p>
                            <p>schön, dass du dich für meine Datenschutzerklärung interessierst. Auf dieser Seite möchte ich kurz erklären welche Daten wir wofür und wie lange speichern. Betreiber und Veranwtorlich im Sinne der DSGVO ist:</p>

                            <p>
                                Hans-Helge Bürger <br>
                                Jakoberstraße 51 <br>
                                86152 Augsburg <br>
                            </p>

                            <p>Wichtel.me ist ein kleiner Service, mit dem man einfach eine Wichtel-Aktion starten kann. Dabei werden Personen eingeladen, ausgelost und jeder Teilnehmer bekommt per Mail mitgeteilt für wen er/sie ein Geschenk besorgen darf.</p>

                            <br>

                            <p><strong>Erfassung von personenbezogenen Daten</strong></p>
                            <p>Die Hürde für Wichtel.me ist bewusst sehr gering gehalten, sodass man sich nicht erst umständlich registrieren muss. Beim erstellen einer neuen Wichtelgruppe wird ein Name der Person und die E-Mail Adresse gespeichert. Es besteht hier keine Klarnamenspflicht und auch eine Trash-Adresse ist natürlich erlaubt. Hauptsache die E-Mail Adresse kann abgerufen werden, denn ohne Mails läuft hier nichts.</p>

                            <p>Für jede weitere Person die vom Admin (Ersteller) der Gruppe eingeladen wird, wird ebenfalls ein Name und eine E-Mail Adresse gespeichert. Selbe Regel wie oben: Die Richtigkeit dieser Daten sind freiwillig.</p>

                            <br>

                            <p><strong>Datenverwaltung durch Wichtel.me</strong></p>
                            <p>Es werden keine personenbezogene Statistiken erstellt oder anderer Schindluder mit den Daten betrieben. Diese werden nur gespeichert um die Teilnehmer zu benachrichtigen und für die Auslosung zu benutzen.</p>

                            <br>

                            <p><strong>Übermittlung von Daten an Dritte</strong></p>
                            <p> Die Betreiber von Wichtel.me geben personenbezogene Daten an Behörden, andere staatliche Stellen oder Privatpersonen weiter, wenn dazu auf Grund von gesetzlichen Bestimmungen, Gerichtsentscheidungen oder behördlichen Anordnungen eine Verpflichtung besteht.</p>
                            <p>Wichtel.me kann Dritte mit der Erhebung, Speicherung und Nutzung von personenbezogenen Daten beauftragen (Auftragsverarbeitung). In diesem Fall bleibt Wichtel.me weiterhin verantwortliche Stelle. Diese Dritten unterliegen denselben Datenschutzbestimmungen wie Wichtel.me.</p>

                            <br>

                            <p><strong>Auskunftsrecht</strong></p>
                            <p>Alle Nutzer von Wichtel.me haben das Recht, von Wichtel.me Auskunft über die zu ihrer Person gespeicherten Daten zu verlangen, sowie das Recht auf Berichtigung, Sperrung oder Löschung dieser Daten. Bei Frage, Auskünften oder Widerruferklärungen wenden Sie sich bitte an
                                <a href="mailto:webmaster@hanshelgebuerger.de">webmaster@hanshelgebuerger.de</a></p>

                            <br>

                            <p><strong>Löschung der Daten</strong></p>
                            <p>30 Tage nach der Auslosung werden sämtliche Daten zu den Teilnehmern und der Gruppe von den Servern gelöscht. Es besteht kein Bedarf diese Daten weiter zu speichern. Wenn eine neue Wichtel-Aktion gestartet werden soll, einfach eine neue Gruppe erstellen.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
