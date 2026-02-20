@extends('admin.layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
    <div class="welcome-section">
        <h1 class="welcome-title">Bienvenue, {{ auth()->user()->name }}</h1>
        <p class="welcome-subtitle">{{ now()->format('l d F Y') }} • Voici un aperçu de votre activité</p>
    </div>
    
    <!-- Quick Stats -->
    <div class="stats-grid">
        <!-- Testimonials -->
        <div class="stats-card">
            <div class="stats-header">
                <div class="stats-title">Temoignages</div>
                <div class="stats-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                    <i class="fas fa-comment-dots"></i>
                </div>
            </div>
            <div class="stats-value">{{ $testimonialsCount ?? 10 }}</div>
            <div class="stats-change positive">
                <i class="fas fa-arrow-up"></i>
            </div>
        </div>

        <!-- Trainings -->
        <div class="stats-card">
            <div class="stats-header">
                <div class="stats-title">Formations Actives</div>
                <div class="stats-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
            </div>
            <div class="stats-value">{{ $trainingsCount ?? 12 }}</div>
            <div class="stats-change positive">
                <i class="fas fa-user-graduate"></i>
            </div>
        </div>

        <!-- Projects -->
        <div class="stats-card">
            <div class="stats-header">
                <div class="stats-title">Projets</div>
                <div class="stats-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="fas fa-project-diagram"></i>
                </div>
            </div>
            <div class="stats-value">{{ $projectsCount ?? 8 }}</div>
            <div class="stats-change neutral">
                <i class="fas fa-clock"></i>
            </div>
        </div>

        <!-- Services -->
        <div class="stats-card">
            <div class="stats-header">
                <div class="stats-title">Services</div>
                <div class="stats-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                    <i class="fas fa-concierge-bell"></i>
                </div>
            </div>
            <div class="stats-value">{{ $servicesCount ?? 14 }} </div>
            <div class="stats-change positive">
                <i class="fas fa-arrow-up"></i>
            </div>
        </div>

        <!-- Team -->
        <div class="stats-card">
            <div class="stats-header">
                <div class="stats-title">Personnel</div>
                <div class="stats-icon" style="background: linear-gradient(135deg, #ec4899, #db2777);">
                    <i class="fas fa-user-tie"></i>
                </div>
            </div>
            <div class="stats-value">{{ $personnelCount ?? 3 }}</div>
            <div class="stats-change neutral">
                <i class="fas fa-map-marker-alt"></i>
            </div>
        </div>

        <!-- Partners -->
        <div class="stats-card">
            <div class="stats-header">
                <div class="stats-title">Partenaires</div>
                <div class="stats-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
            <div class="stats-value">{{ $partnersCount ?? 5 }}</div>
            <div class="stats-change negative">
                <i class="fas fa-exclamation-circle"></i>
            </div>
        </div>
    </div>
    
    <!-- Main Content Sections -->
    <div class="dashboard-sections">
        <!-- Quick Actions -->
        <div class="content-section quick-actions">
            <h2><i class="fas fa-bolt mr-2"></i> Actions Rapides</h2>
            <div class="actions-grid">
                <a href="{{ route('admin.projects.create') }}" class="action-card">
                    <div class="action-icon" style="background-color: #3b82f6;">
                        <i class="fas fa-plus"></i>
                    </div>
                    <span>Nouveau Projet</span>
                </a>
                <a href="{{ route('admin.trainings.create') }}" class="action-card">
                    <div class="action-icon" style="background-color: #10b981;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <span>Ajouter Formation</span>
                </a>
                <a href="{{ route('admin.personnels.create') }}" class="action-card">
                    <div class="action-icon" style="background-color: #8b5cf6;">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <span>Nouveau Personnel</span>
                </a>
                <a href="#" class="action-card">
                    <div class="action-icon" style="background-color: #f59e0b;">
                        <i class="fas fa-cog"></i>
                    </div>
                    <span>Paramètres</span>
                </a>
            </div>
        </div>
    </div>

    <style>
        .welcome-section {
            margin-bottom: 30px;
        }
        
        .welcome-title {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .welcome-subtitle {
            font-size: 16px;
            color: #7f8c8d;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }
        
        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .stats-title {
            font-size: 16px;
            font-weight: 600;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        
        .stats-value {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin: 10px 0;
        }
        
        .stats-change {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            margin-top: 5px;
        }
        
        .stats-change i {
            margin-right: 5px;
        }
        
        .positive {
            color: #10b981;
        }
        
        .negative {
            color: #ef4444;
        }
        
        .neutral {
            color: #f59e0b;
        }
        
        /* Quick Actions Section */
        .content-section.quick-actions {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
        }

        .quick-actions h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
        }

        .quick-actions h2 i {
            margin-right: 0.5rem;
            color: #f59e0b;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .action-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            background-color: #ffffff;
        }

        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            color: white;
            font-size: 1.25rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-card span {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            text-align: center;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .actions-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .actions-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection