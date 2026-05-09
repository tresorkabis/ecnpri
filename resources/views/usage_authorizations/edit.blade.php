@extends('layouts.app')

@section('title', 'Modifier Autorisation - CNPRI')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <h1 class="text-3xl font-semibold mb-6 text-gray-800">Modifier l'Autorisation</h1>

        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="/usage-authorizations/{{ $authorization->id }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PUT">

                @include('usage_authorizations.form')

                <div class="flex items-center justify-end space-x-4">
                    <a href="/usage-authorizations/{{ $authorization->id }}" class="text-gray-600 hover:text-gray-800 text-sm font-semibold">Annuler</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition">
                        Mettre à jour l'Autorisation
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
