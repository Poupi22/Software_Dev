@extends('acceuil.layouts.app')

@section('content')

<!-- Hero Section -->
<section class="about-hero">
    <div class="container text-center">
        <h1 class="hero-title">
            {{ $about->titre ?? "À Propos de l'Institut 3iA" }}
        </h1>
        <p class="hero-subtitle">
            {{ $about->description ?? "Bâtir l'avenir par le savoir, l'innovation et l'intégrité." }}
        </p>
        <div class="hero-scroll-indicator">
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
</section>

<!-- Mission & Values Section -->
<section class="mission-values-section py-5">
    <div class="container">
        <!-- Notre Mission -->
        <div class="text-center mb-5">
            <h2 class="section-title position-relative d-inline-block">
                Notre Mission
                <span class="title-decoration"></span>
            </h2>
            <div class="mission-card mx-auto">
                <p class="lead">
                    {{ $about->mission ?? "Offrir une formation de qualité accessible à tous, promouvoir la recherche et encourager l'innovation continue." }}
                </p>
            </div>
        </div>

        <!-- Nos Valeurs -->
        <div class="text-center mb-5">
            <h2 class="section-title position-relative d-inline-block">
                Nos Valeurs Fondamentales
                <span class="title-decoration"></span>
            </h2>
            <p class="section-subtitle mx-auto">Les principes qui guident nos actions au quotidien</p>
        </div>

        <div class="row g-4">
            @if(!empty($about->valeurs) && is_array($about->valeurs))
                @foreach($about->valeurs as $valeur)
                    <div class="col-lg-4 col-md-6">
                        <div class="value-card h-100">
                            <div class="value-icon">
                                <i class="{{ $valeur['icon'] ?? 'fas fa-star' }}"></i>
                            </div>
                            <h5 class="value-title">
                                {{ $valeur['titre'] ?? 'Titre de la valeur' }}
                            </h5>
                            <p class="value-text">
                                {{ $valeur['description'] ?? 'Description de la valeur.' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Fallback: Valeurs par défaut --}}
                @php
                    $valeurs_defaut = [
                        [
                            'icon' => 'fas fa-medal',
                            'titre' => 'Excellence',
                            'description' => 'Nous visons les plus hauts standards de qualité dans notre enseignement et notre recherche.'
                        ],
                        [
                            'icon' => 'fas fa-lightbulb',
                            'titre' => 'Innovation',
                            'description' => 'Nous encourageons la créativité pour repousser les limites du savoir.'
                        ],
                        [
                            'icon' => 'fas fa-users',
                            'titre' => 'Communauté',
                            'description' => 'Nous bâtissons un environnement inclusif et collaboratif.'
                        ],
                        [
                            'icon' => 'fas fa-graduation-cap',
                            'titre' => 'Pédagogie',
                            'description' => 'Méthodes d\'enseignement adaptées aux besoins des étudiants.'
                        ],
                        [
                            'icon' => 'fas fa-globe',
                            'titre' => 'Ouverture',
                            'description' => 'Nous cultivons une vision globale et multiculturelle.'
                        ],
                        [
                            'icon' => 'fas fa-hand-holding-heart',
                            'titre' => 'Intégrité',
                            'description' => 'Nous agissons avec éthique, transparence et responsabilité.'
                        ]
                    ];
                @endphp

                @foreach($valeurs_defaut as $valeur)
                    <div class="col-lg-4 col-md-6">
                        <div class="value-card h-100">
                            <div class="value-icon"><i class="{{ $valeur['icon'] }}"></i></div>
                            <h5 class="value-title">{{ $valeur['titre'] }}</h5>
                            <p class="value-text">{{ $valeur['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-us-section py-5 bg-light">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="image-container position-relative">
                    <img
                        src="{{ $about?->image ? asset('storage/' . $about->image) : asset('acceuil/assets/images/IMG-20250618-WA0010.jpg') }}"
                        class="img-fluid rounded-3 shadow-lg main-image"
                        alt="Image présentation"
                    >
                    <div class="stats-overlay">
                        <div class="stat-item">
                            <div class="stat-number" data-count="90">0</div>
                            <div class="stat-label">% d'insertion professionnelle</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <h2 class="section-title text-start position-relative">
                    Pourquoi Choisir 3iA ?
                    <span class="title-decoration"></span>
                </h2>
                <p class="lead mb-4">
                    {{ $about->vision ?? "Notre vision est d'être un leader dans l'éducation technologique, formant des talents innovants et responsables." }}
                </p>

                <ul class="why-us-list">
                    @foreach([
                        "Plus de <strong>90% de nos diplômés</strong> trouvent un emploi dans les six mois",
                        "Nos étudiants sont <strong>régulièrement primés</strong> dans des compétitions nationales",
                        "Un taux de satisfaction supérieur à <strong>95%</strong> parmi nos anciens Etudiant",
                        "<strong>Partenariats industriels</strong> avec les leaders du secteur",
                        "<strong>Installations modernes</strong> et équipements de pointe",
                        "<strong>Encadrement personnalisé</strong> par des experts du domaine"
                    ] as $item)
                    <li class="mb-3">
                        <i class="fas fa-check-circle text-primary me-2"></i>
                        <span>{!! $item !!}</span>
                    </li>
                    @endforeach
                </ul>

                <div class="cta-buttons mt-4">
                    <a href="{{ route('acceuil.contact') }}" class="btn btn-primary me-3">
                        <i class="fas fa-envelope me-2"></i> Nous contacter
                    </a>
                    <a href="{{ route('acceuil.formation') }}" class="btn btn-outline-primary">
                        <i class="fas fa-book-open me-2"></i> Nos formations
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Leadership Team Section - Removed Personnel Content -->
<section class="team-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title position-relative d-inline-block">
                Notre Équipe
                <span class="title-decoration"></span>
            </h2>
            <p class="section-subtitle mx-auto">Rencontrez les leaders qui guident notre institution</p>
        </div>

        <div class="text-center">
            <p class="text-muted">Information sur l'équipe non disponible pour le moment.</p>
        </div>
    </div>
</section>

<style>
    .team-section {
        background-color: #f8fafc;
    }

    .section-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: #1e293b;
        position: relative;
        padding-bottom: 1rem;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: #3b82f6;
        border-radius: 2px;
    }

    .section-subtitle {
        color: #64748b;
        max-width: 600px;
        font-size: 1.125rem;
    }

    @media (max-width: 768px) {
        .section-title {
            font-size: 1.75rem;
        }
    }
</style>

<style>
    /* Hero Section - Modified for pure blue */
    .about-hero {
         background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); /* Pure blue background */
        padding: 120px 0 100px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    /* Removed the ::before pseudo-element with the SVG pattern */

    .hero-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 2;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .hero-subtitle {
        font-size: 1.5rem;
        font-weight: 300;
        max-width: 800px;
        margin: 0 auto 2rem;
        position: relative;
        z-index: 2;
    }

    .hero-scroll-indicator {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 1.5rem;
        animation: bounce 2s infinite;
        cursor: pointer;
        z-index: 2;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0) translateX(-50%);}
        40% {transform: translateY(-20px) translateX(-50%);}
        60% {transform: translateY(-10px) translateX(-50%);}
    }

    /* Section Titles */
    .section-title {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1e3a8a;
    }

    .section-subtitle {
        font-size: 1.1rem;
        color: #64748b;
        max-width: 700px;
        margin-bottom: 2rem;
    }

    .title-decoration {
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 60px;
        height: 4px;
        background: #3b82f6;
        border-radius: 2px;
    }

    /* Mission Card */
    .mission-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(30, 58, 138, 0.1);
        max-width: 800px;
        border-left: 5px solid #3b82f6;
    }

    /* Value Cards */
    .value-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border: 1px solid rgba(59, 130, 246, 0.1);
    }

    .value-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(30, 58, 138, 0.15);
        border-color: rgba(59, 130, 246, 0.3);
    }

    .value-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 20px;
        background: rgba(59, 130, 246, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #1e3a8a;
        transition: all 0.3s ease;
    }

    .value-card:hover .value-icon {
        background: #3b82f6;
        color: white;
        transform: scale(1.1);
    }

    .value-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #1e3a8a;
    }

    .value-text {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.7;
    }

    /* Why Us Section */
    .why-us-section {
        position: relative;
        overflow: hidden;
    }

    .image-container {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
    }

    .main-image {
        transition: transform 0.5s ease;
    }

    .image-container:hover .main-image {
        transform: scale(1.03);
    }

    .stats-overlay {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: rgba(30, 58, 138, 0.9);
        color: white;
        padding: 15px;
        border-radius: 8px;
        animation: fadeIn 1s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .why-us-list {
        list-style: none;
        padding: 0;
    }

    .why-us-list li {
        margin-bottom: 12px;
        padding-left: 30px;
        position: relative;
        font-size: 1.05rem;
    }

    .why-us-list i {
        position: absolute;
        left: 0;
        top: 3px;
        font-size: 1.2rem;
    }

    /* Team Section */
    .team-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        text-align: center;
    }

    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(30, 58, 138, 0.15);
    }

    .team-img {
        height: 200px;
        overflow: hidden;
    }

    .team-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .team-card:hover .team-img img {
        transform: scale(1.1);
    }

    .team-info {
        padding: 20px;
    }

    .team-info h5 {
        font-weight: 600;
        margin-bottom: 5px;
        color: #1e3a8a;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.3rem;
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .section-title {
            font-size: 1.8rem;
        }

        .mission-card {
            padding: 20px;
        }
    }

    @media (max-width: 576px) {
        .about-hero {
            padding: 100px 0 80px;
        }

        .hero-title {
            font-size: 1.8rem;
        }

        .value-card {
            padding: 20px;
        }
    }
</style>

<script>
    // Counter animation for stats
    document.addEventListener('DOMContentLoaded', function() {
        const statNumber = document.querySelector('.stat-number');
        if (statNumber) {
            const target = parseInt(statNumber.getAttribute('data-count'));
            let count = 0;
            const duration = 2000; // ms
            const increment = target / (duration / 16);

            const updateCount = () => {
                count += increment;
                if (count < target) {
                    statNumber.textContent = Math.floor(count);
                    requestAnimationFrame(updateCount);
                } else {
                    statNumber.textContent = target;
                }
            };

            // Start animation when element is in viewport
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    updateCount();
                    observer.unobserve(statNumber);
                }
            });

            observer.observe(statNumber);
        }
    });
</script>

@endsection
