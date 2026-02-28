@extends('layouts.app')

@section('title', 'Créer une Colocation - EasyColoc')

@section('breadcrumb', 'Créer une colocation')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg border border-gray-200 p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Créer une colocation</h1>
            <p class="text-gray-600 mb-8">Remplissez le formulaire ci-dessous pour créer votre nouvelle colocation</p>

            <form method="POST" action="{{ route('colocations.store') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nom de la colocation</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        placeholder="ex: Appart rue de la Paix"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description
                        (optionnel)</label>
                    <textarea id="description" name="description" placeholder="Décrivez votre colocation..." rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <span class="font-semibold">ℹ️ Info:</span> Vous deviendrez propriétaire (owner) de cette colocation
                        et pourrez inviter d'autres membres.
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4">
                    <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                        Créer la colocation
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="flex-1 border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold py-2 px-4 rounded-lg transition text-center">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
