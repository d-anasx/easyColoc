<div class="bg-white rounded-lg border border-gray-200 p-4">
    <div class="flex items-start justify-between">
        <div class="flex items-center gap-3 flex-1">
            <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                {{ strtoupper(substr($member->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-gray-900">{{ $member->name }}</h4>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-block px-2 py-1 rounded text-xs font-medium {{ $member->role === 'owner' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $member->role === 'owner' ? 'Propriétaire' : 'Membre' }}
                    </span>
                    @if($member->reputation > 0)
                        <span class="text-xs font-semibold text-emerald-600">📈 +{{ $member->reputation }}</span>
                    @elseif($member->reputation < 0)
                        <span class="text-xs font-semibold text-red-600">📉 {{ $member->reputation }}</span>
                    @endif
                </div>
            </div>
        </div>
        @if($showRemove && auth()->user()->activeColocation->owner_id === auth()->id())
            <button @click="deleteConfirm = true" class="text-red-600 hover:text-red-800 text-sm font-medium">
                Retirer
            </button>
        @endif
    </div>
</div>
