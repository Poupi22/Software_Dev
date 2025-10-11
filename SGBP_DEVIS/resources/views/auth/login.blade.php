<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - {{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .material-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .material-input {
            transition: all 0.3s ease;
        }

        .material-input:focus {
            border-color: #2563EB;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .material-button {
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .material-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .material-button:active {
            transform: translateY(0);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo et titre -->
        <div class="text-center mb-8">
            <div
                class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4 shadow-lg overflow-hidden">
                @if ($parametre->logo_path)
                    <img src="{{ asset('storage/' . $parametre->logo_path) }}" alt="Logo"
                        class="w-full h-full object-contain p-2">
                @else
                    <span class="material-icons text-5xl text-blue-600">receipt_long</span>
                @endif
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">{{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</h1>
            <p class="text-blue-100">Connectez-vous à votre compte</p>
        </div>

        <!-- Formulaire de connexion -->
        <div class="material-card p-8">
            <!-- ✅ ERREURS DE VALIDATION -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-icons text-red-600">error</span>
                        <p class="font-bold text-red-800">Erreur de connexion</p>
                    </div>
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ✅ MESSAGE DE SESSION (ex: déconnexion) -->
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                    <p class="text-sm text-green-800">{{ session('status') }}</p>
                </div>
            @endif

            <!-- ✅ FORMULAIRE LARAVEL -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-6">
                    <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                        <span class="material-icons text-gray-400 mr-2 text-xl">email</span>
                        Adresse email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="material-input w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none"
                        placeholder="votre@email.com" required autofocus>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div class="mb-6">
                    <label class="flex items-center text-sm font-medium text-gray-700 mb-2">
                        <span class="material-icons text-gray-400 mr-2 text-xl">lock</span>
                        Mot de passe
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                            class="material-input w-full px-4 py-3 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:outline-none pr-12"
                            placeholder="••••••••" required>
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <span class="material-icons" id="eyeIcon">visibility_off</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Se souvenir / Mot de passe oublié -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <!-- Bouton de connexion -->
                <button type="submit"
                    class="material-button w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 flex items-center justify-center gap-2">
                    <span class="material-icons">login</span>
                    Se connecter
                </button>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">
                            <a href="/" class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                <span class="material-icons text-base">arrow_back</span>
                                Retour au site
                            </a>
                        </span>
                    </div>
                </div>

                <!-- Footer -->
                <p class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} {{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}. Tous droits
                    réservés.
                </p>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.textContent = 'visibility';
            } else {
                passwordInput.type = 'password';
                eyeIcon.textContent = 'visibility_off';
            }
        }
    </script>
</body>

</html>
