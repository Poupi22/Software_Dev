<nav class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-logo">
            <span class="brand-logo-icon">🏗️</span>
            <span class="brand-text">SOFT</span>
        </a>
    </div>
    
    <ul class="sidebar-nav">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span class="nav-text">Tableau de Bord</span>
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('admin.home_slides.index') }}" class="nav-link {{ request()->routeIs('admin.home_slides*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <i class="fas fa-home"></i>
                </div>
                <span class="nav-text">Slides Accueil</span>
            </a>
        </li>

        <li class="nav-item">
    <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
        <div class="nav-icon">
            <i class="fas fa-concierge-bell"></i> <!-- or choose another Font Awesome icon -->
        </div>
        <span class="nav-text">Services</span>
    </a>
</li>
        
        <!-- Other menu items with # for now -->
       <li class="nav-item">
    <a href="{{ route('admin.abouts.index') }}" class="nav-link {{ request()->routeIs('admin.abouts*') ? 'active' : '' }}">
        <div class="nav-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <span class="nav-text">Section À Propos</span>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.home-services.index') }}" class="nav-link {{ request()->routeIs('admin.home-services*') ? 'active' : '' }}">
        <div class="nav-icon">
            <i class="fas fa-home"></i>
        </div>
        <span class="nav-text">Services à Domicile</span>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.projects.index') }}" class="nav-link {{ request()->routeIs('admin.projects*') ? 'active' : '' }}">
        <div class="nav-icon">
            <i class="fas fa-project-diagram"></i>
        </div>
        <span class="nav-text">Nos Projets</span>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials*') ? 'active' : '' }}">
        <div class="nav-icon">
            <i class="fas fa-quote-left"></i>
        </div>
        <span class="nav-text">Témoignages</span>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.trainings.index') }}" class="nav-link {{ request()->routeIs('admin.trainings*') ? 'active' : '' }}">
        <div class="nav-icon">
            <i class="fas fa-quote-left"></i>
        </div>
        <span class="nav-text">Formations</span>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('admin.partners.index') }}" class="nav-link {{ request()->routeIs('admin.partners*') ? 'active' : ''}}">
        <div class="nav-icon">
            <i class="fas fa-handshake"></i>
        </div>
        <span class="nav-text">Partenaires</span>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->routeIs('admin.contacts*') ? 'active' : ''}}">
        <div class="nav-icon">
            <i class="fas fa-envelope"></i>
        </div>
        <span class="nav-text">Contacts</span>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin.personnels.index') }}" class="nav-link {{ request()->routeIs('admin.personnels*') ? 'active' : ''}}">
        <div class="nav-icon">
            <i class="fas fa-users"></i>
        </div>
        <span class="nav-text">Équipe de Direction</span>
    </a>
</li>
{{-- Permissions --}}
<li class="nav-item">
    <a href="{{ route('admin.permissions.index') }}" 
       class="nav-link {{ request()->routeIs('admin.permissions*') ? 'active' : ''}}">
        <div class="nav-icon">
            <i class="fas fa-key"></i>
        </div>
        <span class="nav-text">Permissions</span>
    </a>
</li>

{{-- Roles --}}
<li class="nav-item">
    <a href="{{ route('admin.roles.index') }}" 
       class="nav-link {{ request()->routeIs('admin.roles*') ? 'active' : ''}}">
        <div class="nav-icon">
            <i class="fas fa-user-shield"></i>
        </div>
        <span class="nav-text">Roles</span>
    </a>
</li>



        
        <!-- Add other menu items as needed -->
    </ul>
</nav>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>