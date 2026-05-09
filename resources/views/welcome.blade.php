@extends('layouts.app')

@section('content')
    <div class="flex-grow container mx-auto px-4 py-16 flex flex-col items-center justify-center text-center">
        <div class="mb-8">
            <svg class="w-24 h-24 text-blue-800 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
        </div>
        
        <h1 class="text-5xl font-extrabold text-gray-900 mb-4">Système de Gestion des Inspections</h1>
        <p class="text-xl text-gray-600 mb-12 max-w-2xl">
            Plateforme centralisée pour le suivi des établissements, la gestion des équipements radiologiques et la supervision des missions d'inspection en République Démocratique du Congo.
        </p>

        <div class="flex flex-col md:flex-row gap-6">
            <a href="/dashboard" class="bg-blue-800 text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-1">
                Accéder au Tableau de Bord
            </a>
            <a href="/establishments" class="bg-white text-blue-800 border-2 border-blue-800 px-8 py-4 rounded-lg font-bold text-lg hover:bg-blue-50 transition transform hover:-translate-y-1">
                Gérer les Établissements
            </a>
            <a href="/radioactive-sources" class="bg-white text-green-800 border-2 border-green-800 px-8 py-4 rounded-lg font-bold text-lg hover:bg-green-50 transition transform hover:-translate-y-1">
                Inventaire des Sources
            </a>
        </div>

        <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8 text-left max-w-5xl">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-lg text-blue-900 mb-2">Établissements</h3>
                <p class="text-gray-600 text-sm">Gestion complète des centres médicaux, sites miniers et industries utilisant des sources de rayonnement.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-lg text-blue-900 mb-2">Conformité</h3>
                <p class="text-gray-600 text-sm">Suivi rigoureux des constats d'inspection et des recommandations pour assurer la sécurité nucléaire.</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-lg text-blue-900 mb-2">Rapports</h3>
                <p class="text-gray-600 text-sm">Génération et archivage sécurisé des rapports d'inspection et des certificats de conformité.</p>
            </div>
        </div>
    </div>
@endsection
