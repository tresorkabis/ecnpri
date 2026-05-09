@extends('layouts.app')

@section('title', 'Inventaire des Équipements - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Inventaire des Équipements</h1>
            <a href="/equipment/create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                + Ajouter un Équipement
            </a>
        </div>


        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Désignation
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Établissement
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Modèle / S/N
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipment as $item)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center">
                                <div class="ml-3">
                                    <p class="text-gray-900 whitespace-no-wrap font-bold">
                                        {{ $item->name }}
                                    </p>
                                    <p class="text-gray-600 text-xs">
                                        Fabricant: {{ $item->manufacturer ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <a href="/establishments/{{ $item->establishment_id }}" class="text-blue-600 hover:underline">
                                {{ $item->establishment->name }}
                            </a>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">Modèle: {{ $item->model ?? '-' }}</p>
                            <p class="text-gray-600 text-xs">S/N: {{ $item->serial_number ?? '-' }}</p>
                            @if($item->regulatory_number)
                            <p class="text-blue-600 text-[10px] font-bold">Réf: {{ $item->regulatory_number }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="relative inline-block px-3 py-1 font-semibold leading-tight {{ $item->status == 'Active' ? 'text-green-900' : 'text-red-900' }}">
                                <span aria-hidden class="absolute inset-0 {{ $item->status == 'Active' ? 'bg-green-200' : 'bg-red-200' }} opacity-50 rounded-full"></span>
                                <span class="relative">{{ $item->status }}</span>
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="/equipment/{{ $item->id }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                <a href="/equipment/{{ $item->id }}/edit" class="text-yellow-600 hover:text-yellow-900">Éditer</a>
                                <form action="/equipment/{{ $item->id }}" method="POST" onsubmit="return confirm('Supprimer cet équipement ?');">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($equipment->isEmpty())
                    <tr>
                        <td colspan="5" class="px-5 py-10 border-b border-gray-200 bg-white text-sm text-center text-gray-500 italic">
                            Aucun équipement enregistré.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
