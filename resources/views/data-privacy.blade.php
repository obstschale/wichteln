@extends('layouts.app')

@section('content')
    <section id="landing-page" class="min-h-screen flex flex-col">
        <nav class="py-4">
            <div class="container mx-auto px-4">
                <a class="text-2xl font-bold text-white" href="/">Wichtel.me</a>
            </div>
        </nav>

        <div class="flex-1 flex items-center justify-center py-8">
            <div class="container mx-auto px-4">
                <div class="max-w-xl mx-auto">
                    <div class="bg-white rounded-lg shadow-lg p-6 space-y-4">
                        <h3 class="text-2xl font-bold text-gray-900">Datenschutzerklärung</h3>

                        <p class="text-gray-700">Hallo,</p>
                        <p class="text-gray-700">schön, dass du dich für meine Datenschutzerklärung interessierst. Auf dieser Seite möchte ich kurz erklären welche Daten wir wofür und wie lange speichern. Betreiber und Veranwtorlich im Sinne der DSGVO ist:</p>

                        <p class="text-gray-700">
                            Hans-Helge Bürger <br>
                            Schillerstraße 41 <br>
                            67578 Gimbsheim
                        </p>

                        <p class="text-gray-700">Wichtel.me ist ein kleiner Service, mit dem man einfach eine Wichtel-Aktion starten kann. Dabei werden Personen eingeladen, ausgelost und jeder Teilnehmer bekommt per Mail mitgeteilt für wen er/sie ein Geschenk besorgen darf.</p>

                        <div>
                            <p class="font-semibold text-gray-800">Erfassung von personenbezogenen Daten</p>
                            <p class="text-gray-700">Die Hürde für Wichtel.me ist bewusst sehr gering gehalten, sodass man sich nicht erst umständlich registrieren muss. Beim erstellen einer neuen Wichtelgruppe wird ein Name der Person und die E-Mail Adresse gespeichert. Es besteht hier keine Klarnamenspflicht und auch eine Trash-Adresse ist natürlich erlaubt. Hauptsache die E-Mail Adresse kann abgerufen werden, denn ohne Mails läuft hier nichts.</p>
                            <p class="text-gray-700 mt-2">Für jede weitere Person die vom Admin (Ersteller) der Gruppe eingeladen wird, wird ebenfalls ein Name und eine E-Mail Adresse gespeichert. Selbe Regel wie oben: Die Richtigkeit dieser Daten sind freiwillig.</p>
                        </div>

                        <div>
                            <p class="font-semibold text-gray-800">Datenverwaltung durch Wichtel.me</p>
                            <p class="text-gray-700">Es werden keine personenbezogene Statistiken erstellt oder anderer Schindluder mit den Daten betrieben. Diese werden nur gespeichert um die Teilnehmer zu benachrichtigen und für die Auslosung zu benutzen.</p>
                        </div>

                        <div>
                            <p class="font-semibold text-gray-800">Übermittlung von Daten an Dritte</p>
                            <p class="text-gray-700">Die Betreiber von Wichtel.me geben personenbezogene Daten an Behörden, andere staatliche Stellen oder Privatpersonen weiter, wenn dazu auf Grund von gesetzlichen Bestimmungen, Gerichtsentscheidungen oder behördlichen Anordnungen eine Verpflichtung besteht.</p>
                            <p class="text-gray-700 mt-2">Wichtel.me kann Dritte mit der Erhebung, Speicherung und Nutzung von personenbezogenen Daten beauftragen (Auftragsverarbeitung). In diesem Fall bleibt Wichtel.me weiterhin verantwortliche Stelle. Diese Dritten unterliegen denselben Datenschutzbestimmungen wie Wichtel.me.</p>
                        </div>

                        <div>
                            <p class="font-semibold text-gray-800">Auskunftsrecht</p>
                            <p class="text-gray-700">Alle Nutzer von Wichtel.me haben das Recht, von Wichtel.me Auskunft über die zu ihrer Person gespeicherten Daten zu verlangen, sowie das Recht auf Berichtigung, Sperrung oder Löschung dieser Daten. Bei Frage, Auskünften oder Widerruferklärungen wenden Sie sich bitte an
                                <a href="mailto:webmaster@hanshelgebuerger.de" class="text-green-600 hover:underline">webmaster@hanshelgebuerger.de</a></p>
                        </div>

                        <div>
                            <p class="font-semibold text-gray-800">Löschung der Daten</p>
                            <p class="text-gray-700">30 Tage nach der Auslosung werden sämtliche Daten zu den Teilnehmern und der Gruppe von den Servern gelöscht. Es besteht kein Bedarf diese Daten weiter zu speichern. Wenn eine neue Wichtel-Aktion gestartet werden soll, einfach eine neue Gruppe erstellen.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
