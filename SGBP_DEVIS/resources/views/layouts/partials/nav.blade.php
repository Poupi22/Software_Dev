<nav class="fixed top-0 left-0 right-0 bg-white shadow-md z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16 md:h-20">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3">
                @if ($parametre->logo_path)
                    <img src="{{ asset('storage/' . $parametre->logo_path) }}" alt="Logo"
                        class="w-10 h-10 md:w-12 md:h-12 rounded-lg object-contain">
                @else
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                        <span class="material-icons text-white text-2xl">receipt_long</span>
                    </div>
                @endif
                <div>
                    <h1 class="text-lg md:text-xl font-bold text-gray-800">
                        {{ $parametre->nom_entreprise ?? 'Mon Entreprise' }}</h1>
                    <p class="text-xs text-gray-500 hidden md:block">{{ $parametre->slogan ?? '' }}</p>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-8">
                <a href="/" class="text-gray-600 hover:text-blue-600 font-medium {{ request()->is('/') ? 'text-blue-600' : '' }}">Accueil</a>
                <a href="/#services" class="text-gray-600 hover:text-blue-600 font-medium">Services</a>
                <a href="/#projets" class="text-gray-600 hover:text-blue-600 font-medium">Projets</a>
                <a href="/#apropos" class="text-gray-600 hover:text-blue-600 font-medium">À propos</a>
                <a href="{{ route('contact') }}" class="font-medium {{ request()->is('contact') ? 'text-blue-600' : 'text-gray-600 hover:text-blue-600' }}">Contact</a>
            </div>

            <!-- CTA Desktop -->
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('contact') }}"
                    class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    <span class="material-icons">request_quote</span>
                    <span>Demander un devis</span>
                </a>
                <a href="{{ route('login') }}"
                    class="flex items-center gap-2 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 font-medium">
                    <span class="material-icons">login</span>
                    <span>Connexion</span>
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button onclick="toggleMobileMenu()" class="md:hidden p-2 text-gray-600" id="mobile-menu-btn">
                <span class="material-icons text-3xl">menu</span>
            </button>
        </div>
    </div>
</nav>

<!-- Mobile Menu — position fixe mais CACHÉ par défaut via classe hidden -->
<div id="mobileMenu" class="hidden fixed inset-0 bg-white z-40 md:hidden overflow-y-auto">
    <div class="flex flex-col min-h-full">
        <div class="flex items-center justify-between p-4 border-b sticky top-0 bg-white">
            <h2 class="text-xl font-bold text-gray-800">Menu</h2>
            <button onclick="toggleMobileMenu()" class="p-2 text-gray-600">
                <span class="material-icons text-3xl">close</span>
            </button>
        </div>
        <nav class="flex-1 p-6 space-y-2">
            <a href="/" onclick="closeMobileMenu()"
                class="flex items-center gap-3 p-4 {{ request()->is('/') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} rounded-lg font-medium">
                <span class="material-icons">home</span>
                <span>Accueil</span>
            </a>
            <a href="/#services" onclick="closeMobileMenu()"
                class="flex items-center gap-3 p-4 hover:bg-gray-50 text-gray-700 rounded-lg">
                <span class="material-icons">build</span>
                <span>Services</span>
            </a>
            <a href="/#projets" onclick="closeMobileMenu()"
                class="flex items-center gap-3 p-4 hover:bg-gray-50 text-gray-700 rounded-lg">
                <span class="material-icons">photo_library</span>
                <span>Projets</span>
            </a>
            <a href="/#apropos" onclick="closeMobileMenu()"
                class="flex items-center gap-3 p-4 hover:bg-gray-50 text-gray-700 rounded-lg">
                <span class="material-icons">info</span>
                <span>À propos</span>
            </a>
            <a href="{{ route('contact') }}" onclick="closeMobileMenu()"
                class="flex items-center gap-3 p-4 {{ request()->is('contact') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} rounded-lg">
                <span class="material-icons">contact_mail</span>
                <span>Contact</span>
            </a>
        </nav>
        <div class="p-6 border-t space-y-3">
            <a href="{{ route('contact') }}" onclick="closeMobileMenu()"
                class="flex items-center justify-center gap-2 w-full px-6 py-4 bg-blue-600 text-white rounded-lg font-medium">
                <span class="material-icons">request_quote</span>
                <span>Demander un devis</span>
            </a>
            <a href="{{ route('login') }}"
                class="flex items-center justify-center gap-2 w-full px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                <span class="material-icons">login</span>
                <span>Connexion</span>
            </a>
        </div>
    </div>
</div>

<script>
    function toggleMobileMenu() {
        var menu = document.getElementById('mobileMenu');
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            menu.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function closeMobileMenu() {
        var menu = document.getElementById('mobileMenu');
        menu.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Fermer le menu si on clique en dehors
    document.addEventListener('click', function(e) {
        var menu = document.getElementById('mobileMenu');
        var btn   = document.getElementById('mobile-menu-btn');
        if (!menu.classList.contains('hidden') && !menu.contains(e.target) && !btn.contains(e.target)) {
            closeMobileMenu();
        }
    });
</script>
