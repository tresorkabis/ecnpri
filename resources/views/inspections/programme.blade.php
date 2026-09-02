@extends('layouts.app')

@section('title', 'Programme des Inspections - CNPRI')

@php
    $typeHeaders = [
        'réglementaire' => 'INSPECTIONS REGLEMENTAIRES',
        'investigation' => 'INSPECTIONS D\'INVESTIGATIONS',
        'inopiné' => 'INSPECTIONS INOPINEES',
    ];
    $roman = ['I', 'II', 'III', 'IV'];
@endphp

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <h1 class="text-3xl font-semibold text-gray-800">Proposition du Programme des Inspections</h1>
            <a href="{{ route('inspections.programme.export', compact('annee', 'semestre', 'statut')) }}"
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm rounded shadow hover:bg-green-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Exporter en Word (.docx)
            </a>
        </div>

        {{-- Filtres de période --}}
        <form method="GET" action="{{ route('inspections.programme') }}" class="bg-white rounded-lg shadow-md p-4 mb-6 flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-gray-700 text-xs font-bold mb-1">Année</label>
                <select name="annee" class="shadow border rounded px-3 py-2 text-sm text-gray-700">
                    @for($y = now()->format('Y') - 1; $y <= now()->format('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ $annee == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-xs font-bold mb-1">Semestre</label>
                <select name="semestre" class="shadow border rounded px-3 py-2 text-sm text-gray-700">
                    <option value="1" {{ $semestre == 1 ? 'selected' : '' }}>Premier</option>
                    <option value="2" {{ $semestre == 2 ? 'selected' : '' }}>Deuxième</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 text-xs font-bold mb-1">Statut</label>
                <select name="statut" class="shadow border rounded px-3 py-2 text-sm text-gray-700">
                    <option value="prevues" {{ $statut == 'prevues' ? 'selected' : '' }}>Prévues (Brouillon, Approuvée, En cours)</option>
                    <option value="toutes" {{ $statut == 'toutes' ? 'selected' : '' }}>Toutes (incluant effectuées)</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded shadow transition">
                Afficher
            </button>
        </form>

        {{-- Résumé --}}
        <p class="text-sm text-gray-500 mb-6">
            Programme du {{ $semestre == 2 ? 'deuxième' : 'premier' }} semestre {{ $annee }}
            — {{ $inspections->sum(fn ($g) => $g['zones']->sum(fn ($z) => $z['inspections']->count())) }} mission(s) planifiée(s).
        </p>

        @forelse($inspections as $index => $groupe)
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 mb-2">
                    {{ $roman[$index] ?? ($index + 1) }}. {{ $typeHeaders[$groupe['type']] ?? strtoupper($groupe['type']) }}
                </h2>
                @foreach($groupe['zones'] as $zone)
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-200">
            <h3 class="font-bold text-gray-800 tracking-wide">{{ strtoupper($zone['nom']) }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-12">N°</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Installation</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Localisation</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inspecteurs</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($zone['inspections'] as $item)
                        @php $inspection = $item['inspection']; @endphp
                        <tr>
                            <td class="px-4 py-3 text-center text-sm text-gray-900">{{ $item['numero'] }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ $inspection->start_date ? $inspection->start_date->format('d/m/Y') : '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900">
                                <a href="{{ route('inspections.show', $inspection->id) }}" class="hover:underline">
                                    {{ $inspection->establishment->name }}
                                </a>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 uppercase">
                                {{ $inspection->establishment->address
                                    ?: ($inspection->establishment->city
                                        ?: $inspection->establishment->province) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">
                                @forelse($inspection->inspectors as $inspector)
                                    <span class="inline-block bg-gray-100 rounded-full px-2 py-1 text-xs font-semibold text-gray-700 mr-1 mb-1">{{ $inspector->name }}</span>
                                @empty
                                    <span class="text-gray-400 italic">À désigner</span>
                                @endforelse
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($inspection->status == 'Brouillon') bg-gray-100 text-gray-800
                                    @elseif($inspection->status == 'Approuvée') bg-blue-100 text-blue-800
                                    @elseif($inspection->status == 'En cours') bg-yellow-100 text-yellow-800
                                    @elseif($inspection->status == 'Effectuée') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ $inspection->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-md p-10 text-center text-gray-500 italic">
                Aucune inspection dans cette période. Programmez des inspections ou changez de filtre.
            </div>
        @endforelse

        @if($inspections->isNotEmpty())
            <div class="text-right mt-10">
                <p class="text-sm text-gray-600">Fait à {{ config('cnpri.signature_ville') }}, le …/…/{{ $annee }}</p>
                <p class="mt-6 font-bold text-gray-800">{{ config('cnpri.signature_name') }}</p>
                <p class="text-gray-700">{{ config('cnpri.signature_title') }}</p>
            </div>
        @endif
    </div>
@endsection