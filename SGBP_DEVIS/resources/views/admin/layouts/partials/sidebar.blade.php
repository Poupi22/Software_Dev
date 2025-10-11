<!-- Navigation Desktop (Sidebar) -->
<div class="hidden md:fixed md:inset-y-0 md:flex md:w-64 md:flex-col">
    <div class="flex flex-col flex-grow bg-white border-r border-gray-200 overflow-y-auto">
        <!-- Logo -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-200">
            @if ($parametre->logo_path)
                <img src="{{ asset('storage/' . $parametre->logo_path) }}" alt="Logo"
                    class="w-10 h-10 rounded-lg object-contain">
            @else
                <div class="flex items-center justify-center w-10 h-10 bg-blue-600 rounded-lg">
                    <span class="material-icons text-white">receipt_long</span>
                </div>
            @endif
            <div>
                <h1 class="text-lg font-bold text-gray-800">{{ $parametre->nom_entreprise ?? 'Gestion Devis' }}</h1>
                <p class="text-xs text-gray-500">{{ $parametre->slogan ?? 'v1.0' }}</p>
            </div>
        </div>

        <!-- Menu -->
        <nav class="flex-1 px-3 py-4 space-y-1">
            <!-- Dashboard -->
            @can('dashboard.view')
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <span class="material-icons text-xl">dashboard</span>
                    <span>Tableau de bord</span>
                </a>
            @endcan

            <!-- Users (avec permission) -->
            @can('users.read')
                <a href="{{ route('admin.users.index') }}"
                    class="nav-item {{ request()->routeIs('admin.users.*') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <span class="material-icons text-xl">group</span>
                    <span>Utilisateurs</span>
                </a>
            @endcan

            <!-- Roles (Super Admin uniquement) -->
            @can('roles.manage')
                <a href="{{ route('admin.roles.index') }}"
                    class="nav-item {{ request()->routeIs('admin.roles.*') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <span class="material-icons text-xl">shield</span>
                    <span>Rôles & Permissions</span>
                </a>
            @endcan
            <!-- Permissions -->
            @can('permissions.manage')
                <a href="{{ route('admin.permissions.index') }}"
                    class="nav-item {{ request()->routeIs('admin.permissions.*') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <span class="material-icons text-xl">lock</span>
                    <span>Permissions</span>
                </a>
            @endcan

            <!-- Divider -->
            <div class="border-t border-gray-200 my-2"></div>

            <!-- Devis -->
            @can('devis.read')
                <a href="{{ route('admin.devis.index') }}"
                    class="nav-item {{ request()->routeIs('admin.devis.*') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <span class="material-icons text-xl">description</span>
                    <span>Devis</span>
                </a>
            @endcan

            <!-- Factures -->
            @can('factures.read')
                <a href="{{ route('admin.factures.index') }}"
                    class="nav-item {{ request()->routeIs('admin.factures.*') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <span class="material-icons text-xl">receipt_long</span>
                    <span>Factures</span>
                </a>
            @endcan

            <!-- PV -->
            @can('pvs.read')
                <a href="{{ route('admin.pvs.index') }}"
                    class="nav-item {{ request()->routeIs('admin.pvs.*') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <span class="material-icons text-xl">task_alt</span>
                    <span>PV Réception</span>
                </a>
            @endcan

            <!-- Divider -->
            <div class="border-t border-gray-200 my-2"></div>

            <!-- Clients -->
            @can('clients.read')
                <a href="{{ route('admin.clients.index') }}"
                    class="nav-item {{ request()->routeIs('admin.clients.*') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <span class="material-icons text-xl">people</span>
                    <span>Clients</span>
                </a>
            @endcan

            <!-- Articles -->
            @can('articles.read')
                <a href="{{ route('admin.articles.index') }}"
                    class="nav-item {{ request()->routeIs('admin.articles.*') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <span class="material-icons text-xl">inventory_2</span>
                    <span>Articles</span>
                </a>
            @endcan

            <!-- Categories -->
            @can('categories.read')
                <a href="{{ route('admin.categories.index') }}"
                    class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <span class="material-icons text-xl">category</span>
                    <span>Catégories</span>
                </a>
            @endcan

            <!-- Divider -->
            <div class="border-t border-gray-200 my-2"></div>

            <!-- Prospects -->
            @can('prospects.read')
                <a href="{{ route('admin.prospects.index') }}"
                    class="nav-item {{ request()->routeIs('admin.prospects.*') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <span class="material-icons text-xl">contact_page</span>
                    <span>Prospects</span>
                </a>
            @endcan

            <!-- Divider -->
            <div class="border-t border-gray-200 my-2"></div>

            <!-- Site Vitrine -->
            <p class="px-3 py-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Site vitrine</p>

            @can('services.read')
                <a href="{{ route('admin.services.index') }}"
                    class="nav-item {{ request()->routeIs('admin.services.*') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <span class="material-icons text-xl">build</span>
                    <span>Services</span>
                </a>
            @endcan

            @can('projets.read')
                <a href="{{ route('admin.projets.index') }}"
                    class="nav-item {{ request()->routeIs('admin.projets.*') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <span class="material-icons text-xl">photo_library</span>
                    <span>Projets</span>
                </a>
            @endcan

            {{-- <!-- Notifications -->
            <a href="{{ route('admin.notifications.index') }}" class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition relative">
                <span class="material-icons text-xl">notifications</span>
                <span>Notifications</span>
                @php
                    $unreadCount = auth()->user()->notifications()->where('lu', false)->count();
                @endphp
                @if ($unreadCount > 0)
                    <span class="absolute top-2 right-2 flex items-center justify-center w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </a> --}}

            <!-- Divider -->
            <div class="border-t border-gray-200 my-2"></div>

            <!-- Paramètres -->
            @can('settings.view')
                <a href="{{ route('admin.parametres.index') }}"
                    class="nav-item {{ request()->routeIs('admin.parametres.*') ? 'active bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-50' }} flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition">
                    <span class="material-icons text-xl">settings</span>
                    <span>Paramètres</span>
                </a>
            @endcan
        </nav>

        <!-- User section -->
        <div class="px-3 py-4 border-t border-gray-200">
            <div class="flex items-center gap-3 px-3 py-2">
                <div
                    class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full shadow-md">
                    @if (auth()->user()->avatar)
                        <img src="{{ asset(auth()->user()->avatar) }}" alt="Avatar"
                            class="w-full h-full rounded-full object-cover">
                    @else
                        <span class="material-icons text-white">person</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ auth()->user()->nom_complet }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Déconnexion">
                        <span class="material-icons">logout</span>
                    </button>
                </form>
            </div>

            <!-- Role Badge -->
            <div class="px-3 mt-2">
                @foreach (auth()->user()->roles as $role)
                    <span
                        class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                        <span
                            class="material-icons text-xs">{{ $role->name === 'super-admin' ? 'shield' : 'badge' }}</span>
                        {{ ucfirst($role->name) }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Navigation Mobile (Bottom Bar) -->
<div class="mobile-nav md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
    <div class="grid grid-cols-5 gap-1 px-2 py-2">
        <a href="{{ route('admin.dashboard') }}"
            class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-500' }} transition">
            <span class="material-icons text-2xl">dashboard</span>
            <span class="text-xs font-medium">Accueil</span>
        </a>

        @can('devis.read')
            <a href="{{ route('admin.devis.index') }}"
                class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('admin.devis.*') ? 'text-blue-600' : 'text-gray-500' }} transition">
                <span class="material-icons text-2xl">description</span>
                <span class="text-xs">Devis</span>
            </a>
        @else
            <a href="{{ route('admin.clients.index') }}"
                class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('admin.clients.*') ? 'text-blue-600' : 'text-gray-500' }} transition">
                <span class="material-icons text-2xl">people</span>
                <span class="text-xs">Clients</span>
            </a>
        @endcan

        @can('factures.read')
            <a href="{{ route('admin.factures.index') }}"
                class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('admin.factures.*') ? 'text-blue-600' : 'text-gray-500' }} transition">
                <span class="material-icons text-2xl">receipt_long</span>
                <span class="text-xs">Factures</span>
            </a>
        @else
            <a href="{{ route('admin.articles.index') }}"
                class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('admin.articles.*') ? 'text-blue-600' : 'text-gray-500' }} transition">
                <span class="material-icons text-2xl">inventory_2</span>
                <span class="text-xs">Articles</span>
            </a>
        @endcan

        {{-- <!-- Notifications avec badge -->
        <a href="{{ route('admin.notifications.index') }}" class="flex flex-col items-center gap-1 py-2 {{ request()->routeIs('admin.notifications.*') ? 'text-blue-600' : 'text-gray-500' }} transition relative">
            <span class="material-icons text-2xl">notifications</span>
            <span class="text-xs">Notifs</span>
            @php
                $unreadCount = auth()->user()->notifications()->where('lu', false)->count();
            @endphp
            @if ($unreadCount > 0)
                <span class="absolute top-0 right-4 flex items-center justify-center w-4 h-4 bg-red-500 text-white text-xs font-bold rounded-full">
                    {{ $unreadCount > 9 ? '9' : $unreadCount }}
                </span>
            @endif
        </a> --}}

        <!-- Menu burger -->
        <button onclick="toggleMenu()" class="flex flex-col items-center gap-1 py-2 text-gray-500 transition">
            <span class="material-icons text-2xl">menu</span>
            <span class="text-xs">Menu</span>
        </button>
    </div>
