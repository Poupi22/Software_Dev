@extends('admin_site.layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
    <style>
        /* Main Content Styling */
        .main-content {
            margin-left: 250px;
            /* Adjust based on your sidebar width */
            transition: all 0.3s;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        /* Enhanced Card Styling */
        .dashboard-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            position: relative;
            background: white;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
            z-index: 1;
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--card-accent) 0%, rgba(255, 255, 255, 0) 100%);
            z-index: 2;
        }

        .dashboard-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }

        .dashboard-card .card-body {
            position: relative;
            z-index: 3;
            padding: 2rem 1.5rem;
            background: white;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1.25rem;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--card-accent) 0%, var(--card-accent-dark) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .dashboard-card:hover .card-icon {
            transform: scale(1.1);
        }

        .card-title {
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #2d3748;
            transition: all 0.3s ease;
        }

        .dashboard-card:hover .card-title {
            color: var(--card-accent);
        }

        .card-text {
            color: #718096;
            margin-bottom: 1.5rem;
            flex-grow: 1;
        }

        .dashboard-card .btn {
            align-self: flex-start;
            border-radius: 50px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            background: linear-gradient(135deg, var(--card-accent) 0%, var(--card-accent-dark) 100%);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .dashboard-card .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--card-accent-dark) 0%, var(--card-accent) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .dashboard-card .btn:hover::before {
            opacity: 1;
        }

        .dashboard-card .btn i {
            transition: transform 0.3s ease;
        }

        .dashboard-card .btn:hover i {
            transform: translateX(3px);
        }

        /* Card accent colors */
        .bg-primary {
            --card-accent: #3b82f6;
            --card-accent-dark: #2563eb;
        }

        .bg-info {
            --card-accent: #06b6d4;
            --card-accent-dark: #0891b2;
        }

        .bg-success {
            --card-accent: #10b981;
            --card-accent-dark: #059669;
        }

        .bg-warning {
            --card-accent: #f59e0b;
            --card-accent-dark: #d97706;
        }

        .bg-danger {
            --card-accent: #ef4444;
            --card-accent-dark: #dc2626;
        }

        .bg-secondary {
            --card-accent: #64748b;
            --card-accent-dark: #475569;
        }

        .bg-dark {
            --card-accent: #4b5563;
            --card-accent-dark: #374151;
        }

        .bg-purple {
            --card-accent: #8b5cf6;
            --card-accent-dark: #7c3aed;
        }

        /* Section Titles */
        .section-title {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Responsive Adjustments */
        @media (max-width: 1199.98px) {
            .main-content {
                margin-left: 0;
                padding-top: 70px;
                /* For fixed header */
            }
        }

        @media (max-width: 767.98px) {
            .dashboard-card {
                margin-bottom: 20px;
            }

            .section-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 575.98px) {
            .card-icon {
                font-size: 1.8rem;
            }

            .dashboard-card .btn {
                padding: 0.4rem 1rem;
                font-size: 0.85rem;
            }
        }
    </style>

    <div class="main-content" id="mainContent">
        <div class="container-fluid py-4 py-md-5 px-3 px-md-4">

            <!-- Dashboard Cards -->
            <div class="row g-4">

                <!-- Home Card -->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="dashboard-card card bg-primary">
                        <div class="card-body text-center p-4">
                            <div class="card-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <h5 class="card-title fw-semibold mb-2">Page d'Accueil</h5>
                            <p class="card-text text-muted small mb-3">Gérez le contenu de votre page d'accueil</p>
                            <a href="{{ route('dashboard.accueil.index') }}" class="btn btn-primary btn-sm stretched-link">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Actualités Card -->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="dashboard-card card bg-info">
                        <div class="card-body text-center p-4">
                            <div class="card-icon">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <h5 class="card-title fw-semibold mb-2">Actualités</h5>
                            <p class="card-text text-muted small mb-3">Gérez les articles et publications</p>
                            <a href="{{ route('dashboard.actualite.index') }}" class="btn btn-info btn-sm stretched-link">
                                <i class="fas fa-list me-1"></i> Gérer
                            </a>
                        </div>
                    </div>
                </div>

                <!-- À Propos Card -->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="dashboard-card card bg-success">
                        <div class="card-body text-center p-4">
                            <div class="card-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h5 class="card-title fw-semibold mb-2">À Propos</h5>
                            <p class="card-text text-muted small mb-3">Modifiez la page "À propos de nous"</p>
                            <a href="{{ route('dashboard.about.index') }}" class="btn btn-success btn-sm stretched-link">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Card -->
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="dashboard-card card bg-warning">
                        <div class="card-body text-center p-4">
                            <div class="card-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h5 class="card-title fw-semibold mb-2">Contact</h5>
                            <p class="card-text text-muted small mb-3">Gérez les informations de contact</p>
                            <a href="{{ route('dashboard.contact.index') }}" class="btn btn-warning btn-sm stretched-link">
                                <i class="fas fa-cog me-1"></i> Configurer
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Quick Stats Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <h4 class="section-title">Statistiques Rapides</h4>
                </div>

                <div class="container mb-4">

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-header">Utilisateurs Actifs</div>
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <h3 id="activeUsers" class="display-4">--</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-header">Sessions</div>
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <h3 id="sessionsCount" class="display-4">--</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card text-center h-100">
                                <div class="card-header">Pages Vues</div>
                                <div class="card-body d-flex align-items-center justify-content-center">
                                    <h3 id="viewsCount" class="display-4">--</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Évolution des Utilisateurs</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="usersChart"></canvas>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Pages les plus populaires</h5>
                        </div>
                        <div class="card-body">
                            <ul id="mostVisitedPages" class="list-group list-group-flush">
                                <!-- Les pages seront injectées via JS, exemple: /about, /contact -->
                            </ul>
                        </div>
                    </div>

                </div>





                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                    <i class="fas fa-newspaper text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Actualités</h6>
                                    <h3 class="mb-0">24</h3>
                                    <small class="text-success"><i class="fas fa-arrow-up me-1"></i> 12% ce mois</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                                    <i class="fas fa-envelope text-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Messages</h6>
                                    <h3 class="mb-0">56</h3>
                                    <small class="text-danger"><i class="fas fa-arrow-down me-1"></i> 5% cette
                                        semaine</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                                    <i class="fas fa-users text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Utilisateurs actifs</h6>
                                    <h3 id="activeUsersCard" class="mb-0">--</h3>
                                    <small class="text-success">
                                        <i class="fas fa-arrow-up me-1"></i>
                                        <span id="activeUsersChange">--</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                                    <i class="fas fa-image text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0">Médias</h6>
                                    <h3 class="mb-0">347</h3>
                                    <small class="text-success"><i class="fas fa-arrow-up me-1"></i> 23% ce mois</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const activeUsers = document.getElementById('activeUsers');
                const sessionsCount = document.getElementById('sessionsCount');
                const viewsCount = document.getElementById('viewsCount');
                const mostVisitedPages = document.getElementById('mostVisitedPages');
                const usersChartCanvas = document.getElementById('usersChart').getContext('2d');
                let usersChartInstance;

                function loadAnalyticsData(period = '30d') {
                    fetch("{{ route('dashboard.analytics_data') }}?period=" + period)
                        .then(res => res.json())
                        .then(data => {
                            if (data.error) {
                                alert(data.error);
                                return;
                            }

                            activeUsers.innerText = data.totals.activeUsers;
                            sessionsCount.innerText = data.totals.sessions;
                            viewsCount.innerText = data.totals.screenPageViews;

                            // Graphique évolution utilisateurs
                            if (usersChartInstance) usersChartInstance.destroy();
                            usersChartInstance = new Chart(usersChartCanvas, {
                                type: 'line',
                                data: {
                                    labels: data.usersByDate.map(d => d.date),
                                    datasets: [{
                                        label: 'Utilisateurs',
                                        data: data.usersByDate.map(d => d.totalUsers),
                                        borderColor: '#3b82f6',
                                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                        fill: true,
                                        tension: 0.3
                                    }]
                                }
                            });

                            // Pages les plus visitées
                            mostVisitedPages.innerHTML = '';
                            data.mostVisitedPages.forEach(p => {
                                const li = document.createElement('li');
                                li.classList.add('list-group-item');
                                li.innerText = `${p.pagePath} - ${p.views} vues`;
                                mostVisitedPages.appendChild(li);
                            });
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Erreur lors de la récupération des statistiques.');
                        });
                }

                loadAnalyticsData();
            });
        </script>
    @endpush

    @push('scripts')
        <script>
            // Initialize tooltips
            document.addEventListener('DOMContentLoaded', function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>
    @endpush
@endsection
