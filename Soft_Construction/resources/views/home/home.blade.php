@extends('home.layouts.app')
<link rel="stylesheet" href="{{ asset('home/assets/css/home.css') }}">
@section('content')

<!-- Service Details Modal -->
<div id="serviceModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <div class="modal-body">
            <div class="service-modal-header">
                <img id="modalServiceIcon" src="" alt="" class="modal-service-icon">
                <h2 id="modalServiceTitle"></h2>
            </div>
            <div class="service-modal-body">
                <div class="service-description-section">
                    <h3>Description du Service</h3>
                    <p id="modalServiceDescription"></p>
                </div>
                <div class="service-details-section">
                    <h3>Détails du Service</h3>
                    <p id="modalServiceDetails"></p>
                </div>
                <div class="service-actions">
                    <a href="{{ route('home.home.contact') }}" class="btn btn-primary">Demander un devis</a>
                    <button class="btn btn-outline close-modal-btn">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</div>

<section id="home" class="hero">
    <!-- Dynamic Background Image Slider -->
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
            <h1 class="hero-title">Construire le Cameroun de Demain</h1>
            <p class="hero-subtitle">Services de construction premium et programmes de formation professionnelle dans les régions de l'Ouest, du Littoral et du Centre</p>
            <div class="hero-cta">
                <a href="{{ url('/services') }}" class="btn btn-primary">Nos Services</a>
                <a href="{{ url('/contact') }}" class="btn btn-outline">Contactez-nous</a>
            </div>
        </div>
    </div>
</section>

