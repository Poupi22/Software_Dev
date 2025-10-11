<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestion Devis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; }
        .stat-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .nav-item {
            transition: all 0.2s ease;
        }
        .nav-item.active {
            background: #EFF6FF;
            color: #2563EB;
        }
        @media (max-width: 768px) {
            .mobile-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                padding-bottom: env(safe-area-inset-bottom);
            }
            .content-with-mobile-nav {
                padding-bottom: 80px;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    @include('admin.layouts.partials.sidebar')

    <!-- Main Content -->
    <div class="md:pl-64">
        <!-- Header -->
        @include('admin.layouts.partials.header')

        <!-- Dashboard Content -->
        @yield('content')
    </div>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>
