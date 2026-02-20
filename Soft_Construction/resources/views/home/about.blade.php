@extends('home.layouts.app')
<link rel="stylesheet" href="{{ asset('home/assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('home/assets/css/about.css') }}">
@section('styles')
    
@endsection

@section('content')
    <!-- About Hero Section -->
    <section class="hero">
       <div class="hero-bg-slider">
        @forelse($slides as $slide)
        <div class="hero-bg-slide" style="background-image: url('{{ Storage::url($slide->image1) }}')"></div>
        <div class="hero-bg-slide" style="background-image: url('{{ Storage::url($slide->image2) }}')"></div>
        <div class="hero-bg-slide" style="background-image: url('{{ Storage::url($slide->image3) }}')"></div>
        @empty
        <!-- Default slides if none exist -->
        <div class="hero-bg-slide" style="background-image: url('{{ asset('home/assets/img/chantier.png') }}')"></div>
        <div class="hero-bg-slide" style="background-image: url('{{ asset('home/assets/img/2.png') }}')"></div>
        <div class="hero-bg-slide" style="background-image: url('{{ asset('home/assets/img/3.png') }}')"></div>
        @endforelse
    </div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Notre Histoire</h1>
                <p class="hero-subtitle">Découvrez les valeurs qui nous animent et notre vision pour transformer le Cameroun</p>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Notre Mission</h2>
                <p>Transformer le paysage camerounais à travers l'excellence en construction et la formation professionnelle</p>
            </div>
            
            <div class="about-container">
                <div class="about-text">
                    @if($about)
                        <p>{{ $about->description1 }}</p>
                        
                        <div class="about-features">
                            <div class="about-feature">
                                <div class="about-feature-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <div>
                                    <h4>Notre Vision</h4>
                                    <p>{{ $about->description2 }}</p>
                                </div>
                            </div>
                            
                            <div class="about-feature">
                                <div class="about-feature-icon">
                                    <i class="fas fa-hand-holding-heart"></i>
                                </div>
                                <div>
                                    <h4>Nos Valeurs</h4>
                                    <p>Intégrité, Excellence, Innovation, Développement local et Durabilité environnementale guident chacune de nos actions.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <p>Fondée en 2023 à Dschang, SOFTCONSTRUCTION est née de la volonté de créer une entreprise camerounaise capable de rivaliser avec les standards internationaux tout en développant les compétences locales. Nous sommes aujourd'hui un leader de la construction durable et de la formation professionnelle dans les régions de l'Ouest, du Littoral et du Centre Cameroun.</p>
                        
                        <div class="about-features">
                            <div class="about-feature">
                                <div class="about-feature-icon">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <div>
                                    <h4>Notre Vision</h4>
                                    <p>Devenir le leader camerounais de la construction durable et de la formation professionnelle d'ici 2030, en formant plus de 5,000 professionnels qualifiés.</p>
                                </div>
                            </div>
                            
                            <div class="about-feature">
                                <div class="about-feature-icon">
                                    <i class="fas fa-hand-holding-heart"></i>
                                </div>
                                <div>
                                    <h4>Nos Valeurs</h4>
                                    <p>Intégrité, Excellence, Innovation, Développement local et Durabilité environnementale guident chacune de nos actions.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="about-image">
                    @if($about && $about->image)
                        <img src="{{ Storage::url($about->image) }}" alt="À propos de SOFTCONSTRUCTION">
                    @else
                        <img src="{{ asset('home/assets/img/31.png') }}" alt="Équipe SOFTCONSTRUCTION sur un chantier moderne">
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="section stats-section">
        <div class="container">
            <div class="section-title">
                <h2>SOFTCONSTRUCTION en <span class="text-gradient">Chiffres</span></h2>
                <p>Des résultats qui témoignent de notre engagement et de notre expertise</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card fade-in">
                    <div class="stat-number">{{ $stats['trained_people'] }}+</div>
                    <div class="stat-label">Personnes Formées</div>
                    <div class="stat-description">Professionnels certifiés depuis 2015</div>
                </div>
                
                <div class="stat-card fade-in">
                    <div class="stat-number">{{ $stats['completed_projects'] }}+</div>
                    <div class="stat-label">Projets Réalisés</div>
                    <div class="stat-description">Constructions de qualité livrées</div>
                </div>
                
                <div class="stat-card fade-in">
                    <div class="stat-number">{{ $stats['regions'] }}</div>
                    <div class="stat-label">Régions d'Intervention</div>
                    <div class="stat-description">Ouest, Littoral et Centre Cameroun</div>
                </div>
                
                <div class="stat-card fade-in">
                    <div class="stat-number">{{ $stats['employees'] }}+</div>
                    <div class="stat-label">Employés</div>
                    <div class="stat-description">Équipe d'experts passionnés</div>
                </div>
            </div>
        </div>
    </section>

    <!-- History Timeline -->
    <section class="section journey">
        <div class="container">
            <div class="section-title">
                <h2>Notre Parcours d'Excellence</h2>
                <p>Découvrez les étapes clés qui ont façonné notre histoire et notre succès</p>
            </div>
            
            <div class="journey-timeline">
                <div class="journey-item">
                    <div class="journey-year">2023</div>
                    <div class="journey-content">
                        <h3 class="journey-title">Fondation de l'entreprise</h3>
                        <p class="journey-description">
                            Création de notre société à Dschang avec une petite équipe passionnée et une vision claire.
                        </p>
                    </div>
                </div>
                <div class="journey-item">
                    <div class="journey-year">2024</div>
                    <div class="journey-content">
                        <h3 class="journey-title">Expansion à Douala et Yaoundé</h3>
                        <p class="journey-description">
                            Ouverture de bureaux dans les principales villes du Cameroun pour mieux servir nos clients.
                        </p>
                    </div>
                </div>
                <div class="journey-item">
                    <div class="journey-year">2025</div>
                    <div class="journey-content">
                        <h3 class="journey-title">Lancement du Centre de Formation</h3>
                        <p class="journey-description">
                            Inauguration de notre centre d'excellence pour la formation professionnelle à Dschang.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values -->
    <section class="section stats-section">
        <div class="container">
            <div class="section-title">
                <h2>Nos Valeurs Fondamentales</h2>
                <p>Les principes qui guident notre action quotidienne et notre vision d'avenir</p>
            </div>
            
            <div class="values-grid">
                <div class="value-card fade-in">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Intégrité</h3>
                    <p>Transparence absolue dans toutes nos relations avec nos clients, partenaires et équipes. L'honnêteté est la base de notre réputation.</p>
                </div>
                
                <div class="value-card fade-in">
                    <div class="value-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3>Excellence</h3>
                    <p>Recherche constante de la perfection dans chaque projet, de la conception à la livraison, en respectant les standards internationaux.</p>
                </div>
                
                <div class="value-card fade-in">
                    <div class="value-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Innovation</h3>
                    <p>Adoption des dernières technologies et méthodes de construction pour offrir des solutions modernes et durables à nos clients.</p>
                </div>
                
                <div class="value-card fade-in">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Développement Local</h3>
                    <p>Formation et emploi des talents locaux pour contribuer au développement économique et social des communautés camerounaises.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Team -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Notre Leadership d'Excellence</h2>
                <p>L'équipe dirigeante visionnaire qui guide SOFTCONSTRUCTION vers de nouveaux sommets</p>
            </div>
            
            <div class="team-grid">
                @forelse($leadershipTeam as $member)
                    <div class="team-member fade-in">
                        @if($member->image_path)
                            <img src="{{ Storage::url($member->image_path) }}" alt="{{ $member->name }}" class="team-img">
                        @else
                            <img src="{{ asset('home/assets/img/placeholder-team.jpg') }}" alt="{{ $member->name }}" class="team-img">
                        @endif
                        <div class="team-content">
                            <h3 class="team-name">{{ $member->name }}</h3>
                            <span class="team-position">{{ $member->position }}</span>
                            <p>{{ $member->description }}</p>
                        </div>
                    </div>
                @empty
                    <!-- Fallback team members if no data exists -->
                    <div class="team-member fade-in">
                        <img src="{{ asset('home/assets/img/28.png') }}" alt="Jean Fotso - PDG SOFTCONSTRUCTION" class="team-img">
                        <div class="team-content">
                            <h3 class="team-name">Jean Fotso</h3>
                            <span class="team-position">PDG & Fondateur</span>
                            <p>Visionnaire avec 15+ ans d'expérience dans la gestion de projets de construction au Cameroun.</p>
                        </div>
                    </div>
                    
                    <div class="team-member fade-in">
                        <img src="{{ asset('home/assets/img/22.jpg') }}" alt="Amina Njoya - Directrice des Opérations" class="team-img">
                        <div class="team-content">
                            <h3 class="team-name">Amina Njoya</h3>
                            <span class="team-position">Directrice des Opérations</span>
                            <p>Experte en coordination de grands projets et gestion des équipes multiculturelles.</p>
                        </div>
                    </div>
                    
                    <div class="team-member fade-in">
                        <img src="{{ asset('home/assets/img/35.png') }}" alt="Samuel Mbappé - Directeur Technique" class="team-img">
                        <div class="team-content">
                            <h3 class="team-name">Samuel Mbappé</h3>
                            <span class="team-position">Directeur Technique</span>
                            <p>Ingénieur chevronné spécialisé en construction durable et technologies vertes.</p>
                        </div>
                    </div>
                    
                    <div class="team-member fade-in">
                        <img src="{{ asset('home/assets/img/30.jpg') }}" alt="Grace Ngo - Directrice Formation" class="team-img">
                        <div class="team-content">
                            <h3 class="team-name">Grace Ngo</h3>
                            <span class="team-position">Directrice Formation</span>
                            <p>Pédagogue passionnée avec 10 ans d'expérience en formation professionnelle.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <div class="text-center">
                <a href="{{ route('home.home.contact') }}" class="btn btn-primary">Rencontrer Notre Équipe</a>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Back to top functionality
        const backToTop = document.getElementById('backToTop');
        
        if (backToTop) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 300) {
                    backToTop.classList.add('show');
                } else {
                    backToTop.classList.remove('show');
                }
            });

            backToTop.addEventListener('click', function(e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all fade-in elements
        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Counter animation for statistics
        function animateCounter(element, start, end, duration) {
            let current = start;
            const increment = end > start ? 1 : -1;
            const stepTime = Math.abs(Math.floor(duration / (end - start)));
            
            const timer = setInterval(() => {
                current += increment;
                element.textContent = current + (element.textContent.includes('+') ? '+' : '');
                
                if (current === end) {
                    clearInterval(timer);
                }
            }, stepTime);
        }

        // Animate counters when they come into view
        const statsObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target.querySelector('.stat-number');
                    const endValue = parseInt(counter.textContent.replace(/\D/g, ''));
                    animateCounter(counter, 0, endValue, 2000);
                    statsObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.stat-card').forEach(card => {
            statsObserver.observe(card);
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

        // Image slideshow for hero section
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide');
            let currentSlide = 0;
            
            function showSlide(n) {
                slides.forEach(slide => slide.classList.remove('active'));
                currentSlide = (n + slides.length) % slides.length;
                slides[currentSlide].classList.add('active');
            }
            
            // Auto-advance slides every 5 seconds
            setInterval(() => {
                showSlide(currentSlide + 1);
            }, 5000);
        });
    </script>
@endsection