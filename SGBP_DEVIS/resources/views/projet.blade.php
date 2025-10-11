<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Projets - {{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 50%, #60A5FA 100%); }
        .project-card { transition: all 0.3s ease; cursor: pointer; }
        .project-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        .project-card img { transition: transform 0.3s ease; }
        .project-card:hover img { transform: scale(1.05); }

        /* Lightbox */
        #lightbox { display: none; }
        #lightbox.active { display: flex; }
        .lightbox-thumb { cursor: pointer; transition: opacity 0.2s; }
        .lightbox-thumb:hover { opacity: 0.8; }
        .lightbox-thumb.active-thumb { ring: 2px solid white; opacity: 1; }

        @media (max-width: 768px) {
            .mobile-nav { position: fixed; bottom: 0; left: 0; right: 0; background: white; box-shadow: 0 -2px 10px rgba(0,0,0,0.1); z-index: 1000; }
            .content-with-mobile-nav { padding-bottom: 80px; }
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navigation Desktop -->
    <nav class="hidden md:block fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-md shadow-md z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-3">
                    @if ($parametre->logo_path)
                        <img src="{{ asset('storage/' . $parametre->logo_path) }}" alt="Logo" class="w-12 h-12 rounded-lg object-contain">
                    @else
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-file-invoice text-white text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">{{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</h1>
                        <p class="text-xs text-gray-500">{{ $parametre->slogan ?? '' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-8">
                    <a href="/" class="text-gray-600 hover:text-blue-600 font-medium">Accueil</a>
                    <a href="/#services" class="text-gray-600 hover:text-blue-600 font-medium">Services</a>
                    <a href="#" class="text-blue-600 font-medium">Projets</a>
                    <a href="/#apropos" class="text-gray-600 hover:text-blue-600 font-medium">À propos</a>
                    <a href="/contact" class="text-gray-600 hover:text-blue-600 font-medium">Contact</a>
                </div>
                <a href="/contact" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Demander un devis</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Mobile Top Bar -->
    <div class="md:hidden sticky top-0 bg-white shadow-md z-50 px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                @if ($parametre->logo_path)
                    <img src="{{ asset('storage/' . $parametre->logo_path) }}" alt="Logo" class="w-10 h-10 rounded-lg object-contain">
                @else
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-file-invoice text-white"></i>
                    </div>
                @endif
                <h1 class="text-lg font-bold text-gray-800">{{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</h1>
            </div>
            <a href="/contact" class="p-2 bg-blue-600 text-white rounded-lg">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </a>
        </div>
    </div>

    <!-- Mobile Navigation Bottom -->
    <div class="mobile-nav md:hidden">
        <div class="grid grid-cols-5 gap-1 px-2 py-2">
            <a href="/" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <i class="fa-solid fa-house text-xl"></i>
                <span class="text-xs">Accueil</span>
            </a>
            <a href="/#services" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <i class="fa-solid fa-wrench text-xl"></i>
                <span class="text-xs">Services</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 py-2 text-blue-600">
                <i class="fa-solid fa-images text-xl"></i>
                <span class="text-xs font-medium">Projets</span>
            </a>
            <a href="/#apropos" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <i class="fa-solid fa-circle-info text-xl"></i>
                <span class="text-xs">À propos</span>
            </a>
            <a href="/contact" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <i class="fa-solid fa-envelope text-xl"></i>
                <span class="text-xs">Contact</span>
            </a>
        </div>
    </div>

    <div class="content-with-mobile-nav md:pt-20">

        <!-- Hero -->
        <section class="hero-gradient py-16 md:py-20">
            <div class="container mx-auto px-4 text-center text-white">
                <h1 class="text-3xl md:text-5xl font-bold mb-4">Nos Réalisations</h1>
                <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto">
                    Découvrez nos projets récents et la qualité de notre savoir-faire
                </p>
            </div>
        </section>

        <!-- Filtres -->
        @php
            $categories = $projets->pluck('categorie')->filter()->unique()->values();
        @endphp
        @if ($categories->count() > 0)
        <section class="py-8 bg-white border-b">
            <div class="container mx-auto px-4">
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <button onclick="filterProjects('all')" data-filter="all"
                        class="filter-btn px-5 py-2 bg-blue-600 text-white rounded-full font-medium text-sm transition">
                        Tous
                    </button>
                    @foreach ($categories as $cat)
                        <button onclick="filterProjects('{{ $cat }}')" data-filter="{{ $cat }}"
                            class="filter-btn px-5 py-2 bg-white border border-gray-300 text-gray-700 rounded-full font-medium text-sm hover:bg-gray-100 transition">
                            {{ ucfirst($cat) }}
                        </button>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- Grille des projets -->
        <section class="py-12 md:py-16">
            <div class="container mx-auto px-4">
                @if ($projets->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="projects-grid">
                        @foreach ($projets as $projet)
                            @php
                                $photos = $projet->photos;
                                $photoPrincipale = $photos->where('principale', true)->first() ?? $photos->first();
                                $coverUrl = $photoPrincipale
                                    ? asset('storage/' . $photoPrincipale->path)
                                    : ($projet->image_path ? asset('storage/' . $projet->image_path) : null);
                            @endphp
                            <div class="project-card bg-white rounded-2xl overflow-hidden shadow-lg"
                                 data-category="{{ $projet->categorie }}">
                                <!-- Image de couverture -->
                                <div class="h-56 overflow-hidden relative bg-gradient-to-br from-blue-400 to-blue-600">
                                    @if ($coverUrl)
                                        <img src="{{ $coverUrl }}" alt="{{ $projet->titre }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fa-solid fa-building text-white text-7xl opacity-40"></i>
                                        </div>
                                    @endif
                                    <!-- Badge nombre de photos -->
                                    @if ($photos->count() > 1)
                                        <span class="absolute bottom-2 right-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded-full flex items-center gap-1">
                                            <i class="fa-solid fa-images text-xs"></i>
                                            {{ $photos->count() }} photos
                                        </span>
                                    @endif
                                </div>

                                <div class="p-5">
                                    @if ($projet->categorie)
                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs font-medium mb-3">
                                            {{ ucfirst($projet->categorie) }}
                                        </span>
                                    @endif

                                    <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $projet->titre }}</h3>

                                    @if ($projet->description)
                                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $projet->description }}</p>
                                    @endif

                                    <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
                                        @if ($projet->client_nom)
                                            <div>
                                                <p class="text-gray-400 text-xs">Client</p>
                                                <p class="font-medium text-gray-800">{{ $projet->client_nom }}</p>
                                            </div>
                                        @endif
                                        @if ($projet->duree)
                                            <div>
                                                <p class="text-gray-400 text-xs">Durée</p>
                                                <p class="font-medium text-gray-800">{{ $projet->duree }}</p>
                                            </div>
                                        @endif
                                        @if ($projet->superficie)
                                            <div>
                                                <p class="text-gray-400 text-xs">Surface</p>
                                                <p class="font-medium text-gray-800">{{ $projet->superficie }}</p>
                                            </div>
                                        @endif
                                        @if ($projet->annee)
                                            <div>
                                                <p class="text-gray-400 text-xs">Année</p>
                                                <p class="font-medium text-gray-800">{{ $projet->annee }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    @if ($photos->count() > 0)
                                        <button onclick="openGallery({{ $projet->id }})"
                                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 font-medium text-sm transition">
                                            <i class="fa-solid fa-images"></i>
                                            <span>Voir les {{ $photos->count() }} photo{{ $photos->count() > 1 ? 's' : '' }}</span>
                                        </button>
                                    @endif
                                </div>

                                {{-- Données photos pour la galerie JS --}}
                                <script type="application/json" id="photos-{{ $projet->id }}">
                                    [
                                        @foreach ($photos as $photo)
                                            {"url": "{{ asset('storage/' . $photo->path) }}", "legende": "{{ addslashes($photo->legende ?? $projet->titre) }}"}{{ $loop->last ? '' : ',' }}
                                        @endforeach
                                    ]
                                </script>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-20 text-gray-400">
                        <i class="fa-solid fa-folder-open text-6xl mb-4"></i>
                        <p class="text-xl">Aucun projet disponible pour le moment.</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- CTA -->
        <section class="py-16 bg-gradient-to-r from-blue-600 to-blue-700">
            <div class="container mx-auto px-4 text-center text-white">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Votre projet mérite notre expertise</h2>
                <p class="text-lg mb-8 opacity-90 max-w-2xl mx-auto">
                    Concrétisez votre projet avec notre équipe d'experts au Gabon
                </p>
                <a href="/contact"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-white text-blue-600 rounded-lg hover:bg-gray-100 font-bold text-lg">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Demander un devis gratuit</span>
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white pt-12 pb-6">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            @if ($parametre->logo_path)
                                <img src="{{ asset('storage/' . $parametre->logo_path) }}" alt="Logo" class="w-10 h-10 rounded-lg object-contain">
                            @else
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-file-invoice text-white"></i>
                                </div>
                            @endif
                            <h3 class="text-xl font-bold">{{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</h3>
                        </div>
                        <p class="text-gray-400 text-sm">{{ $parametre->slogan ?? '' }}</p>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Liens rapides</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="/" class="text-gray-400 hover:text-white">Accueil</a></li>
                            <li><a href="/#services" class="text-gray-400 hover:text-white">Services</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Projets</a></li>
                            <li><a href="/contact" class="text-gray-400 hover:text-white">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-bold mb-4">Contact</h4>
                        <ul class="space-y-3 text-sm">
                            @if ($parametre->ville)
                                <li class="flex items-start gap-2 text-gray-400">
                                    <i class="fa-solid fa-location-dot mt-0.5"></i>
                                    <span>{{ implode(', ', array_filter([$parametre->ville, $parametre->pays])) }}</span>
                                </li>
                            @endif
                            @if ($parametre->telephone)
                                <li class="flex items-start gap-2 text-gray-400">
                                    <i class="fa-solid fa-phone mt-0.5"></i>
                                    <span>{{ $parametre->telephone }}</span>
                                </li>
                            @endif
                            @if ($parametre->email)
                                <li class="flex items-start gap-2 text-gray-400">
                                    <i class="fa-solid fa-envelope mt-0.5"></i>
                                    <span>{{ $parametre->email }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 pt-6 text-center text-gray-400 text-sm">
                    <p>&copy; {{ date('Y') }} {{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}. Tous droits réservés.</p>
                </div>
            </div>
        </footer>
    </div>

    {{-- ══════════ LIGHTBOX GALERIE ══════════ --}}
    <div id="lightbox" class="fixed inset-0 bg-black bg-opacity-95 z-50 items-center justify-center flex-col"
         onclick="closeLightbox(event)">
        <!-- Bouton fermer -->
        <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-3xl z-10 hover:text-gray-300">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Titre -->
        <p id="lb-title" class="text-white text-sm mb-3 opacity-70"></p>

        <!-- Image principale -->
        <div class="relative flex items-center justify-center w-full max-w-4xl px-12">
            <button onclick="prevPhoto()" class="absolute left-0 text-white text-3xl hover:text-gray-300 p-2 z-10">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <img id="lb-img" src="" alt="" class="max-h-[70vh] max-w-full rounded-lg shadow-2xl object-contain"
                 onclick="event.stopPropagation()">
            <button onclick="nextPhoto()" class="absolute right-0 text-white text-3xl hover:text-gray-300 p-2 z-10">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        <!-- Compteur -->
        <p id="lb-counter" class="text-white text-sm mt-3 opacity-60"></p>

        <!-- Miniatures -->
        <div id="lb-thumbs" class="flex gap-2 mt-4 overflow-x-auto max-w-2xl px-4 pb-2"></div>
    </div>

    <script>
        // ── FILTRAGE ──────────────────────────────────────────────────────
        function filterProjects(category) {
            var cards = document.querySelectorAll('.project-card');
            var btns  = document.querySelectorAll('.filter-btn');

            btns.forEach(function(btn) {
                if (btn.dataset.filter === category) {
                    btn.classList.add('bg-blue-600', 'text-white');
                    btn.classList.remove('bg-white', 'border', 'border-gray-300', 'text-gray-700');
                } else {
                    btn.classList.remove('bg-blue-600', 'text-white');
                    btn.classList.add('bg-white', 'border', 'border-gray-300', 'text-gray-700');
                }
            });

            cards.forEach(function(card) {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // ── GALERIE LIGHTBOX ──────────────────────────────────────────────
        var lbPhotos  = [];
        var lbCurrent = 0;

        function openGallery(projetId) {
            var dataEl = document.getElementById('photos-' + projetId);
            if (!dataEl) return;

            try {
                lbPhotos  = JSON.parse(dataEl.textContent);
                lbCurrent = 0;
                showPhoto(0);
                document.getElementById('lightbox').classList.add('active');
                document.body.style.overflow = 'hidden';
            } catch(e) { console.error(e); }
        }

        function showPhoto(index) {
            if (index < 0) index = lbPhotos.length - 1;
            if (index >= lbPhotos.length) index = 0;
            lbCurrent = index;

            var photo = lbPhotos[index];
            document.getElementById('lb-img').src     = photo.url;
            document.getElementById('lb-img').alt     = photo.legende || '';
            document.getElementById('lb-title').textContent   = photo.legende || '';
            document.getElementById('lb-counter').textContent = (index + 1) + ' / ' + lbPhotos.length;

            // Miniatures
            var thumbsEl = document.getElementById('lb-thumbs');
            thumbsEl.innerHTML = '';
            lbPhotos.forEach(function(p, i) {
                var img = document.createElement('img');
                img.src = p.url;
                img.className = 'h-14 w-20 object-cover rounded cursor-pointer border-2 transition ' +
                    (i === index ? 'border-white opacity-100' : 'border-transparent opacity-50 hover:opacity-80');
                img.onclick = function(e) { e.stopPropagation(); showPhoto(i); };
                thumbsEl.appendChild(img);
            });

            // Scroll la miniature active
            var activeThumb = thumbsEl.children[index];
            if (activeThumb) activeThumb.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        }

        function prevPhoto() { showPhoto(lbCurrent - 1); }
        function nextPhoto() { showPhoto(lbCurrent + 1); }

        function closeLightbox(event) {
            if (event && event.target !== document.getElementById('lightbox')) return;
            document.getElementById('lightbox').classList.remove('active');
            document.body.style.overflow = '';
        }

        // Navigation clavier
        document.addEventListener('keydown', function(e) {
            var lb = document.getElementById('lightbox');
            if (!lb.classList.contains('active')) return;
            if (e.key === 'ArrowLeft')  prevPhoto();
            if (e.key === 'ArrowRight') nextPhoto();
            if (e.key === 'Escape')     { lb.classList.remove('active'); document.body.style.overflow = ''; }
        });
    </script>
</body>
</html>
