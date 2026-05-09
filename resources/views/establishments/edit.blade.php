@extends('layouts.app')

@section('title', 'Modifier l\'Établissement - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-5xl">
        <h1 class="text-3xl font-semibold mb-6 text-gray-800">Modifier l'Établissement</h1>

        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="/establishments/{{ $establishment->id }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PUT">

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Nom de l'établissement *
                    </label>
                    <input type="text" name="name" id="name" value="{{ $establishment->name }}" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: Hôpital Général de Référence">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category">
                        Catégorie *
                    </label>
                    <select name="category" id="category" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="Médical" {{ $establishment->category == 'Médical' ? 'selected' : '' }}>Médical</option>
                        <option value="Industriel" {{ $establishment->category == 'Industriel' ? 'selected' : '' }}>Industriel</option>
                        <option value="Mines" {{ $establishment->category == 'Mines' ? 'selected' : '' }}>Mines</option>
                        <option value="Recherche" {{ $establishment->category == 'Recherche' ? 'selected' : '' }}>Recherche</option>
                        <option value="Autre" {{ $establishment->category == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="province">
                            Province
                        </label>
                        <input type="text" name="province" id="province" value="{{ $establishment->province }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: Kinshasa">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="city">
                            Ville / Territoire
                        </label>
                        <input type="text" name="city" id="city" value="{{ $establishment->city }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: Gombe">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="address">
                        Adresse complète
                    </label>
                    <input type="text" name="address" id="address" value="{{ $establishment->address }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="N°, Avenue, Quartier...">
                </div>

                <hr class="my-6 border-gray-200">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="contact_name">
                            Nom du contact
                        </label>
                        <input type="text" name="contact_name" id="contact_name" value="{{ $establishment->contact_name }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Nom de la personne responsable">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="contact_title">
                            Qualité du représentant
                        </label>
                        <input type="text" name="contact_title" id="contact_title" value="{{ $establishment->contact_title }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: Directeur Général, Gérant">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">
                            Téléphone
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ $establishment->phone }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="+243...">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            Email
                        </label>
                        <input type="email" name="email" id="email" value="{{ $establishment->email }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="contact@etablissement.cd">
                    </div>
                </div>

                <hr class="my-6 border-gray-200">

                <h3 class="text-lg font-semibold text-gray-800 mb-4">Responsable de Protection Radiologique (RPR)</h3>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="rpr_name">
                        Nom du RPR
                    </label>
                    <input type="text" name="rpr_name" id="rpr_name" value="{{ $establishment->rpr_name }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Nom complet du RPR">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="rpr_phone">
                            Téléphone RPR
                        </label>
                        <input type="text" name="rpr_phone" id="rpr_phone" value="{{ $establishment->rpr_phone }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="+243...">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="rpr_email">
                            Email RPR
                        </label>
                        <input type="email" name="rpr_email" id="rpr_email" value="{{ $establishment->rpr_email }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="rpr@etablissement.cd">
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="rpr_accreditation">
                        Numéro d'accréditation RPR
                    </label>
                    <input type="text" name="rpr_accreditation" id="rpr_accreditation" value="{{ $establishment->rpr_accreditation }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Ex: CNPRI/RPR/2024/005">
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="/establishments/{{ $establishment->id }}" class="text-gray-600 hover:text-gray-800 text-sm font-semibold">Annuler</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
                        Mettre à jour l'Établissement
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
