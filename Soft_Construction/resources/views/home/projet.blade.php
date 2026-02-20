@extends('home.layouts.app')
<link rel="stylesheet" href="{{ asset('home/assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('home/assets/css/projet.css') }}">
@section('title', 'Nos Projets | SOFCONSTRUCTION')
@section('description', 'Découvrez nos réalisations à travers l\'Afrique dans les domaines de la construction et de l\'immobilier')

@section('styles')
    
@endsection

@section('content')
    <!-- Projects Hero Section -->
    <section class="projects-hero">
        <div class="image-slideshow">
            @forelse($slides as $index => $slide)
                <div class="slide {{ $index === 0 ? 'active' : '' }}" 
                     style="background-image: url('{{ $slide->image1 ? Storage::url($slide->image1) : asset('home/assets/img/chantier.png') }}')">
                </div>
            @empty
                <!-- Fallback slides if no slides exist -->
                <div class="slide active" style="background-image: url('{{ asset('home/assets/img/chantier.png') }}')"></div>
                <div class="slide" style="background-image: url('{{ asset('home/assets/img/45.png') }}')"></div>
                <div class="slide" style="background-image: url('{{ asset('home/assets/img/46.png') }}')"></div>
                <div class="slide" style="background-image: url('{{ asset('home/assets/img/47.png') }}')"></div>
            @endforelse
            <div class="overlay"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Nos Réalisations</h1>
                <p class="hero-subtitle">Découvrez nos projets emblématiques à travers l'Afrique</p>
            </div>
        </div>
    </section>

    <!-- Projects Gallery Section -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Nos Projets Récents</h2>
                <p>Des réalisations qui témoignent de notre expertise</p>
            </div>
            
            <!-- Region Filter -->
            <div class="region-filter">
                <button class="filter-btn active" data-filter="all">Toutes les régions</button>
                @foreach($regions as $region)
                    <button class="filter-btn" data-filter="{{ Str::slug($region) }}">{{ $region }}</button>
                @endforeach
            </div>
            
            <div class="projects-grid">
                @forelse($featuredProjects as $project)
                <div class="project-card" data-category="{{ Str::slug($project->region) }}" data-type="{{ Str::slug($project->category) }}">
                    @if($project->image)
                        <img src="{{ Storage::url($project->image) }}" alt="{{ $project->title }}" class="project-img">
                    @else
                        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="{{ $project->title }}" class="project-img">
                    @endif
                    <div class="project-overlay">
                        <div class="project-info">
                            <h3 class="project-title">{{ $project->title }}</h3>
                            <p class="project-category">{{ $project->category }}</p>
                            <p class="project-location">📍 {{ $project->location }}, {{ $project->region }}</p>
                            <p class="project-date">{{ $project->created_at->format('Y') }}</p>
                            <a href="#" class="btn btn-outline">Voir les détails</a>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Fallback projects if no projects exist -->
                <div class="project-card" data-category="commercial" data-type="commercial">
                    <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Tour de bureaux à Lagos" class="project-img">
                    <div class="project-overlay">
                        <div class="project-info">
                            <h3 class="project-title">Tour de Bureaux à Lagos</h3>
                            <p class="project-category">Commercial</p>
                            <p class="project-date">2022</p>
                            <a href="#" class="btn btn-outline">Voir les détails</a>
                        </div>
                    </div>
                </div>
                
                <div class="project-card" data-category="residential" data-type="residential">
                    <img src="https://images.unsplash.com/photo-1600607688969-a5bfcd646154?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Résidences à Abidjan" class="project-img">
                    <div class="project-overlay">
                        <div class="project-info">
                            <h3 class="project-title">Résidences à Abidjan</h3>
                            <p class="project-category">Résidentiel</p>
                            <p class="project-date">2021</p>
                            <a href="#" class="btn btn-outline">Voir les détails</a>
                        </div>
                    </div>
                </div>
                
                <div class="project-card" data-category="industrial" data-type="industrial">
                    <img src="https://images.unsplash.com/photo-1581093057307-9caf8cc6f1c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2089&q=80" alt="Usine à Dakar" class="project-img">
                    <div class="project-overlay">
                        <div class="project-info">
                            <h3 class="project-title">Usine à Dakar</h3>
                            <p class="project-category">Industriel</p>
                            <p class="project-date">2023</p>
                            <a href="#" class="btn btn-outline">Voir les détails</a>
                        </div>
                    </div>
                </div>
                
                <div class="project-card" data-category="commercial" data-type="commercial">
                    <img src="https://images.unsplash.com/photo-1600566752229-250ed79470a6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Centre commercial à Nairobi" class="project-img">
                    <div class="project-overlay">
                        <div class="project-info">
                            <h3 class="project-title">Centre Commercial à Nairobi</h3>
                            <p class="project-category">Commercial</p>
                            <p class="project-date">2020</p>
                            <a href="#" class="btn btn-outline">Voir les détails</a>
                        </div>
                    </div>
                </div>
                
                <div class="project-card" data-category="residential" data-type="residential">
                    <img src="https://images.unsplash.com/photo-1600607688969-5c4221cbe430?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwa90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Appartements à Accra" class="project-img">
                    <div class="project-overlay">
                        <div class="project-info">
                            <h3 class="project-title">Appartements à Accra</h3>
                            <p class="project-category">Résidentiel</p>
                            <p class="project-date">2019</p>
                            <a href="#" class="btn btn-outline">Voir les détails</a>
                        </div>
                    </div>
                </div>
                
                <div class="project-card" data-category="industrial" data-type="industrial">
                    <img src="https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Entrepôt à Lomé" class="project-img">
                    <div class="project-overlay">
                        <div class="project-info">
                            <h3 class="project-title">Entrepôt à Lomé</h3>
                            <p class="project-category">Industriel</p>
                            <p class="project-date">2022</p>
                            <a href="#" class="btn btn-outline">Voir les détails</a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Projects by Region Section -->
   

    <!-- Statistics Section -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Nos Réalisations en Chiffres</h2>
                <p>Des résultats concrets qui parlent d'eux-mêmes</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $featuredProjects->count() }}+</div>
                    <div class="stat-label">Projets Réalisés</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">{{ $regions->count() }}</div>
                    <div class="stat-label">Régions d'Intervention</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">5+</div>
                    <div class="stat-label">Années d'Expérience</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">98%</div>
                    <div class="stat-label">Clients Satisfaits</div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Region filter functionality
        $('.filter-btn').click(function() {
            // Remove active class from all buttons
            $('.filter-btn').removeClass('active');
            // Add active class to clicked button
            $(this).addClass('active');
            
            const filter = $(this).data('filter');
            
            if (filter === 'all') {
                $('.project-card').show();
            } else {
                $('.project-card').hide();
                $(`.project-card[data-category="${filter}"]`).show();
            }
        });
        
        // Region tabs functionality
        $('.tab-btn').click(function() {
            // Remove active class from all tabs
            $('.tab-btn').removeClass('active');
            // Add active class to clicked tab
            $(this).addClass('active');
            
            // Hide all tab contents
            $('.tab-content').removeClass('active');
            
            // Show selected tab content
            const tab = $(this).data('tab');
            $(`#${tab}`).addClass('active');
        });
        
        // Smooth scrolling for anchor links
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