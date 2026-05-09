@extends('layouts.app')

@section('title', 'Modifier l\'Inspection - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-semibold mb-6 text-gray-800">Modifier la Mission</h1>

        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="/inspections/{{ $inspection->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="establishment_id">
                        Établissement à inspecter
                    </label>
                    <select name="establishment_id" id="establishment_id" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @foreach($establishments as $est)
                        <option value="{{ $est->id }}" {{ $inspection->establishment_id == $est->id ? 'selected' : '' }}>
                            {{ $est->name }} ({{ $est->city }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="purpose">
                        Objet de la mission
                    </label>
                    <input type="text" name="purpose" id="purpose" value="{{ $inspection->purpose }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: Inspection de radioprotection">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">
                            Date de début
                        </label>
                        <input type="date" name="start_date" id="start_date" value="{{ $inspection->start_date->format('Y-m-d') }}" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                            Date de fin
                        </label>
                        <input type="date" name="end_date" id="end_date" value="{{ $inspection->end_date->format('Y-m-d') }}" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                        Statut de la mission
                    </label>
                    <select name="status" id="status" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ $inspection->status == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="type">
                        Type d'inspection
                    </label>
                    <select name="type" id="type" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="réglementaire" {{ $inspection->type == 'réglementaire' ? 'selected' : '' }}>Réglementaire</option>
                        <option value="inopiné" {{ $inspection->type == 'inopiné' ? 'selected' : '' }}>Inopiné</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="authorized_by">
                        Autorisée par
                    </label>
                    <input type="text" name="authorized_by" id="authorized_by" value="{{ $inspection->authorized_by }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: Le Secrétaire Général">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="team_leader_id">
                        Chef de mission
                    </label>
                    <select name="team_leader_id" id="team_leader_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Sélectionner un chef de mission</option>
                        @foreach($inspectors as $inspector)
                        <option value="{{ $inspector->id }}" {{ $inspection->team_leader_id == $inspector->id ? 'selected' : '' }}>
                            {{ $inspector->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Inspecteurs (Membres de l'équipe)
                    </label>
                    @php $memberIds = $inspection->inspectors->pluck('id')->toArray(); @endphp
                    <select name="inspectors[]" id="inspectors" multiple placeholder="Sélectionner les inspecteurs..." class="w-full">
                        @foreach($inspectors as $inspector)
                        <option value="{{ $inspector->id }}" {{ in_array($inspector->id, $memberIds) ? 'selected' : '' }}>
                            {{ $inspector->name }} ({{ $inspector->specialization }})
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Vous pouvez sélectionner jusqu'à 6 inspecteurs.</p>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="report">
                        Rapport de mission (PDF)
                    </label>
                    @if($inspection->report_path)
                        <div class="mb-2 text-sm text-blue-600">
                            Fichier actuel : <a href="/storage/{{ $inspection->report_path }}" target="_blank" class="underline">Voir le rapport</a>
                        </div>
                    @endif
                    <input type="file" name="report" id="report" accept=".pdf" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p class="text-xs text-gray-500 mt-1">Laissez vide pour conserver le fichier actuel. Format PDF uniquement.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="site_representative">
                            Représentant de l'établissement
                        </label>
                        <input type="text" name="site_representative" id="site_representative" value="{{ $inspection->site_representative }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Nom du responsable">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="site_representative_title">
                            Fonction du représentant
                        </label>
                        <input type="text" name="site_representative_title" id="site_representative_title" value="{{ $inspection->site_representative_title }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: Chef de service">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="summary">
                        Résumé / Introduction
                    </label>
                    <textarea name="summary" id="summary" rows="2" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Introduction succincte...">{{ $inspection->summary }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="methodology">
                        Déroulement / Méthodologie
                    </label>
                    <textarea name="methodology" id="methodology" rows="4" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Chronologie et étapes de la mission...">{{ $inspection->methodology }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="conclusion">
                        Conclusion
                    </label>
                    <textarea name="conclusion" id="conclusion" rows="2" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Conclusion globale...">{{ $inspection->conclusion }}</textarea>
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="/inspections/{{ $inspection->id }}" class="text-gray-600 hover:text-gray-800 text-sm font-semibold">Annuler</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
                        Mettre à jour la Mission
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    new TomSelect("#establishment_id",{
        create: false,
        sortField: {field: "text", direction: "asc"}
    });
    
    new TomSelect("#team_leader_id",{
        create: false,
        sortField: {field: "text", direction: "asc"}
    });

    new TomSelect("#inspectors",{
        plugins: ['remove_button'],
        maxItems: 10,
        persist: false,
        create: false,
        onDelete: function(values) {
            return confirm(values.length > 1 ? 'Supprimer ces ' + values.length + ' inspecteurs ?' : 'Supprimer cet inspecteur ?');
        }
    });
</script>
@endpush