<!-- Single About Section -->
<section id="about" class="section about">
    <div class="container">
        <div class="section-title">
            <h2>{{ $about->title ?? 'À propos de SOFTCONSTRUCTION' }}</h2>
            <p>{{ $about->subtitle ?? 'Une entreprise camerounaise qui façonne l\'avenir du pays à travers la construction et la formation' }}</p>
        </div>
        
        <div class="about-container">
            <div class="about-text">
                <p>{{ $about->description1 ?? 'Fondée en 2023 à Dschang, SOFTCONSTRUCTION s\'est imposée comme un leader dans le secteur de la construction dans les régions de l\'Ouest, du Littoral et du Centre, combinant savoir-faire local et technologies modernes pour réaliser des projets d\'exception.' }}</p>
                <p>{{ $about->description2 ?? 'Notre mission est de transformer le paysage urbain camerounais tout en développant les compétences professionnelles locales à travers nos programmes de formation.' }}</p>
                
                <div class="about-features">
                    @if(isset($about->features) && is_array($about->features) && count($about->features) > 0)
                        @foreach($about->features as $feature)
                        <div class="about-feature">
                            <div class="about-feature-icon">
                                <i class="{{ $feature['icon'] ?? 'fas fa-check' }}"></i>
                            </div>
                            <div>
                                <h4>{{ $feature['title'] ?? 'Excellence Certifiée' }}</h4>
                                <p>{{ $feature['description'] ?? 'Normes de qualité ISO 9001' }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <!-- Default features if no features in database -->
                        <div class="about-feature">
                            <div class="about-feature-icon">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div>
                                <h4>Excellence Certifiée</h4>
                                <p>Leader dans le domaine du batiment</p>
                            </div>
                        </div>
                        
                        <div class="about-feature">
                            <div class="about-feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h4>Équipe Qualifiée</h4>
                                <p>20+ professionnels camerounais</p>
                            </div>
                        </div>
                        
                        <div class="about-feature">
                            <div class="about-feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h4>Sécurité</h4>
                                <p>Classement sécurité 5 étoiles</p>
                            </div>
                        </div>
                        
                        <div class="about-feature">
                            <div class="about-feature-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div>
                                <h4>Centre de Formation</h4>
                                <p>+30 étudiants inscrits cette année</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="about-image">
    
    @if($about && $about->image)
    <img src="{{ Storage::url($about->image) }}" alt="Equipe de construction soft">
    @else
    <img src="{{ asset('home/assets/img/5.png') }}" alt="Équipe de construction camerounaise" style="border: 2px solid blue;">
    @endif
    <div class="about-badge"> 
        <h3>{{ $about->experience_years ?? '3+' }}+</h3>
        <p>{{ $about->experience_text ?? 'Ans d\'expérience' }}</p>
    </div>
</div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="section services">
    <div class="container">
        <div class="section-title">
            <h2>Nos Services</h2>
            <p>Des solutions complètes pour tous vos projets de construction et de rénovation</p>
        </div>
        
        <div class="services-grid">
            @forelse($services as $service)
            <div class="service-card">
                @if($service->icon)
                <img src="{{ Storage::url($service->icon) }}" alt="{{ $service->title }}" class="service-img">
                @else
                <img src="{{ asset('home/assets/img/6.png') }}" alt="{{ $service->title }}" class="service-img">
                @endif
                <div class="service-content">
                    <h3 class="service-title">{{ $service->title }}</h3>
                    <p class="service-description">{{ $service->short_description }}</p>
                    <button class="btn btn-primary learn-more-btn" 
                            data-service-title="{{ $service->title }}"
                            data-service-description="{{ $service->short_description }}"
                            data-service-details="{{ $service->long_description ?? 'Détails complets du service disponibles sur demande. Contactez-nous pour plus d\'informations.' }}"
                            data-service-icon="{{ $service->icon ? Storage::url($service->icon) : asset('home/assets/img/6.png') }}">
                        En savoir plus
                    </button>
                </div>
            </div>
            @empty
            <!-- Default services -->
            <div class="service-card">
                <img src="{{ asset('home/assets/img/6.png') }}" alt="Construction générale" class="service-img">
                <div class="service-content">
                    <h3 class="service-title">Construction Générale</h3>
                    <p class="service-description">Solutions complètes de construction pour projets résidentiels et commerciaux.</p>
                    <button class="btn btn-primary learn-more-btn" 
                            data-service-title="Construction Générale"
                            data-service-description="Solutions complètes de construction pour projets résidentiels et commerciaux."
                            data-service-details="Nous offrons des services complets de construction incluant la planification, l'exécution et la supervision de projets résidentiels et commerciaux. Notre équipe expérimentée garantit des résultats de qualité supérieure."
                            data-service-icon="{{ asset('home/assets/img/6.png') }}">
                        En savoir plus
                    </button>
                </div>
            </div>
            
            <div class="service-card">
                <img src="{{ asset('home/assets/img/27.png') }}" alt="Carrelage et finition" class="service-img">
                <div class="service-content">
                    <h3 class="service-title">Carrelage & Finition</h3>
                    <p class="service-description">Services experts en carrelage et finitions intérieures.</p>
                    <button class="btn btn-primary learn-more-btn" 
                            data-service-title="Carrelage & Finition"
                            data-service-description="Services experts en carrelage et finitions intérieures."
                            data-service-details="Expertise en pose de carreaux pour sols et murs, finitions intérieures et extérieures de qualité. Nous utilisons des matériaux de première qualité adaptés au climat camerounais."
                            data-service-icon="{{ asset('home/assets/img/27.png') }}">
                        En savoir plus
                    </button>
                </div>
            </div>
            
            <div class="service-card">
                <img src="{{ asset('home/assets/img/4.png') }}" alt="Plomberie et sanitaires" class="service-img">
                <div class="service-content">
                    <h3 class="service-title">Plomberie & Sanitaires</h3>
                    <p class="service-description">Installations et réparations de systèmes de plomberie.</p>
                    <button class="btn btn-primary learn-more-btn" 
                            data-service-title="Plomberie & Sanitaires"
                            data-service-description="Installations et réparations de systèmes de plomberie."
                            data-service-details="Services complets de plomberie incluant l'installation de canalisations, robinets, WC, douches et systèmes d'eau chaude. Interventions rapides pour les réparations d'urgence."
                            data-service-icon="{{ asset('home/assets/img/4.png') }}">
                        En savoir plus
                    </button>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number" data-count="120">+30</div>
                <div class="stat-label">Projets Réalisés</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="3">3</div>
                <div class="stat-label">Régions d'Intervention</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="120">+30</div>
                <div class="stat-label">Professionnels</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="1500">+50</div>
                <div class="stat-label">Personnes Formées</div>
            </div>
        </div>
    </div>
</section>

<!-- Training Section -->
<section id="training" class="section training">
    <div class="container">
        <div class="section-title">
            <h2>Nos Programmes de Formation</h2>
            <p>Développez vos compétences avec nos formations professionnelles certifiantes</p>
        </div>
        
        <div class="training-grid">
            @forelse($trainings as $training)
            <div class="training-card">
                @if($training->image)
                <img src="{{ Storage::url($training->image) }}" alt="{{ $training->title }}" class="training-img">
                @else
                <img src="{{ asset('home/assets/img/25.png') }}" alt="{{ $training->title }}" class="training-img">
                @endif
                <div class="training-content">
                    <h3 class="training-title">{{ $training->title }}</h3>
                    <span class="training-duration">{{ $training->duration }}</span>
                    <p class="training-description">{{ $training->description }}</p>
                    <a href="{{ route('home.home.contact') }}" class="btn btn-accent" style="width: 100%;">S'inscrire</a>
                </div>
            </div>
            @empty
            <!-- Default trainings -->
            <div class="training-card">
                <img src="{{ asset('home/assets/img/25.png') }}" alt="Formation en construction" class="training-img">
                <div class="training-content">
                    <h3 class="training-title">Techniques de Construction</h3>
                    <span class="training-duration">1 an</span>
                    <p class="training-description">Formation complète aux techniques modernes de construction.</p>
                    <a href="#contact" class="btn btn-accent" style="width: 100%;">S'inscrire</a>
                </div>
            </div>
            
            <div class="training-card">
                <img src="{{ asset('home/assets/img/4.png') }}" alt="Formation en plomberie" class="training-img">
                <div class="training-content">
                    <h3 class="training-title">Plomberie Professionnelle</h3>
                    <span class="training-duration">1 an</span>
                    <p class="training-description">Acquisition des compétences essentielles en plomberie.</p>
                    <a href="#contact" class="btn btn-accent" style="width: 100%;">S'inscrire</a>
                </div>
            </div>
            
            <div class="training-card">
                <img src="{{ asset('home/assets/img/elec.png') }}" alt="Formation en électricité" class="training-img">
                <div class="training-content">
                    <h3 class="training-title">Électricité du Bâtiment</h3>
                    <span class="training-duration">1 an</span>
                    <p class="training-description">Formation pratique aux installations électriques.</p>
                    <a href="#contact" class="btn btn-accent" style="width: 100%;">S'inscrire</a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Home Services Section -->
<section id="home-services" class="section home-services">
    <div class="container">
        <div class="home-services-container">
            <div class="home-services-image">
                @if($homeServices->count() > 0 && $homeServices[0]->image)
                <img src="{{ Storage::url($homeServices[0]->image) }}" alt="Services à domicile">
                @else
                <img src="{{ asset('home/assets/img/25.png') }}" alt="Services à domicile">
                @endif
            </div>
            
            <div class="home-services-text">
                <h2 class="section-title">Services à Domicile</h2>
                <p>
                    @if($homeServices->count() > 0)
                        {{ $homeServices[0]->description }}
                    @else
                        SOFTCONSTRUCTION propose des services pratiques à votre porte dans les villes de Dschang, Douala et Yaoundé. Nos équipes mobiles interviennent rapidement pour tous vos besoins en construction et rénovation.
                    @endif
                </p>
                
                <div class="home-services-features">
                    <div class="home-service-feature">
                        <div class="home-service-icon">
                            <i class="{{ $homeServices->count() > 0 ? $homeServices[0]->feature_icon_1 : 'fas fa-home' }}"></i>
                        </div>
                        <div>
                            <h4>{{ $homeServices->count() > 0 ? $homeServices[0]->feature_title_1 : 'Évaluation à Domicile' }}</h4>
                            <p>{{ $homeServices->count() > 0 ? $homeServices[0]->feature_description_1 : 'Nos experts se déplacent pour évaluer vos besoins et établir un devis précis.' }}</p>
                        </div>
                    </div>
                    
                    <div class="home-service-feature">
                        <div class="home-service-icon">
                            <i class="{{ $homeServices->count() > 0 ? $homeServices[0]->feature_icon_2 : 'fas fa-tools' }}"></i>
                        </div>
                        <div>
                            <h4>{{ $homeServices->count() > 0 ? $homeServices[0]->feature_title_2 : 'Réparations d\'Urgence' }}</h4>
                            <p>{{ $homeServices->count() > 0 ? $homeServices[0]->feature_description_2 : 'Intervention rapide 24/7 pour les problèmes structurels urgents.' }}</p>
                        </div>
                    </div>
                    
                    <div class="home-service-feature">
                        <div class="home-service-icon">
                            <i class="{{ $homeServices->count() > 0 ? $homeServices[0]->feature_icon_3 : 'fas fa-ruler-combined' }}"></i>
                        </div>
                        <div>
                            <h4>{{ $homeServices->count() > 0 ? $homeServices[0]->feature_title_3 : 'Devis Gratuits' }}</h4>
                            <p>{{ $homeServices->count() > 0 ? $homeServices[0]->feature_description_3 : 'Estimations précises sans engagement à votre domicile ou lieu de travail.' }}</p>
                        </div>
                    </div>
                </div>
                
                <a href="{{ url('/contact') }}" class="btn btn-primary" style="margin-top: 2rem;">Demander un service</a>
            </div>
        </div>
    </div>
</section>

<!-- Projects Section -->
<section id="projects" class="section projects">
    <div class="container">
        <div class="section-title">
            <h2>Nos Projets au Cameroun</h2>
            <p>Découvrez quelques-uns de nos projets emblématiques dans les régions de l'Ouest, du Littoral et du Centre</p>
        </div>
        
        <div class="projects-grid">
            @forelse($projects as $project)
            <div class="project-card">
                <img src="{{ Storage::url($project->image) }}" alt="{{ $project->title }}" class="project-img">
                <div class="project-overlay">
                    <h3 class="project-title">{{ $project->title }}</h3>
                    <p class="project-category">{{ $project->region }}</p>
                </div>
            </div>
            @empty
            <!-- Default projects -->
            <div class="project-card">
                <img src="{{ asset('home/assets/img/19.png') }}" alt="Résidences universitaires à Dschang" class="project-img">
                <div class="project-overlay">
                    <h3 class="project-title">Résidences Universitaires à Dschang</h3>
                    <p class="project-category">Éducation</p>
                </div>
            </div>
            
            <div class="project-card">
                <img src="{{ asset('home/assets/img/12.png') }}" alt="Complexe commercial à Douala" class="project-img">
                <div class="project-overlay">
                    <h3 class="project-title">Complexe Commercial à Douala</h3>
                    <p class="project-category">Commercial</p>
                </div>
            </div>
            
            <div class="project-card">
                <img src="{{ asset('home/assets/img/13.png') }}" alt="Résidences haut standing à Yaoundé" class="project-img">
                <div class="project-overlay">
                    <h3 class="project-title">Résidences Haut Standing à Yaoundé</h3>
                    <p class="project-category">Résidentiel</p>
                </div>
            </div>
            @endforelse
        </div>
        
        <div class="text-center" style="margin-top: 3rem;">
            <a href="{{ url('/projects') }}" class="btn btn-primary">Voir tous nos projets</a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="section testimonials">
    <div class="container">
        <div class="section-title">
            <h2>Témoignages</h2>
            <p>Ce que nos clients et partenaires disent de nous</p>
        </div>
        
        <div class="testimonials-slider">
            @forelse($testimonials as $testimonial)
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="testimonial-rating">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $testimonial->rating)
                            <i class="fas fa-star"></i>
                            @elseif($i - 0.5 <= $testimonial->rating)
                            <i class="fas fa-star-half-alt"></i>
                            @else
                            <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="testimonial-text">"{{ $testimonial->content }}"</p>
                    <div class="testimonial-author">
                        @if($testimonial->avatar)
                        <img src="{{ Storage::url($testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="testimonial-avatar">
                        @else
                        <img src="{{ asset('home/assets/img/tem.png') }}" alt="{{ $testimonial->name }}" class="testimonial-avatar">
                        @endif
                        <div>
                            <h4>{{ $testimonial->name }}</h4>
                            <p>{{ $testimonial->position }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <!-- Default testimonials -->
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">"SOFTCONSTRUCTION a transformé notre vision en réalité. Leur professionnalisme et leur attention aux détails sont exceptionnels."</p>
                    <div class="testimonial-author">
                        <img src="{{ asset('home/assets/img/tem.png') }}" alt="Jean Fotso" class="testimonial-avatar">
                        <div>
                            <h4>Jean Fotso</h4>
                            <p>Directeur, Groupe FOTSO</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <p class="testimonial-text">"La formation en plomberie a changé ma vie. Après 6 mois, j'ai pu créer ma propre entreprise et employer 3 personnes."</p>
                    <div class="testimonial-author">
                        <img src="{{ asset('home/assets/img/tem2.png') }}" alt="Amina Njoya" class="testimonial-avatar">
                        <div>
                            <h4>Amina Njoya</h4>
                            <p>Ancienne stagiaire</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Partners Section -->
<section class="section partners">
    <div class="container">
        <div class="section-title">
            <h2>Nos Partenaires</h2>
            <p>Nous collaborons avec les meilleures organisations au Cameroun et en Afrique</p>
        </div>

        <div class="partners-grid">
            @forelse($partners as $partner)
            <div class="partner-item">
                @if($partner->logo)
                <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}" class="partner-logo">
                @else
                <img src="{{ asset('home/assets/img/clinic.jpg') }}" alt="{{ $partner->name }}" class="partner-logo">
                @endif
                <div class="partner-info">
                    <div class="partner-name">{{ $partner->name }}</div>
                    <div class="partner-desc">{{ $partner->description }}</div>
                </div>
            </div>
            @empty
            <!-- Default partners -->
            <div class="partner-item">
                <img src="{{ asset('home/assets/img/clinic.jpg') }}" alt="Clinic computer" class="partner-logo">
                <div class="partner-info">
                    <div class="partner-name">Clinic computer</div>
                    <div class="partner-desc">Vente d'appareille electronique</div>
                </div>
            </div>

            <div class="partner-item">
                <img src="{{ asset('home/assets/img/mtn.png') }}" alt="MTN" class="partner-logo">
                <div class="partner-info">
                    <div class="partner-name">MTN</div>
                    <div class="partner-desc">Leader des télécommunications en Afrique</div>
                </div>
            </div>

            <div class="partner-item">
                <img src="{{ asset('home/assets/img/ecobank.png') }}" alt="Ecobank" class="partner-logo">
                <div class="partner-info">
                    <div class="partner-name">Ecobank</div>
                    <div class="partner-desc">Banque panafricaine de référence</div>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Contact Section -->

<!-- Map Section -->
<section class="map">
    @if($contact && $contact->location)
        {{-- Check if location contains an iframe (old format) --}}
        @if(str_contains($contact->location, '<iframe'))
            {!! $contact->location !!}
        {{-- Check if location contains google maps embed URL --}}
        @elseif(str_contains($contact->location, 'google.com/maps/embed'))
            <iframe src="{{ $contact->location }}" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        {{-- Otherwise, treat it as an address and generate map --}}
        @else
            <iframe src="https://maps.google.com/maps?q={{ urlencode($contact->location) }}&output=embed" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        @endif
    @else
        {{-- Default map if no location is set --}}
        <iframe src="" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    @endif
</section>

<!-- Newsletter Section -->
<section class="section newsletter">
    <div class="container">
        <div class="newsletter-container">
            <div class="newsletter-text">
                <h3>Abonnez-vous à notre newsletter</h3>
                <p>Recevez les dernières actualités, offres spéciales et conseils directement dans votre boîte email.</p>
            </div>
            
            <form class="newsletter-form">
                <input type="email" placeholder="Votre adresse email" required>
                <button type="submit" class="btn btn-accent">S'abonner</button>
            </form>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const modal = document.getElementById('serviceModal');
    const closeModal = document.querySelector('.close-modal');
    const closeModalBtn = document.querySelector('.close-modal-btn');
    const learnMoreBtns = document.querySelectorAll('.learn-more-btn');

    // Function to open modal with service details
    function openServiceModal(serviceData) {
        document.getElementById('modalServiceTitle').textContent = serviceData.title;
        document.getElementById('modalServiceDescription').textContent = serviceData.description;
        document.getElementById('modalServiceDetails').textContent = serviceData.details;
        document.getElementById('modalServiceIcon').src = serviceData.icon;
        document.getElementById('modalServiceIcon').alt = serviceData.title;
        
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    // Function to close modal
    function closeServiceModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Add click event to all "En savoir plus" buttons
    learnMoreBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const serviceData = {
                title: this.getAttribute('data-service-title'),
                description: this.getAttribute('data-service-description'),
                details: this.getAttribute('data-service-details'),
                icon: this.getAttribute('data-service-icon')
            };
            openServiceModal(serviceData);
        });
    });

    // Close modal when clicking on X
    closeModal.addEventListener('click', closeServiceModal);
    
    // Close modal when clicking on close button
    closeModalBtn.addEventListener('click', closeServiceModal);

    // Close modal when clicking outside the modal content
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeServiceModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeServiceModal();
        }
    });
});
</script>

