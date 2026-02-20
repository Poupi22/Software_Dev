@extends('home.layouts.app')
<link rel="stylesheet" href="{{ asset('home/assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('home/assets/css/service.css') }}">
@section('title', 'Nos Services | SOFCONSTRUCTION')
@section('description', 'Découvrez nos services complets de construction, rénovation et aménagement en Afrique')

@section('styles')
    
@endsection

@section('content')
    <!-- Services Hero Section -->
    <section class="services-hero">
        <div class="image-slideshow">
            @forelse($slides as $index => $slide)
                <div class="slide {{ $index === 0 ? 'active' : '' }}" 
                     style="background-image: url('{{ $slide->image1 ? Storage::url($slide->image1) : asset('home/assets/img/47.png') }}')">
                </div>
            @empty
                <!-- Fallback slides if no slides exist -->
                <div class="slide active" style="background-image: url('{{ asset('home/assets/img/47.png') }}')"></div>
                <div class="slide" style="background-image: url('{{ asset('home/assets/img/43.png') }}')"></div>
                <div class="slide" style="background-image: url('{{ asset('home/assets/img/44.png') }}')"></div>
            @endforelse
            <div class="overlay"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Nos Services</h1>
                <p class="hero-subtitle">Des solutions complètes pour tous vos projets de construction et de rénovation</p>
            </div>
        </div>
    </section>

    <!-- Home Services Features Section -->
    @if($homeServices->count() > 0)
    <section class="section home-services-section">
        <div class="container">
            <div class="section-title">
                <h2>Pourquoi Nous Choisir</h2>
                <p>Découvrez ce qui fait notre différence</p>
            </div>
            
            <div class="home-services-grid">
                @foreach($homeServices as $homeService)
                <div class="home-service-card">
                    @if($homeService->image)
                        <img src="{{ Storage::url($homeService->image) }}" alt="{{ $homeService->title }}" class="home-service-img">
                    @endif
                    <div class="home-service-content">
                        <h3>{{ $homeService->title }}</h3>
                        <p>{{ $homeService->description }}</p>
                        
                        <div class="home-service-features">
                            <div class="feature">
                                <div class="feature-icon">
                                    <i class="{{ $homeService->feature_icon_1 }}"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>{{ $homeService->feature_title_1 }}</h4>
                                    <p>{{ $homeService->feature_description_1 }}</p>
                                </div>
                            </div>
                            
                            <div class="feature">
                                <div class="feature-icon">
                                    <i class="{{ $homeService->feature_icon_2 }}"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>{{ $homeService->feature_title_2 }}</h4>
                                    <p>{{ $homeService->feature_description_2 }}</p>
                                </div>
                            </div>
                            
                            <div class="feature">
                                <div class="feature-icon">
                                    <i class="{{ $homeService->feature_icon_3 }}"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>{{ $homeService->feature_title_3 }}</h4>
                                    <p>{{ $homeService->feature_description_3 }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($homeService->button_text)
                            <a href="#contact" class="btn btn-primary">{{ $homeService->button_text }}</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Main Services Section -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Nos Domaines d'Expertise</h2>
                <p>Des services professionnels adaptés à tous vos besoins en construction</p>
            </div>
            
            <div class="services-grid">
                @forelse($services as $service)
                <div class="service-card">
                    @if($service->icon)
                        <img src="{{ Storage::url($service->icon) }}" alt="{{ $service->title }}" class="service-img">
                    @else
                        <img src="{{ asset('home/assets/img/service-placeholder.jpg') }}" alt="{{ $service->title }}" class="service-img">
                    @endif
                    <div class="service-content">
                        <h3 class="service-title">{{ $service->title }}</h3>
                        <p class="service-description">{{ $service->short_description }}</p>
                        
                        @if($service->long_description)
                        <div class="service-details">
                            {!! $service->long_description !!}
                        </div>
                        @endif
                        
                        <a href="#" class="btn btn-primary">Voir les détails</a>
                    </div>
                </div>
                @empty
                <!-- Fallback services if no services exist -->
                <div class="service-card">
                    <img src="{{ asset('home/assets/img/8.png') }}" alt="Construction générale" class="service-img">
                    <div class="service-content">
                        <h3 class="service-title">Construction Générale</h3>
                        <p class="service-description">Solutions complètes de construction pour projets résidentiels et commerciaux à travers l'Afrique.</p>
                        <ul class="service-features">
                            <li><i class="fas fa-check-circle"></i> Bâtiments résidentiels</li>
                            <li><i class="fas fa-check-circle"></i> Complexes commerciaux</li>
                            <li><i class="fas fa-check-circle"></i> Structures industrielles</li>
                        </ul>
                        <a href="#contact" class="btn btn-primary">Demander un devis</a>
                    </div>
                </div>
                
                <div class="service-card">
                    <img src="{{ asset('home/assets/img/27.png') }}" alt="Carrelage et finition" class="service-img">
                    <div class="service-content">
                        <h3 class="service-title">Carrelage & Finition</h3>
                        <p class="service-description">Services experts en carrelage et finitions intérieures avec des matériaux de qualité supérieure.</p>
                        <ul class="service-features">
                            <li><i class="fas fa-check-circle"></i> Carrelage haut de gamme</li>
                            <li><i class="fas fa-check-circle"></i> Revêtements muraux</li>
                            <li><i class="fas fa-check-circle"></i> Finitions sur mesure</li>
                        </ul>
                        <a href="#contact" class="btn btn-primary">Demander un devis</a>
                    </div>
                </div>
                
                <div class="service-card">
                    <img src="https://images.unsplash.com/photo-1600566752355-35792bedcfea?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80" alt="Plomberie et sanitaires" class="service-img">
                    <div class="service-content">
                        <h3 class="service-title">Plomberie & Sanitaires</h3>
                        <p class="service-description">Installations et réparations de systèmes de plomberie conçus pour l'efficacité et la durabilité.</p>
                        <ul class="service-features">
                            <li><i class="fas fa-check-circle"></i> Systèmes sanitaires</li>
                            <li><i class="fas fa-check-circle"></i> Installations hydrauliques</li>
                            <li><i class="fas fa-check-circle"></i> Maintenance préventive</li>
                        </ul>
                        <a href="#contact" class="btn btn-primary">Demander un devis</a>
                    </div>
                </div>
                
                <div class="service-card">
                    <img src="{{ asset('home/assets/img/29.png') }}" alt="Travaux métalliques" class="service-img">
                    <div class="service-content">
                        <h3 class="service-title">Travaux Métalliques</h3>
                        <p class="service-description">Fabrication et installation sur mesure d'éléments métalliques structurels et décoratifs.</p>
                        <ul class="service-features">
                            <li><i class="fas fa-check-circle"></i> Structures porteuses</li>
                            <li><i class="fas fa-check-circle"></i> Escaliers métalliques</li>
                            <li><i class="fas fa-check-circle"></i> Garde-corps design</li>
                        </ul>
                        <a href="#contact" class="btn btn-primary">Demander un devis</a>
                    </div>
                </div>
                
                <div class="service-card">
                    <img src="{{ asset('home/assets/img/3.png') }}" alt="Génie civil" class="service-img">
                    <div class="service-content">
                        <h3 class="service-title">Génie Civil</h3>
                        <p class="service-description">Services complets de génie civil incluant conception, planification et gestion de projet.</p>
                        <ul class="service-features">
                            <li><i class="fas fa-check-circle"></i> Infrastructure routière</li>
                            <li><i class="fas fa-check-circle"></i> Ouvrages d'art</li>
                            <li><i class="fas fa-check-circle"></i> Aménagement urbain</li>
                        </ul>
                        <a href="#contact" class="btn btn-primary">Demander un devis</a>
                    </div>
                </div>
                
                <div class="service-card">
                    <img src="{{ asset('home/assets/img/35.png') }}" alt="Rénovation" class="service-img">
                    <div class="service-content">
                        <h3 class="service-title">Rénovation</h3>
                        <p class="service-description">Transformation d'espaces existants avec notre expertise en rénovation et modernisation.</p>
                        <ul class="service-features">
                            <li><i class="fas fa-check-circle"></i> Restructuration complète</li>
                            <li><i class="fas fa-check-circle"></i> Modernisation d'intérieurs</li>
                            <li><i class="fas fa-check-circle"></i> Mise aux normes</li>
                        </ul>
                        <a href="#contact" class="btn btn-primary">Demander un devis</a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Service Process Section -->
    <section class="section" style="background: #f3f4f6;">
        <div class="container">
            <div class="section-title">
                <h2>Notre Processus</h2>
                <p>Comment nous travaillons pour garantir votre satisfaction</p>
            </div>
            
            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <h3>Consultation Initiale</h3>
                    <p>Évaluation de vos besoins et élaboration d'un concept préliminaire</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">2</div>
                    <h3>Étude de Faisabilité</h3>
                    <p>Analyse technique et financière du projet</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">3</div>
                    <h3>Conception Détaillée</h3>
                    <p>Plans techniques et sélection des matériaux</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">4</div>
                    <h3>Exécution</h3>
                    <p>Réalisation du projet avec suivi qualité rigoureux</p>
                </div>
                
                <div class="process-step">
                    <div class="step-number">5</div>
                    <h3>Livraison</h3>
                    <p>Remise des clés avec garantie et service après-vente</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Add any service page specific JavaScript here
        // For example, smooth scrolling for anchor links
        $('a[href^="#"]').on('click', function(event) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: $($(this).attr('href')).offset().top - 100
            }, 800);
        });

        // Image slideshow for hero section
        const slides = document.querySelectorAll('.slide');
        if (slides.length > 0) {
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
        }
    });
</script>
@endsection