    <footer class="bg-gray-900 text-white pt-12 pb-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- Company Info -->
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

                <!-- Quick Links -->
                <div>
                    <h4 class="font-bold mb-4">Liens rapides</h4>
                    <ul class="space-y-2">
                        <li><a href="#services" class="text-gray-400 hover:text-white">Services</a></li>
                        <li><a href="#projets" class="text-gray-400 hover:text-white">Projets</a></li>
                        <li><a href="#apropos" class="text-gray-400 hover:text-white">À propos</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white">Contact</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="font-bold mb-4">Nos services</h4>
                    <ul class="space-y-2">
                        @forelse(\App\Models\Service::actifs()->take(4)->get() as $service)
                            <li><a href="{{ route('home') }}#services"
                                    class="text-gray-400 hover:text-white">{{ $service->nom }}</a></li>
                        @empty
                            <li class="text-gray-400">Bientôt disponible</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="font-bold mb-4">Contact</h4>
                    <ul class="space-y-3">
                        @if ($parametre->adresse || $parametre->ville)
                            <li class="flex items-start gap-2 text-gray-400">
                                <span class="material-icons text-xl">location_on</span>
                                <span>{{ implode(', ', array_filter([$parametre->adresse, $parametre->ville, $parametre->pays])) }}</span>
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
