@extends('layouts.app')

@section('title', 'Planning des Inspections - CNPRI')

@if(session('success'))
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        {{ session('success') }}
    </div>
</div>
@endif

@php
    // Paramètres de tri issus de l'URL (sans tri/sens/page pour pouvoir basculer)
    $triQuery = collect(request()->query())->except(['tri', 'sens', 'page'])->all();
    $triUrl = function (string $col) use ($filters, $triQuery) {
        $sens = ($filters['tri'] === $col && $filters['sens'] === 'asc') ? 'desc' : 'asc';
        return route('inspections.index', array_merge($triQuery, ['tri' => $col, 'sens' => $sens]));
    };
    $triArrow = function (string $col) use ($filters) {
        if ($filters['tri'] === $col) {
            return $filters['sens'] === 'asc' ? '▲' : '▼';
        }
        return '↕';
    };
    $groupeUrl = $filters['groupe']
        ? route('inspections.index', collect(request()->query())->except(['groupe', 'page'])->all())
        : route('inspections.index', collect(request()->query())->except(['page'])->merge(['groupe' => '1'])->all());
    $labelsType = [
        'réglementaire' => "I. INSPECTIONS REGLEMENTAIRES",
        'investigation' => "II. INSPECTIONS D'INVESTIGATION",
        'inopiné' => "III. INSPECTIONS INOPINEES",
    ];
