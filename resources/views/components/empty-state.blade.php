<div class="text-center py-12">
    <div class="text-5xl mb-4">{{ $icon }}</div>
    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
    <p class="text-gray-600 text-sm mb-6 max-w-sm mx-auto">{{ $message }}</p>
    @if($ctaUrl && $ctaText)
        <a href="{{ $ctaUrl }}" class="inline-block px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
            {{ $ctaText }}
        </a>
    @endif
</div>
