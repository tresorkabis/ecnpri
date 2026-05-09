@extends('layouts.app')

@section('title', 'Rapport d\'Inspection #' . $inspection->id . ' - CNPRI')

@push('scripts')
<style>
    @media print {
        nav, footer, .no-print { display: none !important; }
        body { background-color: white !important; padding: 0 !important; }
        main { padding: 0 !important; margin: 0 !important; }
        .container { max-width: 100% !important; width: 100% !important; border: none !important; box-shadow: none !important; padding: 0 !important; }
        .shadow-2xl { box-shadow: none !important; }
        .border-t-8 { border-top: none !important; }
        .page-break-avoid { page-break-inside: avoid; }
        .page-break { page-break-after: always; }
    }
</style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8 no-print">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-4">
                <a href="/inspections" class="text-blue-600 hover:text-blue-800 flex items-center font-bold">
                    &larr; Retour
                </a>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                    @if($inspection->status == 'Brouillon') bg-gray-200 text-gray-800
                    @elseif($inspection->status == 'Approuvée') bg-blue-100 text-blue-800
                    @elseif($inspection->status == 'En cours') bg-yellow-100 text-yellow-800
                    @elseif($inspection->status == 'Effectuée') bg-green-100 text-green-800
                    @elseif($inspection->status == 'Annulée') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-600 @endif">
                    {{ $inspection->status }}
                </span>
            </div>
            <div class="flex items-center gap-2">
                @if($previousInspection)
                    <a href="/inspections/{{ $previousInspection->id }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 shadow-sm transition hover:border-gray-300 hover:text-gray-700"
                       title="Inspection précédente #{{ $previousInspection->id }}"
                       aria-label="Voir l'inspection précédente">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @endif
                @if($inspection->status == 'Brouillon')
                <form action="/inspections/{{ $inspection->id }}/approve" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded shadow font-bold hover:bg-green-700" onclick="return confirm('Êtes-vous sûr de vouloir approuver cette mission ?')">
                        Approuver la Mission
                    </button>
                </form>
                @endif

                @if(in_array($inspection->status, ['Brouillon', 'Approuvée', 'En cours']))
                <a href="/inspections/{{ $inspection->id }}/edit" class="bg-yellow-500 text-white px-4 py-2 rounded shadow font-bold hover:bg-yellow-600">
                    Modifier la Mission
                </a>
                @endif
                <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow font-bold hover:bg-blue-700">
                    Imprimer le Rapport (PDF)
                </button>
                @if($nextInspection)
                    <a href="/inspections/{{ $nextInspection->id }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 shadow-sm transition hover:border-gray-300 hover:text-gray-700"
                       title="Inspection suivante #{{ $nextInspection->id }}"
                       aria-label="Voir l'inspection suivante">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="container mx-auto max-w-4xl bg-white shadow-2xl p-20 mb-12 page-break text-center flex flex-col items-center justify-between min-h-[1000px]">
        <div>
            <div class="flex flex-col items-center mb-12">
                <img src="{{ asset('images/logo.png') }}" alt="Logo CNPRI" class="w-32 h-32 mb-4">
                <h2 class="text-xl font-bold uppercase tracking-widest">République Démocratique du Congo</h2>
                <p class="text-sm italic mb-2">Ministère de la Recherche Scientifique et Innovation Technologique</p>
                <div class="w-24 border-b-2 border-blue-900 mb-2"></div>
                <h1 class="text-3xl font-black text-blue-900">C.N.P.R.I</h1>
                <p class="text-xs font-bold uppercase tracking-widest">Comité National de Protection contre les Rayonnements Ionisants</p>
            </div>

            <div class="py-20">
                <h2 class="text-4xl font-black uppercase text-blue-900 mb-6 leading-tight">
                    Rapport de Mission d'Inspection Réglementaire
                </h2>
                <div class="w-48 h-1.5 bg-blue-900 mx-auto mb-8"></div>
                <p class="text-xl text-gray-700 font-bold uppercase">
                    N° {{ str_pad($inspection->id, 4, '0', STR_PAD_LEFT) }}/CNPRI/{{ date('Y') }}
                </p>
            </div>
        </div>

        <div class="w-full">
            <div class="bg-gray-50 border-2 border-blue-900 p-8 rounded-lg text-left mb-12">
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-gray-600 font-bold uppercase text-sm">Établissement :</div>
                    <div class="col-span-2 text-xl font-black text-blue-900">{{ $inspection->establishment->name }}</div>

                    <div class="text-gray-600 font-bold uppercase text-sm">Lieu :</div>
                    <div class="col-span-2 text-lg text-gray-800">{{ $inspection->establishment->city }}, {{ $inspection->establishment->province }}</div>

                    <div class="text-gray-600 font-bold uppercase text-sm">Période :</div>
                    <div class="col-span-2 text-lg text-gray-800">Du {{ $inspection->start_date->format('d/m/Y') }} au {{ $inspection->end_date->format('d/m/Y') }}</div>
                </div>
            </div>

            <div class="text-gray-400 text-sm tracking-widest uppercase">
                Kinshasa, le {{ date('d/m/Y') }}
            </div>
        </div>
    </div>

    <div class="container mx-auto max-w-4xl bg-white shadow-2xl p-12 border-t-8 border-blue-900 mb-12">
        <!-- Section 1: Identification -->
        <div class="mb-10">
            <h4 class="px-4 py-1 font-bold text-gray-500 border-b border-gray-100 mb-4 uppercase text-[10px] tracking-widest">Identification de l'Établissement</h4>
            <div class="grid grid-cols-2 gap-y-3 px-4 text-sm">
                <div class="font-semibold text-gray-600">Dénomination :</div>
                <div class="font-bold text-gray-900">{{ $inspection->establishment->name }}</div>
                
                <div class="font-semibold text-gray-600">Localisation :</div>
                <div class="text-gray-900">{{ $inspection->establishment->city }}, {{ $inspection->establishment->province }}</div>
                
                <div class="font-semibold text-gray-600">Adresse :</div>
                <div class="text-gray-900">{{ $inspection->establishment->address ?? 'N/A' }}</div>
                
                <div class="font-semibold text-gray-600">Catégorie :</div>
                <div class="text-gray-900">{{ $inspection->establishment->category }}</div>

                <div class="font-semibold text-gray-600">Objet de la mission :</div>
                <div class="text-gray-900 font-medium">{{ $inspection->purpose ?? 'Inspection de routine' }}</div>

                <div class="font-semibold text-gray-600">Période :</div>
                <div class="text-gray-900">Du {{ $inspection->start_date->format('d/m/Y') }} au {{ $inspection->end_date->format('d/m/Y') }} ({{ $inspection->duration }} jours)</div>
            </div>
        </div>

        <!-- I. Préambule -->
        <div class="mb-10">
            <h3 class="bg-gray-100 px-4 py-2 font-bold text-blue-900 border-l-4 border-blue-900 mb-4 uppercase text-sm">I. Préambule</h3>
            <div class="px-4 text-gray-800 leading-relaxed text-sm">
                {{ $inspection->summary ?? 'La mission d\'inspection a été réalisée dans le cadre du programme annuel du CNPRI pour s\'assurer de la protection des travailleurs, du public et de l\'environnement contre les effets nocifs des rayonnements ionisants.' }}
            </div>
        </div>

        <!-- II. Contexte légal et réglementaire -->
        <div class="mb-10">
            <h3 class="bg-gray-100 px-4 py-2 font-bold text-blue-900 border-l-4 border-blue-900 mb-4 uppercase text-sm">II. Contexte légal et réglementaire</h3>
            <div class="px-4 text-gray-800 leading-relaxed text-sm italic">
                La présente mission d’inspection se fonde sur la Loi n°017/2002 du 16 octobre 2002 portant dispositions relatives à la protection contre les dangers des rayonnements ionisants et à la protection physique des matières et installations nucléaires, ainsi que ses mesures d'exécution.
            </div>
        </div>

        <!-- III. Composition de l'équipe d'inspection -->
        <div class="mb-10">
            <h3 class="bg-gray-100 px-4 py-2 font-bold text-blue-900 border-l-4 border-blue-900 mb-4 uppercase text-sm">III. Composition de l'équipe d'inspection</h3>
            <div class="px-4">
                <ul class="list-disc list-inside text-gray-900 text-sm">
                    @if($inspection->teamLeader)
                        <li class="font-bold">{{ $inspection->teamLeader->name }} (Chef de mission)</li>
                    @endif
                    @foreach($inspection->inspectors as $inspector)
                        @if(!$inspection->team_leader_id || $inspector->id != $inspection->team_leader_id)
                            <li>{{ $inspector->name }} (Inspecteur)</li>
                        @endif
                    @endforeach
                </ul>
                @if($inspection->authorized_by)
                <div class="mt-4 text-xs text-gray-600">
                    <span class="font-semibold">Mission autorisée par :</span> {{ $inspection->authorized_by }}
                </div>
                @endif
            </div>
        </div>

        <!-- IV. Déroulement de la mission -->
        <div class="mb-10">
            <h3 class="bg-gray-100 px-4 py-2 font-bold text-blue-900 border-l-4 border-blue-900 mb-4 uppercase text-sm">IV. Déroulement de la mission</h3>
            <div class="px-4 space-y-4">
                <div>
                    <h4 class="font-bold text-blue-800 text-xs uppercase mb-1">a. Contrôle Administratif</h4>
                    <p class="text-gray-800 text-sm">Examen des documents de radioprotection, des autorisations et des dossiers du personnel exposé.</p>
                </div>
                <div>
                    <h4 class="font-bold text-blue-800 text-xs uppercase mb-1">b. Contrôle Technique</h4>
                    <p class="text-gray-800 text-sm">Vérification de l'état des équipements, mesures de débit de dose et tests de sûreté sur site.</p>
                </div>
                @if($inspection->methodology)
                <div class="mt-4 pt-2 border-t border-gray-100">
                    <p class="text-gray-800 whitespace-pre-wrap leading-relaxed italic text-sm">{{ $inspection->methodology }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- V. Caractéristique des sources et équipements -->
        <div class="mb-10">
            <h3 class="bg-gray-100 px-4 py-2 font-bold text-blue-900 border-l-4 border-blue-900 mb-4 uppercase text-sm">V. Caractéristiques des sources et équipements</h3>
            <div class="px-4">
                @if($inspection->establishment->equipment->count() > 0 || $inspection->establishment->radioactiveSources->count() > 0)
                    <div class="space-y-6">
                        @if($inspection->establishment->equipment->count() > 0)
                            <div>
                                <h4 class="font-bold text-gray-700 text-xs uppercase mb-2 tracking-wider">Équipements radiologiques</h4>
                                <table class="min-w-full border border-gray-200 text-xs">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="border p-2 text-left font-bold uppercase">Dénomination</th>
                                            <th class="border p-2 text-left font-bold uppercase">Marque / Modèle</th>
                                            <th class="border p-2 text-left font-bold uppercase">N° Série / Réglementaire</th>
                                            <th class="border p-2 text-left font-bold uppercase">Spécifications</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inspection->establishment->equipment as $eq)
                                            <tr>
                                                <td class="border p-2 font-bold">{{ $eq->name }}</td>
                                                <td class="border p-2">{{ $eq->manufacturer }} / {{ $eq->model }}</td>
                                                <td class="border p-2">
                                                    <div class="font-mono text-[10px]">S/N: {{ $eq->serial_number ?? 'N/A' }}</div>
                                                    <div class="font-mono text-[10px] text-blue-700 font-bold">REG: {{ $eq->regulatory_number ?? 'N/A' }}</div>
                                                </td>
                                                <td class="border p-2">
                                                    @if($eq->voltage_max) <span class="block text-[10px]">T. Max: {{ $eq->voltage_max }} V</span> @endif
                                                    @if($eq->intensity_max) <span class="block text-[10px]">I. Max: {{ $eq->intensity_max }} mA</span> @endif
                                                    @if($eq->use_case) <span class="block text-[10px]">Usage: {{ $eq->use_case }}</span> @endif
                                                    @if($eq->filtration) <span class="block text-[10px]">Filtration: {{ $eq->filtration }}</span> @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if($inspection->establishment->radioactiveSources->count() > 0)
                            <div>
                                <h4 class="font-bold text-gray-700 text-xs uppercase mb-2 tracking-wider">Sources radioactives</h4>
                                <table class="min-w-full border border-gray-200 text-xs">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="border p-2 text-left font-bold uppercase">Isotope</th>
                                            <th class="border p-2 text-left font-bold uppercase">Activité Initiale</th>
                                            <th class="border p-2 text-left font-bold uppercase">N° Réglementaire</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inspection->establishment->radioactiveSources as $src)
                                            <tr>
                                                <td class="border p-2 font-bold">{{ $src->isotope }}</td>
                                                <td class="border p-2">{{ $src->initial_activity }} {{ $src->unit }}</td>
                                                <td class="border p-2 font-mono">{{ $src->regulatory_number ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-gray-500 italic text-sm">Aucun équipement ou source répertorié pour cet établissement.</p>
                @endif
            </div>
        </div>

        <!-- VI. Constats -->
        <div class="mb-10">
            <h3 class="bg-gray-100 px-4 py-2 font-bold text-blue-900 border-l-4 border-blue-900 mb-4 uppercase text-sm">VI. Constats</h3>
            <div class="px-4 space-y-6">
                <div>
                    <h4 class="font-bold text-blue-800 text-xs uppercase mb-1">a. De l’Examen des documents demandés par les Inspecteurs du CNPRI</h4>
                    <div class="text-gray-800 text-sm">
                        @php $docFindings = $inspection->findings; @endphp
                        @if($docFindings->count() > 0)
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($docFindings as $finding)
                                    <li>{{ $finding->description }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="italic text-green-700 font-medium">L'examen des documents n'a révélé aucune non-conformité administrative.</p>
                        @endif
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-blue-800 text-xs uppercase mb-1">b. Des Contrôles Techniques</h4>
                    <div class="text-gray-800 text-sm">
                        <p>Les vérifications techniques effectuées sur site ont porté sur l'intégrité des barrières de protection et le fonctionnement des dispositifs de sécurité.</p>
                        <p class="mt-2 italic text-gray-600">Note: Les points spécifiques de non-conformité technique sont inclus dans la liste globale ci-dessus.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- VII. RECOMMANDATIONS -->
        <div class="mb-10">
            <h3 class="bg-gray-100 px-4 py-2 font-bold text-blue-900 border-l-4 border-blue-900 mb-4 uppercase text-sm">VII. RECOMMANDATIONS</h3>
            <div class="px-4">
                @if($inspection->findings->count() > 0)
                    <div class="space-y-3">
                        @foreach($inspection->findings as $index => $finding)
                            <div class="flex items-start text-sm">
                                <span class="bg-blue-100 text-blue-800 font-bold px-2 py-0.5 rounded mr-3">{{ $index + 1 }}</span>
                                <div>
                                    <p class="text-gray-800 font-medium">{{ $finding->recommendation }}</p>
                                    <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-wide">Échéance souhaitée : {{ $finding->deadline ? $finding->deadline->format('d/m/Y') : 'Immédiate' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-green-700 text-sm italic font-medium">Aucune recommandation particulière n'est formulée à l'issue de cette inspection.</p>
                @endif
            </div>
        </div>

        <!-- VIII. CONCLUSION -->
        <div class="mb-12">
            <h3 class="bg-gray-100 px-4 py-2 font-bold text-blue-900 border-l-4 border-blue-900 mb-4 uppercase text-sm">VIII. CONCLUSION</h3>
            <div class="px-4 text-gray-800 leading-relaxed text-sm">
                {{ $inspection->conclusion ?? 'Au terme de notre mission, nous encourageons l\'établissement à poursuivre ses efforts de mise en conformité avec les normes de radioprotection et de sûreté.' }}
            </div>
        </div>

        <!-- Membres de l'équipe d'inspection pour signature -->
        <div class="mb-12 page-break-avoid">
            <h3 class="bg-gray-100 px-4 py-2 font-bold text-blue-900 border-l-4 border-blue-900 mb-4 uppercase text-sm">IX. Membres de l'équipe d'inspection</h3>
            <div class="px-4">
                <table class="min-w-full border-collapse border border-gray-300 text-[10px]">
                    <thead>
                        <tr class="bg-gray-50 uppercase tracking-wider">
                            <th class="border border-gray-300 p-2 text-left w-1/2">Nom et Prénom</th>
                            <th class="border border-gray-300 p-2 text-left w-1/4">Qualité</th>
                            <th class="border border-gray-300 p-2 text-left w-1/4 text-center">Signature</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($inspection->teamLeader)
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold uppercase">{{ $inspection->teamLeader->name }}</td>
                            <td class="border border-gray-300 p-2 italic">Chef de mission{{ $inspection->teamLeader->grade ? ' / ' . $inspection->teamLeader->grade : '' }}</td>
                            <td class="border border-gray-300 p-2 h-14"></td>
                        </tr>
                        @endif
                        @foreach($inspection->inspectors as $inspector)
                            @if(!$inspection->team_leader_id || $inspector->id != $inspection->team_leader_id)
                            <tr>
                                <td class="border border-gray-300 p-2 uppercase">{{ $inspector->name }}</td>
                                <td class="border border-gray-300 p-2 italic">Inspecteur{{ $inspector->grade ? ' / ' . $inspector->grade : '' }}</td>
                                <td class="border border-gray-300 p-2 h-14"></td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Signatures -->
        <div class="mt-20 grid grid-cols-2 gap-12 text-center page-break-avoid">
            <div>
                <p class="font-bold underline mb-16">Pour l'Établissement</p>
                <p class="text-sm font-bold uppercase">{{ $inspection->site_representative ?? 'Le Responsable du Site' }}</p>
                <p class="text-xs italic">{{ $inspection->site_representative_title }}</p>
            </div>
            <div>
                <p class="font-bold underline mb-16">Pour le CNPRI (Chef de Mission)</p>
                <p class="text-sm font-bold uppercase">{{ $inspection->teamLeader->name ?? 'L\'Inspecteur' }}</p>
                <p class="text-xs italic">{{ $inspection->teamLeader->grade ?? 'Expert en Radioprotection' }}</p>
            </div>
        </div>

        <div class="mt-24 pt-8 border-t border-gray-200 text-center text-[10px] text-gray-400 uppercase tracking-tighter">
            CNPRI - 4675, Avenue Colonel Ebeya, Kinshasa/Gombe - République Démocratique du Congo
        </div>
    </div>
@endsection
