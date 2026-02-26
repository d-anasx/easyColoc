@extends('layouts.app')

@section('title', $colocation->name . ' - EasyColoc')

@section('breadcrumb', $colocation->name)

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $colocation->name }}</h1>
                    @if ($colocation->description)
                        <p class="text-gray-600 mt-2">{{ $colocation->description }}</p>
                    @endif
                </div>
                @if (auth()->user()->id === $colocation->owner_id)
                    <div class="flex gap-2">
                        <a href="{{ route('colocations.edit', $colocation) }}"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
                            Modifier
                        </a>
                        <button onclick="showDeleteModal = true"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                            Annuler
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div x-data="{ tab: 'members' }" class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="flex border-b border-gray-200 bg-gray-50 px-6">
                <button @click="tab = 'members'"
                    :class="tab === 'members' ? 'border-b-2 border-indigo-600 text-indigo-600' :
                        'text-gray-600 hover:text-gray-900'"
                    class="px-4 py-4 font-semibold transition">
                    👥 Membres
                </button>
                <button @click="tab = 'expenses'"
                    :class="tab === 'expenses' ? 'border-b-2 border-indigo-600 text-indigo-600' :
                        'text-gray-600 hover:text-gray-900'"
                    class="px-4 py-4 font-semibold transition">
                    💸 Dépenses
                </button>
                <button @click="tab = 'balances'"
                    :class="tab === 'balances' ? 'border-b-2 border-indigo-600 text-indigo-600' :
                        'text-gray-600 hover:text-gray-900'"
                    class="px-4 py-4 font-semibold transition">
                    💰 Soldes
                </button>
                <button @click="tab = 'categories'"
                    :class="tab === 'categories' ? 'border-b-2 border-indigo-600 text-indigo-600' :
                        'text-gray-600 hover:text-gray-900'"
                    class="px-4 py-4 font-semibold transition">
                    🏷️ Catégories
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Members Tab -->
                <div x-show="tab === 'members'" class="space-y-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Membres de la colocation</h2>
                        @if (auth()->user()->id === $colocation->owner_id)
                            <a href="{{ route('invitations.create', $colocation) }}"
                                class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-lg transition">
                                ➕ Inviter un membre
                            </a>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($colocation->members as $member)
                            <div class="bg-white rounded-lg border border-gray-200 p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-3 flex-1">
                                        <div
                                            class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">{{ $member->name }}</h4>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span
                                                    class="inline-block px-2 py-1 rounded text-xs font-medium {{ $member->pivot->role === 'owner' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $member->pivot->role === 'owner' ? 'Propriétaire' : 'Membre' }}
                                                </span>
                                                @if ($member->reputation > 0)
                                                    <span class="text-xs font-semibold text-emerald-600">📈
                                                        +{{ $member->reputation }}</span>
                                                @elseif($member->reputation < 0)
                                                    <span class="text-xs font-semibold text-red-600">📉
                                                        {{ $member->reputation }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if (auth()->user()->id === $colocation->owner_id && $member->id !== $colocation->owner_id)
                                        <button onclick="alert('Retirer le membre')"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            Retirer
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Expenses Tab -->
                <div x-show="tab === 'expenses'" class="space-y-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-gray-900">Dépenses</h2>
                        <a href="{{ route('expenses.create') }}"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
                            ➕ Ajouter une dépense
                        </a>
                    </div>

                    @if ($colocation->expenses->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Titre</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Montant</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Payé par</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Catégorie</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($colocation->expenses as $expense)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <p class="text-sm font-medium text-gray-900">{{ $expense->title }}</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="text-sm text-gray-600 font-semibold">
                                                    {{ number_format($expense->amount, 2, ',', '.') }}€</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="text-sm text-gray-600">{{ $expense->createdBy->name }}</p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="inline-block px-2 py-1 bg-amber-100 text-amber-800 text-xs font-medium rounded">{{ $expense->category->name }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="text-sm text-gray-500">{{ $expense->created_at }}</p>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href=""
                                                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Modifier</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @include('components.empty-state', [
                            'icon' => '💸',
                            'title' => 'Aucune dépense enregistrée',
                            'message' => 'Commencez par ajouter une dépense à votre colocation.',
                            'ctaUrl' => route('expenses.create'),
                            'ctaText' => 'Ajouter une dépense',
                        ])
                    @endif
                </div>

                <!-- Balances Tab -->
                <div x-show="tab === 'balances'" class="space-y-4">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Qui doit à qui?</h2>



                    @if (count($settlements) > 0)
                        <div class="space-y-3">
                            @foreach ($settlements as $settlement)
                                <div
                                    class="bg-white rounded-lg border border-gray-200 p-4 flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">
                                        <span class="font-semibold text-indigo-600">{{ $settlement['from'] }}</span>
                                        <span class="text-gray-600"> doit </span>
                                        <span
                                            class="font-semibold text-emerald-600">{{ number_format($settlement['amount'], 2, ',', '.') }}€</span>
                                        <span class="text-gray-600"> à </span>
                                        <span class="font-semibold text-indigo-600">{{ $settlement['to'] }}</span>
                                        <span class="text-gray-400 text-xs"> ({{ $settlement['expense'] }})</span>
                                    </p>
                                    @if(auth()->user()->name === $settlement['from'])
                                        <button onclick="alert('Marquer comme payé')"
                                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition">
                                            Marquer payé
                                        </button>
                                        
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        @include('components.empty-state', [
                            'icon' => '✓',
                            'title' => 'Tous les soldes sont équilibrés',
                            'message' => 'Il n\'y a aucune dette à régler en ce moment.',
                            'ctaUrl' => null,
                            'ctaText' => null,
                        ])
                    @endif

                    <!-- Individual Balances -->
                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Soldes individuels</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($colocation->members as $member)
                                @php
                                    $balance = $balances[$member->id] ?? 0;
                                @endphp
                                <div class="bg-white rounded-lg border border-gray-200 p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600">{{ $member->name }}</p>
                                            <p
                                                class="text-2xl font-bold mt-2 {{ $balance >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                                {{ $balance >= 0 ? '+' : '' }}{{ number_format($balance, 2, ',', '.') }}€
                                            </p>
                                        </div>
                                        <div class="text-3xl">{{ $balance >= 0 ? '✓' : '↗' }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Categories Tab -->
                <div x-show="tab === 'categories'" class="space-y-4">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Catégories</h2>
                    @if ($colocation->categories->count() > 0)
                        <div class="space-y-2">
                            @foreach ($colocation->categories as $category)
                                <div
                                    class="flex justify-between items-center bg-white rounded-lg border border-gray-200 p-4">
                                    <span class="font-medium text-gray-900">{{ $category->name }}</span>
                                    @if (auth()->user()->id === $colocation->owner_id)
                                        <button onclick="alert('Supprimer')"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">Supprimer</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if (auth()->user()->id === $colocation->owner_id)
                        <div class="mt-6 bg-gray-50 rounded-lg border border-gray-200 p-4">
                            <form method="POST" action="{{ route('categories.store', $colocation) }}"
                                class="flex gap-2">
                                @csrf
                                <input type="text" name="name" placeholder="Nouvelle catégorie..." required
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                                <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition">
                                    Ajouter
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
