@extends('layouts.app')

@section('title', $expense->title . ' - EasyColoc')

@section('breadcrumb', $expense->title)

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <!-- Expense Info -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
            <h1 class="text-2xl font-bold text-gray-900">{{ $expense->title }}</h1>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Montant total</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ number_format($expense->amount, 2, ',', '.') }} DH</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Votre part</p>
                    <p
                        class="text-2xl font-bold {{ $expense->payers->firstWhere('id', auth()->id())?->pivot->is_paid ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ number_format($share, 2, ',', '.') }} DH
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Créé par</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $expense->createdBy->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Catégorie</p>
                    <span class="inline-block px-2 py-1 bg-amber-100 text-amber-800 text-xs font-medium rounded">
                        {{ $expense->category->name }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Date</p>
                    <p class="text-sm text-gray-600">{{ $expense->created_at }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold">Statut</p>
                    @php $currentPayer = $expense->payers->firstWhere('id', auth()->id()); @endphp
                    @if ($currentPayer?->pivot->is_paid)
                        <span class="inline-block px-2 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded">✓
                            Payé</span>
                    @else
                        <span class="inline-block px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">✗ Non
                            payé</span>
                    @endif
                </div>
            </div>

        </div>

        <!-- Payers List -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Participants ({{ $expense->payers->count() }})</h2>
            <div class="space-y-3">
                @foreach ($expense->payers as $payer)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($payer->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $payer->name }}
                                    @if ($payer->id === $expense->created_by)
                                        <span class="text-xs text-indigo-500 font-normal">(créateur)</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">Part: {{ number_format($share, 2, ',', '.') }} DH</p>
                            </div>
                        </div>
                        @if ($payer->pivot->is_paid)
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-800 text-xs font-semibold rounded">✓
                                Payé</span>
                        @else
                            @if ($payer->id === auth()->id())
                                <form method="POST" action="{{ route('expenses.markAsPaid', $expense->id) }}">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded transition">
                                        Marquer payé
                                    </button>
                                </form>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">✗ Non
                                    payé</span>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <a href="{{ url()->previous() }}" class="block text-center text-sm text-indigo-600 hover:underline">← Retour</a>
    </div>
@endsection
