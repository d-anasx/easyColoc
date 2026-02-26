<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg border border-gray-200 p-10 max-w-md w-full text-center space-y-6">
        <div class="text-6xl">🏠</div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Vous êtes invité !</h1>
            <p class="text-gray-600 mt-2">Vous avez été invité à rejoindre la colocation</p>
            <p class="text-2xl font-bold text-indigo-600 mt-2">{{ $colocation->name }}</p>
        </div>

        <div class="flex gap-2 pt-4 border-t border-gray-200">
            <form method="POST" action="{{ route('colocations.join', $token) }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full px-4 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-semibold rounded-lg transition">
                    ✓ Accepter
                </button>
            </form>
            <a href="{{ route('dashboard') }}" class="flex-1 px-4 py-3 bg-red-100 hover:bg-red-200 text-red-700 font-semibold rounded-lg transition text-center">
                ✗ Refuser
            </a>
        </div>
    </div>
</div>
</body>
</html>

