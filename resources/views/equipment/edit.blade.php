@extends('layouts.app')

@section('title', 'Modifier l\'Équipement - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <h1 class="text-3xl font-semibold mb-6 text-gray-800">Modifier l'Équipement</h1>

        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="/equipment/{{ $equipment->id }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PUT">

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="establishment_id">
                        Établissement Détenteur *
                    </label>
                    <select name="establishment_id" id="establishment_id" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @foreach($establishments as $est)
                        <option value="{{ $est->id }}" {{ (old('establishment_id', $equipment->establishment_id) == $est->id) ? 'selected' : '' }}>{{ $est->name }} ({{ $est->city }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Désignation / Type d'Équipement *
                    </label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $equipment->name) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="manufacturer">
                            Fabricant
                        </label>
                        <input type="text" name="manufacturer" id="manufacturer" value="{{ old('manufacturer', $equipment->manufacturer) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="model">
                            Modèle
                        </label>
                        <input type="text" name="model" id="model" value="{{ old('model', $equipment->model) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="serial_number">
                            N° de Série (Fabricant)
                        </label>
                        <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $equipment->serial_number) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="regulatory_number">
                            N° Réglementaire (CNPRI)
                        </label>
                        <input type="text" name="regulatory_number" id="regulatory_number" value="{{ old('regulatory_number', $equipment->regulatory_number) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="voltage_max">
                            Tension Max (V / kV)
                        </label>
                        <input type="text" name="voltage_max" id="voltage_max" value="{{ old('voltage_max', $equipment->voltage_max) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="intensity_max">
                            Intensité Max (mA)
                        </label>
                        <input type="text" name="intensity_max" id="intensity_max" value="{{ old('intensity_max', $equipment->intensity_max) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="use_case">
                            Utilisation / Usage
                        </label>
                        <input type="text" name="use_case" id="use_case" value="{{ old('use_case', $equipment->use_case) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="filtration">
                            Filtration
                        </label>
                        <input type="text" name="filtration" id="filtration" value="{{ old('filtration', $equipment->filtration) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="installation_date">
                        Date de mise en service / d'Installation
                    </label>
                    <input type="date" name="installation_date" id="installation_date" value="{{ old('installation_date', $equipment->installation_date) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                        Statut Opérationnel *
                    </label>
                    <select name="status" id="status" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="Active" {{ old('status', $equipment->status) == 'Active' ? 'selected' : '' }}>En Service (Actif)</option>
                        <option value="Inactive" {{ old('status', $equipment->status) == 'Inactive' ? 'selected' : '' }}>Hors Service (Inactif)</option>
                        <option value="Out of order" {{ old('status', $equipment->status) == 'Out of order' ? 'selected' : '' }}>En Panne</option>
                        <option value="Decommissioned" {{ old('status', $equipment->status) == 'Decommissioned' ? 'selected' : '' }}>Déclassé / Retiré</option>
                    </select>
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="/equipment" class="text-gray-600 hover:text-gray-800 text-sm font-semibold">Annuler</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
                        Mettre à jour l'Équipement
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