<style>
/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
}

.modal-content {
    background-color: #fff;
    margin: 2% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.close-modal {
    position: absolute;
    right: 20px;
    top: 15px;
    font-size: 28px;
    font-weight: bold;
    color: #666;
    cursor: pointer;
    z-index: 10;
    transition: color 0.3s ease;
}

.close-modal:hover {
    color: #333;
}

.modal-body {
    padding: 30px;
}

.service-modal-header {
    text-align: center;
    margin-bottom: 30px;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 20px;
}

.modal-service-icon {
    width: 80px;
    height: 80px;
    object-fit: contain;
    margin-bottom: 15px;
}

.service-modal-header h2 {
    color: #2c3e50;
    font-size: 1.8rem;
    margin: 0;
    font-weight: 600;
}

.service-modal-body h3 {
    color: #34495e;
    margin-bottom: 15px;
    font-size: 1.3rem;
    font-weight: 600;
}

.service-description-section,
.service-details-section {
    margin-bottom: 25px;
}

.service-description-section p,
.service-details-section p {
    color: #555;
    line-height: 1.6;
    font-size: 1rem;
}

.service-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
    flex-wrap: wrap;
}

.service-actions .btn {
    min-width: 150px;
    padding: 12px 24px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        margin: 5% auto;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .service-modal-header h2 {
        font-size: 1.5rem;
    }
    
    .service-actions {
        flex-direction: column;
    }
    
    .service-actions .btn {
        width: 100%;
    }
}

/* Update service card button styles */
.learn-more-btn {
    cursor: pointer;
    transition: all 0.3s ease;
}

.learn-more-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}
</style>
@endpush