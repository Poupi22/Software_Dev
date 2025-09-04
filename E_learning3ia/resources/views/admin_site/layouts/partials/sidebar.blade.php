<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #2563eb;
            --dark-blue: #1e40af;
            --light-blue: #93c5fd;
            --bg-gray: #f8fafc;
            --border-gray: #e2e8f0;
            --text-gray: #4b5563;
            --hover-gray: #f1f5f9;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            margin: 0;
            padding: 0;
            background: var(--bg-gray);
            color: #1e293b;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: white;
            border-right: 1px solid var(--border-gray);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid var(--border-gray);
        }

        .sidebar-header .logo-text {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-blue);
        }

        /* Navigation Items */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
            scrollbar-width: thin;
            scrollbar-color: var(--border-gray) transparent;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background-color: var(--border-gray);
            border-radius: 3px;
        }

        .nav-item {
            position: relative;
            margin: 0.25rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--text-gray);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            width: 100%;
            box-sizing: border-box;
        }

        .nav-item i {
            width: 24px;
            text-align: center;
            margin-right: 12px;
            color: var(--text-gray);
            transition: all 0.3s ease;
        }

        .nav-item:hover {
            background: var(--hover-gray);
        }

        .nav-item:hover a,
        .nav-item:hover i {
            color: var(--primary-blue);
        }

        .nav-item.active {
            background: #eff6ff;
        }

        .nav-item.active a,
        .nav-item.active i {
            color: var(--primary-blue);
            font-weight: 600;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary-blue);
            border-radius: 0 4px 4px 0;
        }

        /* Toggle Button - Visible only on mobile */
        .sidebar-toggle {
            position: fixed;
            top: 1rem;
            left: 1rem;
            width: 40px;
            height: 40px;
            background: white;
            border: 1px solid var(--border-gray);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            z-index: 1100;
            display: none;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-toggle i {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--text-gray);
            font-size: 1.1rem;
        }

        .sidebar-toggle:hover {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .sidebar-toggle:hover i {
            color: white;
        }

        /* Footer */
        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid var(--border-gray);
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-gray);
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-280px);
            }

            .sidebar.show-mobile {
                transform: translateX(0);
            }

            .sidebar-toggle {
                display: flex;
                left: 1rem;
            }

            .sidebar.show-mobile~.sidebar-toggle {
                left: 300px;
            }

            .sidebar.show-mobile {
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
            }
        }

        /* Desktop Styles */
        @media (min-width: 769px) {
            .sidebar {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-text">Admin Panel</div>
        </div>

        <div class="sidebar-nav" id="sidebarNav">
            <div class="nav-item">
                <a href="/dashboard" data-route="dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/actualite" data-route="actualite">
                    <i class="fas fa-newspaper"></i>
                    <span>Actualités</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/accueil" data-route="accueil">
                    <i class="fas fa-home"></i>
                    <span>Accueil</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/about" data-route="about">
                    <i class="fas fa-info-circle"></i>
                    <span>À propos</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/temoignage" data-route="temoignage">
                    <i class="fas fa-comment"></i>
                    <span>Témoignages</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/contact" data-route="contact">
                    <i class="fas fa-envelope"></i>
                    <span>Contact</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/evenement" data-route="evenement">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Événements</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/user" data-route="user">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/fiche_preinscription" data-route="fiche_preinscription">
                    <i class="fas fa-file-alt"></i>
                    <span>Fiche Préinscription</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/formateur" data-route="formateur">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Formateurs</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/qualification" data-route="qualification">
                    <i class="fas fa-tags"></i>
                    <span>Type de Formation</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/formation" data-route="formation">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Formations</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/matiere" data-route="matiere">
                    <i class="fas fa-book"></i>
                    <span>Matières</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/programme" data-route="programme">
                    <i class="fas fa-project-diagram"></i>
                    <span>Programmes</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/annee_academique" data-route="annee_academique">
                    <i class="fas fa-calendar"></i>
                    <span>Années Académiques</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/programme_session" data-route="programme_session">
                    <i class="fas fa-clock"></i>
                    <span>Sessions Programme</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/inscription" data-route="inscription">
                    <i class="fas fa-user-plus"></i>
                    <span>Inscriptions</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/etud" data-route="etud">
                    <i class="fas fa-user-graduate"></i>
                    <span>Étudiants</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="/dashboard/bulletin" data-route="bulletin">
                    <i class="fas fa-file-invoice"></i>
                    <span>Bulletins</span>
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            <p>© 2025 Admin Panel</p>
        </div>
    </aside>

    <!-- Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
        <i class="fas fa-arrow-left" id="toggleIcon"></i>
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarNav = document.getElementById('sidebarNav');
            const toggleButton = document.getElementById('sidebarToggle');
            const toggleIcon = document.getElementById('toggleIcon');

            // Storage keys
            const ACTIVE_LINK_KEY = 'adminPanelActiveLink';
            const SCROLL_POSITION_KEY = 'adminPanelScrollPosition';
            const SIDEBAR_STATE_KEY = 'adminPanelSidebarState';

            // Check if mobile
            function isMobile() {
                return window.innerWidth <= 768;
            }

            // Update toggle icon based on sidebar state
            function updateToggleIcon() {
                if (isMobile()) {
                    // On mobile: arrow-right when closed, arrow-left when open
                    if (sidebar.classList.contains('show-mobile')) {
                        toggleIcon.className = 'fas fa-arrow-left';
                    } else {
                        toggleIcon.className = 'fas fa-arrow-right';
                    }
                } else {
                    // On desktop: arrow-left when open, arrow-right when collapsed
                    if (document.body.classList.contains('sidebar-collapsed')) {
                        toggleIcon.className = 'fas fa-arrow-right';
                    } else {
                        toggleIcon.className = 'fas fa-arrow-left';
                    }
                }
            }

            // Set active link
            function setActiveLink() {
                document.querySelectorAll('.nav-item').forEach(item => {
                    item.classList.remove('active');
                });

                const currentPath = window.location.pathname;
                const routeMatch = currentPath.match(/\/dashboard\/(.+)/);
                let activeRoute = routeMatch ? routeMatch[1] : 'dashboard';

                if (activeRoute === 'dashboard' && window.location.pathname === '/dashboard') {
                    const storedActiveLink = localStorage.getItem(ACTIVE_LINK_KEY);
                    if (storedActiveLink) {
                        activeRoute = storedActiveLink;
                    }
                }

                const activeLink = document.querySelector(`[data-route="${activeRoute}"]`);
                if (activeLink) {
                    activeLink.parentElement.classList.add('active');
                    localStorage.setItem(ACTIVE_LINK_KEY, activeRoute);
                }
            }

            // Save scroll position
            function saveScrollPosition() {
                localStorage.setItem(SCROLL_POSITION_KEY, sidebarNav.scrollTop);
            }

            // Restore scroll position
            function restoreScrollPosition() {
                const savedPosition = localStorage.getItem(SCROLL_POSITION_KEY);
                if (savedPosition) {
                    sidebarNav.scrollTop = parseInt(savedPosition, 10);
                }
            }

            // Save sidebar state
            function saveSidebarState() {
                if (isMobile()) {
                    localStorage.setItem(SIDEBAR_STATE_KEY, sidebar.classList.contains('show-mobile') ? 'open' :
                        'closed');
                } else {
                    localStorage.setItem(SIDEBAR_STATE_KEY, document.body.classList.contains('sidebar-collapsed') ?
                        'collapsed' : 'open');
                }
            }

            // Restore sidebar state
            function restoreSidebarState() {
                const savedState = localStorage.getItem(SIDEBAR_STATE_KEY);
                if (savedState === 'collapsed' && !isMobile()) {
                    document.body.classList.add('sidebar-collapsed');
                } else if (savedState === 'open' && isMobile()) {
                    sidebar.classList.add('show-mobile');
                }
                updateToggleIcon();
            }

            // Set up click handlers for nav items
            function setupNavHandlers() {
                document.querySelectorAll('.nav-item a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        document.querySelectorAll('.nav-item').forEach(item => {
                            item.classList.remove('active');
                        });
                        this.parentElement.classList.add('active');

                        const route = this.getAttribute('data-route');
                        localStorage.setItem(ACTIVE_LINK_KEY, route);

                        if (isMobile()) {
                            sidebar.classList.remove('show-mobile');
                            saveSidebarState();
                            updateToggleIcon();
                        }
                    });
                });
            }

            // Initialize
            function init() {
                setActiveLink();
                restoreScrollPosition();
                restoreSidebarState();
                setupNavHandlers();

                sidebarNav.addEventListener('scroll', saveScrollPosition);

                // Toggle sidebar
                toggleButton.addEventListener('click', function() {
                    if (isMobile()) {
                        sidebar.classList.toggle('show-mobile');
                    } else {
                        document.body.classList.toggle('sidebar-collapsed');
                    }
                    saveSidebarState();
                    updateToggleIcon();
                });

                // Handle window resize
                window.addEventListener('resize', function() {
                    if (!isMobile()) {
                        sidebar.classList.remove('show-mobile');
                    }
                    updateToggleIcon();
                });
            }

            init();
        });
    </script>
</body>

</html>
