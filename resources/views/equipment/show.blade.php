@extends('layouts.app')

@section('title')
    {{ $equipment->name }} - CNPRI
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-start mb-6">
            <div>
                <a href="/equipment" class="text-blue-600 hover:underline text-sm flex items-center mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à l'inventaire
                </a>
                <h1 class="text-3xl font-semibold text-gray-800">{{ $equipment->name }}</h1>
                <p class="text-gray-600">Établissement: <a href="/establishments/{{ $equipment->establishment_id }}" class="text-blue-600 hover:underline">{{ $equipment->establishment->name }}</a></p>
            </div>
            <div class="flex items-center gap-2">
                @if($previousEquipment)
                    <a href="/equipment/{{ $previousEquipment->id }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 shadow-sm transition hover:border-gray-300 hover:text-gray-700"
                       title="Équipement précédent: {{ $previousEquipment->name }}"
                       aria-label="Voir l'équipement précédent">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @endif
                <a href="/equipment/{{ $equipment->id }}/edit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition">
                    Modifier
                </a>
                <form action="/equipment/{{ $equipment->id }}" method="POST" onsubmit="return confirm('Supprimer cet équipement ?');">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                        Supprimer
                    </button>
                </form>
                @if($nextEquipment)
                    <a href="/equipment/{{ $nextEquipment->id }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 shadow-sm transition hover:border-gray-300 hover:text-gray-700"
                       title="Équipement suivant: {{ $nextEquipment->name }}"
                       aria-label="Voir l'équipement suivant">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4 border-b pb-2">Informations Techniques</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Fabricant:</span>
                        <span class="font-semibold">{{ $equipment->manufacturer ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Modèle:</span>
                        <span class="font-semibold">{{ $equipment->model ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">N° de Série (Fabricant):</span>
                        <span class="font-semibold">{{ $equipment->serial_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">N° Réglementaire (CNPRI):</span>
                        <span class="font-semibold">{{ $equipment->regulatory_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date d'Installation:</span>
                        <span class="font-semibold">{{ $equipment->installation_date ? date('d/m/Y', strtotime($equipment->installation_date)) : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Statut:</span>
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $equipment->status == 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $equipment->status }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4 border-b pb-2">Historique des Inspections</h2>
                <p class="text-sm text-gray-500 italic mb-4">Inspections réalisées sur cet équipement ou dans cet établissement.</p>
                <div class="space-y-4">
                    @forelse($equipment->establishment->inspections->take(5) as $insp)
                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                        <p class="text-sm font-bold">{{ date('d/m/Y', strtotime($insp->inspection_date)) }} - {{ $insp->type }}</p>
                        <p class="text-xs text-gray-600">{{ $insp->status }}</p>
                        <a href="/inspections/{{ $insp->id }}" class="text-blue-500 text-xs hover:underline">Voir le rapport</a>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4 italic">Aucune inspection récente.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
