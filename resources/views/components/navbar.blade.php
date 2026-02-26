<header class="bg-white border-b border-gray-200 px-4 sm:px-6 lg:px-8 py-4">
    <div class="flex justify-between items-center">
        <!-- Breadcrumb -->
        <div class="text-sm text-gray-600">
            @yield('breadcrumb', 'Dashboard')
        </div>

        <!-- User Info & Reputation -->
        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                <div class="flex items-center justify-end gap-1">
                    @if(auth()->user()->reputation > 0)
                        <span class="text-xs font-semibold text-emerald-600">📈 +{{ auth()->user()->reputation }}</span>
                    @elseif(auth()->user()->reputation < 0)
                        <span class="text-xs font-semibold text-red-600">📉 {{ auth()->user()->reputation }}</span>
                    @else
                        <span class="text-xs font-semibold text-gray-600">➖ 0</span>
                    @endif
                </div>
            </div>
            <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>
</header>
