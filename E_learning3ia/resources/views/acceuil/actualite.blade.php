@extends('acceuil.layouts.app')

@section('content')
<!-- HERO SECTION -->
<section class="page-hero">
    <div class="container text-center">
        <h1 class="hero-title">Nos Actualités</h1>
        <p class="hero-subtitle">Restez informé des dernières nouvelles et innovations de l'Institut 3iA.</p>
    </div>
</section>

<!-- ACTUALITÉS SECTION -->
<section class="news-grid-section">
    <div class="container">
        <!-- SEARCH FORM -->
        <div class="row mb-4">
            <div class="col-md-6 mx-auto">
                <form method="GET" action="{{ route('acceuil.actualite') }}">
                    <div class="input-group">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control" placeholder="Rechercher des actualités...">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search'))
                        <a href="{{ route('acceuil.actualite') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- ACTUALITÉS GRID -->
        <div class="row g-4">
            @forelse($actualites as $actualite)
                <div class="col-lg-4 col-md-6">
                    <div class="news-card">
                        <div class="news-card-img-container">
                            @if($actualite && $actualite->image)
                                <img src="{{ asset('storage/' . $actualite->image) }}" alt="{{ $actualite->titre }}" class="img-fluid">
                            @else
                                <img src="{{ asset('acceuille/assets/images/OIP 4.png') }}" alt="Image par défaut" class="img-fluid">
                            @endif
                        </div>

                        <div class="news-card-body">
                            <div class="news-card-meta">
                                <span class="badge bg-primary">{{ $actualite ? ($actualite->category->name ?? 'Actualité') : 'Actualité' }}</span>
                                <span class="date">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $actualite ? $actualite->date_publication->translatedFormat('d F Y') : 'Date inconnue' }}
                                </span>
                            </div>
                            <h5 class="news-card-title">{{ $actualite ? $actualite->titre : 'Titre non disponible' }}</h5>
                            <p class="news-card-excerpt">
                                {{ $actualite ? Str::limit(strip_tags($actualite->contenu), 150) : 'Contenu non disponible' }}
                            </p>
                        </div>
                        <div class="news-card-footer">
                            <button type="button" class="btn-read-more" data-bs-toggle="modal" 
                                    data-bs-target="#newsModal{{ $actualite->id ?? 'default' }}">
                                Lire la suite <i class="fas fa-long-arrow-alt-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- News Modal -->
                @if($actualite)
                <div class="modal fade news-modal" id="newsModal{{ $actualite->id }}" tabindex="-1" 
                     aria-labelledby="newsModalLabel{{ $actualite->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="newsModalLabel{{ $actualite->id }}">
                                    {{ $actualite->titre }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if($actualite->image)
                                <div class="news-modal-image mb-4">
                                    <img src="{{ asset('storage/' . $actualite->image) }}" 
                                         alt="{{ $actualite->titre }}" class="img-fluid rounded">
                                </div>
                                @endif
                                
                                <div class="news-modal-meta d-flex justify-content-between align-items-center mb-4">
                                    <span class="badge bg-primary">{{ $actualite->category->name ?? 'Actualité' }}</span>
                                    <span class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $actualite->date_publication->translatedFormat('d F Y') }}
                                    </span>
                                </div>
                                
                                <div class="news-modal-content">
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
                @endif

            @empty
                <div class="col-12 text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-newspaper fa-4x text-muted mb-4"></i>
                        <h4 class="mb-3">Aucune actualité disponible</h4>
                        <p class="text-muted mb-4">Nous n'avons trouvé aucune actualité pour le moment.</p>
                        @if(request('search'))
                        <a href="{{ route('acceuil.actualite') }}" class="btn btn-primary px-4">
                            <i class="fas fa-undo me-2"></i> Réinitialiser la recherche
                        </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        @if($actualites && $actualites->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{-- Previous Page Link --}}
                        @if($actualites->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left me-1"></i> Précédent
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $actualites->previousPageUrl() }}" rel="prev">
                                    <i class="fas fa-chevron-left me-1"></i> Précédent
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach($actualites->getUrlRange(1, $actualites->lastPage()) as $page => $url)
                            @if($page == $actualites->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if($actualites->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $actualites->nextPageUrl() }}" rel="next">
                                    Suivant <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">
                                    Suivant <i class="fas fa-chevron-right ms-1"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
        @endif
    </div>
</section>

