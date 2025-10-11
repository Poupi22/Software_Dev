<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 50%, #60A5FA 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .mobile-menu {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .mobile-menu.open {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="bg-white">
    <!-- Navigation -->
    @include('layouts.partials.nav')

    @yield('content')

    <!-- Footer -->
    @include('layouts.partials.footer')

    <script>
        // Smooth scroll pour les ancres internes uniquement (pas les liens externes)
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>

</html>
