@extends('layouts.app')

@section('title', 'Détails de la Source - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <div>
                <nav class="text-sm font-medium text-gray-500 mb-2">
                    <a href="/radioactive-sources" class="hover:text-gray-700">Sources Radioactives</a> /
                    <span class="text-gray-900">{{ $source->serial_number }}</span>
                </nav>
                <h1 class="text-3xl font-bold text-gray-800">Source: {{ $source->serial_number }}</h1>
            </div>
            <div class="flex items-center gap-2">
                @if($previousSource)
                    <a href="/radioactive-sources/{{ $previousSource->id }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 shadow-sm transition hover:border-gray-300 hover:text-gray-700"
                       title="Source précédente: {{ $previousSource->serial_number }}"
                       aria-label="Voir la source précédente">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @endif
                <a href="/radioactive-sources/{{ $source->id }}/edit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded shadow transition">
                    Modifier
                </a>
                @if($nextSource)
                    <a href="/radioactive-sources/{{ $nextSource->id }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 shadow-sm transition hover:border-gray-300 hover:text-gray-700"
                       title="Source suivante: {{ $nextSource->serial_number }}"
                       aria-label="Voir la source suivante">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2">Caractéristiques Techniques</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Isotope</p>
                            <p class="text-lg font-bold text-blue-800">{{ $source->isotope }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Numéro de Série (Fabricant)</p>
                            <p class="text-gray-900">{{ $source->serial_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Numéro Réglementaire (CNPRI)</p>
                            <p class="text-gray-900">{{ $source->regulatory_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Activité Initiale</p>
                            <p class="text-gray-900 font-bold">{{ $source->initial_activity }} {{ $source->unit }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Date de Référence</p>
                            <p class="text-gray-900">{{ $source->activity_date }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Forme Physique</p>
                            <p class="text-gray-900">{{ $source->physical_form == 'Sealed' ? 'Scellée' : 'Non Scellée' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Catégorie AIEA</p>
                            <p class="text-gray-900">{{ $source->category ?? 'Non classé' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2">Localisation et Détails</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Détenteur (Établissement)</p>
                            <p class="text-gray-900 font-medium">
                                <a href="/establishments/{{ $source->establishment_id }}" class="text-blue-600 hover:underline">{{ $source->establishment->name }}</a>
                            </p>
                            <p class="text-sm text-gray-500">{{ $source->establishment->city }}, {{ $source->establishment->province }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Emplacement Spécifique</p>
                            <p class="text-gray-900">{{ $source->location_details ?? 'Non précisé' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 border-t-4 {{ $source->status == 'Active' ? 'border-green-500' : 'border-red-500' }}">
                    <h2 class="text-lg font-semibold mb-4">Statut Actuel</h2>
                    <div class="text-center py-4">
                        <span class="px-4 py-2 rounded-full text-lg font-bold {{ $source->status == 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $source->status }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-4 text-center">Dernière mise à jour le {{ $source->updated_at->format('d/m/Y') }}</p>
                </div>

                <div class="mt-6 bg-blue-50 rounded-lg p-4 border border-blue-100">
                    <h3 class="font-bold text-blue-800 mb-2">Calcul d'activité</h3>
                    <p class="text-sm text-blue-700">
                        L'activité actuelle est estimée en fonction de la période radioactive de l'isotope (décroissance naturelle).
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
