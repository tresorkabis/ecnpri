@extends('layouts.app')

@section('title', 'Liste des Inspecteurs - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Corps des Inspecteurs</h1>
            <a href="{{ route('inspectors.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                + Nouvel Inspecteur
            </a>
        </div>


        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nom de l'Inspecteur
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Matricule
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Spécialisation
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inspectors as $inspector)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                    {{ substr($inspector->name, 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-900 whitespace-no-wrap font-bold">
                                        {{ $inspector->name }}
                                    </p>
                                    @if($inspector->grade)
                                    <p class="text-gray-500 text-xs">{{ $inspector->grade }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ $inspector->employee_id }}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-semibold">
                                {{ $inspector->specialization ?? 'Généraliste' }}
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('inspectors.show', $inspector) }}" class="text-blue-600 hover:text-blue-900">Détails</a>
                                <a href="{{ route('inspectors.edit', $inspector) }}" class="text-yellow-600 hover:text-yellow-900">Éditer</a>
                                <form action="{{ route('inspectors.destroy', $inspector) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cet inspecteur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($inspectors->isEmpty())
                    <tr>
                        <td colspan="4" class="px-5 py-10 border-b border-gray-200 bg-white text-sm text-center text-gray-500 italic">
                            Aucun inspecteur enregistré.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
