@extends('layouts.app')
@section('title', 'Utilisateurs - EasyColoc')
@section('breadcrumb', 'Utilisateurs')
@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Gestion des utilisateurs</h1>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Utilisateur</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Rôle</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Réputation</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <x-badge :type="$user->role->name" :text="$user->role->name === 'admin' ? 'Admin' : 'User'" />
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold {{ $user->reputation > 0 ? 'text-emerald-600' : ($user->reputation < 0 ? 'text-red-600' : 'text-gray-500') }}">
                                {{ $user->reputation > 0 ? '+' : '' }}{{ $user->reputation }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <x-badge :type="$user->is_banned ? 'banned' : 'active'" :text="$user->is_banned ? 'Banni' : 'Actif'" />
                        </td>
                        <td class="px-6 py-4">
                            @if($user->id !== auth()->id())
                                @if($user->is_banned)
                                    <form method="POST" action="{{ route('admin.users.unban', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded transition">
                                            Débannir
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.ban', $user->id) }}">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded transition">
                                            Bannir
                                        </button>
                                    </form>
                                @endif
                            @else
                                <span class="text-xs text-gray-400">Vous</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection