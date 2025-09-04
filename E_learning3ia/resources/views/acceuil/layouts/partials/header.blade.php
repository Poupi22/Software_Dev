<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Navbar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'slide-down': 'slideDown 0.3s ease-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'fade-in': 'fadeIn 0.2s ease-out',
                        'hamburger-spin': 'hamburgerSpin 0.3s ease-in-out',
                    },
                    keyframes: {
                        slideDown: {
                            '0%': {
                                transform: 'translateY(-100%)',
                                opacity: '0'
                            },
                            '100%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            }
                        },
                        slideUp: {
                            '0%': {
                                transform: 'translateY(0)',
                                opacity: '1'
                            },
                            '100%': {
                                transform: 'translateY(-100%)',
                                opacity: '0'
                            }
                        },
                        fadeIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(-10px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        hamburgerSpin: {
                            '0%': {
                                transform: 'rotate(0deg)'
                            },
                            '100%': {
                                transform: 'rotate(90deg)'
                            }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .hamburger-line {
            transition: all 0.3s ease-in-out;
        }

        .hamburger-active .line1 {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .hamburger-active .line2 {
            opacity: 0;
        }

        .hamburger-active .line3 {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        .nav-link {
            position: relative;
            overflow: hidden;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #3b82f6;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .mobile-menu.active {
            max-height: 500px;
        }

        /* Custom styles for mobile buttons */
        .mobile-cta-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px 16px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .mobile-cta-button:active {
            transform: scale(0.98);
        }

        .mobile-download-button {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .mobile-download-button:hover {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
            transform: translateY(-2px);
        }

        .mobile-login-button {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .mobile-login-button:hover {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
            transform: translateY(-2px);
        }

        /* Desktop button styles */
        .desktop-download-button {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .desktop-download-button:hover {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
            transform: translateY(-2px);
        }

        .desktop-login-button {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .desktop-login-button:hover {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="bg-gray-50">

    <!-- Navigation Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200 shadow-sm">
        <nav class="w-full">
            <div class="flex items-center justify-between h-16 mx-0">

                <!-- Logo - Flush left -->
                <div class="flex-shrink-0 pl-4 md:pl-6">
                    <a href="#" class="flex items-center">
                        <img src="acceuille/assets/images/3ia logo-01 1.png" alt="logo" class="h-[50px]">
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:block">
                    <div class="flex items-baseline space-x-8">
                        <a href="{{ route('acceuil.index') }}"
                            class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                            Accueil
                        </a>
                        <a href="{{ route('acceuil.about') }}"
                            class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                            À propos
                        </a>
                        <a href="{{ route('acceuil.formation') }}"
                            class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                            Formations
                        </a>
                        <a href="{{ route('acceuil.actualite') }}"
                            class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                            Actualités
                        </a>
                        <a href="{{ route('acceuil.contact') }}"
                            class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                            Contact
                        </a>
                    </div>
                </div>

                <!-- Desktop Buttons -->
                <div class="hidden md:flex items-center space-x-4 pr-6">
                    @if (isset($fichePreinscription) && $fichePreinscription)
                        <a href="{{ asset('storage/' . $fichePreinscription->chemin_fichier) }}"
                            download="{{ $fichePreinscription->nom_original }}"
                            class="desktop-download-button inline-flex items-center px-6 py-3 rounded-full text-sm font-semibold transition-all duration-200">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Fiche-Préinscription
                        </a>
                    @endif

                    <a href="{{ route('etudiant.index') }}"
                        class="desktop-login-button inline-flex items-center px-6 py-3 rounded-full text-sm font-semibold transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14v6l9-5-9-5-9 5 9 5z" />
                        </svg>
                        LOGIN
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden pr-4">
                    <button id="mobileMenuBtn"
                        class="hamburger-menu relative w-10 h-10 flex flex-col justify-center items-center bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        <span class="hamburger-line line1 block w-6 h-0.5 bg-gray-700 rounded-full"></span>
                        <span class="hamburger-line line2 block w-6 h-0.5 bg-gray-700 rounded-full mt-1.5"></span>
                        <span class="hamburger-line line3 block w-6 h-0.5 bg-gray-700 rounded-full mt-1.5"></span>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobileMenu" class="mobile-menu md:hidden bg-white border-t border-gray-200 shadow-lg">
                <div class="px-4 pt-4 pb-6 space-y-3">
                    <!-- Navigation Links -->
                    <a href="{{ route('acceuil.index') }}"
                        class="nav-link block px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-lg text-base font-medium transition-all duration-200 transform hover:translate-x-2 border-l-4 border-transparent hover:border-blue-500">
                        Accueil
                    </a>
                    <a href="{{ route('acceuil.about') }}"
                        class="nav-link block px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-lg text-base font-medium transition-all duration-200 transform hover:translate-x-2 border-l-4 border-transparent hover:border-blue-500">
                        À propos
                    </a>
                    <a href="{{ route('acceuil.formation') }}"
                        class="nav-link block px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-lg text-base font-medium transition-all duration-200 transform hover:translate-x-2 border-l-4 border-transparent hover:border-blue-500">
                        Formations
                    </a>
                    <a href="{{ route('acceuil.actualite') }}"
                        class="nav-link block px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-lg text-base font-medium transition-all duration-200 transform hover:translate-x-2 border-l-4 border-transparent hover:border-blue-500">
                        Actualités
                    </a>
                    <a href="{{ route('acceuil.contact') }}"
                        class="nav-link block px-4 py-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-lg text-base font-medium transition-all duration-200 transform hover:translate-x-2 border-l-4 border-transparent hover:border-blue-500">
                        Contact
                    </a>

                    <!-- Mobile Buttons Container -->
                    <div class="pt-6 space-y-4">
                        <!-- Fiche-Préinscription Button -->
                        @if (isset($fichePreinscription) && $fichePreinscription)
                            <a href="{{ asset('storage/' . $fichePreinscription->chemin_fichier) }}"
                                download="{{ $fichePreinscription->nom_original }}"
                                class="mobile-cta-button mobile-download-button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Fiche-Préinscription
                            </a>
                        @endif

                        <!-- LOGIN Button -->
                        <a href="{{ route('etudiant.index') }}"
                            class="mobile-cta-button mobile-login-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 14v6l9-5-9-5-9 5 9 5z" />
                            </svg>
                            LOGIN   
                        </a>
                    </div>

                    <!-- Additional Info -->
                    <div class="pt-6 border-t border-gray-200">
                        <div class="text-center text-sm text-gray-500">
                            <p>Connectez-vous à votre espace étudiant</p>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>


    <script>
        // Mobile menu toggle functionality
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        let isOpen = false;

        mobileMenuBtn.addEventListener('click', function() {
            isOpen = !isOpen;

            // Toggle hamburger animation
            if (isOpen) {
                mobileMenuBtn.classList.add('hamburger-active');
                mobileMenu.classList.add('active');
                // Prevent body scroll when menu is open
                document.body.style.overflow = 'hidden';
            } else {
                mobileMenuBtn.classList.remove('hamburger-active');
                mobileMenu.classList.remove('active');
                // Restore body scroll
                document.body.style.overflow = '';
            }
        });

        // Close mobile menu when clicking on a link
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                isOpen = false;
                mobileMenuBtn.classList.remove('hamburger-active');
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        // Close mobile menu when resizing to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768 && isOpen) {
                isOpen = false;
                mobileMenuBtn.classList.remove('hamburger-active');
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Add scroll effect to navbar
        let lastScrollTop = 0;
        const navbar = document.querySelector('header');

        window.addEventListener('scroll', function() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (scrollTop > 100) {
                navbar.classList.add('shadow-lg', 'bg-white/98');
            } else {
                navbar.classList.remove('shadow-lg');
                navbar.classList.add('bg-white/95');
            }

            lastScrollTop = scrollTop;
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (isOpen && !mobileMenu.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                isOpen = false;
                mobileMenuBtn.classList.remove('hamburger-active');
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Close menu with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && isOpen) {
                isOpen = false;
                mobileMenuBtn.classList.remove('hamburger-active');
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    </script>
</body>

</html>