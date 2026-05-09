<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CNPRI - Gestion des Inspections')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        .ts-control { border-radius: 0.5rem !important; padding: 0.5rem 0.75rem !important; border: 1px solid #d1d5db !important; }
        .ts-dropdown { border-radius: 0.5rem !important; }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal flex flex-col min-h-screen">

    <nav class="bg-blue-800 p-4 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center mb-4 md:mb-0">
                <a href="/" class="text-white font-bold text-xl flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo CNPRI" class="w-10 h-10 mr-2">
                    CNPRI
                </a>
            </div>
            <div class="flex flex-wrap justify-center space-x-2 md:space-x-6 text-sm md:text-base">
                <a href="/" class="text-white hover:text-blue-200 py-1 border-b-2 {{ Request::is('/') ? 'border-white' : 'border-transparent' }}">Accueil</a>
                <a href="/dashboard" class="text-white hover:text-blue-200 py-1 border-b-2 {{ Request::is('dashboard*') ? 'border-white' : 'border-transparent' }}">Dashboard</a>
                <a href="/establishments" class="text-white hover:text-blue-200 py-1 border-b-2 {{ Request::is('establishments*') ? 'border-white' : 'border-transparent' }}">Établissements</a>
                <a href="/usage-authorizations" class="text-white hover:text-blue-200 py-1 border-b-2 {{ Request::is('usage-authorizations*') ? 'border-white' : 'border-transparent' }}">Autorisations</a>
                <a href="/equipment" class="text-white hover:text-blue-200 py-1 border-b-2 {{ Request::is('equipment*') ? 'border-white' : 'border-transparent' }}">Équipements</a>
                <a href="/radioactive-sources" class="text-white hover:text-blue-200 py-1 border-b-2 {{ Request::is('radioactive-sources*') ? 'border-white' : 'border-transparent' }}">Sources</a>
                <a href="/inspectors" class="text-white hover:text-blue-200 py-1 border-b-2 {{ Request::is('inspectors*') ? 'border-white' : 'border-transparent' }}">Inspecteurs</a>
                <a href="/inspections" class="text-white hover:text-blue-200 py-1 border-b-2 {{ Request::is('inspections*') ? 'border-white' : 'border-transparent' }}">Inspections</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        <div class="container mx-auto px-4 mt-6 no-print">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 shadow" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <footer class="bg-gray-800 text-gray-400 py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="mb-2 font-bold text-gray-200">CNPRI - République Démocratique du Congo</p>
            <p class="text-sm italic mb-2">Autorité Réglementaire de la Protection Radiologique et de Sûreté Nucléaire</p>
            <p class="text-xs">4675, Avenue Colonel Ebeya, Kinshasa/Gombe, Immeuble QUITUS, 2e niveau</p>
            <div class="mt-4 border-t border-gray-700 pt-4">
                &copy; {{ date('Y') }} CNPRI. Tous droits réservés.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
