@extends('layouts.app')

@section('title', 'Autorisations - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-semibold text-gray-800">Autorisations d'Utilisation</h1>
                <p class="text-sm text-gray-500 mt-1">Gestion des autorisations CNPRI liées aux sources et équipements.</p>
            </div>
            <a href="/usage-authorizations/create" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">
                + Nouvelle Autorisation
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Établissement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Portée</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($authorizations as $authorization)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-emerald-900">
                                <a href="/usage-authorizations/{{ $authorization->id }}">{{ $authorization->reference_number }}</a>
                                <div class="text-[10px] text-gray-500 font-normal">
                                    {{ $authorization->issued_at?->format('d/m/Y') ?? 'Date non spécifiée' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $authorization->authorization_type ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <a href="/establishments/{{ $authorization->establishment_id }}" class="text-blue-600 hover:underline">{{ $authorization->establishment->name }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $authorization->scope }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $authorization->status === 'Valide' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $authorization->status === 'Expirée' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $authorization->status === 'Suspendue' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $authorization->status === 'En attente' ? 'bg-slate-100 text-slate-700' : '' }}">
                                    {{ $authorization->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="/usage-authorizations/{{ $authorization->id }}" class="text-blue-600 hover:text-blue-900">Détails</a>
                                <a href="/usage-authorizations/{{ $authorization->id }}/edit" class="text-yellow-600 hover:text-yellow-900">Modifier</a>
                                <form action="/usage-authorizations/{{ $authorization->id }}" method="POST" class="inline-block" onsubmit="return confirm('Supprimer cette autorisation ?');">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">Aucune autorisation enregistrée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
