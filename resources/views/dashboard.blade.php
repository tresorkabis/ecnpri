@extends('layouts.app')

@section('title', 'Tableau de Bord - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tableau de Bord</h1>
                <p class="text-gray-600 mt-1">Aperçu global de l'activité du CNPRI - {{ now()->translatedFormat('d F Y') }}</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="/inspections/create" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nouvelle Mission
                </a>
                <a href="/establishments/create" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Nouvel Établissement
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
            <!-- Etablissements -->
            <a href="/establishments" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center hover:shadow-md transition-shadow">
                <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Établissements</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['establishments_count'] }}</p>
                </div>
            </a>

            <!-- Inspections -->
            <a href="/inspections" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center hover:shadow-md transition-shadow">
                <div class="p-3 rounded-full bg-green-50 text-green-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Inspections</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['inspections_count'] }}</p>
                </div>
            </a>

            <!-- Inspecteurs -->
            <a href="/inspectors" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center hover:shadow-md transition-shadow">
                <div class="p-3 rounded-full bg-indigo-50 text-indigo-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Inspecteurs</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['inspectors_count'] }}</p>
                </div>
            </a>

            <!-- Sources -->
            <a href="/radioactive-sources" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center hover:shadow-md transition-shadow">
                <div class="p-3 rounded-full bg-orange-50 text-orange-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Sources</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['sources_count'] }}</p>
                </div>
            </a>

            <!-- Équipements -->
            <a href="/equipment" class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center hover:shadow-md transition-shadow">
                <div class="p-3 rounded-full bg-red-50 text-red-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Équipements</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['equipment_count'] }}</p>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column (2/3) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Recent Inspections -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                        <h2 class="text-lg font-bold text-gray-800">Missions Récentes</h2>
                        <a href="/inspections" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Voir tout &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50/30">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Établissement</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recent_inspections as $inspection)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $inspection->establishment->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $inspection->type }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ date('d/m/Y', strtotime($inspection->start_date)) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'Brouillon' => 'bg-gray-100 text-gray-800',
                                                'Approuvée' => 'bg-blue-100 text-blue-800',
                                                'En cours' => 'bg-yellow-100 text-yellow-800',
                                                'Effectuée' => 'bg-green-100 text-green-800',
                                                'Annulée' => 'bg-red-100 text-red-800',
                                            ];
                                            $colorClass = $statusColors[$inspection->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $colorClass }}">
                                            {{ $inspection->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500 italic">Aucune mission enregistrée</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mission Status Distribution -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-6">État des Missions</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                        @foreach(['Brouillon', 'Approuvée', 'En cours', 'Effectuée', 'Annulée'] as $status)
                            @php
                                $count = $inspection_stats[$status] ?? 0;
                                $colors = [
                                    'Brouillon' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-200'],
                                    'Approuvée' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
                                    'En cours' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'border' => 'border-yellow-200'],
                                    'Effectuée' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-200'],
                                    'Annulée' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-200'],
                                ];
                                $c = $colors[$status];
                            @endphp
                            <div class="{{ $c['bg'] }} {{ $c['border'] }} border rounded-lg p-4 text-center">
                                <p class="text-2xl font-black {{ $c['text'] }}">{{ $count }}</p>
                                <p class="text-xs font-bold text-gray-500 uppercase mt-1">{{ $status }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column (1/3) -->
            <div class="space-y-8">
                <!-- Upcoming Inspections -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-blue-50/50">
                        <h2 class="text-lg font-bold text-blue-900">Prochaines Missions</h2>
                    </div>
                    <div class="p-0">
                        <ul class="divide-y divide-gray-100">
                            @forelse($upcoming_inspections as $upcoming)
                            <li class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                <a href="/inspections/{{ $upcoming->id }}" class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-gray-900">{{ $upcoming->establishment->name }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <span class="inline-flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ date('d/m/Y', strtotime($upcoming->start_date)) }}
                                            </span>
                                        </p>
                                    </div>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-[10px] font-bold rounded uppercase tracking-wider">{{ $upcoming->status }}</span>
                                </a>
                            </li>
                            @empty
                            <li class="px-6 py-8 text-center text-gray-400 text-sm italic">Aucune mission prévue</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Recent Establishments -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-lg font-bold text-gray-800">Nouveaux Établissements</h2>
                    </div>
                    <div class="p-0">
                        <ul class="divide-y divide-gray-100">
                            @foreach($recent_establishments as $est)
                            <li class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                <a href="/establishments/{{ $est->id }}" class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-4 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    </div>
                                    <div class="overflow-hidden">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $est->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $est->address }}, {{ $est->city }}</p>
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        
        <!-- Authority Note -->
        <div class="mt-8 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-white/20 rounded-lg mr-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-lg font-bold">Note de Service</h2>
            </div>
            <p class="text-blue-50 text-sm leading-relaxed mb-4">
                Assurez-vous de la conformité des rapports d'inspection avant soumission au responsable pour approbation.
            </p>
            <div class="pt-4 border-t border-white/20 text-xs text-blue-100 flex justify-between items-center">
                <span>Direction Générale CNPRI</span>
                <span>v1.2.0</span>
            </div>
        </div>
    </div>
@endsection
