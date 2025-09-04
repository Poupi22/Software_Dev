@extends('acceuil.layouts.app')

@section('content')

<!-- ======================== HERO SECTION ========================= -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="hero-title">Nos Formations</h1>
            <p class="hero-subtitle">Découvrez notre catalogue de formations professionnelles pour développer vos compétences dans les technologies les plus demandées</p>
            <a href="#formations" class="btn btn-light btn-lg mt-3">
                <i class="fas fa-arrow-down me-2"></i> Explorer nos formations
            </a>
        </div>
    </div>
</section>

<!-- =================== FORMATIONS SECTION ================== -->
<section id="formations" class="formations-section py-5">
    <div class="container">

        <div class="search-container mb-5">
            <div class="position-relative">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Rechercher une formation..." id="searchInput">
            </div>
        </div>

        <div class="filter-bar mb-5">
            <button class="filter-btn active" data-filter="all">
                <i class="fas fa-layer-group me-2"></i> Toutes
            </button>
            @foreach($qualifications as $qualification)
            <button class="filter-btn" data-filter="{{ strtolower($qualification->code) }}">
                <i class="fas fa-certificate me-2"></i> {{ $qualification->nom }}
            </button>
            @endforeach
        </div>

        <div class="row g-4" id="formationsGrid">
            @forelse($programmes as $programme)
            <div class="col-lg-4 col-md-6 formation-item" data-category="{{ strtolower($programme->qualification->code) }}">
                <div class="formation-card">
                    <div class="formation-img-container">
                        <img src="{{ $programme->formation->image ? asset('storage/' . $programme->formation->image) : 'https://via.placeholder.com/400x250' }}" class="formation-img" alt="{{ $programme->formation->nom }}">
                        <div class="formation-category-badge">{{ $programme->qualification->code }}</div>
                    </div>
                    <div class="formation-content">
                        <h3 class="formation-title">{{ $programme->formation->nom }}</h3>
                        <p class="formation-excerpt">{{ Str::limit($programme->formation->description, 100) }}</p>
                        <div class="formation-meta">
                            <div class="formation-duration">
                                <i class="fas fa-clock"></i>
                                <span>{{ $programme->duree }}</span>
                            </div>
                            <div class="formation-level">{{ $programme->qualification->nom }}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="formation-price">{{ number_format($programme->prix, 0, ',', ' ') }} FCFA</span>
                            <button type="button" class="formation-btn" data-bs-toggle="modal" 
                                    data-bs-target="#formationModal{{ $programme->id }}">
                                Détails <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formation Details Modal -->
            <div class="modal fade formation-details-modal" id="formationModal{{ $programme->id }}" tabindex="-1" 
                 aria-labelledby="formationModalLabel{{ $programme->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="formationModalLabel{{ $programme->id }}">
                                {{ $programme->formation->nom }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="formation-modal-image mb-4">
                                        <img src="{{ $programme->formation->image ? asset('storage/' . $programme->formation->image) : 'https://via.placeholder.com/400x250' }}" 
                                             alt="{{ $programme->formation->nom }}" class="img-fluid rounded">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="formation-basic-info">
                                        <div class="info-item mb-3">
                                            <i class="fas fa-graduation-cap text-primary me-2"></i>
                                            <strong>Qualification:</strong> {{ $programme->qualification->nom }}
                                        </div>
                                        <div class="info-item mb-3">
                                            <i class="fas fa-clock text-primary me-2"></i>
                                            <strong>Durée:</strong> {{ $programme->duree }}
                                        </div>
                                        <div class="info-item mb-3">
                                            <i class="fas fa-money-bill-wave text-primary me-2"></i>
                                            <strong>Prix:</strong> {{ number_format($programme->prix, 0, ',', ' ') }} FCFA
                                        </div>
                                        <div class="info-item mb-3">
                                            <i class="fas fa-certificate text-primary me-2"></i>
                                            <strong>Qualification:</strong> {{ $programme->qualification->code }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="formation-description mt-4">
                                <h6 class="section-title">Description du Programme</h6>
                                <p class="text-muted">{{ $programme->formation->description }}</p>
                            </div>

                            <div class="formation-features mt-4">
                                <h6 class="section-title">Objectifs de la Formation</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="features-list">
                                            <li><i class="fas fa-check text-success me-2"></i> Maîtriser les compétences fondamentales</li>
                                            <li><i class="fas fa-check text-success me-2"></i> Développer une expertise pratique</li>
                                            <li><i class="fas fa-check text-success me-2"></i> Préparation aux certifications</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="features-list">
                                            <li><i class="fas fa-check text-success me-2"></i> Projets concrets et études de cas</li>
                                            <li><i class="fas fa-check text-success me-2"></i> Accompagnement personnalisé</li>
                                            <li><i class="fas fa-check text-success me-2"></i> Accès à la plateforme en ligne</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                          

                            <div class="formation-benefits mt-4">
                                <h6 class="section-title">Avantages</h6>
                                <div class="row">
                                    <div class="col-md-4 text-center mb-3">
                                        <div class="benefit-item">
                                            <i class="fas fa-chalkboard-teacher fa-2x text-primary mb-2"></i>
                                            <h6>Formateurs Experts</h6>
                                            <small class="text-muted">Professionnels expérimentés</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center mb-3">
                                        <div class="benefit-item">
                                            <i class="fas fa-laptop-code fa-2x text-primary mb-2"></i>
                                            <h6>Pratique Intensive</h6>
                                            <small class="text-muted">70% de pratique</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center mb-3">
                                        <div class="benefit-item">
                                            <i class="fas fa-briefcase fa-2x text-primary mb-2"></i>
                                            <h6>Insertion Professionnelle</h6>
                                            <small class="text-muted">Accompagnement carrière</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                  
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Aucune formation n'est ouverte aux inscriptions pour le moment. Revenez bientôt!
                </div>
            </div>
            @endforelse
        </div>

        <div class="text-center mt-5 d-none" id="noResults">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">Aucune formation trouvée</h4>
            <p class="text-muted">Essayez de modifier vos critères de recherche</p>
        </div>
    </div>
</section>

<style>
    :root {
        --primary-blue: #2563eb;
        --secondary-blue: #1e40af;
        --light-blue: #dbeafe;
        --dark-grey: #374151;
        --medium-grey: #6b7280;
        --light-grey: #f3f4f6;
        --white: #ffffff;
        --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        --aqp-color: #10b981;
        --cqp-color: #3b82f6;
        --dqp-color: #8b5cf6;
        --general-color: #f59e0b;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: var(--white);
        padding: 100px 0;
        position: relative;
        overflow: hidden;
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Search Bar */
    .search-container {
        max-width: 600px;
        margin: 0 auto 3rem;
    }

    .search-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e5e7eb;
        border-radius: 50px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--medium-grey);
    }

    /* Filter Bar */
    .filter-bar {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 3rem;
    }

    .filter-btn {
        background-color: var(--white);
        color: var(--medium-grey);
        border: 2px solid #e5e7eb;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .filter-btn:hover {
        border-color: var(--primary-blue);
        color: var(--primary-blue);
    }

    .filter-btn.active {
        background-color: var(--primary-blue);
        color: var(--white);
        border-color: var(--primary-blue);
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }

    /* Formation Cards */
    .formation-card {
        background: var(--white);
        border-radius: 16px;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
        border: none;
        position: relative;
    }

    .formation-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-hover);
    }

    .formation-img-container {
        height: 200px;
        overflow: hidden;
        position: relative;
    }

    .formation-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .formation-card:hover .formation-img {
        transform: scale(1.05);
    }

    .formation-category-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background-color: var(--primary-blue);
        color: var(--white);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 2;
    }

    /* Couleurs spécifiques pour chaque catégorie */
    .formation-item[data-category="aqp"] .formation-category-badge {
        background-color: var(--aqp-color);
    }

    .formation-item[data-category="cqp"] .formation-category-badge {
        background-color: var(--cqp-color);
    }

    .formation-item[data-category="dqp"] .formation-category-badge {
        background-color: var(--dqp-color);
    }

    .formation-item[data-category="general"] .formation-category-badge {
        background-color: var(--general-color);
    }

    .formation-content {
        padding: 1.5rem;
    }

    .formation-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark-grey);
        margin-bottom: 0.75rem;
    }

    .formation-excerpt {
        color: var(--medium-grey);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .formation-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 0.875rem;
        color: var(--medium-grey);
    }

    .formation-duration {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .formation-level {
        background-color: var(--light-blue);
        color: var(--primary-blue);
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-weight: 500;
    }

    .formation-price {
        font-weight: 600;
        color: var(--dark-grey);
        font-size: 1.1rem;
    }

    .formation-btn {
        background-color: var(--primary-blue);
        color: var(--white);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .formation-btn:hover {
        background-color: var(--secondary-blue);
        color: var(--white);
        transform: translateY(-1px);
    }

    /* Formation Details Modal */
    .formation-details-modal .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .formation-details-modal .modal-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        color: white;
        border-bottom: none;
        padding: 1.5rem 2rem;
        border-radius: 16px 16px 0 0;
    }

    .formation-details-modal .modal-title {
        font-weight: 600;
        font-size: 1.5rem;
    }

    .formation-details-modal .modal-body {
        padding: 2rem;
        max-height: 70vh;
        overflow-y: auto;
    }

    .formation-modal-image {
        border-radius: 12px;
        overflow: hidden;
    }

    .formation-modal-image img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .info-item {
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid var(--primary-blue);
    }

    .section-title {
        color: var(--dark-grey);
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--light-blue);
    }

    .features-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .features-list li {
        padding: 0.5rem 0;
        color: var(--medium-grey);
    }

    .curriculum-list {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
    }

    .curriculum-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
    }

    .curriculum-item:last-child {
        border-bottom: none;
    }

    .benefit-item {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }

    .benefit-item:hover {
        transform: translateY(-2px);
    }

    .formation-details-modal .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 1.5rem 2rem;
        border-radius: 0 0 16px 16px;
    }

    /* Animations */
    .formation-item {
        opacity: 1;
        transform: translateY(0);
        transition: all 0.3s ease;
    }

    .formation-item.hidden {
        opacity: 0;
        transform: translateY(20px);
        pointer-events: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .filter-bar {
            justify-content: center;
        }

        .formation-details-modal .modal-body {
            padding: 1.5rem;
            max-height: 60vh;
        }

        .formation-details-modal .modal-header,
        .formation-details-modal .modal-footer {
            padding: 1rem 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .hero-title {
            font-size: 2rem;
        }

        .formation-img-container {
            height: 180px;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .formation-details-modal .modal-dialog {
            margin: 0.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const formationItems = document.querySelectorAll('.formation-item');
        const searchInput = document.getElementById('searchInput');
        const noResults = document.getElementById('noResults');
        let currentFilter = 'all';

        // Filter functionality
        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                currentFilter = this.getAttribute('data-filter');

                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Filter formations
                filterFormations();
            });
        });

        // Search functionality
        searchInput.addEventListener('input', function () {
            filterFormations();
        });

        function filterFormations() {
            const searchTerm = searchInput.value.toLowerCase();
            let visibleCount = 0;

            formationItems.forEach(item => {
                const category = item.getAttribute('data-category');
                const title = item.querySelector('.formation-title').textContent.toLowerCase();
                const excerpt = item.querySelector('.formation-excerpt').textContent.toLowerCase();

                const matchesFilter = currentFilter === 'all' || category === currentFilter;
                const matchesSearch = searchTerm === '' ||
                                    title.includes(searchTerm) ||
                                    excerpt.includes(searchTerm);

                if (matchesFilter && matchesSearch) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            // Show/hide no results message
            if (visibleCount === 0) {
                noResults.classList.remove('d-none');
            } else {
                noResults.classList.add('d-none');
            }
        }

        // Modal functionality
        const formationModals = document.querySelectorAll('.formation-details-modal');
        formationModals.forEach(modal => {
            modal.addEventListener('show.bs.modal', function () {
                // Add loading animation
                const modalBody = this.querySelector('.modal-body');
                modalBody.style.opacity = '0';
                
                setTimeout(() => {
                    modalBody.style.transition = 'opacity 0.3s ease';
                    modalBody.style.opacity = '1';
                }, 100);
            });

            // Handle action buttons in modal
            const subscribeBtn = modal.querySelector('.btn-primary');
            if (subscribeBtn) {
                subscribeBtn.addEventListener('click', function() {
                    const formationTitle = modal.querySelector('.modal-title').textContent;
                    alert(`Redirection vers le formulaire d'inscription pour: ${formationTitle}`);
                    // Here you would typically redirect to an inscription page
                });
            }

            const brochureBtn = modal.querySelector('.btn-outline-primary');
            if (brochureBtn) {
                brochureBtn.addEventListener('click', function() {
                    const formationTitle = modal.querySelector('.modal-title').textContent;
                    alert(`Téléchargement de la brochure pour: ${formationTitle}`);
                    // Here you would typically trigger a brochure download
                });
            }
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all formation cards
        formationItems.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'all 0.6s ease';
            observer.observe(item);
        });
    });
</script>

@endsection