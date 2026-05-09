@extends('layouts.app')

@section('title', 'Modifier la Source - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <h1 class="text-3xl font-semibold mb-6 text-gray-800">Modifier la Source Radioactive</h1>

        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="/radioactive-sources/{{ $source->id }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PUT">

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="establishment_id">
                        Établissement Détenteur *
                    </label>
                    <select name="establishment_id" id="establishment_id" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @foreach($establishments as $est)
                        <option value="{{ $est->id }}" {{ $source->establishment_id == $est->id ? 'selected' : '' }}>{{ $est->name }} ({{ $est->city }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="serial_number">
                            N° de Série (Fabricant) *
                        </label>
                        <input type="text" name="serial_number" id="serial_number" value="{{ $source->serial_number }}" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="regulatory_number">
                            N° Réglementaire (CNPRI)
                        </label>
                        <input type="text" name="regulatory_number" id="regulatory_number" value="{{ $source->regulatory_number }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="isotope">
                            Isotope *
                        </label>
                        <input type="text" name="isotope" id="isotope" value="{{ $source->isotope }}" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="initial_activity">
                            Activité Initiale *
                        </label>
                        <input type="number" step="any" name="initial_activity" id="initial_activity" value="{{ $source->initial_activity }}" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="unit">
                            Unité *
                        </label>
                        <select name="unit" id="unit" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="GBq" {{ $source->unit == 'GBq' ? 'selected' : '' }}>GBq</option>
                            <option value="TBq" {{ $source->unit == 'TBq' ? 'selected' : '' }}>TBq</option>
                            <option value="mCi" {{ $source->unit == 'mCi' ? 'selected' : '' }}>mCi</option>
                            <option value="Ci" {{ $source->unit == 'Ci' ? 'selected' : '' }}>Ci</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="activity_date">
                            Date de Référence *
                        </label>
                        <input type="date" name="activity_date" id="activity_date" value="{{ $source->activity_date }}" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="physical_form">
                            Forme Physique *
                        </label>
                        <select name="physical_form" id="physical_form" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="Sealed" {{ $source->physical_form == 'Sealed' ? 'selected' : '' }}>Scellée</option>
                            <option value="Unsealed" {{ $source->physical_form == 'Unsealed' ? 'selected' : '' }}>Non Scellée</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                            Catégorie AIEA
                        </label>
                        <select name="category" id="category" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="" {{ $source->category == '' ? 'selected' : '' }}>N/A</option>
                            <option value="1" {{ $source->category == '1' ? 'selected' : '' }}>Catégorie 1 (Danger extrême)</option>
                            <option value="2" {{ $source->category == '2' ? 'selected' : '' }}>Catégorie 2 (Très dangereux)</option>
                            <option value="3" {{ $source->category == '3' ? 'selected' : '' }}>Catégorie 3 (Dangereux)</option>
                            <option value="4" {{ $source->category == '4' ? 'selected' : '' }}>Catégorie 4 (Faible danger)</option>
                            <option value="5" {{ $source->category == '5' ? 'selected' : '' }}>Catégorie 5 (Danger minime)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                            Statut *
                        </label>
                        <select name="status" id="status" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="Active" {{ $source->status == 'Active' ? 'selected' : '' }}>En cours d'utilisation</option>
                            <option value="Stored" {{ $source->status == 'Stored' ? 'selected' : '' }}>En stockage (Réserve)</option>
                            <option value="Disposed" {{ $source->status == 'Disposed' ? 'selected' : '' }}>Évacuée / Déclassée</option>
                            <option value="Lost" {{ $source->status == 'Lost' ? 'selected' : '' }}>Perdue / Volée</option>
                        </select>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="location_details">
                        Localisation Précise / Détails
                    </label>
                    <textarea name="location_details" id="location_details" rows="3" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $source->location_details }}</textarea>
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="/radioactive-sources/{{ $source->id }}" class="text-gray-600 hover:text-gray-800 text-sm font-semibold">Annuler</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
                        Enregistrer les Modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
