@extends('layouts.app')

@section('title', 'Planning des Inspections - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Historique et Planning des Inspections</h1>
            <a href="/inspections/create" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                + Programmer une Inspection
            </a>
        </div>


        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Établissement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inspecteurs</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rapport</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($inspections as $inspection)
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
                            <a href="{{ route('inspections.edit', $inspection->id) }}" class="text-yellow-600 hover:text-yellow-900">Modifier</a>
                            @endif
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
    </div>
@endsection
