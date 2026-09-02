@extends('layouts.app')

@section('title', 'Établissements - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Liste des Établissements</h1>
            <a href="/establishments/create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">
                + Nouvel Établissement
            </a>
        </div>

        {{-- Formulaire de recherche --}}
        <form method="GET" class="mb-4 flex items-end gap-2">
            <div>
                <label for="recherche" class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input
                    type="text"
                    name="recherche"
                    id="recherche"
                    value="{{ request('recherche') }}"
                    placeholder="Nom, ville, province..."
                    class="border border-gray-300 rounded px-3 py-2 text-sm w-64"
                />
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                Filtrer
            </button>
            @if(request('recherche'))
                <a href="/establishments" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-sm">
                    Effacer
                </a>
            @endif
        </form>


        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Localisation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($establishments as $establishment)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-900">
                            <a href="/establishments/{{ $establishment->id }}">{{ $establishment->name }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <span class="px-2 py-1 bg-gray-100 rounded-full text-xs font-semibold">{{ $establishment->category }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $establishment->city }}, {{ $establishment->province }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $establishment->contact_name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="/establishments/{{ $establishment->id }}" class="text-blue-600 hover:text-blue-900">Détails</a>
                            <a href="/establishments/{{ $establishment->id }}/edit" class="text-yellow-600 hover:text-yellow-900">Modifier</a>
                            <form action="/establishments/{{ $establishment->id }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet établissement ?');">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
