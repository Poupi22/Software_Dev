<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="acceuille/assets/images/3ia logo-01 1.png">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    @stack('scripts_head')
</head>
<style>
    .main-content {
        margin-left: 250px;
        padding: 2rem;
        transition: margin-left 0.3s ease-in-out;
    }

    .main-content.collapsed {
        margin-left: 0;
    }

    @media (max-width: 767px) {
        .main-content {
            margin-left: 200px;
        }

        .main-content.collapsed {
            margin-left: 0;
        }
    }
</style>



<body class="d-flex">

    @include('admin_site.layouts.partials.sidebar')

    <div class="flex-grow-1">
        @include('admin_site.layouts.partials.navbar')

        <main class="p-4" style="margin-left: 20px;">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    @stack('scripts')
</body>

</html>
