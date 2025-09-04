<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="icon" type="image/png" href="acceuille/assets/images/3ia logo-01 1.png">

    <!-- CSS personnalisé dans public/admin/assets/css -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/dashboard.css') }}">

    <!-- Vite (compilation Tailwind etc.) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('custom-styles')
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">


    @include('admin.layouts.partials.navbar')

    <div class="flex flex-1 pt-16">


        <aside class="">
            @include('admin.layouts.partials.sidebar')
        </aside>


        <main class="flex-1 p-6 ml-0 lg:ml-64">

            @yield('content')
        </main>

    </div>

</body>

</html>
