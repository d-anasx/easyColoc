<aside class="hidden sm:flex sm:flex-col sm:w-64 bg-white border-r border-gray-200 overflow-y-auto">
    <div class="p-6 border-b border-gray-200">
        <h1 class="text-2xl font-bold text-indigo-600">EasyColoc</h1>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="{{ route('dashboard') }}"
            class="block px-4 py-2 rounded-lg font-medium transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">
            📊 Dashboard
        </a>

        @php $activeColocation = auth()->user()->activeColocation(); @endphp

        @if ($activeColocation)
            <a href="{{ route('colocations.show', $activeColocation->id) }}"
                class="block px-4 py-2 rounded-lg font-medium transition {{ request()->routeIs('colocations.show') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">
                🏠 Ma Colocation
            </a>
        @endif

        <hr class="my-4">

        @if (auth()->user()->isAdmin())
            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase">Admin</div>
            <a href="{{ route('admin.dashboard') }}"
                class="block px-4 py-2 rounded-lg font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">
                🧮 Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="block px-4 py-2 rounded-lg font-medium transition {{ request()->routeIs('admin.users*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">
                👥 Utilisateurs
            </a>
            <a href="{{ route('admin.stats') }}"
                class="block px-4 py-2 rounded-lg font-medium transition {{ request()->routeIs('admin.stats') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }}">
                📈 Statistiques
            </a>
        @endif
    </nav>

    <div class="p-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit"
                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 rounded transition">
                Déconnexion
            </button>
        </form>
    </div>
</aside>
