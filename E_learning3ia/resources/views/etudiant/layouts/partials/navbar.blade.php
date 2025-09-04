<div>
    <link rel="stylesheet" href="{{ asset('etudiant/assets/css/navbar.css') }}">

    <nav class="fixed w-full z-50 glass-effect">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3">
            <div class="flex items-center justify-between h-16">
                <!-- Logo - Extreme left -->
                <div class="logo-container">
                    <a href="{{ route('acceuil.index') }}" class="flex items-center">
                        <img src="/acceuille/assets/images/3ia logo-01 1.png" alt="3IA Logo" class="h-12">
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="desktop-nav">
                    <!-- Dashboard Dropdown -->
                    @hasrole('Administrateur')
                        <div class="dropdown-container">
                            <button class="nav-link dropdown-trigger" onclick="toggleDashboardDropdown()" id="dashboardBtn">
                                Switch
                                <i class="fas fa-chevron-down ml-1 text-xs transition-transform duration-300"></i>
                            </button>

                            <div id="dashboardDropdown" class="dropdown-menu dashboard-dropdown">
                                <a href="{{ route('dashboard.index') }}" class="dropdown-item" role="menuitem">
                                    <i class="fas fa-globe-americas mr-2 text-blue-500"></i>
                                    Website Dashboard
                                </a>
                                <a href="{{ route('etudiant.index') }}" class="dropdown-item" role="menuitem">
                                    <i class="fas fa-laptop-code mr-2 text-green-500"></i>
                                    E-Learning Dashboard
                                </a>
                                <a href="{{ route('dashboard1.index') }}" class="dropdown-item" role="menuitem">
                                    <i class="fas fa-user-shield mr-2 text-orange-500"></i>
                                    Roles & Permissions
                                </a>
                            </div>
                        </div>
                    @endhasrole

                    @hasrole('Etudiant')
                        <a href="{{ route('etudiant.index') }}"
                            class="nav-link {{ request()->routeIs('etudiant.index') ? 'active' : '' }}">
                            Dashboard
                        </a>
                    @endhasrole
                    @hasrole('Administrateur')
                        <a href="{{ route('etudiant.notifications') }}"
                            class="nav-link {{ request()->routeIs('etudiant.notifications') ? 'active' : '' }}">
                            Notifications
                        </a>
                    @endhasrole

                    @hasrole('Etudiant')
                        <a href="{{ route('etudiant.course.index') }}"
                            class="nav-link {{ request()->routeIs('etudiant.course.index') ? 'active' : '' }}">
                            Courses
                        </a>
                    @endhasrole

                    {{-- @hasrole('Etudiant')
                        <a href="{{ route('etudiant.bulletin.index') }}"
                            class="nav-link {{ request()->routeIs('etudiant.bulletin.*') ? 'active' : '' }}">
                            Bulletins
                        </a>
                    @endhasrole --}}

                    <a href="{{ route('etudiant.chat.index') }}"
                        class="nav-link relative {{ request()->routeIs('etudiant.chat') ? 'active' : '' }}">
                        Chat
                        @if ($totalUnreadMessages > 0)
                            <span class="notification-badge">{{ $totalUnreadMessages }}</span>
                        @endif
                    </a>

                    @hasrole('Etudiant')
                        <a href="{{ route('etudiant.email') }}"
                            class="nav-link relative {{ request()->routeIs('etudiant.email') ? 'active' : '' }}">
                            Email
                        </a>
                    @endhasrole

                    @hasrole('Administrateur')
                        <a href="{{ route('etudiant.profile') }}"
                            class="nav-link relative {{ request()->routeIs('etudiant.profile') ? 'active' : '' }}">
                            Admin
                        </a>
                    @endhasrole
                </div>

                <!-- Right Side - User Menu & CTA -->
                <div class="flex items-center gap-4">
                    <!-- Desktop User Menu -->
                    <div class="hidden md:flex items-center space-x-4">
                        <div class="user-avatar">
                            <a href="{{ route('profile.student_edit') }}" class="user-avatar hover:text-blue-600"
                                title="Edit Profile">
                                <i class="fas fa-user" aria-hidden="true"></i>
                                <span class="sr-only">Edit Profile</span>
                            </a>
                        </div>
                        <span class="text-gray-800">{{ Auth::user()->name ?? 'Guest' }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-gray-600 hover:text-red-600 transition-colors" type="submit">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <div class="hamburger" id="hamburger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu md:hidden" id="mobileMenu">
            <div class="mobile-nav-container">
                <div class="flex flex-col space-y-1 px-2">
                    <!-- Mobile Dashboard Dropdown -->
                    <div class="mobile-dropdown-container">
                        <button class="mobile-nav-link mobile-dropdown-trigger"
                            onclick="toggleMobileDashboardDropdown()">
                            <i class="fas fa-th-large"></i>
                            <span>Dashboard</span>
                            <i class="fas fa-chevron-down ml-auto transition-transform duration-300"></i>
                        </button>
                        <div id="mobileDashboardDropdown" class="mobile-dropdown-menu">
                            <a href="{{ route('dashboard.index') }}" class="mobile-dropdown-item" role="menuitem">
                                <i class="fas fa-globe-americas mr-2 text-blue-400"></i>
                                Website Dashboard
                            </a>
                            <a href="{{ route('etudiant.index') }}" class="mobile-dropdown-item" role="menuitem">
                                <i class="fas fa-laptop-code mr-2 text-green-400"></i>
                                E-Learning Dashboard
                            </a>
                            <a href="{{ route('dashboard1.index') }}" class="mobile-dropdown-item" role="menuitem">
                                <i class="fas fa-user-shield mr-2 text-orange-400"></i>
                                Roles & Permissions
                            </a>
                        </div>
                    </div>

                    @hasrole('Etudiant')
                        <a href="{{ route('etudiant.index') }}"
                            class="mobile-nav-link {{ request()->routeIs('etudiant.index') ? 'active-mobile-link' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    @endhasrole
                    @hasrole('Etudiant')
                        <a href="{{ route('etudiant.course.index') }}"
                            class="mobile-nav-link {{ request()->routeIs('etudiant.course.index') ? 'active-mobile-link' : '' }}">
                            <i class="fas fa-book"></i>
                            <span>Courses</span>
                        </a>
                    @endhasrole
                    @hasrole('Etudiant')
                        <a href="{{ route('etudiant.bulletin.index') }}"
                            class="mobile-nav-link {{ request()->routeIs('etudiant.bulletin.*') ? 'active-mobile-link' : '' }}">
                            <i class="fas fa-file-invoice"></i>
                            <span>Bulletins</span>
                        </a>
                    @endhasrole
                    <a href="{{ route('etudiant.chat.index') }}"
                        class="mobile-nav-link {{ request()->routeIs('etudiant.chat') ? 'active-mobile-link' : '' }}">
                        <i class="fas fa-comments"></i>
                        <span>Chat</span>
                        @if ($totalUnreadMessages > 0)
                            <span class="mobile-notification-badge">{{ $totalUnreadMessages }}</span>
                        @endif
                    </a>

                    @hasrole('Etudiant')
                        <a href="{{ route('etudiant.email') }}"
                            class="mobile-nav-link {{ request()->routeIs('etudiant.email') ? 'active-mobile-link' : '' }}">
                            <i class="fas fa-envelope"></i>
                            <span>Email</span>
                        </a>
                    @endhasrole
                    @hasrole('Administrateur')
                        <a href="{{ route('etudiant.profile') }}"
                            class="mobile-nav-link {{ request()->routeIs('etudiant.profile') ? 'active-mobile-link' : '' }}">
                            <i class="fas fa-cog"></i>
                            <span>Admin Dashboard</span>
                        </a>
                    @endhasrole
                    @hasrole('Etudiant')
                        <a href="{{ route('etudiant.profile') }}"
                            class="mobile-nav-link {{ request()->routeIs('etudiant.profile') ? 'active-mobile-link' : '' }}">
                            <i class="fas fa-user"></i>
                            <span>Profile</span>
                        </a>
                    @endhasrole
                </div>

                <div class="mt-8 pt-4 border-t border-gray-700 px-4">
                    <div class="flex items-center space-x-3 p-3 rounded-lg bg-white bg-opacity-10">
                        <div class="user-avatar">
                            <a href="{{ route('profile.student_edit') }}" class="user-avatar hover:text-blue-600"
                                title="Edit Profile">
                                <i class="fas fa-user" aria-hidden="true"></i>
                                <span class="sr-only">Edit Profile</span>
                            </a>
                        </div>
                        <div>
                            <p class="text-white font-medium">{{ Auth::user()->name ?? 'Guest' }}</p>
                            <p class="text-blue-200 text-sm">User</p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <a href="#"
                            class="bg-white bg-opacity-10 hover:bg-opacity-20 p-3 rounded-lg transition-all text-white text-sm text-center">
                            <i class="fas fa-cog block mb-1"></i>
                            Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="contents">
                            @csrf
                            <button type="submit"
                                class="bg-white bg-opacity-10 hover:bg-opacity-20 p-3 rounded-lg transition-all text-white text-sm text-center w-full">
                                <i class="fas fa-sign-out-alt block mb-1"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <style>
        /* Dropdown Styles */
        .dropdown-container {
            position: relative;
            display: inline-block;
        }

        .dropdown-trigger {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-size: inherit;
            color: inherit;
            padding: 0;
        }

        .dropdown-trigger.active .fa-chevron-down {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 0.5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(229, 231, 235, 0.8);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 0.5rem;
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #374151;
            text-decoration: none;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            transform: translateX(5px);
        }

        .dropdown-item:hover i {
            color: white !important;
        }

        .dropdown-item i {
            width: 16px;
            text-align: center;
        }

        /* Mobile Dropdown Styles */
        .mobile-dropdown-container {
            position: relative;
        }

        .mobile-dropdown-trigger {
            display: flex;
            align-items: center;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            color: inherit;
            font-family: inherit;
            font-size: inherit;
            cursor: pointer;
            padding: 0.75rem 0;
        }

        .mobile-dropdown-menu {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            margin: 0.5rem 0;
            padding: 0.5rem;
            display: none;
        }

        .mobile-dropdown-menu.show {
            display: block;
        }

        .mobile-dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            margin-bottom: 0.25rem;
        }

        .mobile-dropdown-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
        }

        .mobile-dropdown-item:last-child {
            margin-bottom: 0;
        }

        .mobile-dropdown-item i {
            width: 16px;
            text-align: center;
        }

        /* Ensure existing styles work with dropdown */
        .desktop-nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }

        /* Animation for dropdown arrow */
        .fa-chevron-down {
            transition: transform 0.3s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.getElementById('hamburger');
            const mobileMenu = document.getElementById('mobileMenu');

            // Dashboard dropdown functionality
            function toggleDashboardDropdown() {
                const dropdown = document.getElementById('dashboardDropdown');
                const dashboardBtn = document.getElementById('dashboardBtn');
                const isShowing = dropdown.classList.contains('show');

                if (isShowing) {
                    dropdown.classList.remove('show');
                    dashboardBtn.classList.remove('active');
                } else {
                    dropdown.classList.add('show');
                    dashboardBtn.classList.add('active');
                }
            }

            function toggleMobileDashboardDropdown() {
                const dropdown = document.getElementById('mobileDashboardDropdown');
                dropdown.classList.toggle('show');
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                // Desktop dropdown
                const desktopDropdown = document.getElementById('dashboardDropdown');
                const desktopBtn = document.getElementById('dashboardBtn');
                if (desktopDropdown && desktopBtn &&
                    !desktopDropdown.contains(e.target) &&
                    !desktopBtn.contains(e.target)) {
                    desktopDropdown.classList.remove('show');
                    desktopBtn.classList.remove('active');
                }

                // Mobile dropdown
                const mobileDropdown = document.getElementById('mobileDashboardDropdown');
                const mobileBtn = document.querySelector('.mobile-dropdown-trigger');
                if (mobileDropdown && mobileBtn &&
                    !mobileDropdown.contains(e.target) &&
                    !mobileBtn.contains(e.target)) {
                    mobileDropdown.classList.remove('show');
                }
            });

            // Original mobile menu functionality
            if (hamburger && mobileMenu) {
                let isMenuOpen = false;

                function openMenu() {
                    hamburger.classList.add('open');
                    mobileMenu.classList.add('open');
                    mobileMenu.style.overflowY = 'auto';
                    isMenuOpen = true;
                }

                function closeMenu() {
                    hamburger.classList.remove('open');
                    mobileMenu.classList.remove('open');
                    mobileMenu.style.overflowY = 'hidden';
                    isMenuOpen = false;
                }

                function toggleMenu() {
                    if (isMenuOpen) {
                        closeMenu();
                    } else {
                        openMenu();
                    }
                }

                hamburger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleMenu();
                });

                // Close menu when clicking on mobile links
                document.querySelectorAll('.mobile-nav-link, .mobile-dropdown-item').forEach(link => {
                    link.addEventListener('click', closeMenu);
                });

                // Close menu when pressing Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && isMenuOpen) {
                        closeMenu();
                    }
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (isMenuOpen &&
                        !mobileMenu.contains(e.target) &&
                        !hamburger.contains(e.target)) {
                        closeMenu();
                    }
                });

                // Close menu on window resize
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768 && isMenuOpen) {
                        closeMenu();
                    }
                });
            }

            // Make functions globally available
            window.toggleDashboardDropdown = toggleDashboardDropdown;
            window.toggleMobileDashboardDropdown = toggleMobileDashboardDropdown;
        });
    </script>
</div>
