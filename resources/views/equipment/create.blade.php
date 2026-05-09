@extends('layouts.app')

@section('title', 'Ajouter un Équipement - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-semibold mb-6 text-gray-800">Ajouter un Nouvel Équipement</h1>

        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="/equipment" method="POST">
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

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Désignation / Type d'Équipement *
                    </label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: Scanner CT, Appareil Mobile de Radiographie">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="manufacturer">
                            Fabricant
                        </label>
                        <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: GE, Siemens, Philips">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="model">
                            Modèle
                        </label>
                        <input type="text" name="model" id="model" value="{{ old('model') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: Revolution Evo">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="serial_number">
                            N° de Série (Fabricant)
                        </label>
                        <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="regulatory_number">
                            N° Réglementaire (CNPRI)
                        </label>
                        <input type="text" name="regulatory_number" id="regulatory_number" value="{{ old('regulatory_number') }}" readonly class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-50">
                        <p class="mt-2 text-xs text-gray-500">Le numéro est généré automatiquement après sélection de l'établissement.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="voltage_max">
                            Tension Max (V / kV)
                        </label>
                        <input type="text" name="voltage_max" id="voltage_max" value="{{ old('voltage_max') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: 440 V">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="intensity_max">
                            Intensité Max (mA)
                        </label>
                        <input type="text" name="intensity_max" id="intensity_max" value="{{ old('intensity_max') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: 500 mA">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="use_case">
                            Utilisation / Usage
                        </label>
                        <input type="text" name="use_case" id="use_case" value="{{ old('use_case') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: Radio conv, Scanner">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="filtration">
                            Filtration
                        </label>
                        <input type="text" name="filtration" id="filtration" value="{{ old('filtration') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="installation_date">
                        Date d'Installation
                    </label>
                    <input type="date" name="installation_date" id="installation_date" value="{{ old('installation_date') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                        Statut Opérationnel *
                    </label>
                    <select name="status" id="status" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>En Service (Actif)</option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Hors Service (Inactif)</option>
                        <option value="Out of order" {{ old('status') == 'Out of order' ? 'selected' : '' }}>En Panne</option>
                        <option value="Decommissioned" {{ old('status') == 'Decommissioned' ? 'selected' : '' }}>Déclassé / Retiré</option>
                    </select>
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="/equipment" class="text-gray-600 hover:text-gray-800 text-sm font-semibold">Annuler</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
                        Enregistrer l'Équipement
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
        const installationDateField = document.getElementById('installation_date');
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
            const installationDate = installationDateField?.value || '';
            const year = installationDate ? new Date(installationDate).getFullYear() : new Date().getFullYear();
            const currentMax = previewMap?.[categoryCode]?.[String(year)] || 0;
            const sequence = String(Number(currentMax) + 1).padStart(3, '0');

            regulatoryNumberField.value = `CNPRI-EQ-${categoryCode}-${year}-${sequence}`;
        };

        establishmentField.addEventListener('change', buildRegulatoryNumber);
        installationDateField?.addEventListener('change', buildRegulatoryNumber);

        buildRegulatoryNumber();
    });
</script>
@endpush
