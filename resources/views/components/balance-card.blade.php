<div class="bg-white rounded-lg border border-gray-200 p-4">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-600">{{ $member->name }}</p>
            <p class="text-2xl font-bold mt-2 {{ $balance >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                {{ $balance >= 0 ? '+' : '' }}{{ number_format($balance, 2, ',', '.') }}€
            </p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $balance > 0 ? 'À recevoir' : ($balance < 0 ? 'À payer' : 'Équilibré') }}
            </p>
        </div>
        <div class="text-3xl">
            {{ $balance >= 0 ? '✓' : '↗' }}
        </div>
    </div>
</div>
