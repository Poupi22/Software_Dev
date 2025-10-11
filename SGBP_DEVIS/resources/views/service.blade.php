<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Services - {{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #1E40AF 0%, #3B82F6 50%, #60A5FA 100%);
        }

        .service-card {
            transition: all 0.3s ease;
        }

        .service-card:hover {
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
                    <a href="/" class="text-gray-600 hover:text-blue-600 font-medium">Accueil</a>
                    <a href="#" class="text-blue-600 font-medium">Services</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 font-medium">Projets</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 font-medium">À propos</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 font-medium">Contact</a>
                </div>

                <a href="#contact"
                    class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    <span class="material-icons">request_quote</span>
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
                    <img src="{{ asset('storage/' . $parametre->logo_path) }}" alt="Logo"
                        class="w-10 h-10 rounded-lg object-contain">
                @else
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <span class="material-icons text-white">receipt_long</span>
                    </div>
                @endif
                <h1 class="text-lg font-bold text-gray-800">{{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</h1>
            </div>
            <a href="#contact" class="p-2 bg-blue-600 text-white rounded-lg">
                <span class="material-icons">request_quote</span>
            </a>
        </div>
    </div>

    <!-- Mobile Navigation Bottom -->
    <div class="mobile-nav md:hidden">
        <div class="grid grid-cols-5 gap-1 px-2 py-2">
            <a href="/" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <span class="material-icons text-2xl">home</span>
                <span class="text-xs">Accueil</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 py-2 text-blue-600">
                <span class="material-icons text-2xl">build</span>
                <span class="text-xs font-medium">Services</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <span class="material-icons text-2xl">photo_library</span>
                <span class="text-xs">Projets</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <span class="material-icons text-2xl">info</span>
                <span class="text-xs">À propos</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <span class="material-icons text-2xl">contact_mail</span>
                <span class="text-xs">Contact</span>
            </a>
        </div>
    </div>

    <div class="content-with-mobile-nav md:pt-20">
        <!-- Hero Section -->
        <section class="hero-gradient py-16 md:py-20">
            <div class="container mx-auto px-4 text-center text-white">
                <h1 class="text-3xl md:text-5xl font-bold mb-4">Nos Services</h1>
                <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto">
                    Une expertise complète pour tous vos projets de construction et rénovation
                </p>
            </div>
        </section>

        <!-- Services Section -->
        <section class="py-16 md:py-24">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Service 1: Maçonnerie -->
                    <div class="service-card bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="h-48 bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <span class="material-icons text-white text-8xl">foundation</span>
                        </div>
                        <div class="p-8">
                            <div
                                class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center mb-4 -mt-16 shadow-lg">
                                <span class="material-icons text-blue-600 text-3xl">foundation</span>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">Maçonnerie</h3>
                            <p class="text-gray-600 mb-4">
                                Nous réalisons tous vos travaux de maçonnerie générale : fondations, murs porteurs,
                                dalles, chapes, et gros œuvre complet.
                            </p>
                            <div class="mb-4">
                                <h4 class="font-bold text-gray-800 mb-2">Nos prestations :</h4>
                                <ul class="space-y-1 text-sm text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-blue-600 text-sm">check_circle</span>
                                        <span>Construction de murs et cloisons</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-blue-600 text-sm">check_circle</span>
                                        <span>Fondations et terrassements</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-blue-600 text-sm">check_circle</span>
                                        <span>Dalles et chapes</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-blue-600 text-sm">check_circle</span>
                                        <span>Rénovation de façades</span>
                                    </li>
                                </ul>
                            </div>
                            <a href="#contact"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium w-full justify-center">
                                <span class="material-icons">request_quote</span>
                                <span>Demander un devis</span>
                            </a>
                        </div>
                    </div>

                    <!-- Service 2: Plomberie -->
                    <div class="service-card bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div
                            class="h-48 bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                            <span class="material-icons text-white text-8xl">plumbing</span>
                        </div>
                        <div class="p-8">
                            <div
                                class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center mb-4 -mt-16 shadow-lg">
                                <span class="material-icons text-green-600 text-3xl">plumbing</span>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">Plomberie</h3>
                            <p class="text-gray-600 mb-4">
                                Installation complète de systèmes sanitaires, alimentation en eau, évacuations et
                                dépannage plomberie.
                            </p>
                            <div class="mb-4">
                                <h4 class="font-bold text-gray-800 mb-2">Nos prestations :</h4>
                                <ul class="space-y-1 text-sm text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-green-600 text-sm">check_circle</span>
                                        <span>Installation sanitaire complète</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-green-600 text-sm">check_circle</span>
                                        <span>Raccordement eau et évacuation</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-green-600 text-sm">check_circle</span>
                                        <span>Réparation et dépannage</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-green-600 text-sm">check_circle</span>
                                        <span>Mise aux normes</span>
                                    </li>
                                </ul>
                            </div>
                            <a href="#contact"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium w-full justify-center">
                                <span class="material-icons">request_quote</span>
                                <span>Demander un devis</span>
                            </a>
                        </div>
                    </div>

                    <!-- Service 3: Électricité -->
                    <div class="service-card bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div
                            class="h-48 bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center">
                            <span class="material-icons text-white text-8xl">electrical_services</span>
                        </div>
                        <div class="p-8">
                            <div
                                class="w-16 h-16 bg-yellow-100 rounded-xl flex items-center justify-center mb-4 -mt-16 shadow-lg">
                                <span class="material-icons text-yellow-600 text-3xl">electrical_services</span>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">Électricité</h3>
                            <p class="text-gray-600 mb-4">
                                Installation électrique, mise aux normes, tableaux électriques, domotique et solutions
                                d'éclairage.
                            </p>
                            <div class="mb-4">
                                <h4 class="font-bold text-gray-800 mb-2">Nos prestations :</h4>
                                <ul class="space-y-1 text-sm text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-yellow-600 text-sm">check_circle</span>
                                        <span>Installation électrique complète</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-yellow-600 text-sm">check_circle</span>
                                        <span>Tableaux et disjoncteurs</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-yellow-600 text-sm">check_circle</span>
                                        <span>Domotique et automatismes</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-yellow-600 text-sm">check_circle</span>
                                        <span>Éclairage LED économique</span>
                                    </li>
                                </ul>
                            </div>
                            <a href="#contact"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-medium w-full justify-center">
                                <span class="material-icons">request_quote</span>
                                <span>Demander un devis</span>
                            </a>
                        </div>
                    </div>

                    <!-- Service 4: Peinture -->
                    <div class="service-card bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div
                            class="h-48 bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                            <span class="material-icons text-white text-8xl">format_paint</span>
                        </div>
                        <div class="p-8">
                            <div
                                class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center mb-4 -mt-16 shadow-lg">
                                <span class="material-icons text-purple-600 text-3xl">format_paint</span>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">Peinture</h3>
                            <p class="text-gray-600 mb-4">
                                Peinture intérieure et extérieure, finitions décoratives, revêtements muraux et
                                traitement de façades.
                            </p>
                            <div class="mb-4">
                                <h4 class="font-bold text-gray-800 mb-2">Nos prestations :</h4>
                                <ul class="space-y-1 text-sm text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-purple-600 text-sm">check_circle</span>
                                        <span>Peinture intérieure et extérieure</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-purple-600 text-sm">check_circle</span>
                                        <span>Enduits et crépis</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-purple-600 text-sm">check_circle</span>
                                        <span>Papiers peints et revêtements</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-purple-600 text-sm">check_circle</span>
                                        <span>Finitions décoratives</span>
                                    </li>
                                </ul>
                            </div>
                            <a href="#contact"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium w-full justify-center">
                                <span class="material-icons">request_quote</span>
                                <span>Demander un devis</span>
                            </a>
                        </div>
                    </div>

                    <!-- Service 5: Menuiserie -->
                    <div class="service-card bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="h-48 bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center">
                            <span class="material-icons text-white text-8xl">carpenter</span>
                        </div>
                        <div class="p-8">
                            <div
                                class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center mb-4 -mt-16 shadow-lg">
                                <span class="material-icons text-red-600 text-3xl">carpenter</span>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">Menuiserie</h3>
                            <p class="text-gray-600 mb-4">
                                Pose de portes, fenêtres, placards sur mesure et aménagements en bois et matériaux
                                composites.
                            </p>
                            <div class="mb-4">
                                <h4 class="font-bold text-gray-800 mb-2">Nos prestations :</h4>
                                <ul class="space-y-1 text-sm text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-red-600 text-sm">check_circle</span>
                                        <span>Portes et fenêtres</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-red-600 text-sm">check_circle</span>
                                        <span>Placards et dressings</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-red-600 text-sm">check_circle</span>
                                        <span>Parquets et revêtements sol</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-red-600 text-sm">check_circle</span>
                                        <span>Aménagements sur mesure</span>
                                    </li>
                                </ul>
                            </div>
                            <a href="#contact"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium w-full justify-center">
                                <span class="material-icons">request_quote</span>
                                <span>Demander un devis</span>
                            </a>
                        </div>
                    </div>

                    <!-- Service 6: Rénovation -->
                    <div class="service-card bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div
                            class="h-48 bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
                            <span class="material-icons text-white text-8xl">engineering</span>
                        </div>
                        <div class="p-8">
                            <div
                                class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center mb-4 -mt-16 shadow-lg">
                                <span class="material-icons text-orange-600 text-3xl">engineering</span>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">Rénovation Complète</h3>
                            <p class="text-gray-600 mb-4">
                                Projets de rénovation globale avec coordination de tous les corps de métier pour
                                transformer votre espace.
                            </p>
                            <div class="mb-4">
                                <h4 class="font-bold text-gray-800 mb-2">Nos prestations :</h4>
                                <ul class="space-y-1 text-sm text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-orange-600 text-sm">check_circle</span>
                                        <span>Coordination multi-métiers</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-orange-600 text-sm">check_circle</span>
                                        <span>Rénovation appartements/maisons</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-orange-600 text-sm">check_circle</span>
                                        <span>Rénovation locaux commerciaux</span>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span class="material-icons text-orange-600 text-sm">check_circle</span>
                                        <span>Extensions et surélévations</span>
                                    </li>
                                </ul>
                            </div>
                            <a href="#contact"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium w-full justify-center">
                                <span class="material-icons">request_quote</span>
                                <span>Demander un devis</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 md:py-24 bg-gradient-to-r from-blue-600 to-blue-700">
            <div class="container mx-auto px-4 text-center text-white">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Un projet en tête ?</h2>
                <p class="text-lg md:text-xl mb-8 opacity-90 max-w-2xl mx-auto">
                    Contactez-nous pour un devis gratuit et personnalisé
                </p>
                <a href="#contact"
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
                            <li><a href="/" class="text-gray-400 hover:text-white">Accueil</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Services</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Projets</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-bold mb-4">Nos services</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white">Maçonnerie</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Plomberie</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Électricité</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Rénovation</a></li>
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
</body>

</html>
