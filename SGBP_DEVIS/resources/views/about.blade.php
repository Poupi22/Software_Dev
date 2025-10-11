<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À Propos - {{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</title>
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

        .value-card {
            transition: all 0.3s ease;
        }

        .value-card:hover {
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

        .timeline-line {
            position: relative;
        }

        .timeline-line::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #E5E7EB;
        }

        @media (max-width: 768px) {
            .timeline-line::before {
                left: 20px;
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
                    <a href="#" class="text-gray-600 hover:text-blue-600 font-medium">Services</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 font-medium">Projets</a>
                    <a href="#" class="text-blue-600 font-medium">À propos</a>
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
            <a href="#" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <span class="material-icons text-2xl">build</span>
                <span class="text-xs">Services</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <span class="material-icons text-2xl">photo_library</span>
                <span class="text-xs">Projets</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 py-2 text-blue-600">
                <span class="material-icons text-2xl">info</span>
                <span class="text-xs font-medium">À propos</span>
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
                <h1 class="text-3xl md:text-5xl font-bold mb-4">À Propos de Nous</h1>
                <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto">
                    Plus de 15 ans d'expertise au service de vos projets
                </p>
            </div>
        </section>

        <!-- Notre Histoire -->
        <section class="py-16 md:py-24">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Notre Histoire</h2>
                        <div class="w-24 h-1 bg-blue-600 mx-auto mb-6"></div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12">
                        <p class="text-lg text-gray-700 leading-relaxed mb-6">
                            Fondée en 2010, <strong>{{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</strong> est
                            née de la passion de ses fondateurs pour le bâtiment et leur volonté de proposer des
                            services de qualité à leurs clients au Cameroun.
                        </p>
                        <p class="text-lg text-gray-700 leading-relaxed mb-6">
                            Ce qui a commencé comme une petite équipe de 3 personnes s'est transformé au fil des années
                            en une entreprise reconnue, comptant aujourd'hui plus de 25 collaborateurs qualifiés et
                            passionnés.
                        </p>
                        <p class="text-lg text-gray-700 leading-relaxed">
                            Avec plus de <strong>500 projets réalisés</strong> et un taux de satisfaction de
                            <strong>98%</strong>, nous sommes fiers d'être devenus un acteur incontournable du BTP à
                            Douala et dans tout le Cameroun.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Timeline -->
        <section class="py-16 md:py-24 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Notre Évolution</h2>
                    <div class="w-24 h-1 bg-blue-600 mx-auto mb-6"></div>
                </div>

                <div class="max-w-4xl mx-auto timeline-line">
                    <!-- Timeline Item 1 -->
                    <div class="relative mb-12 md:mb-16">
                        <div class="md:grid md:grid-cols-2 md:gap-8">
                            <div class="md:text-right mb-4 md:mb-0">
                                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                                    <h3 class="text-2xl font-bold text-blue-600 mb-2">2010</h3>
                                    <h4 class="font-bold text-gray-800 mb-3">Création de l'entreprise</h4>
                                    <p class="text-gray-600">
                                        Début de l'aventure avec 3 collaborateurs et une vision : proposer des services
                                        de qualité dans le BTP.
                                    </p>
                                </div>
                            </div>
                            <div class="hidden md:block"></div>
                        </div>
                        <div
                            class="absolute left-5 md:left-1/2 top-0 w-10 h-10 bg-blue-600 rounded-full border-4 border-white shadow-lg flex items-center justify-center md:-ml-5">
                            <span class="material-icons text-white text-xl">star</span>
                        </div>
                    </div>

                    <!-- Timeline Item 2 -->
                    <div class="relative mb-12 md:mb-16">
                        <div class="md:grid md:grid-cols-2 md:gap-8">
                            <div class="hidden md:block"></div>
                            <div class="md:text-left mb-4 md:mb-0">
                                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                                    <h3 class="text-2xl font-bold text-green-600 mb-2">2015</h3>
                                    <h4 class="font-bold text-gray-800 mb-3">100ème projet réalisé</h4>
                                    <p class="text-gray-600">
                                        Franchissement d'une étape importante avec notre centième projet et expansion de
                                        l'équipe à 15 personnes.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="absolute left-5 md:left-1/2 top-0 w-10 h-10 bg-green-600 rounded-full border-4 border-white shadow-lg flex items-center justify-center md:-ml-5">
                            <span class="material-icons text-white text-xl">trending_up</span>
                        </div>
                    </div>

                    <!-- Timeline Item 3 -->
                    <div class="relative mb-12 md:mb-16">
                        <div class="md:grid md:grid-cols-2 md:gap-8">
                            <div class="md:text-right mb-4 md:mb-0">
                                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                                    <h3 class="text-2xl font-bold text-purple-600 mb-2">2020</h3>
                                    <h4 class="font-bold text-gray-800 mb-3">Extension de nos services</h4>
                                    <p class="text-gray-600">
                                        Diversification de nos activités et obtention de certifications qualité ISO
                                        9001.
                                    </p>
                                </div>
                            </div>
                            <div class="hidden md:block"></div>
                        </div>
                        <div
                            class="absolute left-5 md:left-1/2 top-0 w-10 h-10 bg-purple-600 rounded-full border-4 border-white shadow-lg flex items-center justify-center md:-ml-5">
                            <span class="material-icons text-white text-xl">verified</span>
                        </div>
                    </div>

                    <!-- Timeline Item 4 -->
                    <div class="relative">
                        <div class="md:grid md:grid-cols-2 md:gap-8">
                            <div class="hidden md:block"></div>
                            <div class="md:text-left">
                                <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                                    <h3 class="text-2xl font-bold text-orange-600 mb-2">2026</h3>
                                    <h4 class="font-bold text-gray-800 mb-3">Aujourd'hui</h4>
                                    <p class="text-gray-600">
                                        Plus de 500 projets, 25 collaborateurs et une ambition : devenir le leader
                                        régional du BTP.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="absolute left-5 md:left-1/2 top-0 w-10 h-10 bg-orange-600 rounded-full border-4 border-white shadow-lg flex items-center justify-center md:-ml-5">
                            <span class="material-icons text-white text-xl">rocket_launch</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission & Vision -->
        <section class="py-16 md:py-24">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto">
                    <div
                        class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl shadow-lg p-8 md:p-12 text-white">
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center mb-6">
                            <span class="material-icons text-white text-4xl">flag</span>
                        </div>
                        <h3 class="text-3xl font-bold mb-4">Notre Mission</h3>
                        <p class="text-lg opacity-90 leading-relaxed">
                            Accompagner nos clients dans la réalisation de leurs projets de construction et rénovation
                            en leur offrant des services de qualité, dans le respect des délais et du budget, tout en
                            garantissant leur satisfaction.
                        </p>
                    </div>

                    <div
                        class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl shadow-lg p-8 md:p-12 text-white">
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center mb-6">
                            <span class="material-icons text-white text-4xl">visibility</span>
                        </div>
                        <h3 class="text-3xl font-bold mb-4">Notre Vision</h3>
                        <p class="text-lg opacity-90 leading-relaxed">
                            Devenir la référence incontournable du BTP au Cameroun et en Afrique centrale, reconnue pour
                            son expertise, son innovation et son engagement envers l'excellence.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Nos Valeurs -->
        <section class="py-16 md:py-24 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Nos Valeurs</h2>
                    <div class="w-24 h-1 bg-blue-600 mx-auto mb-6"></div>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Les principes qui guident notre action au quotidien
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
                    <div class="value-card bg-white rounded-2xl shadow-lg p-8 text-center">
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-icons text-blue-600 text-4xl">verified_user</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Qualité</h3>
                        <p class="text-gray-600">
                            Nous nous engageons à fournir des prestations de la plus haute qualité sur tous nos
                            chantiers.
                        </p>
                    </div>

                    <div class="value-card bg-white rounded-2xl shadow-lg p-8 text-center">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-icons text-green-600 text-4xl">handshake</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Confiance</h3>
                        <p class="text-gray-600">
                            La transparence et l'honnêteté sont au cœur de nos relations avec nos clients et
                            partenaires.
                        </p>
                    </div>

                    <div class="value-card bg-white rounded-2xl shadow-lg p-8 text-center">
                        <div
                            class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-icons text-purple-600 text-4xl">lightbulb</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Innovation</h3>
                        <p class="text-gray-600">
                            Nous adoptons les techniques les plus modernes et innovantes du secteur.
                        </p>
                    </div>

                    <div class="value-card bg-white rounded-2xl shadow-lg p-8 text-center">
                        <div
                            class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-icons text-orange-600 text-4xl">favorite</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Passion</h3>
                        <p class="text-gray-600">
                            Notre équipe est animée par une véritable passion pour le métier et vos projets.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- L'Équipe -->
        <section class="py-16 md:py-24">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Notre Équipe</h2>
                    <div class="w-24 h-1 bg-blue-600 mx-auto mb-6"></div>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Des experts passionnés à votre service
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <!-- Team Member 1 -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="h-64 bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center">
                                <span class="material-icons text-blue-600 text-6xl">person</span>
                            </div>
                        </div>
                        <div class="p-6 text-center">
                            <h3 class="text-xl font-bold text-gray-800 mb-1">Jean Kamga</h3>
                            <p class="text-blue-600 font-medium mb-3">Directeur Général</p>
                            <p class="text-gray-600 text-sm">
                                15 ans d'expérience dans le BTP et la gestion de projets complexes.
                            </p>
                        </div>
                    </div>

                    <!-- Team Member 2 -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div
                            class="h-64 bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                            <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center">
                                <span class="material-icons text-green-600 text-6xl">person</span>
                            </div>
                        </div>
                        <div class="p-6 text-center">
                            <h3 class="text-xl font-bold text-gray-800 mb-1">Marie Nkomo</h3>
                            <p class="text-green-600 font-medium mb-3">Directrice Technique</p>
                            <p class="text-gray-600 text-sm">
                                Ingénieure en génie civil, experte en coordination de chantiers.
                            </p>
                        </div>
                    </div>

                    <!-- Team Member 3 -->
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div
                            class="h-64 bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                            <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center">
                                <span class="material-icons text-purple-600 text-6xl">person</span>
                            </div>
                        </div>
                        <div class="p-6 text-center">
                            <h3 class="text-xl font-bold text-gray-800 mb-1">Paul Assomo</h3>
                            <p class="text-purple-600 font-medium mb-3">Chef de Projet</p>
                            <p class="text-gray-600 text-sm">
                                Spécialiste en rénovation et suivi client avec 10 ans d'expérience.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 md:py-24 bg-gradient-to-r from-blue-600 to-blue-700">
            <div class="container mx-auto px-4 text-center text-white">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Rejoignez nos clients satisfaits</h2>
                <p class="text-lg md:text-xl mb-8 opacity-90 max-w-2xl mx-auto">
                    Faites confiance à notre expertise pour réaliser vos projets
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
