@if($message = Session::get('success'))
    <div x-data="{ open: true }" x-show="open" class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
        <div class="flex justify-between items-start gap-4">
            <div class="flex items-start gap-3">
                <span class="text-emerald-600 text-lg shrink-0">✓</span>
                <p class="text-sm font-medium text-emerald-800 break-all">{{ $message }}</p>
            </div>
            <button @click="open = false" class="text-emerald-600 hover:text-emerald-800 shrink-0">✕</button>
        </div>
    </div>
@endif

@if($message = Session::get('error'))
    <div x-data="{ open: true }" x-show="open" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex justify-between items-center">
        <div class="flex items-center gap-3">
            <span class="text-red-600 text-lg">✕</span>
            <p class="text-sm font-medium text-red-800">{{ $message }}</p>
        </div>
        <button @click="open = false" class="text-red-600 hover:text-red-800">✕</button>
    </div>
@endif

@if($errors->any())
    <div x-data="{ open: true }" x-show="open" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex justify-between items-start">
            <div class="flex items-start gap-3">
                <span class="text-red-600 text-lg">⚠️</span>
                <div>
                    <p class="text-sm font-medium text-red-800 mb-2">Erreur de validation</p>
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button @click="open = false" class="text-red-600 hover:text-red-800">✕</button>
        </div>
    </div>
@endif
