@extends('acceuil.layouts.app')

@section('content')

    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"
                aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">

            @if (isset($carouselItems) && $carouselItems->count() > 0)
                {{-- Premier item actif --}}
                <div class="carousel-item active">
                    <img src="{{ asset('storage/' . $carouselItems[0]->photo) }}" class="d-block w-100"
                        alt="Description de l'image 1">
                    <div class="carousel-caption">
                        <h5 class="caption-title">Bienvenue à l'Institut 3iA</h5>
                        <p class="caption-text">Votre avenir dans l'innovation et la technologie commence ici.</p>
                        <a href="{{ route('acceuil.index') }}" class="btn btn-primary btn-lg caption-button">Découvrir nos
                            formations</a>
                    </div>
                </div>

                {{-- Deuxième item --}}
                @if ($carouselItems->count() > 1)
                    <div class="carousel-item">
                        <img src="{{ asset('storage/' . $carouselItems[1]->photo) }}" class="d-block w-100"
                            alt="Description de l'image 2">
                        <div class="carousel-caption">
                            <h5 class="caption-title">Formations de Pointe</h5>
                            <p class="caption-text">Découvrez nos programmes conçus pour les leaders de demain.</p>
                            <a href="#" class="btn btn-primary btn-lg caption-button">Voir les programmes</a>
                        </div>
                    </div>
                @endif

                {{-- Troisième item --}}
                @if ($carouselItems->count() > 2)
                    <div class="carousel-item">
                        <img src="{{ asset('storage/' . $carouselItems[2]->photo) }}" class="d-block w-100"
                            alt="Description de l'image 3">
                        <div class="carousel-caption">
                            <h5 class="caption-title">Inscrivez-vous Maintenant</h5>
                            <p class="caption-text">Rejoignez une communauté d'excellence et préparez votre carrière.</p>
                            <a href="#" class="btn btn-primary btn-lg caption-button">Commencer l'inscription</a>
                        </div>
                    </div>
                @endif
            @else
                {{-- Affichage alternatif si aucune image --}}
                <div class="carousel-item active">
                    <img src="{{ asset('images/default-carousel.jpg') }}" class="d-block w-100" alt="Image par défaut">
                    <div class="carousel-caption">
                        <h5 class="caption-title">Bienvenue à l'Institut 3iA</h5>
                        <p class="caption-text">Votre avenir dans l'innovation et la technologie commence ici.</p>
                        <a href="#" class="btn btn-primary btn-lg caption-button">Découvrir nos formations</a>
                    </div>
                </div>
            @endif

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- ======================================================= -->
    <!-- SECTION VIDÉO PRÉSENTATION INSTITUT                    -->
    <!-- ======================================================= -->
    <section class="video-presentation-section py-5 animate-on-scroll">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="video-container position-relative">
                        <div class="video-placeholder bg-dark rounded shadow">
                            <!-- Replace with your actual video embed code -->
                            <div class="ratio ratio-16x9">
                                <!-- Example video embed - replace with your actual video -->
                                <!-- Real working video embed -->
                                <iframe src="acceuille/assets/video/video3ia.mp4"
                                    title="The first 20 hours — how to learn anything | Josh Kaufman | TEDxCSU"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen>
                                </iframe>

                            </div>
                        </div>
                        <div class="video-overlay-content text-center">
                            <div class="play-button-container">
                                <div class="play-button-circle">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ps-lg-4">
                        <h2 class="display-5 fw-bold mb-4">Découvrez l'Institut 3iA</h2>
                        <p class="lead mb-4">Plongez au cœur de notre école d'excellence et découvrez notre environnement
                            d'apprentissage unique.</p>

                        <div class="features-list mb-4">
                            <div class="feature-item d-flex mb-3">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-graduation-cap text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Formations d'excellence</h5>
                                    <p class="text-muted mb-0">Des programmes conçus avec des experts du secteur</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex mb-3">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-users text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Communauté dynamique</h5>
                                    <p class="text-muted mb-0">Rejoignez une communauté d'apprenants passionnés</p>
                                </div>
                            </div>

                            <div class="feature-item d-flex">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-briefcase text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold">Débouchés professionnels</h5>
                                    <p class="text-muted mb-0">Des carrières prometteuses dans des secteurs innovants</p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('acceuil.formation') }}" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-book-open me-2"></i>Nos Formations
                            </a>
                            <a href="{{ route('acceuil.contact') }}" class="btn btn-outline-primary btn-lg px-4">
                                <i class="fas fa-envelope me-2"></i>Nous Contacter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- ======================================================= -->
    <!--       SECTION ACTUALITÉS & ÉVÉNEMENTS (OPTIMISÉE)     -->
    <!-- ======================================================= -->
    <section class="news-events-section animate-on-scroll">
        <div class="container">
            <div class="row g-5">
                <!-- === COLONNE DE GAUCHE : ACTUALITÉS === -->
                <div class="col-lg-7">
                    <div class="section-header d-flex justify-content-between align-items-center">
                        <h2>Actualités</h2>
                        <a href="{{ route('acceuil.actualite') }}" class="btn btn-outline-primary">Toutes les
                            nouvelles</a>
                    </div>

                    <div class="row g-4">
                        @foreach ($actualites as $actualite)
                            <div class="col-md-6">
                                <div class="news-card">
                                    <a href="#" class="news-card-plus-button" aria-label="Lire la suite"
                                        data-bs-toggle="modal" data-bs-target="#actualiteModal{{ $actualite->id }}">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                    <a href="#" class="news-card-link" data-bs-toggle="modal"
                                        data-bs-target="#actualiteModal{{ $actualite->id }}">
                                        <div class="news-card-img-container">
                                            <img src="{{ asset($actualite->image ? 'storage/' . ltrim($actualite->image, '/') : 'acceuil/assets/images/default-news.jpg') }}"
                                                alt="{{ $actualite->titre }}" class="img-fluid rounded" />
                                        </div>
                                        <div class="news-card-body">
                                            <div class="news-card-meta d-flex justify-content-between align-items-center">
                                                <span
                                                    class="badge
                                                    @switch($actualite->type)
                                                        @case('À la une') bg-primary @break
                                                        @case('Innovation') bg-success @break
                                                        @case('Évènement') bg-warning @break
                                                        @case('Recherche') bg-info @break
                                                        @default bg-secondary
                                                    @endswitch
                                                ">
                                                    {{ $actualite->type ?? 'Actualité' }}
                                                </span>
                                                <span
                                                    class="date">{{ \Carbon\Carbon::parse($actualite->date_publication)->format('d/m/Y') }}</span>
                                            </div>
                                            <h5 class="news-card-title">{{ $actualite->titre }}</h5>
                                            <p class="news-card-excerpt">
                                                {{ $actualite ? Str::limit(strip_tags($actualite->contenu), 150) : 'Contenu non disponible' }}
                                            </p>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- Actualité Modal -->
                            <div class="modal fade actualite-modal" id="actualiteModal{{ $actualite->id }}"
                                tabindex="-1" aria-labelledby="actualiteModalLabel{{ $actualite->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="actualiteModalLabel{{ $actualite->id }}">
                                                {{ Str::limit($actualite->titre, 60) }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @if ($actualite->image)
                                                <div class="actualite-modal-image">
                                                    <img src="{{ asset('storage/' . $actualite->image) }}"
                                                        alt="{{ $actualite->titre }}" class="img-fluid">
                                                </div>
                                            @endif

                                            <div class="actualite-meta">
                                                <span
                                                    class="actualite-type-badge badge
                                                    @switch($actualite->type)
                                                        @case('À la une') bg-primary @break
                                                        @case('Innovation') bg-success @break
                                                        @case('Évènement') bg-warning @break
                                                        @case('Recherche') bg-info @break
                                                        @default bg-secondary
                                                    @endswitch
                                                ">
                                                    {{ $actualite->type ?? 'Actualité' }}
                                                </span>
                                                <span class="actualite-date">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    Publié le
                                                    {{ \Carbon\Carbon::parse($actualite->date_publication)->format('d/m/Y à H:i') }}
                                                </span>
                                            </div>

                                            <div class="actualite-content">
                                                {!! $actualite->contenu !!}
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-2"></i>Fermer
                                            </button>
                                            <button type="button" class="btn btn-primary">
                                                <i class="fas fa-share-alt me-2"></i>Partager
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- === COLONNE DE DROITE : ÉVÉNEMENTS === -->
                <div class="col-lg-5">
                    <!-- En-tête de section -->
                    <div class="section-header">
                        <h2>Événements</h2>
                        <a href="#" class="btn btn-outline-primary">Voir tout</a>
                    </div>

                    <!-- Liste des événements -->
                    @if ($evenements->isNotEmpty())
                        <div class="event-list">
                            @foreach ($evenements->take(6) as $evenement)
                                <a href="#" class="event-item" data-bs-toggle="modal"
                                    data-bs-target="#eventModal{{ $evenement->id }}">
                                    <div class="event-date">
                                        <span
                                            class="day">{{ \Carbon\Carbon::parse($evenement->date_debut)->format('d') }}</span>
                                        <span
                                            class="month">{{ \Carbon\Carbon::parse($evenement->date_debut)->locale('fr')->isoFormat('MMM') }}</span>
                                    </div>
                                    <div class="event-details">
                                        <h6 class="event-title">{{ $evenement->titre }}</h6>
                                        <div class="event-meta">
                                            <span><i class="fas fa-clock"></i>
                                                {{ \Carbon\Carbon::parse($evenement->date_debut)->format('H:i') }}</span>
                                            <span><i class="fas fa-map-marker-alt"></i> {{ $evenement->lieu }}</span>
                                        </div>
                                    </div>
                                </a>

                                <!-- Event Modal - Compact Version -->
                                <div class="modal fade event-modal" id="eventModal{{ $evenement->id }}" tabindex="-1"
                                    aria-labelledby="eventModalLabel{{ $evenement->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header position-relative">
                                                <h5 class="modal-title" id="eventModalLabel{{ $evenement->id }}">
                                                    {{ Str::limit($evenement->titre, 40) }}
                                                </h5>
                                                <span
                                                    class="event-status-badge badge
                                                    @if ($evenement->statut === 'actif') bg-success
                                                    @elseif($evenement->statut === 'brouillon') bg-secondary
                                                    @else bg-warning @endif">
                                                    {{ $evenement->statut }}
                                                </span>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @if ($evenement->image)
                                                    <div class="event-image-container">
                                                        <img src="{{ asset('storage/' . $evenement->image) }}"
                                                            alt="{{ $evenement->titre }}" class="img-fluid">
                                                    </div>
                                                @endif

                                                <div class="event-details-compact">
                                                    <div class="event-detail-compact">
                                                        <i class="fas fa-calendar-alt"></i>
                                                        <div>
                                                            <strong>Date:</strong>
                                                            {{ \Carbon\Carbon::parse($evenement->date_debut)->format('d/m/Y à H:i') }}
                                                            @if ($evenement->date_fin)
                                                                -
                                                                {{ \Carbon\Carbon::parse($evenement->date_fin)->format('H:i') }}
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="event-detail-compact">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        <div>
                                                            <strong>Lieu:</strong> {{ $evenement->lieu }}
                                                        </div>
                                                    </div>

                                                    @if ($evenement->type_evenement)
                                                        <div class="event-detail-compact">
                                                            <i class="fas fa-tag"></i>
                                                            <div>
                                                                <strong>Type:</strong> {{ $evenement->type_evenement }}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                @if ($evenement->description)
                                                    <div class="event-description-compact">
                                                        <strong>Description:</strong>
                                                        <p class="mb-0">{{ Str::limit($evenement->description, 200) }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    data-bs-dismiss="modal">Fermer</button>
                                                <button type="button" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-calendar-plus me-1"></i>Ajouter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <p>Aucun événement à venir</p>
                        </div>
                    @endif

                </div>

                <!-- Pagination -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center py-3">
                    <div class="small text-muted mb-2 mb-md-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Affichage de <strong>{{ $evenements->firstItem() }}</strong> à
                        <strong>{{ $evenements->lastItem() }}</strong> sur <strong>{{ $evenements->total() }}</strong>
                    </div>
                    <div>
                        {{ $evenements->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ======================================================= -->
    <!--       SECTION STATS & APPEL À L'ACTION (OPTIMISÉE)      -->
    <!-- ======================================================= -->
    <section class="stats-section animate-on-scroll">
        <div class="container">
            <div class="row g-0 d-flex">
                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="stat-card w-100">
                        <div class="stat-number">
                            +<span class="counter" data-target="200">0</span>
                        </div>
                        <div class="stat-title">Programmes d'Études</div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 d-flex">
                    <div class="stat-card w-100">
                        <div class="stat-number">
                            +<span class="counter" data-target="60">0</span>
                        </div>
                        <div class="stat-title">Diplômés Satisfaits</div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 d-flex">
                    <div class="cta-card w-100">
                        <h3 class="cta-title">Choisir une formation</h3>
                        <a href="/formation" class="cta-button" aria-label="Voir les formations">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ======================================================= -->
    <!--                NOS COURS POPULAIRES (UPDATED)          -->
    <!-- ======================================================= -->
    <div class="container text-center my-5 animate-on-scroll">
        <h2 class="display-4 fw-bold mb-3">Nos Cours Populaires</h2>
        <p class="lead text-muted">Découvrez nos formations les plus demandées</p>
    </div>

    <div class="container mb-5">
        @if ($programmes->count() > 0)
            <div class="row g-4" id="formationsGrid">
                @foreach ($programmes as $programme)
                    <div class="col-lg-4 col-md-6 formation-item"
                        data-category="{{ strtolower($programme->qualification->code) }}">
                        <div class="formation-card no-hover-effect">
                            <div class="formation-img-container">
                                <img src="{{ $programme->formation->image ? asset('storage/' . $programme->formation->image) : 'https://via.placeholder.com/400x250' }}"
                                    class="formation-img" alt="{{ $programme->formation->nom }}">
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
                                    <span class="formation-price">{{ number_format($programme->prix, 0, ',', ' ') }}
                                        FCFA</span>
                                    <button type="button" class="formation-btn" data-bs-toggle="modal"
                                        data-bs-target="#formationModal{{ $programme->id }}">
                                        Détails <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formation Details Modal -->
                    <div class="modal fade formation-details-modal" id="formationModal{{ $programme->id }}"
                        tabindex="-1" aria-labelledby="formationModalLabel{{ $programme->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="formationModalLabel{{ $programme->id }}">
                                        {{ $programme->formation->nom }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
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
                                                    <strong>Prix:</strong>
                                                    {{ number_format($programme->prix, 0, ',', ' ') }} FCFA
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
                                                    <li><i class="fas fa-check text-success me-2"></i> Maîtriser les
                                                        compétences fondamentales</li>
                                                    <li><i class="fas fa-check text-success me-2"></i> Développer une
                                                        expertise pratique</li>
                                                    <li><i class="fas fa-check text-success me-2"></i> Préparation aux
                                                        certifications</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <ul class="features-list">
                                                    <li><i class="fas fa-check text-success me-2"></i> Projets concrets et
                                                        études de cas</li>
                                                    <li><i class="fas fa-check text-success me-2"></i> Accompagnement
                                                        personnalisé</li>
                                                    <li><i class="fas fa-check text-success me-2"></i> Accès à la
                                                        plateforme en ligne</li>
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
                @endforeach
            </div>
        @else
            <div class="alert alert-info text-center animate-on-scroll py-5">
                <i class="fas fa-info-circle fa-3x mb-3 text-primary"></i>
                <h4 class="alert-heading">Aucune formation disponible</h4>
                <p class="mb-0">De nouvelles sessions seront bientôt disponibles. Revenez plus tard !</p>
            </div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* ======================================================= */
        /* VIDEO PRESENTATION SECTION STYLES */
        /* ======================================================= */
        .video-presentation-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            position: relative;
            overflow: hidden;
        }

        .video-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .video-container:hover {
            transform: translateY(-5px);
        }

        .video-overlay-content {
            pointer-events: none;
        }


        .video-overlay-content {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .video-container:hover .video-overlay-content {
            opacity: 1;
        }

        .play-button-circle {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .play-button-circle:hover {
            background: white;
            transform: scale(1.1);
        }

        .play-button-circle i {
            font-size: 24px;
            color: #007bff;
            margin-left: 4px;
        }

        .features-list .feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(0, 123, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-icon i {
            font-size: 20px;
        }

        /* ======================================================= */
        /* FORMATION CARDS STYLES (FROM FORMATION PAGE) */
        /* ======================================================= */
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

        .benefit-item {
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        /* ======================================================= */
        /* REMOVE ALL HOVER EFFECTS FROM NOS COURS POPULAIRES */
        /* ======================================================= */
        .no-hover-effect {
            transition: none !important;
            transform: none !important;
        }

        .no-hover-effect:hover {
            transform: none !important;
            box-shadow: var(--shadow) !important;
        }

        .no-hover-effect .formation-img,
        .no-hover-effect .formation-content,
        .no-hover-effect .formation-title,
        .no-hover-effect .formation-excerpt,
        .no-hover-effect .formation-meta,
        .no-hover-effect .formation-btn {
            transition: none !important;
        }

        .no-hover-effect:hover .formation-img {
            transform: none !important;
        }

        .no-hover-effect:hover .formation-btn {
            background-color: var(--primary-blue) !important;
            color: white !important;
            transform: none !important;
        }

        /* Animations */
        .formation-item {
            opacity: 1;
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
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
            .formation-img-container {
                height: 180px;
            }

            .formation-details-modal .modal-dialog {
                margin: 0.5rem;
            }
        }
    </style>

    <script>
        // ===========================
        // VIDEO PRESENTATION FUNCTIONALITY
        // ===========================
        document.addEventListener('DOMContentLoaded', function() {
            const playButton = document.querySelector('.play-button-circle');
            const videoIframe = document.querySelector('.video-container iframe');

            if (playButton && videoIframe) {
                playButton.addEventListener('click', function() {
                    // Get the current src of the iframe
                    let src = videoIframe.src;

                    // Check if autoplay is already enabled
                    if (src.includes('autoplay=1')) {
                        return;
                    }

                    // Add autoplay parameter
                    if (src.includes('?')) {
                        src += '&autoplay=1';
                    } else {
                        src += '?autoplay=1';
                    }

                    // Update the iframe src
                    videoIframe.src = src;

                    // Hide the play button
                    this.style.display = 'none';
                });
            }
        });

        // ===========================
        // SCROLL ANIMATIONS
        // ===========================
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');

                    // Animate child elements with stagger
                    const children = entry.target.querySelectorAll('.animate-on-scroll');
                    children.forEach((child, index) => {
                        setTimeout(() => {
                            child.classList.add('animated');
                        }, index * 100);
                    });
                }
            });
        }, observerOptions);

        // Observe all elements with animate-on-scroll class
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // ===========================
        // COUNTER ANIMATION
        // ===========================
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-target'));
            const increment = target / 100;
            let count = 0;

            const timer = setInterval(() => {
                count += increment;
                if (count >= target) {
                    element.textContent = target;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(count);
                }
            }, 20);
        }

        // Start counter animation when stats section is visible
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.counter');
                    counters.forEach(counter => {
                        animateCounter(counter);
                    });
                    statsObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.5
        });

        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }

        // ===========================
        // SMOOTH SCROLL FOR ANCHORS
        // ===========================
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
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

        // ===========================
        // PARALLAX EFFECT FOR CAROUSEL
        // ===========================
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.carousel-item img');

            parallaxElements.forEach(element => {
                const speed = 0.5;
                element.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });

        // ===========================
        // ACTUALITÉ MODAL ENHANCEMENTS
        // ===========================
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced actualité modal functionality
            const actualiteModals = document.querySelectorAll('.actualite-modal');

            actualiteModals.forEach(modal => {
                modal.addEventListener('show.bs.modal', function() {
                    // Add loading animation
                    const modalBody = this.querySelector('.modal-body');
                    modalBody.style.opacity = '0';

                    setTimeout(() => {
                        modalBody.style.transition = 'opacity 0.3s ease';
                        modalBody.style.opacity = '1';
                    }, 100);
                });

                // Handle share button
                const shareBtn = modal.querySelector('.btn-primary');
                if (shareBtn) {
                    shareBtn.addEventListener('click', function() {
                        const actualiteTitle = modal.querySelector('.modal-title').textContent;
                        if (navigator.share) {
                            navigator.share({
                                    title: actualiteTitle,
                                    text: 'Découvrez cette actualité de l\'Institut 3iA',
                                    url: window.location.href,
                                })
                                .catch(console.error);
                        } else {
                            // Fallback for browsers that don't support Web Share API
                            alert(`Partager l'actualité: ${actualiteTitle}`);
                        }
                    });
                }
            });
        });

        // ===========================
        // FORMATION MODAL ENHANCEMENTS
        // ===========================
        document.addEventListener('DOMContentLoaded', function() {
            // Enhanced formation modal functionality
            const formationModals = document.querySelectorAll('.formation-details-modal');

            formationModals.forEach(modal => {
                modal.addEventListener('show.bs.modal', function() {
                    // Add loading animation
                    const modalBody = this.querySelector('.modal-body');
                    modalBody.style.opacity = '0';

                    setTimeout(() => {
                        modalBody.style.transition = 'opacity 0.3s ease';
                        modalBody.style.opacity = '1';
                    }, 100);
                });

                // Handle action buttons
                const subscribeBtn = modal.querySelector('.btn-primary');
                if (subscribeBtn) {
                    subscribeBtn.addEventListener('click', function() {
                        const formationTitle = modal.querySelector('.modal-title').textContent;
                        alert(
                            `Redirection vers le formulaire d'inscription pour: ${formationTitle}`);
                        // Here you would typically redirect to an inscription page
                    });
                }
            });
        });

        // ===========================
        // FORMATION CARDS ANIMATION
        // ===========================
        document.addEventListener('DOMContentLoaded', function() {
            const formationItems = document.querySelectorAll('.formation-item');

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
