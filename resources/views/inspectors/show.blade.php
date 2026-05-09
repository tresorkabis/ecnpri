@extends('layouts.app')

@section('title', $inspector->name . ' - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-start mb-6">
            <div>
                <a href="{{ route('inspectors.index') }}" class="text-blue-600 hover:underline text-sm flex items-center mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à la liste
                </a>
                <h1 class="text-3xl font-semibold text-gray-800">{{ $inspector->name }}</h1>
                @if($inspector->grade)
                <p class="text-blue-600 font-bold">{{ $inspector->grade }}</p>
                @endif
                <p class="text-gray-600">Matricule: {{ $inspector->employee_id }}</p>
            </div>
            <div class="flex items-center gap-2">
                @if($previousInspector)
                    <a href="{{ route('inspectors.show', $previousInspector) }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 shadow-sm transition hover:border-gray-300 hover:text-gray-700"
                       title="Inspecteur précédent: {{ $previousInspector->name }}"
                       aria-label="Voir l'inspecteur précédent">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @endif
                <a href="{{ route('inspectors.edit', $inspector) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition">
                    Modifier
                </a>
                <form action="{{ route('inspectors.destroy', $inspector) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cet inspecteur ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                        Supprimer
                    </button>
                </form>
                @if($nextInspector)
                    <a href="{{ route('inspectors.show', $nextInspector) }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 shadow-sm transition hover:border-gray-300 hover:text-gray-700"
                       title="Inspecteur suivant: {{ $nextInspector->name }}"
                       aria-label="Voir l'inspecteur suivant">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1">
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 border-b pb-2 text-blue-800">Profil</h2>
                    <div class="space-y-4">
                        <div>
                            <span class="block text-gray-500 text-xs uppercase font-bold">Spécialisation</span>
                            <span class="text-gray-800 font-semibold">{{ $inspector->specialization ?? 'Généraliste' }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-500 text-xs uppercase font-bold">Inscrit le</span>
                            <span class="text-gray-800">{{ $inspector->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-gray-500 text-xs uppercase font-bold">Missions effectuées</span>
                            <span class="text-gray-800">{{ $inspector->inspections->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="bg-blue-800 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Historique des Missions</h2>
                    </div>
                    <div class="p-6">
                        @if($inspector->inspections->isEmpty())
                            <p class="text-gray-500 italic text-center py-4">Aucune mission enregistrée pour cet inspecteur.</p>
                        @else
                            <div class="space-y-6">
                                @foreach($inspector->inspections as $inspection)
                                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-bold text-gray-900">
                                                    <a href="{{ route('inspections.show', $inspection) }}" class="hover:underline">
                                                        {{ $inspection->establishment->name }}
                                                    </a>
                                                </h3>
                                                <p class="text-sm text-gray-600">{{ $inspection->inspection_date }} • {{ $inspection->type }}</p>
                                            </div>
                                            <span class="px-2 py-1 {{ $inspection->status == 'Completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded-full text-xs font-bold">
                                                {{ $inspection->status }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