</div>

<!-- Menu mobile overlay -->
<div id="mobileMenu" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50" onclick="toggleMenu()">
    <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl p-6 max-h-[80vh] overflow-y-auto"
        onclick="event.stopPropagation()">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Menu</h2>
            <button onclick="toggleMenu()" class="text-gray-400 hover:text-gray-600">
                <span class="material-icons">close</span>
            </button>
        </div>

        <!-- User Info Mobile -->
        <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl mb-4">
            <div
                class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full shadow-md">
                <span class="material-icons text-white">person</span>
            </div>
            <div class="flex-1">
                <p class="font-bold text-gray-800">{{ auth()->user()->nom_complet }}</p>
                <p class="text-xs text-gray-600">{{ auth()->user()->email }}</p>
                <div class="flex gap-1 mt-1">
                    @foreach (auth()->user()->roles as $role)
                        <span class="px-2 py-0.5 bg-blue-600 text-white text-xs rounded-full">
                            {{ ucfirst($role->name) }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <nav class="space-y-2">
            <!-- Dashboard -->
            @can('dashboard.view')
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} transition">
                    <span class="material-icons">dashboard</span>
                    <span class="font-medium">Tableau de bord</span>
                </a>
            @endcan

            <!-- Users -->
            @can('users.read')
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} transition">
                    <span class="material-icons">group</span>
                    <span>Utilisateurs</span>
                </a>
            @endcan

            <!-- Roles -->
            @can('roles.manage')
                <a href="{{ route('admin.roles.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.roles.*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} transition">
                    <span class="material-icons">shield</span>
                    <span>Rôles</span>
                </a>
            @endcan

            <div class="border-t border-gray-200 my-2"></div>

            <!-- Devis -->
            @can('devis.read')
                <a href="{{ route('admin.devis.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.devis.*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} transition">
                    <span class="material-icons">description</span>
                    <span>Devis</span>
                </a>
            @endcan

            <!-- Factures -->
            @can('factures.read')
                <a href="{{ route('admin.factures.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.factures.*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} transition">
                    <span class="material-icons">receipt_long</span>
                    <span>Factures</span>
                </a>
            @endcan

            <!-- PV -->
            @can('pvs.read')
                <a href="{{ route('admin.pvs.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.pvs.*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} transition">
                    <span class="material-icons">task_alt</span>
                    <span>PV Réception</span>
                </a>
            @endcan

            <div class="border-t border-gray-200 my-2"></div>

            <!-- Clients -->
            @can('clients.read')
                <a href="{{ route('admin.clients.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.clients.*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} transition">
                    <span class="material-icons">people</span>
                    <span>Clients</span>
                </a>
            @endcan

            <!-- Articles -->
            @can('articles.read')
                <a href="{{ route('admin.articles.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.articles.*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} transition">
                    <span class="material-icons">inventory_2</span>
                    <span>Articles</span>
                </a>
            @endcan

            <!-- Categories -->
            @can('categories.read')
                <a href="{{ route('admin.categories.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} transition">
                    <span class="material-icons">category</span>
                    <span>Catégories</span>
                </a>
            @endcan

            <!-- Prospects -->
            @can('prospects.read')
                <a href="{{ route('admin.prospects.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.prospects.*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} transition">
                    <span class="material-icons">contact_page</span>
                    <span>Prospects</span>
                </a>
            @endcan

            <div class="border-t border-gray-200 my-2"></div>

            <!-- Services -->
            @can('services.read')
                <a href="{{ route('admin.services.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.services.*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} transition">
                    <span class="material-icons">build</span>
                    <span>Services</span>
                </a>
            @endcan

            <!-- Projets -->
            @can('projets.read')
                <a href="{{ route('admin.projets.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.projets.*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} transition">
                    <span class="material-icons">photo_library</span>
                    <span>Projets</span>
                </a>
            @endcan

            <div class="border-t border-gray-200 my-2"></div>

            <!-- Parametres -->
            @can('settings.view')
                <a href="{{ route('admin.parametres.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.parametres.*') ? 'bg-blue-50 text-blue-600' : 'hover:bg-gray-50 text-gray-700' }} transition">
                    <span class="material-icons">settings</span>
                    <span>Paramètres</span>
                </a>
            @endcan

            <!-- Divider -->
            <div class="border-t border-gray-200 my-2"></div>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-red-50 text-red-600 transition">
                    <span class="material-icons">logout</span>
                    <span class="font-medium">Déconnexion</span>
                </button>
            </form>
        </nav>
    </div>
</div>

<script>
    function toggleMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }
</script>

<style>
    .mobile-nav {
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    }
</style>
