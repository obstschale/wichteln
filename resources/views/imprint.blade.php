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
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Impressum</h3>

                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Verantwortlich für den Inhalt der Webseite nach § 55 Abs. 2 RStV</h4>
                        <p class="text-gray-700">Hans-Helge Bürger</p>
                        <p class="text-gray-700">Schillertraße 41</p>
                        <p class="text-gray-700 mb-6">67578 Gimbsheim</p>

                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Kontakt:</h4>
                        <p class="text-gray-700">E-Mail: <a href="mailto:webmaster@hanshelgebuerger.de" class="text-green-600 hover:underline">webmaster@hanshelgebuerger.de</a></p>
                        <p class="text-gray-700 mb-6">Telefon: 015771583142</p>

                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Hinweis</h4>
                        <p class="text-gray-700 mb-4">Die Informationen auf dieser Webseite wurden nach bestem Wissen und Gewissen zusammengestellt. Gleichwohl birgt die Datenschutz-Grundverordnung (DSGVO) eine Reihe juristischer Unschärfen und/oder Regelungslücken. Die notwendige Rechtssicherheit und -klarheit werden voraussichtlich erst gerichtliche Verfahren auf nationaler Ebene oder vor dem Europäischen Gerichtshof bringen. Daher erheben dieser Flyer und die darin bereitgestellten Inhalte keinen Anspruch auf Rechtsverbindlichkeit, Aktualität und Vollständigkeit.</p>
                        <p class="text-gray-500 text-sm">Stand aller Informationen: Mai 2018</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
