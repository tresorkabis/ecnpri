@php
    $isEditMode = isset($authorization) && $authorization->exists;
@endphp

<div class="mb-6">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="establishment_id">
        Établissement titulaire *
    </label>
    <select name="establishment_id" id="establishment_id" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        <option value="">Sélectionner un établissement</option>
        @foreach($establishments as $establishment)
            <option
                value="{{ $establishment->id }}"
                data-category="{{ $establishment->category }}"
                data-category-code="{{ \App\Models\UsageAuthorization::categoryCode($establishment->category) }}"
                {{ (string) old('establishment_id', $selectedEstablishmentId ?? $authorization->establishment_id ?? '') === (string) $establishment->id ? 'selected' : '' }}>
                {{ $establishment->name }} ({{ $establishment->city }})
            </option>
        @endforeach
    </select>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div>
        <label class="block text-gray-700 text-sm font-bold mb-2" for="reference_number">
            Référence / Numéro *
        </label>
        <input
            type="text"
            name="reference_number"
            id="reference_number"
            required
            value="{{ old('reference_number', $authorization->reference_number ?? '') }}"
            class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline {{ $isEditMode ? '' : 'bg-gray-50' }}"
            placeholder="Ex: CNPRI/AUT/MED/2026/014"
            @if(!$isEditMode) readonly @endif>
        @if(!$isEditMode)
            <p class="mt-2 text-xs text-gray-500">Le numéro est généré automatiquement après sélection de l'établissement.</p>
        @endif
    </div>
    <div>
        <label class="block text-gray-700 text-sm font-bold mb-2" for="issuing_authority">
            Autorité de délivrance
        </label>
        <input type="text" name="issuing_authority" id="issuing_authority" value="{{ old('issuing_authority', $authorization->issuing_authority ?? 'CNPRI') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div>
        <label class="block text-gray-700 text-sm font-bold mb-2" for="authorization_type">
            Type d'autorisation *
        </label>
        <select name="authorization_type" id="authorization_type" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <option value="">Sélectionner un type</option>
            @foreach($authorizationTypes as $type)
                <option value="{{ $type }}" {{ old('authorization_type', $authorization->authorization_type ?? '') === $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>
        <p class="text-xs text-gray-500 mt-2">Types relevés sur le site officiel `cnpri.cd`.</p>
    </div>
    <div>
        <label class="block text-gray-700 text-sm font-bold mb-2" for="scope">
            Portée *
        </label>
        <select name="scope" id="scope" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @foreach($scopes as $scope)
                <option value="{{ $scope }}" {{ old('scope', $authorization->scope ?? 'Sources et Équipements') === $scope ? 'selected' : '' }}>{{ $scope }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div>
        <label class="block text-gray-700 text-sm font-bold mb-2" for="issued_at">
            Date de délivrance
        </label>
        <input type="date" name="issued_at" id="issued_at" value="{{ old('issued_at', isset($authorization) && $authorization->issued_at ? $authorization->issued_at->format('Y-m-d') : '') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div>
        <label class="block text-gray-700 text-sm font-bold mb-2" for="expires_at">
            Date d'expiration
        </label>
        <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at', isset($authorization) && $authorization->expires_at ? $authorization->expires_at->format('Y-m-d') : '') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    <div>
        <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
            Statut *
        </label>
        <select name="status" id="status" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @foreach($statuses as $status)
                <option value="{{ $status }}" {{ old('status', $authorization->status ?? 'Valide') === $status ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-8">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">
        Observations
    </label>
    <textarea name="notes" id="notes" rows="4" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Conditions d'exploitation, équipements ou sources couverts, observations réglementaires...">{{ old('notes', $authorization->notes ?? '') }}</textarea>
</div>

@if(!$isEditMode)
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const establishmentField = document.getElementById('establishment_id');
        const issuedAtField = document.getElementById('issued_at');
        const referenceField = document.getElementById('reference_number');
        const previewMap = @json($referencePreviewMap ?? []);

        if (!establishmentField || !referenceField) {
            return;
        }

        const buildReference = () => {
            const option = establishmentField.options[establishmentField.selectedIndex];

            if (!option || !option.value) {
                referenceField.value = '';
                return;
            }

            const categoryCode = option.dataset.categoryCode || 'AUT';
            const issuedAt = issuedAtField?.value || '';
            const year = issuedAt ? new Date(issuedAt).getFullYear() : new Date().getFullYear();
            const currentMax = previewMap?.[categoryCode]?.[String(year)] || 0;
            const sequence = String(Number(currentMax) + 1).padStart(3, '0');

            referenceField.value = `CNPRI/AUT/${categoryCode}/${year}/${sequence}`;
        };

        establishmentField.addEventListener('change', buildReference);
        issuedAtField?.addEventListener('change', buildReference);

        buildReference();
    });
</script>
@endif
