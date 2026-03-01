@extends('layouts.app')
@section('title', 'Admin Dashboard - EasyColoc')
@section('breadcrumb', 'Admin Dashboard')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Tableau de bord Admin</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <p class="text-xs font-semibold text-gray-500 uppercase">Utilisateurs</p>
            <p class="text-4xl font-bold text-indigo-600 mt-2">{{ $stats['users'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <p class="text-xs font-semibold text-gray-500 uppercase">Colocations actives</p>
            <p class="text-4xl font-bold text-emerald-600 mt-2">{{ $stats['active_colocations'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <p class="text-xs font-semibold text-gray-500 uppercase">Total colocations</p>
            <p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['colocations'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <p class="text-xs font-semibold text-gray-500 uppercase">Dépenses</p>
            <p class="text-4xl font-bold text-amber-600 mt-2">{{ $stats['expenses'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <p class="text-xs font-semibold text-gray-500 uppercase">Montant total</p>
            <p class="text-4xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_amount'], 2, ',', '.') }} DH</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <p class="text-xs font-semibold text-gray-500 uppercase">Utilisateurs bannis</p>
            <p class="text-4xl font-bold text-red-600 mt-2">{{ $stats['banned'] }}</p>
        </div>
    </div>

    <div class="flex gap-4">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
            Gérer les utilisateurs
        </a>
        <a href="{{ route('admin.stats') }}" class="px-4 py-2 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-semibold rounded-lg transition">
            Voir les statistiques
        </a>
    </div>
</div>
@endsection