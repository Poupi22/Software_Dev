<nav class="navbar" role="navigation" aria-label="Main navigation">
  <div class="desktop-menu">
    <!-- Dashboard Dropdown -->
    <div class="dropdown-container">
      <button class="nav-link dropdown-trigger" onclick="toggleDashboardDropdown()" id="dashboardBtn">
        Dashboard
        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      <div id="dashboardDropdown" class="dropdown-menu dashboard-dropdown">
        <a href="{{ route('dashboard.index') }}" class="dropdown-item" role="menuitem">
          <i class="fas fa-globe-americas mr-2"></i>
          Website Dashboard
        </a>
        <a href="{{ route('etudiant.index') }}" class="dropdown-item" role="menuitem">
          <i class="fas fa-laptop-code mr-2"></i>
          E-Learning Dashboard
        </a>
        <a href="{{ route('dashboard1.index') }}" class="dropdown-item" role="menuitem">
          <i class="fas fa-user-shield mr-2"></i>
          Roles & Permissions
        </a>
      </div>
    </div>
  </div>

  <div class="flex items-center gap-2">
    <button class="search-button" aria-label="Open search" onclick="toggleSearchModal()">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
      </svg>
    </button>

    <button class="theme-toggle" aria-label="Toggle dark mode" onclick="toggleTheme()">
      <svg id="theme-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
      </svg>
    </button>

    <div class="profile-container">
      <button class="profile-button" onclick="toggleDropdown()" id="profileBtn">
        <div class="profile-avatar" aria-hidden="true">
          {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <span>{{ Auth::user()->name }}</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      <div id="dropdownMenu" class="dropdown-menu">
        <a href="{{ route('profile.edit') }}" class="dropdown-item" role="menuitem">Profile</a>
        <div class="border-t my-1"></div>
        <form method="POST" action="{{ route('logout') }}" x-data>
          @csrf
          <button type="submit" class="dropdown-item w-full text-left">
            Logout
          </button>
        </form>
      </div>
    </div>

    <button class="mobile-menu-button" aria-label="Toggle menu" aria-expanded="false" aria-controls="mobileMenu" onclick="toggleMobileMenu()">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>
</nav>

<div id="mobileMenu" class="mobile-menu" role="menu" aria-label="Mobile navigation">
  <!-- Mobile Dashboard Dropdown -->
  <div class="mobile-dropdown-container">
    <button class="mobile-nav-link mobile-dropdown-trigger" onclick="toggleMobileDashboardDropdown()">
      Dashboard
      <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>
    <div id="mobileDashboardDropdown" class="mobile-dropdown-menu">
      <a href="{{ route('dashboard.index') }}" class="mobile-dropdown-item" role="menuitem">
        <i class="fas fa-globe-americas mr-2"></i>
        Website Dashboard
      </a>
      <a href="{{ route('etudiant.index') }}" class="mobile-dropdown-item" role="menuitem">
        <i class="fas fa-laptop-code mr-2"></i>
        E-Learning Dashboard
      </a>
      <a href="{{ route('dashboard1.index') }}" class="mobile-dropdown-item" role="menuitem">
        <i class="fas fa-user-shield mr-2"></i>
        Roles & Permissions
      </a>
    </div>
  </div>

  <a href="#" class="mobile-nav-link" role="menuitem" tabindex="0">Users</a>
  <a href="/analytics" class="mobile-nav-link" role="menuitem" tabindex="0">Analytics</a>
  <a href="/reports" class="mobile-nav-link" role="menuitem" tabindex="0">Reports</a>
</div>

<!-- Search Modal -->
<div id="searchModal" class="search-modal" role="dialog" aria-modal="true" aria-labelledby="searchLabel">
  <div class="search-container">
    <label id="searchLabel" for="searchInput" class="sr-only">Search</label>
    <input id="searchInput" type="text" class="search-input" placeholder="Search..." />
    <button class="search-button mt-2" onclick="toggleSearchModal()">Close</button>
  </div>
</div>

<style>
  :root {
    --primary: #1e3a8a;
    --primary-hover: #1e40af;
    --muted: #4b5563;
    --light: #f1f5f9;
    --background: #f8fafc;
    --text: #1f2937;
    --border: #e2e8f0;
  }

  [data-theme="dark"] {
    --primary: #3b82f6;
    --primary-hover: #60a5fa;
    --muted: #d1d5db;
    --light: #374151;
    --background: #1f2937;
    --text: #f3f4f6;
    --border: #4b5563;
  }

  /* Navbar */
  .navbar {
    background-color: var(--background);
    border-bottom: 1px solid var(--border);
    position: fixed;
    top: 0;
    left: 260px;
    width: calc(100% - 260px);
    height: 87px;
    z-index: 60;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    padding: 0 1rem;
    justify-content: space-between;
  }

  /* Desktop menu */
  .desktop-menu {
    display: flex;
    gap: 1.5rem;
    align-items: center;
  }

  .nav-link {
    color: var(--muted);
    font-weight: 500;
    text-decoration: none;
    transition: color 0.2s ease;
    display: flex;
    align-items: center;
    background: none;
    border: none;
    cursor: pointer;
    font-size: inherit;
    font-family: inherit;
  }

  .nav-link:hover {
    color: var(--primary);
  }

  /* Dropdown container */
  .dropdown-container {
    position: relative;
  }

  .dropdown-trigger {
    display: flex;
    align-items: center;
  }

  .dashboard-dropdown {
    width: 220px;
    left: 0;
    right: auto;
  }

  .dropdown-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: var(--muted);
    text-decoration: none;
    font-size: 0.875rem;
    transition: background-color 0.2s ease, color 0.2s ease;
    user-select: none;
  }

  .dropdown-item:hover,
  .dropdown-item:focus {
    background-color: var(--light);
    color: var(--primary);
    outline: none;
  }

  .dropdown-item i {
    width: 16px;
    text-align: center;
  }

  /* Profile container */
  .profile-container {
    position: relative;
  }

  .profile-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary);
    font-weight: 600;
    background: none;
    border: none;
    cursor: pointer;
    user-select: none;
  }

  .profile-avatar {
    width: 2.5rem;
    height: 2.5rem;
    background-color: var(--primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    font-weight: 700;
    user-select: none;
  }

  /* Dropdown menu */
  .dropdown-menu {
    position: absolute;
    right: 0;
    top: 100%;
    margin-top: 0.5rem;
    width: 12rem;
    background-color: var(--background);
    border: 1px solid var(--border);
    border-radius: 0.375rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    opacity: 0;
    transform: translateY(-10px);
    transition: opacity 0.2s ease, transform 0.2s ease;
    pointer-events: none;
    z-index: 100;
  }

  .dropdown-menu.show {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
  }

  /* Mobile menu button */
  .mobile-menu-button {
    background: none;
    border: none;
    color: var(--muted);
    cursor: pointer;
    display: none;
    user-select: none;
  }

  /* Mobile menu */
  .mobile-menu {
    background-color: var(--background);
    border-top: 1px solid var(--border);
    padding: 1rem;
    display: none;
    position: fixed;
    top: 87px;
    left: 0;
    width: 100%;
    z-index: 50;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .mobile-menu.show {
    display: block;
  }

  .mobile-nav-link {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    color: var(--muted);
    text-decoration: none;
    transition: color 0.2s ease;
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    font-size: inherit;
    font-family: inherit;
    cursor: pointer;
  }

  .mobile-nav-link:hover {
    color: var(--primary);
  }

  /* Mobile dropdown */
  .mobile-dropdown-container {
    position: relative;
  }

  .mobile-dropdown-trigger {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .mobile-dropdown-menu {
    background-color: var(--light);
    border-radius: 0.375rem;
    margin: 0.5rem 0;
    padding: 0.5rem;
    display: none;
  }

  .mobile-dropdown-menu.show {
    display: block;
  }

  .mobile-dropdown-item {
    display: flex;
    align-items: center;
    padding: 0.5rem 0.75rem;
    color: var(--muted);
    text-decoration: none;
    font-size: 0.875rem;
    transition: color 0.2s ease;
    border-radius: 0.25rem;
  }

  .mobile-dropdown-item:hover {
    color: var(--primary);
    background-color: rgba(59, 130, 246, 0.1);
  }

  .mobile-dropdown-item i {
    width: 16px;
    text-align: center;
    margin-right: 0.5rem;
  }

  /* Theme toggle and search */
  .theme-toggle,
  .search-button {
    background: none;
    border: none;
    color: var(--muted);
    cursor: pointer;
    padding: 0.5rem;
    user-select: none;
  }

  /* Search modal */
  .search-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 70;
  }

  .search-modal.show {
    display: flex;
  }

  .search-container {
    background: var(--background);
    padding: 1rem;
    border-radius: 0.5rem;
    width: 90%;
    max-width: 500px;
  }

  .search-input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid var(--border);
    border-radius: 0.375rem;
    background: var(--light);
    color: var(--text);
    font-size: 1rem;
  }

  /* Responsive */
  @media (max-width: 1024px) {
    .navbar {
      left: 0;
      width: 100%;
    }
  }

  @media (max-width: 768px) {
    .desktop-menu {
      display: none;
    }
    .mobile-menu-button {
      display: block;
    }
    .profile-button span {
      display: none;
    }
  }

  @media (max-width: 768px) {
    .dropdown-menu {
      position: fixed;
      top: 87px;
      right: 1rem;
      z-index: 1000;
    }
  }
</style>

<script>
  // Dashboard dropdown toggle
  function toggleDashboardDropdown() {
    const dropdown = document.getElementById('dashboardDropdown');
    const dashboardBtn = document.getElementById('dashboardBtn');
    const expanded = dashboardBtn.getAttribute('aria-expanded') === 'true';
    dashboardBtn.setAttribute('aria-expanded', !expanded);
    dropdown.classList.toggle('show');
  }

  // Mobile dashboard dropdown toggle
  function toggleMobileDashboardDropdown() {
    const dropdown = document.getElementById('mobileDashboardDropdown');
    dropdown.classList.toggle('show');
  }

  // Profile dropdown toggle
  function toggleDropdown() {
    const dropdown = document.getElementById('dropdownMenu');
    const profileBtn = document.getElementById('profileBtn');
    const expanded = profileBtn.getAttribute('aria-expanded') === 'true';
    profileBtn.setAttribute('aria-expanded', !expanded);
    dropdown.classList.toggle('show');
  }

  // Mobile menu toggle
  function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobileMenu');
    const btn = document.querySelector('.mobile-menu-button');
    const expanded = btn.getAttribute('aria-expanded') === 'true';
    btn.setAttribute('aria-expanded', !expanded);
    mobileMenu.classList.toggle('show');
  }

  // Search modal toggle
  function toggleSearchModal() {
    const searchModal = document.getElementById('searchModal');
    const isShown = searchModal.classList.contains('show');
    searchModal.classList.toggle('show');

    if (!isShown) {
      document.getElementById('searchInput').focus();
    }
  }

  // Dark mode toggle
  function toggleTheme() {
    const html = document.documentElement;
    const themeIcon = document.getElementById('theme-icon');
    const isDark = html.getAttribute('data-theme') === 'dark';
    html.setAttribute('data-theme', isDark ? 'light' : 'dark');

    // Update icon path for sun/moon
    themeIcon.setAttribute(
      'd',
      isDark
        ? 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'
        : 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z'
    );
    localStorage.setItem('theme', isDark ? 'light' : 'dark');
  }

  // Load theme from localStorage
  document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    const themeIcon = document.getElementById('theme-icon');
    if (savedTheme === 'dark') {
      themeIcon.setAttribute('d', 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z');
    }

    // Initialize dropdown buttons
    document.getElementById('dashboardBtn').setAttribute('aria-expanded', 'false');
  });

  // Close dropdowns when clicking outside
  document.addEventListener('click', (e) => {
    // Close dashboard dropdown
    const dashboardDropdown = document.getElementById('dashboardDropdown');
    const dashboardBtn = document.getElementById('dashboardBtn');
    if (!dashboardDropdown.contains(e.target) && !dashboardBtn.contains(e.target)) {
      dashboardDropdown.classList.remove('show');
      dashboardBtn.setAttribute('aria-expanded', 'false');
    }

    // Close profile dropdown
    const profileDropdown = document.getElementById('dropdownMenu');
    const profileButton = document.getElementById('profileBtn');
    if (!profileDropdown.contains(e.target) && !profileButton.contains(e.target)) {
      profileDropdown.classList.remove('show');
      profileButton.setAttribute('aria-expanded', 'false');
    }

    // Close mobile dashboard dropdown
    const mobileDashboardDropdown = document.getElementById('mobileDashboardDropdown');
    const mobileDashboardBtn = document.querySelector('.mobile-dropdown-trigger');
    if (!mobileDashboardDropdown.contains(e.target) && !mobileDashboardBtn.contains(e.target)) {
      mobileDashboardDropdown.classList.remove('show');
    }
  });

  // Close mobile menu when clicking on a link
  document.addEventListener('click', (e) => {
    if (e.target.classList.contains('mobile-dropdown-item')) {
      const mobileMenu = document.getElementById('mobileMenu');
      const mobileBtn = document.querySelector('.mobile-menu-button');
      mobileMenu.classList.remove('show');
      mobileBtn.setAttribute('aria-expanded', 'false');
    }
  });
</script>