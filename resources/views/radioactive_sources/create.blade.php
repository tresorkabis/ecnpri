@extends('layouts.app')

@section('title', 'Ajouter une Source - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <h1 class="text-3xl font-semibold mb-6 text-gray-800">Ajouter une Nouvelle Source Radioactive</h1>

        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="/radioactive-sources" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="establishment_id">
                        Établissement Détenteur *
                    </label>
                    <select name="establishment_id" id="establishment_id" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Sélectionner un établissement</option>
                        @foreach($establishments as $est)
                        <option value="{{ $est->id }}" data-category-code="{{ \App\Models\UsageAuthorization::categoryCode($est->category) }}" {{ (request('establishment_id') == $est->id || old('establishment_id') == $est->id) ? 'selected' : '' }}>{{ $est->name }} ({{ $est->city }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="serial_number">
                            N° de Série (Fabricant) *
                        </label>
                        <input type="text" name="serial_number" id="serial_number" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: SRC-12345">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="regulatory_number">
                            N° Réglementaire (CNPRI)
                        </label>
                        <input type="text" name="regulatory_number" id="regulatory_number" value="{{ old('regulatory_number') }}" readonly class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-50">
                        <p class="mt-2 text-xs text-gray-500">Le numéro est généré automatiquement après sélection de l'établissement.</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="isotope">
                            Isotope *
                        </label>
                        <input type="text" name="isotope" id="isotope" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: Co-60, Cs-137">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="initial_activity">
                            Activité Initiale *
                        </label>
                        <input type="number" step="any" name="initial_activity" id="initial_activity" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="unit">
                            Unité *
                        </label>
                        <select name="unit" id="unit" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="GBq">GBq</option>
                            <option value="TBq">TBq</option>
                            <option value="mCi">mCi</option>
                            <option value="Ci">Ci</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="activity_date">
                            Date de Référence *
                        </label>
                        <input type="date" name="activity_date" id="activity_date" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="physical_form">
                            Forme Physique *
                        </label>
                        <select name="physical_form" id="physical_form" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="Sealed">Scellée</option>
                            <option value="Unsealed">Non Scellée</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                            Catégorie AIEA
                        </label>
                        <select name="category" id="category" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">N/A</option>
                            <option value="1">Catégorie 1 (Danger extrême)</option>
                            <option value="2">Catégorie 2 (Très dangereux)</option>
                            <option value="3">Catégorie 3 (Dangereux)</option>
                            <option value="4">Catégorie 4 (Faible danger)</option>
                            <option value="5">Catégorie 5 (Danger minime)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                            Statut *
                        </label>
                        <select name="status" id="status" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="Active">En cours d'utilisation</option>
                            <option value="Stored">En stockage (Réserve)</option>
                            <option value="Disposed">Évacuée / Déclassée</option>
                            <option value="Lost">Perdue / Volée</option>
                        </select>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="location_details">
                        Localisation Précise / Détails
                    </label>
                    <textarea name="location_details" id="location_details" rows="3" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: Dans le silo n°4, blindage en plomb..."></textarea>
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="/radioactive-sources" class="text-gray-600 hover:text-gray-800 text-sm font-semibold">Annuler</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
                        Enregistrer la Source
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const establishmentField = document.getElementById('establishment_id');
        const activityDateField = document.getElementById('activity_date');
        const regulatoryNumberField = document.getElementById('regulatory_number');
        const previewMap = @json($regulatoryPreviewMap ?? []);

        if (!establishmentField || !regulatoryNumberField) {
            return;
        }

        const buildRegulatoryNumber = () => {
            const option = establishmentField.options[establishmentField.selectedIndex];

            if (!option || !option.value) {
                regulatoryNumberField.value = '';
                return;
            }

            const categoryCode = option.dataset.categoryCode || 'AUT';
            const activityDate = activityDateField?.value || '';
            const year = activityDate ? new Date(activityDate).getFullYear() : new Date().getFullYear();
            const currentMax = previewMap?.[categoryCode]?.[String(year)] || 0;
            const sequence = String(Number(currentMax) + 1).padStart(3, '0');

            regulatoryNumberField.value = `CNPRI-SRC-${categoryCode}-${year}-${sequence}`;
        };

        establishmentField.addEventListener('change', buildRegulatoryNumber);
        activityDateField?.addEventListener('change', buildRegulatoryNumber);

        buildRegulatoryNumber();
    });
</script>
@endpush