@endphp

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Historique et Planning des Inspections</h1>
            <a href="/inspections/create" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                + Programmer une Inspection
            </a>
        </div>

        {{-- Bandeau de filtres --}}
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form method="GET" action="{{ route('inspections.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-gray-700 text-xs font-bold mb-1" for="recherche">Recherche</label>
                    <input type="text" name="recherche" id="recherche" value="{{ $filters['recherche'] ?? '' }}"
                           placeholder="Établissement, objet…" class="w-full shadow border rounded px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>

                <div>
                    <label class="block text-gray-700 text-xs font-bold mb-1" for="etablissement_id">Établissement</label>
                    <select name="etablissement_id" id="etablissement_id" class="w-full shadow border rounded px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">Tous</option>
                        @foreach($etablissements as $est)
                            <option value="{{ $est->id }}" {{ (string) ($filters['etablissement_id'] ?? '') === (string) $est->id ? 'selected' : '' }}>
                                {{ $est->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-xs font-bold mb-1" for="inspecteur_id">Inspecteur</label>
                    <select name="inspecteur_id" id="inspecteur_id" class="w-full shadow border rounded px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">Tous</option>
                        @foreach($inspecteurs as $insp)
                            <option value="{{ $insp->id }}" {{ (string) ($filters['inspecteur_id'] ?? '') === (string) $insp->id ? 'selected' : '' }}>
                                {{ $insp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-xs font-bold mb-1" for="type_filtre">Type</label>
                    <select name="type" id="type_filtre" class="w-full shadow border rounded px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">Tous</option>
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" {{ ($filters['type'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-xs font-bold mb-1" for="statut_filtre">Statut</label>
                    <select name="statut" id="statut_filtre" class="w-full shadow border rounded px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">Tous</option>
                        @foreach($statuts as $value => $label)
                            <option value="{{ $value }}" {{ ($filters['statut'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-xs font-bold mb-1" for="date_debut">Du</label>
                    <input type="date" name="date_debut" id="date_debut" value="{{ $filters['date_debut'] ?? '' }}"
                           class="w-full shadow border rounded px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>

                <div>
                    <label class="block text-gray-700 text-xs font-bold mb-1" for="date_fin">Au</label>
                    <input type="date" name="date_fin" id="date_fin" value="{{ $filters['date_fin'] ?? '' }}"
                           class="w-full shadow border rounded px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded shadow transition">
                        Filtrer
                    </button>
                    <a href="{{ route('inspections.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-semibold px-3 py-2">
                        Effacer
                    </a>
                </div>
                <input type="hidden" name="tri" value="{{ $filters['tri'] }}">
                <input type="hidden" name="sens" value="{{ $filters['sens'] }}">
            </form>
            <p class="text-xs text-gray-500 mt-3">
                {{ $inspections->total() }} inspection(s)
                @if($inspections->hasPages())
                    <span class="text-gray-400">— page {{ $inspections->currentPage() }}/{{ $inspections->lastPage() }}, affichage {{ $inspections->firstItem() ?? 0 }}–{{ $inspections->lastItem() ?? 0 }}</span>
                @endif
                @if(collect($filters)->except(['tri', 'sens'])->filter()->isNotEmpty())
                    <span class="text-blue-600">— filtres actifs</span>
                @endif
                @if($filters['groupe'])
                    <span class="text-indigo-600">— groupé par type puis province</span>
                @endif
                <a href="{{ $groupeUrl }}" class="ml-3 inline-flex items-center {{ $filters['groupe'] ? 'text-gray-600 hover:text-gray-800' : 'text-indigo-600 hover:text-indigo-800' }} font-semibold">
                    {{ $filters['groupe'] ? 'Afficher en liste simple' : 'Grouper par type et province' }}
                </a>
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @if($filters['groupe'])
                                Date
                            @else
                            <a href="{{ $triUrl('date') }}" class="inline-flex items-center gap-1 hover:text-blue-700 transition">
                                Date <span class="text-gray-400">{{ $triArrow('date') }}</span>
                            </a>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @if($filters['groupe'])
                                Établissement
                            @else
                            <a href="{{ $triUrl('etablissement') }}" class="inline-flex items-center gap-1 hover:text-blue-700 transition">
                                Établissement <span class="text-gray-400">{{ $triArrow('etablissement') }}</span>
                            </a>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @if($filters['groupe'])
                                Type
                            @else
                            <a href="{{ $triUrl('type') }}" class="inline-flex items-center gap-1 hover:text-blue-700 transition">
                                Type <span class="text-gray-400">{{ $triArrow('type') }}</span>
                            </a>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inspecteurs</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @if($filters['groupe'])
                                Statut
                            @else
                            <a href="{{ $triUrl('statut') }}" class="inline-flex items-center gap-1 hover:text-blue-700 transition">
                                Statut <span class="text-gray-400">{{ $triArrow('statut') }}</span>
                            </a>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rapport</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php $lastType = null; $lastProvince = null; @endphp
                    @forelse($inspections as $inspection)
                    @php
                        $provGroupe = $inspection->establishment->province
                            ?: ($inspection->establishment->city ?: 'Autres provinces');
                    @endphp
                    @if($filters['groupe'])
                        @if($lastType !== $inspection->type)
                        <tr class="bg-indigo-50 border-t-2 border-indigo-200">
                            <td colspan="7" class="px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-indigo-800">
                                {{ $labelsType[$inspection->type] ?? mb_strtoupper($inspection->type) }}
                            </td>
                        </tr>
                        @php $lastProvince = null; @endphp
                        @endif
                        @if($lastProvince !== $provGroupe)
                        <tr class="bg-gray-50">
                            <td colspan="7" class="px-6 py-1.5 pl-10 text-xs font-semibold text-gray-600">
                                {{ $provGroupe }}
                            </td>
                        </tr>
                        @endif
                        @php $lastType = $inspection->type; $lastProvince = $provGroupe; @endphp
                    @endif
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            @if($inspection->start_date && $inspection->end_date)
                                {{ $inspection->start_date->format('d/m/Y') }}
                                @if($inspection->start_date != $inspection->end_date)
                                    - {{ $inspection->end_date->format('d/m/Y') }}
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $inspection->establishment->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $inspection->type }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            @foreach($inspection->inspectors as $inspector)
                                <span class="inline-block bg-gray-100 rounded-full px-2 py-1 text-xs font-semibold text-gray-700 mr-1 mb-1">
                                    {{ $inspector->name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($inspection->status == 'Brouillon') bg-gray-100 text-gray-800
                                @elseif($inspection->status == 'Approuvée') bg-blue-100 text-blue-800
                                @elseif($inspection->status == 'En cours') bg-yellow-100 text-yellow-800
                                @elseif($inspection->status == 'Effectuée') bg-green-100 text-green-800
                                @elseif($inspection->status == 'Annulée') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ $inspection->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($inspection->report_path)
                            <a href="{{ asset('storage/' . $inspection->report_path) }}" target="_blank" class="text-red-600 hover:text-red-800 flex items-center">
                                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h3l2 2V4a2 2 0 00-2-2H9zM11 11H9v-1h2v1zm0-2H9V8h2v1z"></path>
                                </svg>
                                PDF
                            </a>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('inspections.show', $inspection->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Détails</a>
                            @if(in_array($inspection->status, ['Brouillon', 'Approuvée', 'En cours']))
                            <a href="{{ route('inspections.edit', $inspection->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Modifier</a>
                            @endif
                            <form action="{{ route('inspections.destroy', $inspection->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Confirmez-vous la suppression définitive de cette inspection ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">
                            Aucune inspection enregistrée ou programmée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inspections->hasPages())
            <div class="mt-6 flex justify-end">
                {{ $inspections->links() }}
            </div>
        @endif
    </div>
@endsection
