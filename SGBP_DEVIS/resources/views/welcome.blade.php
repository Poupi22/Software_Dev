<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - {{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .carousel-item {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .carousel-item.active {
            display: block;
            opacity: 1;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .mobile-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                padding-bottom: env(safe-area-inset-bottom);
            }

            .content-with-mobile-nav {
                padding-bottom: 80px;
            }
        }
    </style>
</head>

<body class="bg-white">
    <!-- Navigation Desktop -->
    <nav class="hidden md:block fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-md shadow-md z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-3">
                    @if ($parametre->logo_path)
                        <img src="{{ asset('storage/' . $parametre->logo_path) }}" alt="Logo"
                            class="w-12 h-12 rounded-lg object-contain">
                    @else
                        <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-2xl">receipt_long</span>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">{{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}
                        </h1>
                        <p class="text-xs text-gray-500">{{ $parametre->slogan ?? '' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-8">
                    <a href="#" class="text-blue-600 font-medium hover:text-blue-800">Accueil</a>
                    <a href="#services" class="text-gray-600 hover:text-blue-600 font-medium">Services</a>
                    <a href="#projets" class="text-gray-600 hover:text-blue-600 font-medium">Projets</a>
                    <a href="#apropos" class="text-gray-600 hover:text-blue-600 font-medium">À propos</a>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-blue-600 font-medium">Contact</a>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('contact') }}"
                        class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        <span class="material-icons">request_quote</span>
                        <span>Demander un devis</span>
                    </a>

                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Top Bar -->
    <div class="md:hidden sticky top-0 bg-white shadow-md z-50 px-4 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                @if ($parametre->logo_path)
                    <img src="{{ asset('storage/' . $parametre->logo_path) }}" alt="Logo"
                        class="w-10 h-10 rounded-lg object-contain">
                @else
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <span class="material-icons text-white">receipt_long</span>
                    </div>
                @endif
                <h1 class="text-lg font-bold text-gray-800">{{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</h1>
            </div>
            <div class="flex items-center gap-2">

                <a href="{{ route('contact') }}" class="p-2 bg-blue-600 text-white rounded-lg">
                    <span class="material-icons">request_quote</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Bottom -->
    <div class="mobile-nav md:hidden">
        <div class="grid grid-cols-5 gap-1 px-2 py-2">
            <a href="#" class="flex flex-col items-center gap-1 py-2 text-blue-600">
                <span class="material-icons text-2xl">home</span>
                <span class="text-xs font-medium">Accueil</span>
            </a>
            <a href="#services" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <span class="material-icons text-2xl">build</span>
                <span class="text-xs">Services</span>
            </a>
            <a href="#projets" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <span class="material-icons text-2xl">photo_library</span>
                <span class="text-xs">Projets</span>
            </a>
            <a href="#apropos" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <span class="material-icons text-2xl">info</span>
                <span class="text-xs">À propos</span>
            </a>
            <a href="{{ route('contact') }}" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <span class="material-icons text-2xl">contact_mail</span>
                <span class="text-xs">Contact</span>
            </a>
        </div>
    </div>

    <div class="content-with-mobile-nav md:pt-20">
        <!-- Hero Carousel Section -->
        <section class="relative h-[500px] md:h-[700px] overflow-hidden">
            <!-- Carousel Items -->
            <div id="carousel" class="relative w-full h-full">
                <!-- Slide 1 -->
                <div class="carousel-item active absolute inset-0">
                    @if (file_exists(public_path('images/carousel-1.jpg')))
                        <img src="{{ asset('images/carousel-1.jpg') }}" alt="Slide 1"
                            class="absolute inset-0 w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-black/50"></div>
                    <div class="absolute inset-0 flex items-center">
                        <div class="container mx-auto px-4">
                            <div class="max-w-3xl text-white">
                                <div class="mb-4">
                                    <span
                                        class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">15
                                        ans d'expérience</span>
                                </div>
                                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                                    Construisons ensemble votre projet
                                </h1>
                                <p class="text-lg md:text-xl mb-8 opacity-90">
                                    Du gros œuvre à la finition, nous accompagnons tous vos travaux de construction et
                                    rénovation
                                </p>
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <a href="{{ route('contact') }}"
                                        class="flex items-center justify-center gap-2 px-8 py-4 bg-white text-blue-600 rounded-lg hover:bg-gray-100 font-bold text-lg">
                                        <span class="material-icons">request_quote</span>
                                        <span>Demander un devis</span>
                                    </a>
                                    <a href="#projets"
                                        class="flex items-center justify-center gap-2 px-8 py-4 border-2 border-white text-white rounded-lg hover:bg-white/10 font-bold text-lg">
                                        <span class="material-icons">photo_library</span>
                                        <span>Nos réalisations</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item absolute inset-0">
                    @if (file_exists(public_path('images/carousel-2.jpg')))
                        <img src="{{ asset('images/carousel-2.jpg') }}" alt="Slide 2"
                            class="absolute inset-0 w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-black/50"></div>
                    <div class="absolute inset-0 flex items-center">
                        <div class="container mx-auto px-4">
                            <div class="max-w-3xl text-white">
                                <div class="mb-4">
                                    <span
                                        class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">500+
                                        projets réalisés</span>
                                </div>
                                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                                    Votre satisfaction, notre priorité
                                </h1>
                                <p class="text-lg md:text-xl mb-8 opacity-90">
                                    98% de clients satisfaits grâce à notre engagement qualité et respect des délais
                                </p>
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <a href="#services"
                                        class="flex items-center justify-center gap-2 px-8 py-4 bg-white text-green-600 rounded-lg hover:bg-gray-100 font-bold text-lg">
                                        <span class="material-icons">build</span>
                                        <span>Nos services</span>
                                    </a>
                                    <a href="#apropos"
                                        class="flex items-center justify-center gap-2 px-8 py-4 border-2 border-white text-white rounded-lg hover:bg-white/10 font-bold text-lg">
                                        <span class="material-icons">info</span>
                                        <span>Qui sommes-nous ?</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item absolute inset-0">
                    @if (file_exists(public_path('images/carousel-3.jpg')))
                        <img src="{{ asset('images/carousel-3.jpg') }}" alt="Slide 3"
                            class="absolute inset-0 w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-black/50"></div>
                    <div class="absolute inset-0 flex items-center">
                        <div class="container mx-auto px-4">
                            <div class="max-w-3xl text-white">
                                <div class="mb-4">
                                    <span
                                        class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">Devis
                                        gratuit en 24h</span>
                                </div>
                                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                                    Des experts à votre écoute
                                </h1>
                                <p class="text-lg md:text-xl mb-8 opacity-90">
                                    Une équipe qualifiée et passionnée pour donner vie à vos projets les plus ambitieux
                                </p>
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <a href="{{ route('contact') }}"
                                        class="flex items-center justify-center gap-2 px-8 py-4 bg-white text-purple-600 rounded-lg hover:bg-gray-100 font-bold text-lg">
                                        <span class="material-icons">phone</span>
                                        <span>Contactez-nous</span>
                                    </a>
                                    <a href="tel:{{ $parametre->telephone ?? '' }}"
                                        class="flex items-center justify-center gap-2 px-8 py-4 border-2 border-white text-white rounded-lg hover:bg-white/10 font-bold text-lg">
                                        <span class="material-icons">call</span>
                                        <span>{{ $parametre->telephone ?? '+237 6 XX XX XX XX' }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel Controls -->
            <button onclick="previousSlide()"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-full flex items-center justify-center text-white transition-all">
                <span class="material-icons text-3xl">chevron_left</span>
            </button>
            <button onclick="nextSlide()"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-white/20 backdrop-blur-sm hover:bg-white/30 rounded-full flex items-center justify-center text-white transition-all">
                <span class="material-icons text-3xl">chevron_right</span>
            </button>

            <!-- Carousel Indicators -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex items-center gap-3">
                <button onclick="goToSlide(0)"
                    class="indicator w-3 h-3 rounded-full bg-white transition-all"></button>
                <button onclick="goToSlide(1)"
                    class="indicator w-3 h-3 rounded-full bg-white/50 transition-all"></button>
                <button onclick="goToSlide(2)"
                    class="indicator w-3 h-3 rounded-full bg-white/50 transition-all"></button>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="py-12 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center p-6 bg-white rounded-2xl shadow-sm">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-icons text-blue-600 text-3xl">schedule</span>
                        </div>
                        <div class="text-4xl md:text-5xl font-bold text-blue-600 mb-2">15+</div>
                        <p class="text-gray-600 font-medium">Années d'expérience</p>
                    </div>
                    <div class="text-center p-6 bg-white rounded-2xl shadow-sm">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-icons text-green-600 text-3xl">check_circle</span>
                        </div>
                        <div class="text-4xl md:text-5xl font-bold text-green-600 mb-2">500+</div>
                        <p class="text-gray-600 font-medium">Projets réalisés</p>
                    </div>
                    <div class="text-center p-6 bg-white rounded-2xl shadow-sm">
                        <div
                            class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-icons text-purple-600 text-3xl">people</span>
                        </div>
                        <div class="text-4xl md:text-5xl font-bold text-purple-600 mb-2">350+</div>
                        <p class="text-gray-600 font-medium">Clients satisfaits</p>
                    </div>
                    <div class="text-center p-6 bg-white rounded-2xl shadow-sm">
                        <div
                            class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-icons text-orange-600 text-3xl">star</span>
                        </div>
                        <div class="text-4xl md:text-5xl font-bold text-orange-600 mb-2">98%</div>
                        <p class="text-gray-600 font-medium">Taux de satisfaction</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="py-16 md:py-24">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12 md:mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Nos Services</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Une expertise complète pour tous vos projets de construction et rénovation
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @forelse($services as $service)
                        <div class="card-hover bg-white rounded-2xl shadow-lg p-8">
                            @if ($service->image_path)
                                <div class="w-full h-40 rounded-xl overflow-hidden mb-6">
                                    <img src="{{ asset('storage/' . $service->image_path) }}"
                                        alt="{{ $service->nom }}" class="w-full h-full object-cover">
                                </div>
                            @elseif($service->icon)
                                <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                                    <span class="material-icons text-blue-600 text-3xl">{{ $service->icon }}</span>
                                </div>
                            @else
                                <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                                    <span class="material-icons text-blue-600 text-3xl">build</span>
                                </div>
                            @endif
                            <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $service->nom }}</h3>
                            <p class="text-gray-600 mb-4">
                                {{ $service->description_courte ?? Str::limit($service->description, 100) }}
                            </p>
                            <a href="{{ route('contact') }}"
                                class="text-blue-600 font-medium hover:text-blue-800 flex items-center gap-1">
                                <span>Demander un devis</span>
                                <span class="material-icons text-xl">arrow_forward</span>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <span class="material-icons text-gray-300 text-6xl mb-4">build</span>
                            <p class="text-gray-500 text-lg">Nos services seront bientôt disponibles</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Projets / Réalisations Section -->
        <section id="projets" class="py-16 md:py-24 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12 md:mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Nos Réalisations</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Découvrez quelques-uns de nos projets récents
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @forelse($projets as $projet)
                        @php
                            $photoPrincipale = $projet->photos->where('principale', true)->first()
                                ?? $projet->photos->first();
                            $coverUrl = $photoPrincipale
                                ? asset('storage/' . $photoPrincipale->path)
                                : ($projet->image_path ? asset('storage/' . $projet->image_path) : null);
                            // Toutes les photos pour la galerie (inclure image_path si pas de photos)
                            $allPhotos = $projet->photos->count() > 0
                                ? $projet->photos->map(fn($p) => ['url' => asset('storage/' . $p->path), 'legende' => $p->legende ?? $projet->titre])
                                : ($projet->image_path ? collect([['url' => asset('storage/' . $projet->image_path), 'legende' => $projet->titre]]) : collect([]));
                        @endphp
                        <div class="card-hover bg-white rounded-2xl shadow-lg overflow-hidden">
                            {{-- Image de couverture cliquable --}}
                            @if ($coverUrl)
                                <div class="h-52 overflow-hidden relative cursor-pointer"
                                     onclick="openProjectGallery({{ $projet->id }})">
                                    <img src="{{ $coverUrl }}"
                                        alt="{{ $projet->titre }}"
                                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    {{-- Overlay au survol --}}
                                    <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-30 transition flex items-center justify-center">
                                        <span class="material-icons text-white text-5xl opacity-0 hover:opacity-100 transition">zoom_in</span>
                                    </div>
                                    @if ($allPhotos->count() > 1)
                                        <span class="absolute bottom-2 right-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded-full flex items-center gap-1">
                                            <span class="material-icons text-xs">photo_library</span>
                                            {{ $allPhotos->count() }} photos
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div class="h-52 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                    <span class="material-icons text-blue-400 text-6xl">photo_library</span>
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center gap-2 mb-3">
                                    @if ($projet->categorie)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">{{ ucfirst($projet->categorie) }}</span>
                                    @endif
                                    @if ($projet->annee)
                                        <span class="text-sm text-gray-500">{{ $projet->annee }}</span>
                                    @endif
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $projet->titre }}</h3>
                                @if ($projet->client_nom || $projet->lieu)
                                    <p class="text-gray-500 text-sm mb-3 flex items-center gap-1">
                                        <span class="material-icons text-base">location_on</span>
                                        {{ implode(' — ', array_filter([$projet->client_nom, $projet->lieu])) }}
                                    </p>
                                @endif
                                @if ($projet->description)
                                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($projet->description, 100) }}</p>
                                @endif
                                @if ($projet->duree || $projet->superficie)
                                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                                        @if ($projet->duree)
                                            <span class="flex items-center gap-1">
                                                <span class="material-icons text-base">schedule</span>
                                                {{ $projet->duree }}
                                            </span>
                                        @endif
                                        @if ($projet->superficie)
                                            <span class="flex items-center gap-1">
                                                <span class="material-icons text-base">square_foot</span>
                                                {{ $projet->superficie }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                {{-- Bouton voir les photos --}}
                                @if ($allPhotos->count() > 0)
                                    <button onclick="openProjectGallery({{ $projet->id }})"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 font-medium text-sm transition border border-blue-200">
                                        <span class="material-icons text-base">photo_library</span>
                                        Voir {{ $allPhotos->count() > 1 ? 'les ' . $allPhotos->count() . ' photos' : 'la photo' }}
                                    </button>
                                @endif
                            </div>

                            {{-- Données photos encodées pour JS --}}
                            <script type="application/json" id="proj-photos-{{ $projet->id }}">
                                [
                                    @foreach ($allPhotos as $p)
                                        {"url": "{{ $p['url'] }}", "legende": "{{ addslashes($p['legende']) }}"}{{ $loop->last ? '' : ',' }}
                                    @endforeach
                                ]
                            </script>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <span class="material-icons text-gray-300 text-6xl mb-4">photo_library</span>
                            <p class="text-gray-500 text-lg">Nos réalisations seront bientôt disponibles</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- ══════════ LIGHTBOX GALERIE PROJETS ══════════ --}}
        <div id="proj-lightbox" class="fixed inset-0 bg-black bg-opacity-95 z-50 hidden flex-col items-center justify-center p-4"
             onclick="closeProjLightbox(event)">
            <button onclick="closeProjLightbox()" class="absolute top-4 right-4 text-white text-3xl z-10 hover:text-gray-300 w-10 h-10 flex items-center justify-center">
                <span class="material-icons text-3xl">close</span>
            </button>
            <p id="proj-lb-title" class="text-white text-sm mb-3 opacity-70 text-center max-w-lg"></p>
            <div class="relative flex items-center justify-center w-full max-w-4xl px-12">
                <button onclick="projPrev()" class="absolute left-0 text-white text-4xl hover:text-gray-300 p-2 z-10">
                    <span class="material-icons text-4xl">chevron_left</span>
                </button>
                <img id="proj-lb-img" src="" alt=""
                     class="max-h-[70vh] max-w-full rounded-lg shadow-2xl object-contain"
                     onclick="event.stopPropagation()">
                <button onclick="projNext()" class="absolute right-0 text-white text-4xl hover:text-gray-300 p-2 z-10">
                    <span class="material-icons text-4xl">chevron_right</span>
                </button>
            </div>
            <p id="proj-lb-counter" class="text-white text-sm mt-3 opacity-60"></p>
            <div id="proj-lb-thumbs" class="flex gap-2 mt-4 overflow-x-auto max-w-2xl px-4 pb-2"></div>
        </div>

        <!-- À propos Section -->
        <section id="apropos" class="py-16 md:py-24">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12 md:mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">À propos de nous</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        {{ $parametre->apropos_mission ?? 'Découvrez notre entreprise et nos valeurs' }}
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center max-w-6xl mx-auto">
                    <!-- Image -->
                    <div>
                        @if ($parametre->apropos_image_path)
                            <img src="{{ asset('storage/' . $parametre->apropos_image_path) }}" alt="À propos"
                                class="w-full rounded-2xl shadow-lg">
                        @else
                            <div
                                class="w-full h-80 bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl flex items-center justify-center">
                                <span class="material-icons text-blue-400 text-8xl">business</span>
                            </div>
                        @endif
                    </div>

                    <!-- Texte -->
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">
                            {{ $parametre->nom_entreprise ?? 'Notre entreprise' }}</h3>
                        @if ($parametre->apropos_texte)
                            <p class="text-gray-600 mb-6 leading-relaxed">{!! nl2br(e($parametre->apropos_texte)) !!}</p>
                        @else
                            <p class="text-gray-600 mb-6 leading-relaxed">{{ $parametre->slogan ?? '' }}</p>
                        @endif

                        @if ($parametre->apropos_vision)
                            <div class="mb-4 p-4 bg-blue-50 rounded-xl">
                                <h4 class="font-bold text-blue-700 mb-1">Notre vision</h4>
                                <p class="text-gray-600 text-sm">{{ $parametre->apropos_vision }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4 mt-6">
                            @if ($parametre->apropos_annee_creation)
                                <div class="text-center p-4 bg-gray-50 rounded-xl">
                                    <div class="text-2xl font-bold text-blue-600">
                                        {{ date('Y') - $parametre->apropos_annee_creation }}+</div>
                                    <p class="text-sm text-gray-500">Années d'expérience</p>
                                </div>
                            @endif
                            @if ($parametre->apropos_nombre_employes)
                                <div class="text-center p-4 bg-gray-50 rounded-xl">
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ $parametre->apropos_nombre_employes }}+</div>
                                    <p class="text-sm text-gray-500">Employés</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 md:py-24 bg-gradient-to-r from-blue-600 to-blue-700">
            <div class="container mx-auto px-4 text-center text-white">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Prêt à démarrer votre projet ?</h2>
                <p class="text-lg md:text-xl mb-8 opacity-90 max-w-2xl mx-auto">
                    Obtenez un devis gratuit et personnalisé en moins de 24 heures
                </p>
                <a href="{{ route('contact') }}"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-white text-blue-600 rounded-lg hover:bg-gray-100 font-bold text-lg">
                    <span class="material-icons">request_quote</span>
                    <span>Demander un devis gratuit</span>
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white pt-12 pb-6">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            @if ($parametre->logo_path)
                                <img src="{{ asset('storage/' . $parametre->logo_path) }}" alt="Logo"
                                    class="w-10 h-10 rounded-lg object-contain">
                            @else
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                    <span class="material-icons text-white">receipt_long</span>
                                </div>
                            @endif
                            <h3 class="text-xl font-bold">{{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</h3>
                        </div>
                        <p class="text-gray-400 mb-4">
                            {{ $parametre->slogan ?? '' }}
                        </p>
                    </div>

                    <div>
                        <h4 class="font-bold mb-4">Liens rapides</h4>
                        <ul class="space-y-2">
                            <li><a href="#services" class="text-gray-400 hover:text-white">Services</a></li>
                            <li><a href="#projets" class="text-gray-400 hover:text-white">Projets</a></li>
                            <li><a href="#apropos" class="text-gray-400 hover:text-white">À propos</a></li>
                            <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white">Contact</a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-bold mb-4">Nos services</h4>
                        <ul class="space-y-2">
                            @forelse($services->take(4) as $service)
                                <li><a href="#services"
                                        class="text-gray-400 hover:text-white">{{ $service->nom }}</a></li>
                            @empty
                                <li class="text-gray-400">Bientôt disponible</li>
                            @endforelse
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-bold mb-4">Contact</h4>
                        <ul class="space-y-3">
                            @if ($parametre->adresse || $parametre->ville)
                                <li class="flex items-start gap-2 text-gray-400">
                                    <span class="material-icons text-xl">location_on</span>
                                    <span>{{ implode(', ', array_filter([$parametre->ville, $parametre->pays])) }}</span>
                                </li>
                            @endif
                            @if ($parametre->telephone)
                                <li class="flex items-start gap-2 text-gray-400">
                                    <span class="material-icons text-xl">phone</span>
                                    <span>{{ $parametre->telephone }}</span>
                                </li>
                            @endif
                            @if ($parametre->email)
                                <li class="flex items-start gap-2 text-gray-400">
                                    <span class="material-icons text-xl">email</span>
                                    <span>{{ $parametre->email }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-800 pt-6 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} {{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}. Tous droits
                        réservés.</p>
                </div>
            </div>
        </footer>
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.carousel-item');
        const indicators = document.querySelectorAll('.indicator');
        let autoplayInterval;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('active');
                indicators[i].classList.remove('bg-white');
                indicators[i].classList.add('bg-white/50');
            });

            slides[index].classList.add('active');
            indicators[index].classList.add('bg-white');
            indicators[index].classList.remove('bg-white/50');
            currentSlide = index;
        }

        function nextSlide() {
            let next = (currentSlide + 1) % slides.length;
            showSlide(next);
            resetAutoplay();
        }

        function previousSlide() {
            let prev = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(prev);
            resetAutoplay();
        }

        function goToSlide(index) {
            showSlide(index);
            resetAutoplay();
        }

        function startAutoplay() {
            autoplayInterval = setInterval(nextSlide, 5000);
        }

        function resetAutoplay() {
            clearInterval(autoplayInterval);
            startAutoplay();
        }

        // Start autoplay on load
        startAutoplay();

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // ── LIGHTBOX GALERIE PROJETS ──────────────────────────────────────
        var projPhotos  = [];
        var projCurrent = 0;

        function openProjectGallery(projetId) {
            var dataEl = document.getElementById('proj-photos-' + projetId);
            if (!dataEl) return;
            try {
                projPhotos  = JSON.parse(dataEl.textContent.trim());
                if (!projPhotos || projPhotos.length === 0) return;
                projCurrent = 0;
                showProjPhoto(0);
                var lb = document.getElementById('proj-lightbox');
                lb.classList.remove('hidden');
                lb.classList.add('flex');
                document.body.style.overflow = 'hidden';
            } catch(e) { console.error('Galerie erreur:', e); }
        }

        function showProjPhoto(index) {
            if (index < 0) index = projPhotos.length - 1;
            if (index >= projPhotos.length) index = 0;
            projCurrent = index;

            var photo = projPhotos[index];
            document.getElementById('proj-lb-img').src              = photo.url;
            document.getElementById('proj-lb-img').alt              = photo.legende || '';
            document.getElementById('proj-lb-title').textContent    = photo.legende || '';
            document.getElementById('proj-lb-counter').textContent  = (index + 1) + ' / ' + projPhotos.length;

            // Miniatures
            var thumbsEl = document.getElementById('proj-lb-thumbs');
            thumbsEl.innerHTML = '';
            projPhotos.forEach(function(p, i) {
                var img = document.createElement('img');
                img.src       = p.url;
                img.className = 'h-14 w-20 object-cover rounded cursor-pointer border-2 flex-shrink-0 transition ' +
                    (i === index ? 'border-white opacity-100' : 'border-transparent opacity-50 hover:opacity-80');
                img.onclick = function(e) { e.stopPropagation(); showProjPhoto(i); };
                thumbsEl.appendChild(img);
            });

            // Scroll miniature active
            var activeThumb = thumbsEl.children[index];
            if (activeThumb) activeThumb.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        }

        function projPrev() { showProjPhoto(projCurrent - 1); }
        function projNext() { showProjPhoto(projCurrent + 1); }

        function closeProjLightbox(event) {
            // Fermer seulement si clic sur le fond noir (pas sur l'image ou les boutons)
            if (event && event.target !== document.getElementById('proj-lightbox')) return;
            var lb = document.getElementById('proj-lightbox');
            lb.classList.add('hidden');
            lb.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Navigation clavier
        document.addEventListener('keydown', function(e) {
            var lb = document.getElementById('proj-lightbox');
            if (!lb || lb.classList.contains('hidden')) return;
            if (e.key === 'ArrowLeft')  projPrev();
            if (e.key === 'ArrowRight') projNext();
            if (e.key === 'Escape') {
                lb.classList.add('hidden');
                lb.classList.remove('flex');
                document.body.style.overflow = '';
            }
        });
    </script>
</body>

</html>
