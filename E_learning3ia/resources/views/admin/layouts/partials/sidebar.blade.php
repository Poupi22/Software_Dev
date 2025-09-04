<button id="hamburgerBtn" class="hamburger">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
    </svg>
</button>

<aside id="sidebar" class="sidebar">
    <!-- Sidebar Header -->
    <div class="logo flex items-center justify-between p-4 bg-white w-full">
        <!-- Toggle Button (Visible only on mobile) -->
        <button id="toggleBtn" class="toggle-btn text-gray-600 hover:text-gray-900 flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
            </svg>
        </button>

        <!-- Logo and Title -->
        <a href="#" class="flex items-center space-x-2 overflow-hidden">
            <img src="{{ asset('admin/assets/images/3ia.png') }}" alt="3IA Logo" class="h-10 w-auto" />
        </a>
    </div>

    <!-- Navigation -->
    <nav class="nav">
        <a href="{{ route('dashboard1.index') }}" class="nav-item {{ request()->routeIs('dashboard1.index') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="nav-text">Dashboard</span>
        </a>
         <a href="{{ route('dashboard1.forum.index') }}" class="nav-item {{ request()->routeIs('dashboard1.forum.*') ? 'active' : '' }}">

             <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
             </svg>
            <span class="nav-text">Forums</span>
       </a>
        <a href="{{ route('dashboard1.permission.index') }}" class="nav-item {{ request()->routeIs('dashboard1.permission.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span class="nav-text">Permissions</span>
        </a>

        <a href="{{ route('dashboard1.role.index') }}" class="nav-item {{ request()->routeIs('dashboard1.role.*') ? 'active' : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="nav-text">Roles</span>
        </a>
        <a href="#" class="nav-item">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <span class="nav-text">Messages</span>
        </a>
    </nav>
</aside>

<style>
    .sidebar {
        width: 260px;
        background-color: #f1f5f9;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        border-right: 1px solid #e2e8f0;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.03);
        transition: width 0.3s ease, transform 0.3s ease;
        z-index: 50;
    }

    .sidebar.collapsed {
        width: 80px;
    }

    .sidebar.mobile-open {
        transform: translateX(0);
    }

    .logo {
        background-color: white;
        color: #1e3a8a;
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #e2e8f0;
    }

    .nav {
        padding: 1rem 0.5rem;
    }

    .nav-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        color: #334155;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .nav-item:hover {
        background-color: #e2e8f0;
        color: #1e3a8a;
    }

    .nav-item.active {
        background-color: #cbd5e1;
        color: #1e3a8a;
        font-weight: 600;
    }

    .nav-icon {
        width: 22px;
        height: 22px;
        flex-shrink: 0;
    }

    .nav-text {
        font-weight: 500;
    }

    .sidebar.collapsed .nav-text {
        display: none;
    }

    .toggle-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.5rem;
        display: none;
    }

    .logo {
        background-color: white;
        color: #1e3a8a;
        padding: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #e2e8f0;
        min-height: 64px;
        width: 100%;
        flex-wrap: nowrap;
        gap: 1rem;
    }

    .hamburger {
        display: none;
        position: fixed;
        top: 1rem;
        left: 1rem;
        z-index: 60;
        background: #f1f5f9;
        border: none;
        padding: 0.5rem;
        border-radius: 0.25rem;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .logo h1 {
            font-size: 1rem;
        }

        .logo a {
            flex-grow: 1;
            justify-content: center;
        }

        .toggle-btn {
            display: block;
        }

        .logo {
            justify-content: flex-start;
        }

        .sidebar {
            width: 260px;
            transform: translateX(-100%);
        }

        .sidebar.mobile-open {
            transform: translateX(0);
        }

        .hamburger {
            display: block;
        }
    }
</style>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleBtn');
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const navItems = document.querySelectorAll('.nav-item');

    function toggleSidebar() {
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('mobile-open');
        }
    }

    // Toggle sidebar with both buttons on mobile
    toggleBtn.addEventListener('click', toggleSidebar);
    hamburgerBtn.addEventListener('click', toggleSidebar);

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768 && sidebar.classList.contains('mobile-open')) {
            if (!sidebar.contains(e.target) && !hamburgerBtn.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('mobile-open');
            }
        }
    });

    // Adjust sidebar state on window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('mobile-open');
        }
    });

    // Add active class to clicked nav item
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all items
            navItems.forEach(navItem => navItem.classList.remove('active'));
            // Add active class to clicked item
            this.classList.add('active');

            // Close sidebar on mobile after clicking a link
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('mobile-open');
            }
        });
    });
</script>
