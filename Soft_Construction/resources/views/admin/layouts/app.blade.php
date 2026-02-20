<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOFCONSTRUCTION | @yield('title', 'Admin Dashboard')</title>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- CSS -->
  <style>
        :root {
            --primary: 220 91% 35%;
            --primary-dark: 220 91% 40%;
            --primary-light: 220 70% 50%;
            --accent: 159 73% 40%;
            --accent-dark: 159 73% 35%;
            --background: 210 40% 98%;
            --card: 0 0% 100%;
            --card-foreground: 222.2 84% 4.9%;
            --muted: 210 40% 96%;
            --muted-foreground: 215.4 16.3% 46.9%;
            --border: 214.3 31.8% 91.4%;
            --input: 214.3 31.8% 91.4%;
            --ring: 220 91% 35%;
            --destructive: 0 84.2% 60.2%;
            
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --navbar-height: 70px;
            
            --shadow-sm: 0 1px 2px 0 hsl(0 0% 0% / 0.05);
            --shadow-md: 0 4px 6px -1px hsl(0 0% 0% / 0.1);
            --shadow-lg: 0 10px 15px -3px hsl(0 0% 0% / 0.1);
            --shadow-xl: 0 20px 25px -5px hsl(0 0% 0% / 0.1);
            
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dark {
            --background: 222.2 84% 4.9%;
            --card: 222.2 84% 4.9%;
            --card-foreground: 210 40% 98%;
            --muted: 217.2 32.6% 17.5%;
            --muted-foreground: 215 20.2% 65.1%;
            --border: 217.2 32.6% 17.5%;
            --input: 217.2 32.6% 17.5%;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: hsl(var(--background));
            color: hsl(var(--card-foreground));
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Dashboard Layout */
        .dashboard {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: hsl(var(--card));
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            z-index: 1000;
            overflow-y: auto;
            border-right: 1px solid hsl(var(--border));
            transform: translateX(0);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar.mobile-hidden {
            transform: translateX(-100%);
        }

        .sidebar-brand {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid hsl(var(--border));
            height: var(--navbar-height);
            background: linear-gradient(135deg, hsl(var(--primary)), hsl(var(--primary-dark)));
        }

        .brand-logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .brand-logo-icon {
            font-size: 1.8rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        .brand-text {
            transition: var(--transition);
            white-space: nowrap;
        }

        .sidebar.collapsed .brand-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar-nav {
            list-style: none;
            padding: 1.5rem 0;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: hsl(var(--muted-foreground));
            text-decoration: none;
            transition: var(--transition);
            border-radius: 8px;
            position: relative;
        }

        .nav-link:hover {
            color: hsl(var(--primary));
            background-color: hsl(var(--primary) / 0.05);
        }

        .nav-link.active {
            color: white;
            background: linear-gradient(135deg, hsl(var(--primary)), hsl(var(--primary-dark)));
            box-shadow: var(--shadow-sm);
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.1rem;
        }

        .sidebar.collapsed .nav-icon {
            margin-right: 0;
        }

        .nav-text {
            transition: var(--transition);
            white-space: nowrap;
            font-weight: 500;
        }

        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        .main-content.full-width {
            margin-left: 0;
        }

        /* Navbar */
        .navbar {
            background: hsl(var(--card));
            box-shadow: var(--shadow-sm);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 900;
            height: var(--navbar-height);
            border-bottom: 1px solid hsl(var(--border));
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: hsl(var(--primary));
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }

        .sidebar-toggle:hover {
            background: hsl(var(--muted));
        }

        .navbar-title {
            font-size: 1.5rem;
            color: hsl(var(--primary));
            font-weight: 700;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-menu {
            position: relative;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, hsl(var(--primary)), hsl(var(--primary-dark)));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .user-avatar:hover {
            transform: scale(1.05);
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            background: hsl(var(--card));
            border-radius: 12px;
            box-shadow: var(--shadow-xl);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: var(--transition);
            z-index: 1000;
            border: 1px solid hsl(var(--border));
            overflow: hidden;
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: hsl(var(--card-foreground));
            text-decoration: none;
            transition: var(--transition);
            gap: 0.5rem;
        }

        .dropdown-item:hover {
            background: hsl(var(--muted));
            color: hsl(var(--primary));
        }

        /* Dashboard Content */
        .dashboard-content {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .welcome-section {
            margin-bottom: 2rem;
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            color: hsl(var(--card-foreground));
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            color: hsl(var(--muted-foreground));
            font-size: 1.1rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stats-card {
            background: hsl(var(--card));
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            border: 1px solid hsl(var(--border));
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(135deg, hsl(var(--primary)), hsl(var(--accent)));
        }

        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stats-title {
            font-size: 0.875rem;
            color: hsl(var(--muted-foreground));
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            background: linear-gradient(135deg, hsl(var(--primary)), hsl(var(--primary-dark)));
            box-shadow: var(--shadow-sm);
        }

        .stats-value {
            font-size: 2.25rem;
            font-weight: 800;
            color: hsl(var(--card-foreground));
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .stats-change {
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stats-change.positive {
            color: hsl(var(--accent));
        }

        .stats-change.negative {
            color: hsl(var(--destructive));
        }

        /* Content Section */
        .content-section {
            background: hsl(var(--card));
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid hsl(var(--border));
        }

        .content-section h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: hsl(var(--card-foreground));
            margin-bottom: 1rem;
        }

        .content-section p {
            color: hsl(var(--muted-foreground));
            line-height: 1.6;
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .dashboard-content {
                padding: 1.5rem;
            }
            
            .navbar {
                padding: 1rem 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .navbar {
                padding: 1rem;
            }
            
            .dashboard-content {
                padding: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .welcome-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .stats-card {
                padding: 1rem;
            }
            
            .content-section {
                padding: 1.5rem;
            }
            
            .navbar-title {
                font-size: 1.25rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar and Overlay -->
        @include('admin.layouts.partials.sidebar')
        
        <!-- Main Content -->
        <div class="main-content" id="mainContent">
            <!-- Navbar -->
            @include('admin.layouts.partials.navbar')
            
            <!-- Content Section -->
            <div class="dashboard-content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="{{ asset('js/admin.js') }}"></script>
    @stack('scripts')
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mainContent = document.getElementById('mainContent');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const userMenu = document.getElementById('userMenu');
    const dropdownMenu = document.getElementById('dropdownMenu');
    
    let isMobile = window.innerWidth <= 768;
    
    // Initialize sidebar state
    function initializeSidebar() {
        isMobile = window.innerWidth <= 768;
        
        if (isMobile) {
            sidebar.classList.add('mobile-hidden');
            mainContent.classList.add('full-width');
        } else {
            sidebar.classList.remove('mobile-hidden');
            mainContent.classList.remove('full-width');
        }
    }
    
    // Toggle sidebar
    sidebarToggle.addEventListener('click', function() {
        if (isMobile) {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        } else {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
    });
    
    // Close sidebar when clicking overlay (mobile)
    sidebarOverlay.addEventListener('click', function() {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
    });
    
    // Toggle user dropdown
    userMenu.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
    });
    
    // Close dropdown when clicking elsewhere
    document.addEventListener('click', function() {
        dropdownMenu.classList.remove('show');
    });
    
    // Handle window resize
    window.addEventListener('resize', initializeSidebar);
    
    // Initialize
    initializeSidebar();
});
</script>
</html>