<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableaux de Bord - Institut 3iA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary-blue: #1e40af;
            --secondary-blue: #3b82f6;
            --light-blue: #dbeafe;
            --dark-blue: #1e3a8a;
            --accent-blue: #2563eb;
            --white: #ffffff;
            --light-gray: #f8fafc;
            --medium-gray: #e2e8f0;
            --dark-gray: #64748b;
            --text-dark: #1e293b;
            --text-light: #6b7280;
        }

        body {
            background: linear-gradient(135deg, var(--light-gray) 0%, var(--white) 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .dashboard-selection {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
            background: linear-gradient(135deg, var(--light-gray) 0%, var(--white) 100%);
        }

        .selection-container {
            max-width: 1400px;
            width: 100%;
            padding: 0 1.5rem;
        }

        .selection-header {
            text-align: center;
            margin-bottom: 3rem;
            color: var(--text-dark);
        }

        .selection-header h1 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .selection-header p {
            font-size: 1.3rem;
            color: var(--text-light);
            font-weight: 400;
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 2.5rem;
            margin-bottom: 3rem;
        }

        .dashboard-card {
            background: var(--white);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(30, 64, 175, 0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid var(--medium-gray);
            position: relative;
            overflow: hidden;
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            transition: all 0.3s ease;
        }

        .dashboard-card.website::before {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
        }

        .dashboard-card.elearning::before {
            background: linear-gradient(135deg, var(--secondary-blue) 0%, var(--accent-blue) 100%);
        }

        .dashboard-card.admin::before {
            background: linear-gradient(135deg, var(--dark-blue) 0%, var(--primary-blue) 100%);
        }

        .dashboard-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 50px rgba(30, 64, 175, 0.15);
            border-color: var(--secondary-blue);
        }

        .dashboard-card:hover::before {
            height: 8px;
        }

        .card-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 2.5rem;
            position: relative;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.2);
        }

        .dashboard-card.website .card-icon {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
        }

        .dashboard-card.elearning .card-icon {
            background: linear-gradient(135deg, var(--secondary-blue) 0%, var(--accent-blue) 100%);
        }

        .dashboard-card.admin .card-icon {
            background: linear-gradient(135deg, var(--dark-blue) 0%, var(--primary-blue) 100%);
        }

        .dashboard-card:hover .card-icon {
            transform: scale(1.1);
            box-shadow: 0 12px 30px rgba(30, 64, 175, 0.3);
        }

        .card-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-dark);
            position: relative;
        }

        .card-title::after {
            content: '';
            position: absolute;
            bottom: -0.5rem;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            border-radius: 2px;
        }

        .dashboard-card.website .card-title::after {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
        }

        .dashboard-card.elearning .card-title::after {
            background: linear-gradient(135deg, var(--secondary-blue) 0%, var(--accent-blue) 100%);
        }

        .dashboard-card.admin .card-title::after {
            background: linear-gradient(135deg, var(--dark-blue) 0%, var(--primary-blue) 100%);
        }

        .card-description {
            color: var(--text-light);
            line-height: 1.7;
            margin-bottom: 2.5rem;
            font-size: 1.1rem;
            font-weight: 400;
        }

        .card-features {
            text-align: left;
            margin-bottom: 2.5rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            color: var(--text-dark);
            transition: all 0.3s ease;
            padding: 0.5rem;
            border-radius: 8px;
        }

        .feature-item:hover {
            transform: translateX(5px);
            background: var(--light-blue);
        }

        .feature-item i {
            margin-right: 1rem;
            font-size: 1rem;
            width: 20px;
            text-align: center;
            color: var(--primary-blue);
        }

        .dashboard-card.website .feature-item i {
            color: var(--primary-blue);
        }

        .dashboard-card.elearning .feature-item i {
            color: var(--secondary-blue);
        }

        .dashboard-card.admin .feature-item i {
            color: var(--dark-blue);
        }

        .btn-dashboard {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            color: var(--white);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            width: 100%;
            font-size: 1.1rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(30, 64, 175, 0.2);
        }

        .dashboard-card.website .btn-dashboard {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
        }

        .dashboard-card.elearning .btn-dashboard {
            background: linear-gradient(135deg, var(--secondary-blue) 0%, var(--accent-blue) 100%);
        }

        .dashboard-card.admin .btn-dashboard {
            background: linear-gradient(135deg, var(--dark-blue) 0%, var(--primary-blue) 100%);
        }

        .btn-dashboard:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(30, 64, 175, 0.3);
            color: var(--white);
        }

        .btn-dashboard i {
            margin-left: 0.75rem;
            transition: transform 0.3s ease;
        }

        .btn-dashboard:hover i {
            transform: translateX(8px);
        }

        .user-welcome {
            background: var(--white);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 3rem;
            text-align: center;
            color: var(--text-dark);
            border: 2px solid var(--medium-gray);
            box-shadow: 0 10px 30px rgba(30, 64, 175, 0.08);
        }

        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-blue) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 2.5rem;
            font-weight: bold;
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.2);
            border: 3px solid var(--white);
        }

        .user-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .user-role {
            color: var(--text-light);
            font-size: 1.1rem;
            font-weight: 400;
        }

        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-item {
            background: var(--light-blue);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            color: var(--text-dark);
            border: 1px solid var(--medium-gray);
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(30, 64, 175, 0.1);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--primary-blue);
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-light);
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .dashboard-cards {
                grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .dashboard-cards {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .dashboard-card {
                padding: 2.5rem 2rem;
            }
            
            .selection-header h1 {
                font-size: 2.2rem;
            }
            
            .selection-header p {
                font-size: 1.1rem;
            }
            
            .card-icon {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }
            
            .user-welcome {
                padding: 2rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-card {
                padding: 2rem 1.5rem;
            }
            
            .card-title {
                font-size: 1.5rem;
            }
            
            .selection-container {
                padding: 0 1rem;
            }
            
            .user-avatar {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-8px);
            }
        }

        .dashboard-card {
            animation: fadeInUp 0.8s ease-out;
        }

        .dashboard-card:nth-child(1) { animation-delay: 0.1s; }
        .dashboard-card:nth-child(2) { animation-delay: 0.2s; }
        .dashboard-card:nth-child(3) { animation-delay: 0.3s; }

        .user-avatar {
            animation: float 3s ease-in-out infinite;
        }

        .footer-links {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid var(--medium-gray);
        }

        .footer-link {
            color: var(--primary-blue);
            text-decoration: none;
            margin: 0 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }

        .footer-link:hover {
            color: var(--dark-blue);
            background: var(--light-blue);
            transform: translateY(-2px);
        }

        .footer-link i {
            margin-right: 0.5rem;
        }

        /* Background pattern */
        .background-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(59, 130, 246, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(30, 64, 175, 0.03) 0%, transparent 50%);
            z-index: -1;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <!-- Background Pattern -->
    <div class="background-pattern"></div>

    <div class="dashboard-selection">
        <div class="selection-container">
            <!-- User Welcome Section -->
            <div class="user-welcome animate__animated animate__fadeInDown">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="user-name">Bienvenue, {{ Auth::user()->name }}!</div>
                <div class="user-role">Administrateur Système</div>
                
                <!-- Quick Stats -->
                <div class="quick-stats mt-4">
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\User::role('etudiant')->count() }}</div>
                        <div class="stat-label">Étudiants</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\User::role('formateur')->count() }}</div>
                        <div class="stat-label">Formateurs</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ \App\Models\ProgrammeSession::where('statut', 'En cours')->count() }}</div>
                        <div class="stat-label">Sessions Actives</div>
                    </div>
                </div>
            </div>

            <!-- Header -->
            <div class="selection-header animate__animated animate__fadeIn">
                <h1>Centre de Contrôle Administratif</h1>
                <p>Sélectionnez l'interface de gestion adaptée à vos besoins</p>
            </div>

            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <!-- Website Dashboard Card -->
                <div class="dashboard-card website animate__animated animate__fadeInUp">
                    <div class="card-icon">
                        <i class="fas fa-globe-americas"></i>
                    </div>
                    <h3 class="card-title">Gestion du Site Web</h3>
                    <p class="card-description">
                        Interface complète pour la gestion du contenu public, des pages institutionnelles et de la communication externe.
                    </p>
                    <div class="card-features">
                        <div class="feature-item">
                            <i class="fas fa-newspaper"></i>
                            <span>Gestion des actualités et articles</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Organisation des événements</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-cog"></i>
                            <span>Configuration générale du site</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-images"></i>
                            <span>Gestion des médias et galeries</span>
                        </div>
                    </div>
                    <a href="{{ route('dashboard.index') }}" class="btn-dashboard">
                        <i class="fas fa-external-link-alt"></i>
                        Accéder au Portail Web
                    </a>
                </div>

                <!-- E-learning Dashboard Card -->
                <div class="dashboard-card elearning animate__animated animate__fadeInUp">
                    <div class="card-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3 class="card-title">Plateforme E-Learning</h3>
                    <p class="card-description">
                        Environnement d'apprentissage numérique avec gestion pédagogique complète, évaluations et suivi des progrès.
                    </p>
                    <div class="card-features">
                        <div class="feature-item">
                            <i class="fas fa-book-open"></i>
                            <span>Gestion des cours et leçons</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-chart-line"></i>
                            <span>Analytics et statistiques détaillées</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-tasks"></i>
                            <span>Évaluations et système de quiz</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-users"></i>
                            <span>Suivi individuel des apprenants</span>
                        </div>
                    </div>
                    <a href="{{ route('etudiant.profile') }}" class="btn-dashboard">
                        <i class="fas fa-graduation-cap"></i>
                        Accéder à la Plateforme Éducative
                    </a>
                </div>

                <!-- Admin Dashboard Card -->
                <div class="dashboard-card admin animate__animated animate__fadeInUp">
                    <div class="card-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h3 class="card-title">Administration Système</h3>
                    <p class="card-description">
                        Panneau de contrôle avancé pour la gestion des utilisateurs, permissions et paramètres techniques du système.
                    </p>
                    <div class="card-features">
                        <div class="feature-item">
                            <i class="fas fa-user-cog"></i>
                            <span>Gestion des Forums pour étudiants</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-key"></i>
                            <span>Contrôle des rôles et permissions</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-database"></i>
                            <span>Maintenance et sauvegarde</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Sécurité et audit du système</span>
                        </div>
                    </div>
                    <a href="{{ route('dashboard1.index') }}" class="btn-dashboard">
                        <i class="fas fa-cogs"></i>
                        Accéder aux Contrôles Admin
                    </a>
                </div>
            </div>

            <!-- Footer Links -->
            <div class="footer-links">
                <a href="{{ route('acceuil.index') }}" class="footer-link">
                    <i class="fas fa-home me-2"></i>Retour à l'accueil public
                </a>
                <a href="{{ route('profile.edit') }}" class="footer-link">
                    <i class="fas fa-user-cog me-2"></i>Paramètres du compte
                </a>
                <a href="{{ route('logout') }}" class="footer-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.dashboard-card');
            
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-12px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Add loading state to buttons
            const buttons = document.querySelectorAll('.btn-dashboard');
            buttons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Chargement...';
                    this.style.pointerEvents = 'none';
                    
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.style.pointerEvents = 'auto';
                    }, 2000);
                });
            });

            // Add keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key >= '1' && e.key <= '3') {
                    const index = parseInt(e.key) - 1;
                    const buttons = document.querySelectorAll('.btn-dashboard');
                    if (buttons[index]) {
                        buttons[index].click();
                    }
                }
            });

            // Add scroll animation
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.dashboard-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>