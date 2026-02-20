<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Navbar - SOFTCONSTRUCTION</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            padding-top: 80px;
        }

        /* Header Styles */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .header.scrolled {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 80px;
            position: relative;
        }

        /* Logo Styles - Fixed proportions */
        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            background: #fff;
            padding: 8px 12px;
            height: 56px;
            min-width: 200px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        }

        .logo:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: rgba(59, 130, 246, 0.3);
        }

        .logo img {
            height: 40px;
            width: auto;
            object-fit: contain;
            margin-right: 10px;
            border-radius: 4px;
            transition: transform 0.3s ease;
        }

        .logo:hover img {
            transform: scale(1.05);
        }

        .logo-text {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -0.5px;
            transition: color 0.3s ease;
        }

        .logo:hover .logo-text {
            color: #3b82f6;
        }

        /* Navigation Menu */
        .nav-menu {
            list-style: none;
            display: flex;
            gap: 8px;
            margin: 0;
            padding: 0;
            align-items: center;
        }

        .nav-link {
            text-decoration: none;
            color: #475569;
            font-weight: 500;
            padding: 10px 16px;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            font-size: 15px;
            letter-spacing: -0.2px;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 8px;
            background: linear-gradient(135deg, #10b981, #059669);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .nav-link:hover {
            color: white;
            transform: translateY(-1px);
        }

        .nav-link:hover::before {
            opacity: 1;
        }

        /* Active Link - Different from contact button */
        .nav-link.active {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            color: white;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
        }

        .nav-link.active::before {
            display: none;
        }

        /* Contact Button - Distinct styling */
        .nav-contact {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
            margin-left: 12px;
            position: relative;
            overflow: hidden;
        }

        .nav-contact::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #d97706, #b45309);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .nav-contact:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
        }

        .nav-contact:hover::before {
            opacity: 1;
        }

        .nav-contact span {
            position: relative;
            z-index: 1;
        }

        /* Mobile Menu Button - Fixed positioning */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #475569;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1001;
        }

        .mobile-menu-btn:hover {
            background: #f1f5f9;
            color: #3b82f6;
        }

        /* Demo Content */
        .demo-content {
            padding: 60px 20px;
            text-align: center;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .demo-content h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #3b82f6, #10b981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .demo-content p {
            font-size: 1.2rem;
            color: #64748b;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        Responsive Design
        @media (max-width: 1024px) {
            .nav-menu {
                gap: 4px;
            }
            
            .nav-link {
                padding: 8px 12px;
                font-size: 14px;
            }
            
            .nav-contact {
                padding: 10px 16px;
                margin-left: 8px;
            }
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }

            .nav-menu {
                position: fixed;
                top: 80px;
                left: 0;
                width: 100%;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                flex-direction: column;
                gap: 0;
                padding: 20px;
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
                border-top: 1px solid rgba(0, 0, 0, 0.08);
                transform: translateY(-20px);
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .nav-menu.active {
                transform: translateY(0);
                opacity: 1;
                visibility: visible;
            }

            .nav-menu li {
                width: 100%;
                margin-bottom: 8px;
            }

            .nav-link {
                display: block;
                width: 100%;
                text-align: center;
                padding: 14px 20px;
                font-size: 16px;
            }

            .nav-contact {
                width: 100%;
                text-align: center;
                margin-left: 0;
                margin-top: 12px;
                padding: 14px 20px;
            }

            .logo-text {
                font-size: 16px;
            }

            .logo img {
                height: 36px;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 15px;
            }

            .nav-container {
                padding: 10px 0;
            }

            .logo {
                padding: 6px 10px;
            }

            .logo-text {
                font-size: 15px;
            }

            .logo img {
                height: 32px;
                margin-right: 8px;
            }

            .demo-content h1 {
                font-size: 2rem;
            }

            .demo-content p {
                font-size: 1rem;
            }
        }

        /* Smooth animations */
        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .header {
            animation: slideInDown 0.6s ease-out;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav-container">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="logo" aria-label="Accueil SOFTCONSTRUCTION">
                    <img src="{{ asset('home/assets/img/logo.jpg') }}" alt="SOFTCONSTRUCTION Logo">
                    <!-- <span class="logo-text">SOFTCONSTRUCTION</span> -->
                </a>

                <!-- Navigation Menu -->
                <ul class="nav-menu" id="navMenu">
                    <li><a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Accueil</a></li>
                    <li><a href="{{ url('/about') }}" class="nav-link {{ request()->is('about') ? 'active' : '' }}">À propos</a></li>
                    <li><a href="{{ url('/services') }}" class="nav-link {{ request()->is('services') ? 'active' : '' }}">Services</a></li>
                    <li><a href="{{ url('/formations') }}" class="nav-link {{ request()->is('formations') ? 'active' : '' }}">Formations</a></li>
                    <li><a href="{{ url('/projets') }}" class="nav-link {{ request()->is('projets') ? 'active' : '' }}">Projets</a></li>
                    <li><a href="{{ url('/contact') }}" class="nav-contact"><span>Contact</span></a></li>
                </ul>

                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn" aria-label="Ouvrir le menu mobile">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
        </div>
    </header> 
    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navMenu = document.querySelector('.nav-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            const isActive = navMenu.classList.contains('active');
            
            // Update button icon with smooth transition
            mobileMenuBtn.innerHTML = isActive ? 
                '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
            
            // Toggle body overflow when menu is open
            document.body.style.overflow = isActive ? 'hidden' : '';
        });
        
        // Close mobile menu when clicking a link
        document.querySelectorAll('.nav-link, .nav-contact').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
                mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
                document.body.style.overflow = '';
            });
        });
        
        // Header scroll effect
        let lastScrollY = 0;
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.header');
            const currentScrollY = window.scrollY;
            
            // Add scrolled class for styling
            header.classList.toggle('scrolled', currentScrollY > 20);
            
            lastScrollY = currentScrollY;
        });
        
        // Remove active link management JS since Laravel blade already controls active class via request()->is()

        // Smooth hover effects
        document.querySelectorAll('.nav-link, .nav-contact').forEach(link => {
            link.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-1px)';
            });
            
            link.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });

        // Logo animation on load
        window.addEventListener('load', () => {
            const logo = document.querySelector('.logo');
            logo.style.animation = 'slideInDown 0.8s ease-out 0.3s both';
        });
    </script>
</body>
</html>
