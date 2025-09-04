<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '3IA LMS')</title>
    <link rel="icon" type="image/png" href="acceuille/assets/images/3ia logo-01 1.png">

    <!-- Styles globaux -->
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <!-- Styles personnalisés -->
    @yield('styles')
</head>

<body class="bg-gray-50 text-gray-900">
    @include('etudiant.layouts.partials.navbar')

    <main class="py-6 px-4">
        @yield('content')
    </main>

    {{-- Footer facultatif --}}
    <footer class="bg-white text-center text-sm text-gray-500 mt-10 py-4 shadow-inner">
        © {{ date('Y') }} Institut 3IA. Tous droits réservés.
    </footer>

    {{-- Scripts personnalisés --}}
    @yield('scripts')
</body>

</html>
