<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard.index') }}">
            <img src="/acceuille/assets/images/3ia logo-01 1.png" alt="3IA Logo">
            Admin Panel
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav gap-3">
                <!-- Dashboard Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" 
                       aria-expanded="false" id="dashboardDropdown">
                        <i class="fas fa-th-large me-1"></i> Dashboard
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dashboardDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard.index') }}">
                                <i class="fas fa-globe-americas me-2 text-primary"></i>
                                Website Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('etudiant.index') }}">
                                <i class="fas fa-laptop-code me-2 text-success"></i>
                                E-Learning Dashboard
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('dashboard1.index') }}">
                                <i class="fas fa-user-shield me-2 text-warning"></i>
                                Roles & Permissions
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-circle"></i> Profile
                    </a>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn-logout" type="submit">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<style>
    /* Navbar Glassmorphism Style */
    .navbar {
        background: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid #e5e7eb;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        padding: 1rem 2rem;
        position: sticky;
        top: 0;
        z-index: 1000;
        animation: slideDown 0.6s ease;
    }

    /* Logo & Brand */
    .navbar-brand {
        display: flex;
        align-items: center;
        color: #1e3a8a;
        font-size: 1.5rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .navbar-brand img {
        width: 40px;
        margin-right: 0.75rem;
        transition: transform 0.4s ease-in-out;
    }

    .navbar-brand:hover img {
        transform: rotate(15deg) scale(1.1);
    }

    /* Navigation Links */
    .nav-link {
        color: #1e3a8a;
        font-weight: 500;
        position: relative;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 8px;
    }

    .nav-link:hover {
        color: #2563eb;
        background: rgba(37, 99, 235, 0.08);
    }

    /* Subtle underline effect for nav links */
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        width: 0;
        height: 1px;
        background: #2563eb;
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .nav-link:hover::after {
        width: 70%;
    }

    /* Dropdown Styles */
    .dropdown-toggle::after {
        margin-left: 0.5rem;
        transition: transform 0.3s ease;
    }

    .dropdown-toggle.show::after {
        transform: rotate(180deg);
    }

    .dropdown-menu {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(229, 231, 235, 0.9);
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        padding: 0.5rem;
        margin-top: 0.5rem;
        animation: dropdownFade 0.3s ease;
        min-width: 220px;
    }

    .dropdown-item {
        color: #374151;
        font-weight: 500;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        margin: 0.15rem 0;
        border: none;
        background: transparent;
    }

    .dropdown-item:hover {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        color: #1e40af;
        transform: translateX(3px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .dropdown-item:hover i {
        transform: scale(1.1);
    }

    .dropdown-item i {
        width: 16px;
        text-align: center;
        transition: transform 0.3s ease;
    }

    /* Enhanced Dashboard Dropdown Hover */
    .nav-item.dropdown:hover .nav-link {
        background: rgba(37, 99, 235, 0.1);
        color: #2563eb;
    }

    .nav-item.dropdown:hover .nav-link::after {
        width: 80%;
    }

    /* Logout Button */
    .btn-logout {
        background: none;
        border: none;
        color: #1e3a8a;
        font-weight: 500;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
    }

    .btn-logout:hover {
        color: #dc2626;
        background: rgba(220, 38, 38, 0.08);
    }

    .btn-logout i {
        margin-right: 6px;
        transition: transform 0.3s ease;
    }

    .btn-logout:hover i {
        transform: translateX(-2px);
    }

    /* Animations */
    @keyframes slideDown {
        from {
            transform: translateY(-30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes dropdownFade {
        from {
            opacity: 0;
            transform: translateY(-8px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes fadeUp {
        0% {
            opacity: 0;
            transform: translateY(30px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-up {
        animation: fadeUp 0.8s ease forwards;
        opacity: 0;
    }

    .delay-1 {
        animation-delay: 0.2s;
    }
    .delay-2 {
        animation-delay: 0.4s;
    }
    .delay-3 {
        animation-delay: 0.6s;
    }

    /* Mobile Styles */
    @media (max-width: 768px) {
        .navbar-brand img {
            width: 30px;
        }
        .navbar-brand {
            font-size: 1.25rem;
        }
        .nav-link {
            font-size: 0.95rem;
            padding: 0.5rem 0.75rem;
        }
        
        .dropdown-menu {
            margin-top: 0.5rem;
            border-radius: 8px;
            min-width: 200px;
        }
        
        .dropdown-item {
            padding: 0.6rem 0.8rem;
            font-size: 0.9rem;
        }
        
        .btn-logout {
            padding: 0.5rem 0.75rem;
        }
    }

    /* Enhanced dropdown for better visibility */
    .nav-item.dropdown:hover .dropdown-menu {
        display: block;
    }

    /* Smooth transitions */
    .dropdown-menu {
        transition: all 0.3s ease;
    }

    /* Icon colors */
    .text-primary {
        color: #3b82f6 !important;
    }
    
    .text-success {
        color: #10b981 !important;
    }
    
    .text-warning {
        color: #f59e0b !important;
    }

    /* Active state for current page */
    .nav-link.active {
        color: #2563eb;
        background: rgba(37, 99, 235, 0.1);
    }

    .nav-link.active::after {
        width: 80%;
    }

    /* Focus states for accessibility */
    .nav-link:focus,
    .dropdown-item:focus,
    .btn-logout:focus {
        outline: 2px solid #3b82f6;
        outline-offset: 2px;
    }

    /* Subtle pulse animation for dropdown items */
    @keyframes subtlePulse {
        0% {
            transform: translateX(0);
        }
        50% {
            transform: translateX(3px);
        }
        100% {
            transform: translateX(0);
        }
    }

    .dropdown-item:hover {
        animation: subtlePulse 0.6s ease;
    }
</style>

<script>
    // Enhanced dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownToggle = document.querySelector('.dropdown-toggle');
        const dropdownMenu = document.querySelector('.dropdown-menu');
        
        // Add hover effect with delay for better UX
        let hoverTimeout;
        
        dropdownToggle.addEventListener('mouseenter', function() {
            clearTimeout(hoverTimeout);
            hoverTimeout = setTimeout(() => {
                const bsDropdown = bootstrap.Dropdown.getInstance(this) || new bootstrap.Dropdown(this);
                bsDropdown.show();
            }, 150);
        });
        
        dropdownToggle.addEventListener('mouseleave', function() {
            clearTimeout(hoverTimeout);
            hoverTimeout = setTimeout(() => {
                const bsDropdown = bootstrap.Dropdown.getInstance(this);
                if (bsDropdown && !dropdownMenu.matches(':hover')) {
                    bsDropdown.hide();
                }
            }, 300);
        });
        
        // Keep dropdown open when hovering over it
        dropdownMenu.addEventListener('mouseenter', function() {
            clearTimeout(hoverTimeout);
        });
        
        dropdownMenu.addEventListener('mouseleave', function() {
            hoverTimeout = setTimeout(() => {
                const bsDropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
                if (bsDropdown) {
                    bsDropdown.hide();
                }
            }, 300);
        });
        
        // Add click animation to dropdown items
        const dropdownItems = document.querySelectorAll('.dropdown-item');
        dropdownItems.forEach(item => {
            item.addEventListener('click', function() {
                // Add click feedback
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                const bsDropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
                if (bsDropdown) {
                    bsDropdown.hide();
                }
            }
        });

        // Add active state based on current page
        function setActiveNavItem() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link, .dropdown-item');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                    
                    // If it's a dropdown item, also highlight the parent dropdown
                    if (link.classList.contains('dropdown-item')) {
                        const dropdownToggle = document.querySelector('.dropdown-toggle');
                        dropdownToggle.classList.add('active');
                    }
                }
            });
        }
        
        setActiveNavItem();

        // Enhanced scroll behavior for navbar
        let lastScrollTop = 0;
        const navbar = document.querySelector('.navbar');
        
        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                // Scrolling down
                navbar.style.transform = 'translateY(-100%)';
            } else {
                // Scrolling up
                navbar.style.transform = 'translateY(0)';
            }
            
            // Add background on scroll
            if (scrollTop > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                navbar.style.backdropFilter = 'blur(20px)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.6)';
                navbar.style.backdropFilter = 'blur(12px)';
            }
            
            lastScrollTop = scrollTop;
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    });
</script>