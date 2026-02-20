@extends('home.layouts.app')
<link rel="stylesheet" href="{{ asset('home/assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('home/assets/css/formation.css') }}">
@section('styles')
    
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="image-slideshow">
            @forelse($slides as $index => $slide)
                <div class="slide {{ $index === 0 ? 'active' : '' }}" 
                     style="background-image: url('{{ $slide->image1 ? Storage::url($slide->image1) : asset('home/assets/img/form.png') }}')">
                </div>
            @empty
                <!-- Fallback slides if no slides exist -->
                <div class="slide active" style="background-image: url('{{ asset('home/assets/img/form.png') }}')"></div>
            @endforelse
            <div class="overlay"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Formations Professionnelles Construction</h1>
                <p class="hero-subtitle">
                    Développez vos compétences avec nos programmes certifiants aux métiers de la construction. 
                    Outils modernes, WiFi gratuit, paiement par tranches.
                </p>
            </div>
        </div>
    </section>

    <!-- Special Offer Banner -->
    <div class="container">
        <div class="special-offer">
            <div class="offer-title">🎯 Offre Spéciale - Premiers Inscrits</div>
            <div class="offer-text">10% de réduction pour les 10 premiers inscrits de chaque formation + WiFi gratuit</div>
        </div>
    </div>

    <!-- Training Programs Section -->
    <section class="section" id="formations">
        <div class="container">
            <div class="section-title">
                <h2>Nos Programmes de Formation</h2>
                <p>Formations adaptées à votre profil et vos ambitions professionnelles</p>
            </div>
            
            <div class="training-grid">
                @forelse($trainings as $training)
                <article class="training-card {{ $loop->first ? 'popular' : '' }}">
                    @if($training->image)
                        <img src="{{ Storage::url($training->image) }}" 
                             alt="{{ $training->title }}" 
                             class="training-img"
                             loading="lazy">
                    @else
                        <img src="{{ asset('home/assets/img/form.png') }}" 
                             alt="{{ $training->title }}" 
                             class="training-img"
                             loading="lazy">
                    @endif
                    <div class="training-content">
                        <h3 class="training-title">{{ $training->title }}</h3>
                        
                        @if($training->duration)
                        <span class="training-duration">⏱️ {{ $training->duration }} • 💳 Paiement par tranches</span>
                        @endif
                        
                        <div class="training-features">
                            <div class="training-description">
                                {{ $training->description }}
                            </div>
                            
                            @if($training->requirements)
                            <h4>📋 Prérequis :</h4>
                            <p>{{ $training->requirements }}</p>
                            @endif
                            
                            @if($training->career_opportunities)
                            <h4>🎯 Débouchés professionnels :</h4>
                            <ul class="opportunities">
                                @foreach(explode("\n", $training->career_opportunities) as $opportunity)
                                    @if(trim($opportunity))
                                        <li>{{ trim($opportunity) }}</li>
                                    @endif
                                @endforeach
                            </ul>
                            @endif
                        </div>
                        
                        <div class="training-footer">
                            @if($training->price)
                                <div class="training-price">{{ number_format($training->price, 0, ',', ' ') }} FCFA</div>
                            @endif
                            
                            <a href="#" class="btn btn-accent" style="width: 100%; margin-top: 1rem;">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                </article>
                @empty
                <!-- Fallback trainings if no trainings exist -->
                <article class="training-card popular">
                    <img src="{{ asset('home/assets/img/form.png') }}" 
                         alt="Formation de base construction - menuiserie, électricité, soudure" 
                         class="training-img"
                         loading="lazy">
                    <div class="training-content">
                        <h3 class="training-title">Formation de Base</h3>
                        <span class="training-level">Probatoire/BEPC/Sans Diplôme</span>
                        
                        <div class="training-price">250,000 FCFA</div>
                        <span class="training-duration">⏱️ 1 an • 💳 Paiement par tranches</span>
                        
                        <div class="training-features">
                            <h4>🛠️ Compétences développées :</h4>
                            <div class="skills-grid">
                                <span class="skill-tag">Menuiserie</span>
                                <span class="skill-tag">Électricité</span>
                                <span class="skill-tag">Peinture</span>
                                <span class="skill-tag">Soudure</span>
                                <span class="skill-tag">Charpente</span>
                                <span class="skill-tag">Carrelage</span>
                                <span class="skill-tag">Maçonnerie</span>
                            </div>
                            
                            <h4>🎯 Débouchés professionnels :</h4>
                            <ul class="opportunities">
                                <li>Ouvrier spécialisé en bâtiment</li>
                                <li>Artisan indépendant</li>
                                <li>Chef d'équipe de chantier</li>
                                <li>Contremaître</li>
                            </ul>
                        </div>
                        
                        <a href="#contact" class="btn btn-accent" style="width: 100%; margin-top: 1rem;">
                            S'inscrire maintenant
                        </a>
                    </div>
                </article>
                
                <article class="training-card">
                    <img src="{{ asset('home/assets/img/form.png') }}" 
                         alt="Formation intermédiaire - AutoCAD, gestion de projets" 
                         class="training-img"
                         loading="lazy">
                    <div class="training-content">
                        <h3 class="training-title">Formation Intermédiaire</h3>
                        <span class="training-level">Niveau BAC</span>
                        
                        <div class="training-price">250,000 FCFA</div>
                        <span class="training-duration">⏱️ 1 an • 💳 Paiement par tranches</span>
                        
                        <div class="training-features">
                            <h4>💻 Compétences développées :</h4>
                            <div class="skills-grid">
                                <span class="skill-tag">Gestion projets</span>
                                <span class="skill-tag">AutoCAD 2D</span>
                                <span class="skill-tag">ArchiCAD 2D</span>
                                <span class="skill-tag">Maçonnerie</span>
                                <span class="skill-tag">Planning</span>
                            </div>
                            
                            <h4>🎯 Débouchés professionnels :</h4>
                            <ul class="opportunities">
                                <li>Technicien en bâtiment</li>
                                <li>Dessinateur industriel</li>
                                <li>Gestionnaire de projet</li>
                                <li>Superviseur de chantier</li>
                            </ul>
                        </div>
                        
                        <a href="#contact" class="btn btn-accent" style="width: 100%; margin-top: 1rem;">
                            S'inscrire maintenant
                        </a>
                    </div>
                </article>
                
                <article class="training-card">
                    <img src="{{ asset('home/assets/img/form.png') }}" 
                         alt="Formation avancée - Revit, BIM, Lumion" 
                         class="training-img"
                         loading="lazy">
                    <div class="training-content">
                        <h3 class="training-title">Formation Avancée</h3>
                        <span class="training-level">Niveau BTS ou plus</span>
                        
                        <div class="training-price">380,000 FCFA</div>
                        <span class="training-duration">⏱️ 3 mois • 💳 Paiement par tranches</span>
                        
                        <div class="training-features">
                            <h4>🚀 Compétences développées :</h4>
                            <div class="skills-grid">
                                <span class="skill-tag">AutoCAD 2D/3D</span>
                                <span class="skill-tag">ArchiCAD 2D/3D</span>
                                <span class="skill-tag">Robot S.A</span>
                                <span class="skill-tag">Revit</span>
                                <span class="skill-tag">Lumion</span>
                                <span class="skill-tag">BIM</span>
                            </div>
                            
                            <h4>🎯 Débouchés professionnels :</h4>
                            <ul class="opportunities">
                                <li>Ingénieur BIM</li>
                                <li>Chef de projet construction</li>
                                <li>Architecte technique</li>
                                <li>Consultant en construction</li>
                            </ul>
                        </div>
                        
                        <a href="#contact" class="btn btn-accent" style="width: 100%; margin-top: 1rem;">
                            S'inscrire maintenant
                        </a>
                    </div>
                </article>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Training Benefits Section -->
    <section class="section" style="background: var(--light);">
        <div class="container">
            <div class="section-title">
                <h2>Pourquoi Choisir SOFCONSTRUCTION</h2>
                <p>Des avantages exclusifs qui font la différence dans votre formation</p>
            </div>
            
            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon">🛠️</div>
                    <h3>Formation Pratique</h3>
                    <p>70% de formation pratique sur des projets réels avec des outils professionnels modernes</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">📶</div>
                    <h3>WiFi Gratuit</h3>
                    <p>Accès internet haut débit gratuit dans toutes nos salles de classe pour vos recherches</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">💼</div>
                    <h3>Insertion Professionnelle</h3>
                    <p>Partenariats avec des entreprises locales pour faciliter l'emploi de nos diplômés</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">🏆</div>
                    <h3>Certification Reconnue</h3>
                    <p>Diplômes et certificats reconnus par les autorités nationales et internationales</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">💳</div>
                    <h3>Paiement Flexible</h3>
                    <p>Possibilité de payer en plusieurs tranches selon vos moyens financiers</p>
                </div>
                
                <div class="benefit-card">
                    <div class="benefit-icon">🚀</div>
                    <h3>Outils Modernes</h3>
                    <p>Formation avec des logiciels et équipements à la pointe de la technologie</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section">
        <div class="container">
            <div class="cta-section">
                <h2>Prêt à Commencer Votre Formation ?</h2>
                <p>Rejoignez les centaines d'étudiants qui ont déjà transformé leur avenir avec SOFCONSTRUCTION</p>
                <a href="#contact" class="btn btn-accent" style="font-size: 1.1rem; padding: 1rem 2rem;">
                    🎯 Réserver Ma Place Maintenant
                </a>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Back to top functionality
        window.addEventListener('scroll', function() {
            const backToTop = document.getElementById('backToTop');
            if (backToTop) {
                if (window.pageYOffset > 300) {
                    backToTop.style.display = 'block';
                } else {
                    backToTop.style.display = 'none';
                }
            }
        });

        if (document.getElementById('backToTop')) {
            document.getElementById('backToTop').addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
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