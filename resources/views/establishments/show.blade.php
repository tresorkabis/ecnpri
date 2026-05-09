@extends('layouts.app')

@section('title')
    {{ $establishment->name }} - CNPRI
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-start mb-6">
            <div>
                <nav class="text-sm font-medium text-gray-500 mb-2" aria-label="Breadcrumb">
                    <a href="/establishments" class="hover:text-gray-700">Établissements</a> /
                    <span class="text-gray-900">{{ $establishment->name }}</span>
                </nav>
                <h1 class="text-3xl font-bold text-gray-800">{{ $establishment->name }}</h1>
                <p class="text-gray-600">{{ $establishment->city }}, {{ $establishment->province }} | {{ $establishment->category }}</p>
            </div>
            <div class="flex items-center gap-2">
                @if($previousEstablishment)
                    <a href="/establishments/{{ $previousEstablishment->id }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 shadow-sm transition hover:border-gray-300 hover:text-gray-700"
                       title="Établissement précédent: {{ $previousEstablishment->name }}"
                       aria-label="Voir l'établissement précédent">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @endif

                <a href="/establishments/{{ $establishment->id }}/edit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded shadow transition">
                    Modifier
                </a>

                @if($nextEstablishment)
                    <a href="/establishments/{{ $nextEstablishment->id }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 shadow-sm transition hover:border-gray-300 hover:text-gray-700"
                       title="Établissement suivant: {{ $nextEstablishment->name }}"
                       aria-label="Voir l'établissement suivant">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Details Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Informations de Contact</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Représentant</p>
                            <p class="text-gray-900 font-medium">
                                {{ $establishment->contact_name ?? 'Non spécifié' }}
                                @if($establishment->contact_title)
                                    <span class="text-gray-500 text-sm italic">({{ $establishment->contact_title }})</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Téléphone</p>
                            <p class="text-gray-900">{{ $establishment->phone ?? 'Non spécifié' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Email</p>
                            <p class="text-gray-900">{{ $establishment->email ?? 'Non spécifié' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Adresse</p>
                            <p class="text-gray-900">{{ $establishment->address ?? 'Non spécifiée' }}</p>
                        </div>
                    </div>
                </div>

                <!-- RPR Card -->
                <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden border-l-4 border-blue-600">
                    <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-blue-800">Responsable de Protection Radiologique (RPR)</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Nom du RPR</p>
                            <p class="text-gray-900 font-bold text-lg">{{ $establishment->rpr_name ?? 'Non spécifié' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Numéro d'accréditation</p>
                            <p class="text-gray-900 font-medium">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">
                                    {{ $establishment->rpr_accreditation ?? 'En attente' }}
                                </span>
                            </p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <p class="text-xs font-bold text-gray-500 uppercase">Téléphone</p>
                                <p class="text-gray-900">{{ $establishment->rpr_phone ?? 'Non spécifié' }}</p>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold text-gray-500 uppercase">Email</p>
                                <p class="text-gray-900 text-sm truncate">{{ $establishment->rpr_email ?? 'Non spécifié' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden" data-tabs-container>
                    <div class="border-b border-gray-200 bg-gray-50 px-3 py-3">
                        <div class="flex flex-wrap gap-2" role="tablist" aria-label="Contenu établissement">
                            <button type="button" data-tab-trigger="authorizations" class="rounded-full bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm">
                                Autorisations
                                <span data-tab-count class="ml-2 inline-flex min-w-[1.75rem] items-center justify-center rounded-full bg-white px-2 py-0.5 text-xs font-bold text-emerald-700 shadow-sm">
                                    {{ $establishment->usageAuthorizations->count() }}
                                </span>
                            </button>
                            <button type="button" data-tab-trigger="sources" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200">
                                Sources
                                <span data-tab-count class="ml-2 inline-flex min-w-[1.75rem] items-center justify-center rounded-full bg-slate-700 px-2 py-0.5 text-xs font-bold text-white shadow-sm">
                                    {{ $establishment->radioactiveSources->count() }}
                                </span>
                            </button>
                            <button type="button" data-tab-trigger="equipment" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200">
                                Équipements
                                <span data-tab-count class="ml-2 inline-flex min-w-[1.75rem] items-center justify-center rounded-full bg-slate-700 px-2 py-0.5 text-xs font-bold text-white shadow-sm">
                                    {{ $establishment->equipment->count() }}
                                </span>
                            </button>
                            <button type="button" data-tab-trigger="inspections" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-gray-600 border border-gray-200">
                                Inspections
                                <span data-tab-count class="ml-2 inline-flex min-w-[1.75rem] items-center justify-center rounded-full bg-slate-700 px-2 py-0.5 text-xs font-bold text-white shadow-sm">
                                    {{ $establishment->inspections->count() }}
                                </span>
                            </button>
                        </div>
                    </div>

                    <div data-tab-panel="authorizations" class="p-6">
                        <div class="mb-5 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-emerald-800">Autorisations d'utilisation</h2>
                            <a href="/usage-authorizations/create?establishment_id={{ $establishment->id }}" class="text-sm text-emerald-700 hover:underline">+ Ajouter</a>
                        </div>
                        @if($establishment->usageAuthorizations->count() > 0)
                            <div class="space-y-4">
                                @foreach($establishment->usageAuthorizations->sortByDesc('issued_at') as $authorization)
                                    <div class="rounded-lg border border-emerald-100 bg-emerald-50/40 p-4">
                                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                            <div>
                                                <p class="font-bold text-emerald-900">
                                                    <a href="/usage-authorizations/{{ $authorization->id }}" class="hover:underline">{{ $authorization->reference_number }}</a>
                                                </p>
                                                <p class="text-sm text-gray-700">{{ $authorization->authorization_type ?? 'Type non précisé' }}</p>
                                                <p class="text-sm text-emerald-700">{{ $authorization->scope }}</p>
                                                <p class="mt-1 text-xs text-gray-500">Délivrée par {{ $authorization->issuing_authority ?? 'CNPRI' }}</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-bold rounded-full
                                                {{ $authorization->status === 'Valide' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $authorization->status === 'Expirée' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $authorization->status === 'Suspendue' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $authorization->status === 'En attente' ? 'bg-slate-100 text-slate-700' : '' }}">
                                                {{ $authorization->status }}
                                            </span>
                                        </div>
                                        <div class="mt-3 grid grid-cols-1 gap-3 text-sm text-gray-700 md:grid-cols-2 xl:grid-cols-4">
                                            <p>Date de délivrance: {{ $authorization->issued_at?->format('d/m/Y') ?? 'Non spécifiée' }}</p>
                                            <p>Date d'expiration: {{ $authorization->expires_at?->format('d/m/Y') ?? 'Non spécifiée' }}</p>
                                            <p>Sources concernées:
                                                <span class="font-semibold">
                                                    {{ in_array($authorization->scope, ['Sources', 'Sources et Équipements']) ? $establishment->radioactiveSources->count() : 0 }}
                                                </span>
                                            </p>
                                            <p>Équipements concernés:
                                                <span class="font-semibold">
                                                    {{ in_array($authorization->scope, ['Équipements', 'Sources et Équipements']) ? $establishment->equipment->count() : 0 }}
                                                </span>
                                            </p>
                                        </div>
                                        @if($authorization->notes)
                                            <div class="mt-3 rounded-md border border-emerald-100 bg-white/70 p-3">
                                                <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Observations</p>
                                                <p class="mt-1 text-sm text-gray-600">{{ $authorization->notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Aucune autorisation d'utilisation enregistrée pour cet établissement.</p>
                        @endif
                    </div>

                    <div data-tab-panel="sources" class="hidden p-6">
                        <div class="mb-5 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Inventaire des Sources</h2>
                            <a href="/radioactive-sources/create?establishment_id={{ $establishment->id }}" class="text-sm text-blue-600 hover:underline">+ Ajouter une source</a>
                        </div>
                        <div class="space-y-3">
                            @forelse($establishment->radioactiveSources as $source)
                            <div class="flex items-center justify-between rounded border bg-gray-50 p-3">
                                <div>
                                    <p class="font-bold text-blue-800">{{ $source->isotope }} ({{ $source->serial_number }})</p>
                                    <p class="text-xs text-gray-500">{{ $source->initial_activity }} {{ $source->unit }} - {{ $source->status }}</p>
                                </div>
                                <a href="/radioactive-sources/{{ $source->id }}" class="text-sm text-blue-600 hover:text-blue-900">Détails</a>
                            </div>
                            @empty
                            <p class="text-sm text-gray-500">Aucune source radioactive enregistrée.</p>
                            @endforelse
                        </div>
                    </div>

                    <div data-tab-panel="equipment" class="hidden">
                        <div class="flex items-center justify-between border-b border-blue-200 bg-blue-50 px-6 py-4">
                            <h2 class="text-lg font-semibold text-blue-800">Équipements Radiologiques</h2>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full">{{ $establishment->equipment->count() }}</span>
                        </div>
                        <div class="p-6">
                            @if($establishment->equipment->count() > 0)
                                <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
                                    @foreach($establishment->equipment as $equip)
                                    <div class="rounded-lg border border-blue-100 bg-blue-50/40 p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="font-bold text-blue-900">
                                                    <a href="/equipment/{{ $equip->id }}" class="hover:underline">{{ $equip->name }}</a>
                                                </p>
                                                <p class="text-sm text-gray-700">{{ $equip->model ?? 'Modèle non précisé' }}</p>
                                                <p class="mt-1 text-xs text-gray-500">{{ $equip->manufacturer ?? 'Fabricant non précisé' }}</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-bold rounded-full {{ $equip->status == 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $equip->status }}
                                            </span>
                                        </div>

                                        <div class="mt-4 grid grid-cols-1 gap-3 text-sm text-gray-700 md:grid-cols-2">
                                            <p>S/N fabricant: <span class="font-semibold">{{ $equip->serial_number ?? 'N/A' }}</span></p>
                                            <p>Réf. CNPRI: <span class="font-semibold">{{ $equip->regulatory_number ?? 'N/A' }}</span></p>
                                            <p>Date d'installation: <span class="font-semibold">{{ $equip->installation_date ? date('d/m/Y', strtotime($equip->installation_date)) : 'N/A' }}</span></p>
                                            <p>Utilisation: <span class="font-semibold">{{ $equip->use_case ?? 'N/A' }}</span></p>
                                            <p>Tension max: <span class="font-semibold">{{ $equip->voltage_max ?? 'N/A' }}</span></p>
                                            <p>Intensité max: <span class="font-semibold">{{ $equip->intensity_max ?? 'N/A' }}</span></p>
                                        </div>

                                        @if($equip->filtration)
                                            <div class="mt-3 rounded-md border border-blue-100 bg-white/70 p-3">
                                                <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Filtration</p>
                                                <p class="mt-1 text-sm text-gray-600">{{ $equip->filtration }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            @else
                            <p class="text-center italic text-gray-500">Aucun équipement enregistré pour cet établissement.</p>
                            @endif
                        </div>
                    </div>

                    <div data-tab-panel="inspections" class="hidden">
                        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                            <h2 class="text-lg font-semibold text-gray-800">Historique des Inspections</h2>
                        </div>
                        <div class="overflow-x-auto">
                            @if($establishment->inspections->count() > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($establishment->inspections->sortByDesc('start_date') as $inspection)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                            {{ $inspection->start_date?->format('d/m/Y') ?? 'Non planifiée' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $inspection->type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $inspection->status == 'Effectuée' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $inspection->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="/inspections/{{ $inspection->id }}" class="text-blue-600 hover:text-blue-900">Voir Rapport</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <p class="p-6 text-gray-500 italic text-center">Aucune inspection enregistrée.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('[data-tabs-container]');

        if (!container) {
            return;
        }

        const triggers = container.querySelectorAll('[data-tab-trigger]');
        const panels = container.querySelectorAll('[data-tab-panel]');

        const activateTab = (tabName) => {
            triggers.forEach((trigger) => {
                const isActive = trigger.dataset.tabTrigger === tabName;
                const counter = trigger.querySelector('[data-tab-count]');
                trigger.classList.toggle('bg-emerald-600', isActive);
                trigger.classList.toggle('text-white', isActive);
                trigger.classList.toggle('shadow-sm', isActive);
                trigger.classList.toggle('bg-white', !isActive);
                trigger.classList.toggle('text-gray-600', !isActive);
                trigger.classList.toggle('border', !isActive);
                trigger.classList.toggle('border-gray-200', !isActive);

                if (counter) {
                    counter.classList.toggle('bg-white', isActive);
                    counter.classList.toggle('text-emerald-700', isActive);
                    counter.classList.toggle('bg-slate-700', !isActive);
                    counter.classList.toggle('text-white', !isActive);
                }
            });

            panels.forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.tabPanel !== tabName);
            });
        };

        triggers.forEach((trigger) => {
            trigger.addEventListener('click', () => activateTab(trigger.dataset.tabTrigger));
        });

        activateTab('authorizations');
    });
</script>
@endpush
