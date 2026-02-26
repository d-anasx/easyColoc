<div class="bg-white rounded-lg border border-gray-200 p-4 flex items-center justify-between">
    <div>
        <p class="text-sm font-medium text-gray-900">
            <span class="font-semibold text-indigo-600">{{ $from }}</span>
            <span class="text-gray-600"> doit </span>
            <span class="font-semibold text-emerald-600">{{ $amount }}€</span>
            <span class="text-gray-600"> à </span>
            <span class="font-semibold text-indigo-600">{{ $to }}</span>
        </p>
        <p class="text-xs text-gray-500 mt-1">Pour les dépenses partagées</p>
    </div>
    <form method="POST" action="{{ $markPaidUrl }}" class="ml-4">
        @csrf
        @method('PATCH')
        <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition">
            Marquer payé
        </button>
    </form>
</div>
