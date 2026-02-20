<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer Template</title>
    <style>
        :root {
            --primary: #1e3a8a;
            --accent: #10b981;
            --dark: #1f2937;
            --white: #ffffff;
            --gray-light: #f3f4f6;
            --transition-fast: all 0.15s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .footer {
            background: var(--dark);
            color: var(--white);
            padding: 5rem 0 2rem;
        }
        
        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }
        
        .footer-logo {
            font-size: 1.8rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .footer-logo-icon {
            color: var(--accent);
            font-size: 2rem;
        }
        
        .footer-about {
            margin-bottom: 1.5rem;
            opacity: 0.8;
            line-height: 1.7;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
        }
        
        .social-link {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-fast);
        }
        
        .social-link:hover {
            background: var(--accent);
            transform: translateY(-3px);
        }
        
        .footer-title {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
            color: var(--accent);
        }
        
        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--accent);
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-link {
            margin-bottom: 0.8rem;
        }
        
        .footer-link a {
            opacity: 0.8;
            transition: var(--transition-fast);
        }
        
        .footer-link a:hover {
            opacity: 1;
            color: var(--accent);
            padding-left: 5px;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            opacity: 0.7;
            font-size: 0.9rem;
        }
        
        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 50px;
            height: 50px;
            background: var(--accent);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .back-to-top.active {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            background: #059669;
            transform: translateY(-5px);
        }
        
        /* WhatsApp Float */
        .whatsapp-float {
            position: fixed;
            left: 20px;
            bottom: 20px;
            z-index: 1000;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #25D366;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .whatsapp-float:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 8px 24px rgba(37, 211, 102, 0.4);
        }
        
        @media (max-width: 768px) {
            .footer {
                padding: 3rem 0 2rem;
            }
            
            .footer-grid {
                gap: 2rem;
            }
        }
        
        @media (max-width: 576px) {
            .container {
                padding: 0 1.5rem;
            }
            
            .footer-logo {
                font-size: 1.5rem;
            }
            
            .back-to-top {
                width: 40px;
                height: 40px;
                font-size: 1rem;
                bottom: 80px;
                right: 15px;
            }
            
            .whatsapp-float {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
                bottom: 15px;
                left: 15px;
            }
        }
    </style>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="footer-logo">
                        <!-- <span class="footer-logo-icon">🏗️</span> -->
                        <span>SOFTCONSTRUCTION</span>
                    </div>
                    <p class="footer-about">
                        Entreprise leader en construction, rénovation et formation professionnelle dans les régions de l'Ouest, du Littoral et du Centre Cameroun.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h3 class="footer-title">Liens Rapides</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="{{ route('home.home') }}">Accueil</a></li>
                        <li class="footer-link"><a href="{{ route('home.home.about') }}">À propos</a></li>
                        <li class="footer-link"><a href="{{ route('home.home.services') }}">Services</a></li>
                        <li class="footer-link"><a href="{{ route('home.home.formations') }}">Formations</a></li>
                        <li class="footer-link"><a href="{{ route('home.home.projets') }}">Projets</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3 class="footer-title">Services</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="{{ route('home.home.services') }}">Construction Générale</a></li>
                        <li class="footer-link"><a href="{{ route('home.home.services') }}">Carrelage & Finition</a></li>
                        <li class="footer-link"><a href="{{ route('home.home.services') }}">Plomberie & Sanitaires</a></li>
                        <li class="footer-link"><a href="{{ route('home.home.services') }}">Travaux Métalliques</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3 class="footer-title">Contact</h3>
                    <ul class="footer-links">
                        <li class="footer-link"><a href="tel:+237 693630574"><i class="fas fa-phone"></i> +237 693630574 </a></li>
                        <li class="footer-link"><a href="mailto:sofconstruction0@gmail.com"><i class="fas fa-envelope"></i> contact@soft-construction.org </a></li>
                        <li class="footer-link"><a href="#"><i class="fas fa-map-marker-alt"></i> Dschang, Cameroun</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2023 SOFCONSTRUCTION. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/237693630574" class="whatsapp-float" aria-label="Contactez-nous sur WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" aria-label="Retour en haut">
        <i class="fas fa-arrow-up"></i>
    </a>

    <script>
        // Back to Top Button functionality
        document.addEventListener('DOMContentLoaded', function() {
            const backToTopBtn = document.querySelector('.back-to-top');
            
            // Show/hide button based on scroll position
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopBtn.classList.add('active');
                } else {
                    backToTopBtn.classList.remove('active');
                }
            });
            
            // Smooth scroll to top when clicked
            backToTopBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    
                    // Only prevent default for anchor links that aren't # alone
                    if (href !== '#') {
                        e.preventDefault();
                        
                        const targetElement = document.querySelector(href);
                        if (targetElement) {
                            window.scrollTo({
                                top: targetElement.offsetTop - 80,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            });
        });
        // For the image slideshow option
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    let currentSlide = 0;
    
    function nextSlide() {
        slides[currentSlide].classList.remove('active');
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].classList.add('active');
    }
    
    // Change slide every 5 seconds
    setInterval(nextSlide, 5000);
});
    </script>
</body>
</html>