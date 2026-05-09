@extends('layouts.app')

@section('title', 'Enregistrer un Inspecteur - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-lg">
        <h1 class="text-3xl font-semibold mb-6 text-gray-800">Enregistrer un Nouvel Inspecteur</h1>

        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="{{ route('inspectors.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Nom Complet *
                    </label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: Jean-Luc Picard">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="grade">
                        Grade
                    </label>
                    <input type="text" name="grade" id="grade" value="{{ old('grade') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: Inspecteur Principal">
                    @error('grade')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="employee_id">
                        Matricule / ID Employé *
                    </label>
                    <input type="text" name="employee_id" id="employee_id" required value="{{ old('employee_id') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: INS-2024-001">
                    @error('employee_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="specialization">
                        Spécialisation
                    </label>
                    <select name="specialization" id="specialization" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Sélectionner une spécialisation</option>
                        <option value="Radioprotection" {{ old('specialization') == 'Radioprotection' ? 'selected' : '' }}>Radioprotection</option>
                        <option value="Sécurité Nucléaire" {{ old('specialization') == 'Sécurité Nucléaire' ? 'selected' : '' }}>Sécurité Nucléaire</option>
                        <option value="Dosimétrie" {{ old('specialization') == 'Dosimétrie' ? 'selected' : '' }}>Dosimétrie</option>
                        <option value="Imagerie Médicale" {{ old('specialization') == 'Imagerie Médicale' ? 'selected' : '' }}>Imagerie Médicale</option>
                        <option value="Applications Industrielles" {{ old('specialization') == 'Applications Industrielles' ? 'selected' : '' }}>Applications Industrielles</option>
                    </select>
                    @error('specialization')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('inspectors.index') }}" class="text-gray-600 hover:text-gray-800 text-sm font-semibold">Annuler</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