<style>
    /* HERO SECTION */
    .page-hero {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        padding: 100px 0;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 60px;
    }

    .page-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.1);
    }

    .hero-title {
        font-size: 2.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .hero-subtitle {
        font-size: 1.25rem;
        opacity: 0.9;
        max-width: 700px;
        margin: 0 auto;
    }

    /* NEWS CARD */
    .news-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        height: 100%;
        background: white;
    }

    .news-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(30, 58, 138, 0.15);
    }

    .news-card-img-container {
        height: 200px;
        overflow: hidden;
        position: relative;
    }

    .news-card-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .news-card:hover .news-card-img-container img {
        transform: scale(1.05);
    }

    .news-card-body {
        padding: 20px;
    }

    .news-card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        font-size: 0.85rem;
    }

    .news-card-meta .badge {
        font-weight: 500;
        padding: 5px 10px;
    }

    .news-card-meta .date {
        color: #64748b;
    }

    .news-card-title {
        font-weight: 600;
        margin-bottom: 10px;
        color: #1e293b;
        font-size: 1.25rem;
    }

    .news-card-excerpt {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 15px;
    }

    .news-card-footer {
        padding: 15px 20px;
        border-top: 1px solid #f1f5f9;
        text-align: center;
    }

    .btn-read-more {
        background: none;
        border: none;
        color: #3b82f6;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        cursor: pointer;
        padding: 0;
        font-size: inherit;
    }

    .btn-read-more:hover {
        color: #1e3a8a;
        transform: translateX(5px);
    }

    /* NEWS MODAL */
    .news-modal .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .news-modal .modal-header {
        border-bottom: 1px solid #e2e8f0;
        padding: 1.5rem 2rem;
    }

    .news-modal .modal-title {
        font-weight: 600;
        color: #1e293b;
        font-size: 1.5rem;
    }

    .news-modal .modal-body {
        padding: 2rem;
    }

    .news-modal-image {
        border-radius: 10px;
        overflow: hidden;
    }

    .news-modal-image img {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
    }

    .news-modal-meta {
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .news-modal-content {
        line-height: 1.7;
        color: #374151;
        font-size: 1.05rem;
    }

    .news-modal-content h1,
    .news-modal-content h2,
    .news-modal-content h3,
    .news-modal-content h4,
    .news-modal-content h5,
    .news-modal-content h6 {
        color: #1e293b;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }

    .news-modal-content p {
        margin-bottom: 1rem;
    }

    .news-modal-content ul,
    .news-modal-content ol {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }

    .news-modal-content li {
        margin-bottom: 0.5rem;
    }

    .news-modal .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 1.5rem 2rem;
    }

    /* EMPTY STATE */
    .empty-state {
        max-width: 500px;
        margin: 0 auto;
        padding: 30px;
    }

    /* PAGINATION */
    .pagination .page-item {
        margin: 0 5px;
    }

    .pagination .page-link {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        color: #3b82f6;
        font-weight: 500;
        min-width: 40px;
        text-align: center;
    }

    .pagination .page-item.active .page-link {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    .pagination .page-item.disabled .page-link {
        color: #94a3b8;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .page-hero {
            padding: 80px 0;
        }

        .hero-title {
            font-size: 2rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .news-card-img-container {
            height: 180px;
        }

        .news-modal .modal-header,
        .news-modal .modal-body,
        .news-modal .modal-footer {
            padding: 1rem;
        }

        .news-modal .modal-title {
            font-size: 1.25rem;
        }
    }

    @media (max-width: 576px) {
        .news-modal .modal-dialog {
            margin: 0.5rem;
        }
    }
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Enhanced modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus on search input
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.focus();
        }

        // Enhanced modal animations
        const newsModals = document.querySelectorAll('.news-modal');
        newsModals.forEach(modal => {
            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const card = button.closest('.news-card');
                
                // Add loading animation
                const modalBody = this.querySelector('.modal-body');
                modalBody.style.opacity = '0';
                
                setTimeout(() => {
                    modalBody.style.transition = 'opacity 0.3s ease';
                    modalBody.style.opacity = '1';
                }, 100);
            });

            modal.addEventListener('hidden.bs.modal', function () {
                // Reset modal state
                const modalBody = this.querySelector('.modal-body');
                modalBody.style.transition = 'none';
                modalBody.style.opacity = '1';
            });
        });

        // Share functionality
        document.querySelectorAll('.news-modal .btn-primary').forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('.news-modal');
                const title = modal.querySelector('.modal-title').textContent;
                const url = window.location.href;
                
                // Simple share implementation
                if (navigator.share) {
                    navigator.share({
                        title: title,
                        url: url
                    }).catch(console.error);
                } else {
                    // Fallback: copy to clipboard
                    navigator.clipboard.writeText(url).then(() => {
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-check me-2"></i>Copié!';
                        setTimeout(() => {
                            this.innerHTML = originalText;
                        }, 2000);
                    });
                }
            });
        });

        // Smooth scrolling for pagination
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector('.news-grid-section');
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    // Navigate after scroll
                    setTimeout(() => {
                        window.location.href = this.href;
                    }, 500);
                }
            });
        });
    });
</script>
@endsection