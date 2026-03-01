<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte suspendu - EasyColoc</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg border border-gray-200 p-10 max-w-md w-full text-center space-y-6">
        <div class="text-6xl">🚫</div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Compte suspendu</h1>
            <p class="text-gray-600 mt-2">Votre compte a été banni par un administrateur.</p>
        </div>
        <a href="{{ route('login') }}" class="block px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition">
            Retour à la connexion
        </a>
    </div>
</body>
</html>