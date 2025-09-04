<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="acceuille/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>institut 3iA</title>
    <link rel="icon" type="image/png" href="acceuille/assets/images/3ia logo-01 1.png">

    <!-- Google Analytics -->
    <script async
        src="https://www.googletagmanager.com/gtag/js?id={{ config('services.google_analytics.measurement_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', '{{ config('services.google_analytics.measurement_id') }}');
    </script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }

        /* Main Content Placeholder */
        .main-content {
            min-height: 50vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        /* Footer Styles */
        .footer {
            background: #fff;
            border-top: 3px solid #3b82f6;
        }

        /* Newsletter Section */
        .newsletter-section {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 60px 20px;
        }

        .newsletter-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }

        .newsletter-content h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .newsletter-content p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .newsletter-form {
            display: flex;
            gap: 10px;
        }

        .newsletter-form input {
            flex: 1;
            padding: 16px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
        }

        .newsletter-form button {
            padding: 16px 32px;
            background: white;
            color: #2563eb;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .newsletter-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        /* Partner Banner */
        .partner-banner {
            background: linear-gradient(to right, #e5e7eb 0%, #f3f4f6 50%, #e5e7eb 100%);
            padding: 40px 20px;
            border-bottom: 1px solid #d1d5db;
        }

        .partner-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .partner-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .partner-header i {
            font-size: 2rem;
            color: #3b82f6;
        }

        .partner-header h3 {
            font-size: 1.8rem;
            color: #1f2937;
            font-weight: 700;
        }

        .partner-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .partner-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .partner-item i {
            color: #3b82f6;
        }

        .separator {
            color: #9ca3af;
        }

        /* Main Footer Content */
        .footer-main {
            padding: 80px 20px 40px;
            background: white;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
            margin-bottom: 60px;
        }

        .footer-column h4 {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: #1f2937;
        }

        .footer-about p {
            color: #6b7280;
            margin-bottom: 20px;
            line-height: 1.8;
        }

        .social-links {
            display: flex;
            gap: 12px;
        }

        .social-link {
            width: 45px;
            height: 45px;
            background: #eff6ff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3b82f6;
            transition: all 0.3s;
            text-decoration: none;
        }

        .social-link:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-3px);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 15px;
        }

        .footer-links a {
            color: #6b7280;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
        }

        .footer-links a:hover {
            color: #3b82f6;
            padding-left: 5px;
        }

        .footer-links i {
            font-size: 0.9rem;
        }

        .contact-item {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.3s;
        }

        .contact-item:hover {
            color: #3b82f6;
        }

        .contact-item i {
            color: #3b82f6;
            font-size: 1.2rem;
            min-width: 20px;
        }

        .contact-item span {
            font-size: 0.95rem;
        }

        /* Quick Contact Grid */
        .quick-contact {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            padding: 50px 20px;
            margin: 0 -20px 60px;
        }

        .contact-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .contact-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
        }

        .contact-card i {
            font-size: 2rem;
            color: #3b82f6;
            margin-bottom: 15px;
        }

        .contact-card span {
            display: block;
            color: #374151;
            font-weight: 500;
        }

        /* Footer Bottom */
        .footer-bottom {
            border-top: 1px solid #e5e7eb;
            padding: 30px 0;
        }

        .footer-bottom-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .copyright {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .footer-bottom-links {
            display: flex;
            gap: 30px;
        }

        .footer-bottom-links a {
            color: #6b7280;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .footer-bottom-links a:hover {
            color: #3b82f6;
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .newsletter-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .newsletter-content h2 {
                font-size: 2rem;
            }
        }

        @media (max-width: 640px) {
            .footer-grid {
                grid-template-columns: 1fr;
            }

            .newsletter-form {
                flex-direction: column;
            }

            .newsletter-form button {
                width: 100%;
            }

            .partner-list {
                flex-direction: column;
                gap: 15px;
            }

            .separator {
                display: none;
            }

            .footer-bottom-content {
                flex-direction: column;
                text-align: center;
            }

            .contact-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

</head>

<body>
    @include('acceuil.layouts.partials.header')

    <!-- Changed from container to full-width wrapper -->
    <main class="w-100 px-0 mx-0">
        @yield('content')
    </main>

    <!-- ================== SECTION TÉMOIGNAGE ================== -->
    @if (isset($temoignages) && $temoignages->count() > 0)
        <!-- ================== SECTION TÉMOIGNAGE ================== -->
        <section class="testimonial-carousel py-5" style="background: #f5f7fa;">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="section-title"
                        style="color: #2d3748; font-weight: 700; font-size: 2.5rem; margin-bottom: 0.5rem;">Ce que
                        disent nos apprenants</h2>
                    <p class="section-subtitle" style="color: #718096; font-size: 1.2rem;">Témoignages des étudiants de
                        l'Institut 3IA</p>
                    <p class="lead" style="max-width: 700px; margin: 0 auto; color: #4a5568;">
                        Découvrez les expériences authentiques de nos apprenants. Leurs retours témoignent de la qualité
                        de notre formation.
                    </p>
                </div>

                <div class="owl-carousel owl-theme">
                    @foreach ($temoignages as $temoignage)
                        <div class="testimonial-item text-center p-4 bg-white rounded"
                            style="
                    border-radius: 15px !important;
                    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
                    margin: 10px;
                    height: 100%;
                    transition: all 0.3s ease;
                    transform: scale(0.95);
                ">
                            <div class="testimonial-img-container mb-3">
                                @if ($temoignage->photo)
                                    <img src="{{ asset('storage/' . $temoignage->photo) }}"
                                        alt="{{ $temoignage->nom }}">
                                @else
                                    <img src="{{ asset('acceuil/assets/images/default-user.png') }}"
                                        alt="Avatar par défaut">
                                @endif
                            </div>

                            <h5 class="mb-1" style="color: #2d3748; font-weight: 600; font-size: 1.3rem;">
                                {{ $temoignage->nom }}</h5>

                            <small class="text-muted d-block mb-2"
                                style="color: #4a6cf7 !important; font-weight: 500; font-size: 0.95rem;">
                                {{ $temoignage->profession }}
                            </small>

                            @if ($temoignage->note)
                                <div class="star-rating mb-3" style="color: #fbbf24; font-size: 1.1rem;">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $temoignage->note ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                    <span
                                        style="color: #718096; font-size: 0.85rem; margin-left: 5px;">({{ $temoignage->note }}/5)</span>
                                </div>
                            @endif

                            <div
                                style="position: relative; background: white; border-radius: 12px; padding: 20px; margin-top: 10px; border: 1px solid #e2e8f0;">
                                <i class="fas fa-quote-left"
                                    style="color: #4a6cf7; opacity: 0.5; position: absolute; top: 10px; left: 15px;"></i>
                                <p class="testimonial-text"
                                    style="font-style: italic; color: #4a5568; line-height: 1.6; margin: 0; padding: 0 1rem;">
                                    "{{ $temoignage->message }}"</p>
                                <i class="fas fa-quote-right"
                                    style="color: #4a6cf7; opacity: 0.5; position: absolute; bottom: 10px; right: 15px;"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Required CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 30,
                nav: true,
                dots: true,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                smartSpeed: 800,
                navText: [
                    '<i class="fas fa-chevron-left"></i>',
                    '<i class="fas fa-chevron-right"></i>'
                ],
                responsive: {
                    0: {
                        items: 1,
                        stagePadding: 20
                    },
                    768: {
                        items: 2,
                        stagePadding: 40
                    },
                    992: {
                        items: 3,
                        stagePadding: 60
                    }
                }
            });

            // Add hover animation
            $('.testimonial-item').hover(
                function() {
                    $(this).css('transform', 'scale(1)');
                    $(this).css('box-shadow', '0 15px 30px rgba(0,0,0,0.1)');
                },
                function() {
                    $(this).css('transform', 'scale(0.95)');
                    $(this).css('box-shadow', '0 10px 25px rgba(0,0,0,0.05)');
                }
            );
        });
    </script>



    <!-- =============== PRE-FOOTER =================== -->
    <footer class="footer">
    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="newsletter-container">
            <div class="newsletter-content">
                <h2>Newsletter</h2>
                <p>Inscrivez-vous à notre newsletter pour recevoir les dernières nouvelles et offres exclusives.</p>
            </div>
            <div class="newsletter-form-wrapper">
                <form class="newsletter-form" method="POST" action="#">
                    @csrf
                    <input type="email" placeholder="Votre adresse email" required>
                    <button type="submit">
                        <i class="fas fa-paper-plane"></i> S'inscrire
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Partner Banner -->
    <section class="partner-banner">
        <div class="partner-content">
            <div class="partner-header">
                <i class="fas fa-handshake"></i>
                <h3>Nos Partenaires Officiels</h3>
            </div>
            <div class="partner-list">
                <div class="partner-item">
                    <i class="fas fa-building"></i>
                    <span>MINISTÈRE DE LA JEUNESSE</span>
                </div>
                <span class="separator">•</span>
                <div class="partner-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>MINISTÈRE DE L’EMPLOI ET DE LA FORMATION PROFESSIONNELLE</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Contact Grid -->
    <section class="quick-contact">
        <div class="contact-grid">
            <a href="#" class="contact-card">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $contact?->adresse ?? 'Adresse non disponible' }}</span>
            </a>
            <a href="tel:{{ $contact?->telephone ?? '#' }}" class="contact-card">
                <i class="fas fa-phone-volume"></i>
                <span>{{ $contact?->telephone ?? 'Téléphone non disponible' }}</span>
            </a>
            <a href="mailto:{{ $contact?->email ?? '#' }}" class="contact-card">
                <i class="fas fa-envelope"></i>
                <span>{{ $contact?->email ?? 'Email non disponible' }}</span>
            </a>
            <a href="https://wa.me/{{ $contact?->whatsapp ?? '#' }}" class="contact-card" target="_blank">
                <i class="fab fa-whatsapp"></i>
                <span>WhatsApp</span>
            </a>

            @if ($contact)
                @if ($contact->facebook_link)
                    <a href="{{ $contact->facebook_link }}" class="contact-card" target="_blank">
                        <i class="fab fa-facebook"></i>
                        <span>Facebook</span>
                    </a>
                @endif
                @if ($contact->linkedin_link)
                    <a href="{{ $contact->linkedin_link }}" class="contact-card" target="_blank">
                        <i class="fab fa-linkedin"></i>
                        <span>LinkedIn</span>
                    </a>
                @endif
                @if ($contact->tiktok_link)
                    <a href="{{ $contact->tiktok_link }}" class="contact-card" target="_blank">
                        <i class="fab fa-tiktok"></i>
                        <span>TikTok</span>
                    </a>
                @endif
            @endif
        </div>
    </section>

    <!-- Main Footer -->
    <div class="footer-main">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- About Column -->
                <div class="footer-column footer-about">
                    <h4>À Propos</h4>
                    <p>Notre mission est de promouvoir l’éducation, la formation et l’emploi grâce à des partenariats solides et des initiatives durables.</p>
                    <div class="social-links">
                        @if ($contact?->facebook_link)
                            <a href="{{ $contact->facebook_link }}" class="social-link" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if ($contact?->linkedin_link)
                            <a href="{{ $contact->linkedin_link }}" class="social-link" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        @endif
                        @if ($contact?->tiktok_link)
                            <a href="{{ $contact->tiktok_link }}" class="social-link" target="_blank"><i class="fab fa-tiktok"></i></a>
                        @endif
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-column">
                    <h4>Liens Rapides</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('acceuil.formation') }}"><i class="fas fa-graduation-cap"></i> Formations</a></li>
                        <li><a href="#"><i class="fas fa-briefcase"></i> Emplois</a></li>
                        <li><a href="{{ route('acceuil.actualite') }}"><i class="fas fa-newspaper"></i> Actualités</a></li>
                        <li><a href="#"><i class="fas fa-users"></i> Partenaires</a></li>
                        <li><a href="{{ route('acceuil.contact') }}"><i class="fas fa-envelope-open-text"></i> Contact</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div class="footer-column">
                    <h4>Nos Services</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-certificate"></i> Certifications</a></li>
                        <li><a href="#"><i class="fas fa-user-tie"></i> Orientation</a></li>
                        <li><a href="#"><i class="fas fa-chalkboard-teacher"></i> Formation Continue</a></li>
                        <li><a href="#"><i class="fas fa-clipboard-list"></i> Conseils</a></li>
                    </ul>
                </div>

                <!-- Legal Info -->
                <div class="footer-column">
                    <h4>Informations</h4>
                    <ul class="footer-links">
                        <li><a href="#"><i class="fas fa-gavel"></i> Mentions Légales</a></li>
                        <li><a href="#"><i class="fas fa-shield-alt"></i> Confidentialité</a></li>
                        <li><a href="#"><i class="fas fa-question-circle"></i> FAQ</a></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p class="copyright">© {{ date('Y') }} Tous droits réservés - Institut de Formation et Apprentissage.</p>
                    <div class="footer-bottom-links">
                        <a href="#">Aide</a>
                        <a href="#">Support</a>
                        <a href="#">Plan du Site</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>


    <!-- =============== MAIN FOOTER =================== -->
    <footer class="main-footer py-3">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center text-center">
            <p class="mb-2 mb-md-0">© Copyright 2024 • 3IA • Tous droits réservés.</p>
            <div class="footer-links">
                <a href="#">Plan du site</a>
                <a href="#">Politique de confidentialité</a>
            </div>
        </div>
    </footer>

    @include('acceuil.layouts.partials.footer')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
