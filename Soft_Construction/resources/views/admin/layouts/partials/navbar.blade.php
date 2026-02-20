<header class="navbar">
    <div class="navbar-left">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="navbar-title">@yield('title', 'Tableau de Bord')</h1>
    </div>
    <div class="navbar-right">
        <div class="user-menu" id="userMenu">
            <div class="user-avatar">
                {{ Str::upper(Str::substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="dropdown-menu" id="dropdownMenu">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                    <i class="fas fa-user"></i>
                    <span>Mon Profil</span>
                </a>
                <!-- <a href="#" class="dropdown-item">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </a> -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" class="dropdown-item" 
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Déconnexion</span>
                    </a>
                </form>
            </div>
        </div>
    </div>
</header>