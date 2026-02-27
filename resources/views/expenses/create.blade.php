@extends('layouts.app')

@section('title', 'Ajouter une dépense - EasyColoc')

@section('breadcrumb', 'Ajouter une dépense')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg border border-gray-200 p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Ajouter une dépense</h1>

        <form method="POST" action="{{ route('expenses.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Titre</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"
                       placeholder="Ex: Courses Marjane">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Montant (DH)</label>
                <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" min="0"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none"
                       placeholder="Ex: 250.00">
                @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Payé par</label>
                <select name="paid_by" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" {{ old('paid_by') == $member->id ? 'selected' : '' }}>
                            {{ $member->name }} {{ $member->id === auth()->id() ? '(moi)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('paid_by') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Catégorie</label>
                <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="">-- Choisir une catégorie --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="flex-1 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
                    Ajouter la dépense
                </button>
                <a href="{{ url()->previous() }}" class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg transition text-center hover:bg-gray-50">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection