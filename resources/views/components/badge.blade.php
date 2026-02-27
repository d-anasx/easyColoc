@php
    $colors = [
        'owner'    => 'bg-indigo-100 text-indigo-800',
        'member'   => 'bg-gray-100 text-gray-800',
        'admin'    => 'bg-red-100 text-red-800',
        'active'   => 'bg-emerald-100 text-emerald-800',
        'inactive' => 'bg-gray-100 text-gray-800',
        'banned'   => 'bg-red-100 text-red-800',
    ];

    $icons = [
        'owner'    => '👑',
        'member'   => '👤',
        'admin'    => '🛡️',
        'active'   => '✓',
        'inactive' => '○',
        'banned'   => '🚫',
    ];
@endphp

<span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium {{ $colors[$type] ?? 'bg-gray-100 text-gray-800' }}">
    <span>{{ $icons[$type] ?? '' }}</span>
    {{ $text }}
</span>