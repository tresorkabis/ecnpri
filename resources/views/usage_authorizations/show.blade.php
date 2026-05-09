@extends('layouts.app')

@section('title', 'Autorisation - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <div class="flex justify-between items-center mb-6">
            <div>
                <nav class="text-sm font-medium text-gray-500 mb-2">
                    <a href="/usage-authorizations" class="hover:text-gray-700">Autorisations</a> /
                    <span class="text-gray-900">{{ $authorization->reference_number }}</span>
                </nav>
                <h1 class="text-3xl font-bold text-gray-800">{{ $authorization->reference_number }}</h1>
                <p class="text-gray-600">{{ $authorization->authorization_type }} | {{ $authorization->scope }}</p>
            </div>
            <div class="flex items-center gap-2">
                @if($previousAuthorization)
                    <a href="/usage-authorizations/{{ $previousAuthorization->id }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 shadow-sm transition hover:border-gray-300 hover:text-gray-700"
                       title="Autorisation précédente: {{ $previousAuthorization->reference_number }}"
                       aria-label="Voir l'autorisation précédente">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @endif
                <a href="/usage-authorizations/{{ $authorization->id }}/edit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded shadow transition">
                    Modifier
                </a>
                @if($nextAuthorization)
                    <a href="/usage-authorizations/{{ $nextAuthorization->id }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 shadow-sm transition hover:border-gray-300 hover:text-gray-700"
                       title="Autorisation suivante: {{ $nextAuthorization->reference_number }}"
                       aria-label="Voir l'autorisation suivante">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2">Données réglementaires</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Type d'autorisation</p>
                            <p class="text-lg font-bold text-emerald-800">{{ $authorization->authorization_type }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Portée</p>
                            <p class="text-gray-900">{{ $authorization->scope }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Autorité de délivrance</p>
                            <p class="text-gray-900">{{ $authorization->issuing_authority ?? 'CNPRI' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Date de délivrance</p>
                            <p class="text-gray-900">{{ $authorization->issued_at?->format('d/m/Y') ?? 'Non spécifiée' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Date d'expiration</p>
                            <p class="text-gray-900">{{ $authorization->expires_at?->format('d/m/Y') ?? 'Non spécifiée' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-2">Titulaire et observations</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Établissement</p>
                            <p class="text-gray-900 font-medium">
                                <a href="/establishments/{{ $authorization->establishment_id }}" class="text-blue-600 hover:underline">{{ $authorization->establishment->name }}</a>
                            </p>
                            <p class="text-sm text-gray-500">{{ $authorization->establishment->city }}, {{ $authorization->establishment->province }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Observations</p>
                            <p class="text-gray-900">{{ $authorization->notes ?? 'Aucune observation enregistrée.' }}</p>
                        </div>
                    </div>
                </div>

                @php
                    $showsSources = in_array($authorization->scope, ['Sources', 'Sources et Équipements']);
                    $showsEquipment = in_array($authorization->scope, ['Équipements', 'Sources et Équipements']);
                @endphp

                @if($showsSources || $showsEquipment)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-4 border-b pb-2">Sources et Équipements Concernés</h2>

                        @if($showsSources)
                            <div class="{{ $showsEquipment ? 'mb-6' : '' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-amber-800">Sources radioactives</h3>
                                    <span class="px-2 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">
                                        {{ $authorization->establishment->radioactiveSources->count() }}
                                    </span>
                                </div>

                                @if($authorization->establishment->radioactiveSources->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Isotope</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">N° Série</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Réf. CNPRI</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                @foreach($authorization->establishment->radioactiveSources as $source)
                                                    <tr>
                                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                                            <a href="/radioactive-sources/{{ $source->id }}" class="text-blue-600 hover:underline">{{ $source->isotope }}</a>
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $source->serial_number }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $source->regulatory_number ?? 'N/A' }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $source->status }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">Aucune source radioactive n'est enregistrée pour cet établissement.</p>
                                @endif
                            </div>
                        @endif

                        @if($showsEquipment)
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-blue-800">Équipements</h3>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">
                                        {{ $authorization->establishment->equipment->count() }}
                                    </span>
                                </div>

                                @if($authorization->establishment->equipment->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Modèle</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">N° Série</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Réf. CNPRI</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                @foreach($authorization->establishment->equipment as $equipment)
                                                    <tr>
                                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                                            <a href="/equipment/{{ $equipment->id }}" class="text-blue-600 hover:underline">{{ $equipment->name }}</a>
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $equipment->model ?? 'N/A' }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $equipment->serial_number ?? 'N/A' }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $equipment->regulatory_number ?? 'N/A' }}</td>
                                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $equipment->status }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">Aucun équipement n'est enregistré pour cet établissement.</p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 border-t-4
                    {{ $authorization->status === 'Valide' ? 'border-green-500' : '' }}
                    {{ $authorization->status === 'Expirée' ? 'border-red-500' : '' }}
                    {{ $authorization->status === 'Suspendue' ? 'border-yellow-500' : '' }}
                    {{ $authorization->status === 'En attente' ? 'border-slate-400' : '' }}">
                    <h2 class="text-lg font-semibold mb-4">Statut</h2>
                    <div class="text-center py-4">
                        <span class="px-4 py-2 rounded-full text-lg font-bold
                            {{ $authorization->status === 'Valide' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $authorization->status === 'Expirée' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $authorization->status === 'Suspendue' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $authorization->status === 'En attente' ? 'bg-slate-100 text-slate-700' : '' }}">
                            {{ $authorization->status }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-4 text-center">Dernière mise à jour le {{ $authorization->updated_at->format('d/m/Y') }}</p>
                </div>

                <div class="mt-6 bg-emerald-50 rounded-lg p-4 border border-emerald-100">
                    <h3 class="font-bold text-emerald-800 mb-2">Source métier</h3>
                    <p class="text-sm text-emerald-700">
                        Les types proposés dans ce formulaire ont été alignés sur les rubriques et formulaires publiés sur le site officiel du CNPRI.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
