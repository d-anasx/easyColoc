@extends('layouts.app')

@section('title', 'Dashboard - EasyColoc')

@section('breadcrumb', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-lg p-8 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold">Bienvenue, {{ auth()->user()->name }}! 👋</h1>
                    <p class="mt-2 text-indigo-100">Gérez vos dépenses de colocation en quelques clics</p>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold">
                        @if (auth()->user()->reputation > 0)
                            <span class="text-emerald-400">📈 +{{ auth()->user()->reputation }}</span>
                        @elseif(auth()->user()->reputation < 0)
                            <span class="text-red-300">📉 {{ auth()->user()->reputation }}</span>
                        @else
                            <span>➖ 0</span>
                        @endif
                    </div>
                    <p class="text-sm text-indigo-100 mt-2">Votre réputation</p>
                </div>
            </div>
        </div>

        <!-- Active Colocation or CTA -->

        @if ($activeColocation)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Colocation Summary -->
                <div class="md:col-span-2 bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">🏠 Ma Colocation Active</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-600">Nom de la colocation</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <p class="text-2xl font-bold text-gray-900">{{ $activeColocation->name }} </p>
                                    <x-badge type="{{ $role }}" text="{{ $role }}"></x-badge>
                                </div>

                            </div>
                            <div class="flex gap-4 flex-col items-end">
                                <a href="{{ route('colocations.show', $activeColocation) }}"
                                    class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                    Voir →
                                </a>
                                <form action="{{ route('colocations.leave', $activeColocation) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-900 font-semibold">Quitter</button>
                                </form>
                            </div>

                        </div>
                        <div class="flex gap-4 pt-4 border-t border-gray-200">
                            <div>
                                <p class="text-xs text-gray-600 uppercase font-semibold">Membres</p>
                                <p class="text-lg font-bold text-gray-900">{{ $activeColocation->members->count() }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase font-semibold">Dépenses</p>
                                <p class="text-lg font-bold text-gray-900">{{ $activeColocation->expenses->count() }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 uppercase font-semibold">Total</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ number_format($activeColocation->expenses->sum('amount'), 2, ',', '.') }}DH</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Your Balance -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">💰 Votre Solde</h2>

                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-2">Solde actuel</p>
                        <p class="text-4xl font-bold {{ $userBalance >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $userBalance >= 0 ? '+' : '' }}{{ number_format($userBalance, 2, ',', '.') }}DH
                        </p>
                        <p class="text-xs text-gray-500 mt-2">
                            {{ $userBalance > 0 ? '✓ À recevoir' : ($userBalance < 0 ? '↗ À payer' : 'Équilibré') }}
                        </p>
                    </div>
                    <div class="mt-4 space-y-2">
                        <a href="{{ route('expenses.create') }}"
                            class="block w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition text-center">
                            Ajouter une dépense
                        </a>
                        <a href="{{ route('colocations.show', auth()->user()->activeColocation()) }}"
                            class="block w-full px-4 py-2 border border-indigo-600 text-indigo-600 hover:bg-indigo-50 text-sm font-medium rounded-lg transition text-center">
                            Voir les soldes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Expenses -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">Dépenses récentes</h2>
                </div>
                @if ($activeColocation->expenses->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Titre</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Montant</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Payé par</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activeColocation->expenses as $expense)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-medium text-gray-900">{{ $expense->title }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-600">
                                                {{ number_format($expense->amount, 2, ',', '.') }}€</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-600">{{ $expense->createdBy->name }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-500">{{ $expense->created_at }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    @include('components.empty-state', [
                        'icon' => '📊',
                        'title' => 'Aucune dépense enregistrée',
                        'message' => 'Commencez par ajouter une dépense à votre colocation.',
                        'ctaUrl' => '',
                        'ctaText' => 'Ajouter une dépense',
                    ])
                @endif
            </div>
        @else
            <!-- No Active Colocation CTAs -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Create Colocation -->
                <a href="{{ route('colocations.create') }}" class="group">
                    <div
                        class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-8 text-white cursor-pointer hover:shadow-lg transition transform hover:scale-105 h-full">
                        <div class="text-4xl mb-4">🏠</div>
                        <h3 class="text-2xl font-bold mb-2">Créer une colocation</h3>
                        <p class="text-indigo-100 mb-4">Créez votre propre colocation et invitez vos colocataires</p>
                        <div
                            class="inline-block px-4 py-2 bg-white text-indigo-600 font-semibold rounded-lg group-hover:bg-indigo-50 transition">
                            Commencer →
                        </div>
                    </div>
                </a>

                <!-- Join Colocation -->
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg p-8 text-white">
                    <div class="text-4xl mb-4">🔗</div>
                    <h3 class="text-2xl font-bold mb-2">Rejoindre une colocation</h3>
                    <p class="text-emerald-100 mb-4">Avez-vous reçu une invitation? Acceptez-la pour rejoindre une
                        colocation existante.</p>
                    <p class="text-sm text-emerald-100">Recherchez votre lien d'invitation dans vos emails</p>
                </div>
            </div>
        @endif
    </div>
@endsection
