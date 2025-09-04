<!-- resources/views/etudiant/profile.blade.php -->
@extends('etudiant.layouts.app')

@section('title', 'Système de Gestion d\'Apprentissage 3IA - Tableau de Bord Administrateur')

@section('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        :root {
            --primary-blue: #1e40af;
            --secondary-blue: #3b82f6;
            --light-blue: #dbeafe;
            --dark-grey: #374151;
            --medium-grey: #6b7280;
            --light-grey: #f3f4f6;
            --white: #ffffff;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(30, 64, 175, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .gradient-blue {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(30, 64, 175, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(30, 64, 175, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-field {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(30, 64, 175, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background: rgba(255, 255, 255, 1);
        }

        .tab-button {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
            border: 2px solid transparent;
            font-size: 0.875rem;
        }

        .tab-button.active {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
        }

        .tab-button:not(.active) {
            background: rgba(255, 255, 255, 0.7);
            color: #374151;
        }

        .tab-button:not(.active):hover {
            background: rgba(255, 255, 255, 0.9);
            border-color: #3b82f6;
        }

        .progress-bar {
            background: linear-gradient(90deg, #10b981, #059669);
            height: 8px;
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .blocked-indicator {
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .completed-indicator {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Styles des modales */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 12px;
            width: 90%;
            max-width: 900px;
            max-height: 80vh;
            overflow-y: auto;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        /* Styles des tableaux responsives */
        .responsive-table {
            width: 100%;
            overflow-x: auto;
        }

        .table-container {
            min-width: 600px;
        }

        /* Navigation mobile */
        .mobile-tab-select {
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: 2px solid rgba(30, 64, 175, 0.1);
            background: rgba(255, 255, 255, 0.9);
            font-size: 0.875rem;
        }

        /* Ajustements de grille responsive */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
            
            .modal-content {
                margin: 10% auto;
                width: 95%;
                padding: 15px;
            }
            
            .tab-content {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr !important;
            }
            
            .search-form {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .search-form input,
            .search-form select {
                width: 100%;
            }
            
            .modal-content {
                margin: 5% auto;
                width: 98%;
                padding: 10px;
            }
        }

        /* Styles de cartes responsives */
        .card-grid {
            display: grid;
            gap: 1rem;
        }

        @media (min-width: 640px) {
            .card-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .card-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* Groupe de boutons responsive */
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .btn-group .btn {
            flex: 1;
            min-width: fit-content;
        }

        /* Suivi de progression responsive */
        .progress-grid {
            display: grid;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .progress-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Texte responsive */
        .responsive-text {
            font-size: clamp(0.875rem, 2vw, 1rem);
        }

        .responsive-heading {
            font-size: clamp(1.25rem, 3vw, 1.5rem);
        }
    </style>
@endsection

@section('content')
<main class="gradient-bg min-h-screen pt-16">
    <!-- En-tête -->
   

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Aperçu des statistiques -->
        <div class="stats-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="glass-effect rounded-xl p-4 text-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 gradient-blue rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-users text-white text-sm sm:text-base"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-1">{{ $stats['total_students'] }}</h3>
                <p class="text-gray-600 text-sm">Étudiants Totaux</p>
            </div>
            <div class="glass-effect rounded-xl p-4 text-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-book text-white text-sm sm:text-base"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-1">{{ $stats['active_courses'] }}</h3>
                <p class="text-gray-600 text-sm">Cours Actifs</p>
            </div>
            <div class="glass-effect rounded-xl p-4 text-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-chart-line text-white text-sm sm:text-base"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-1">{{ $stats['quiz_attempts'] }}</h3>
                <p class="text-gray-600 text-sm">Tentatives de Quiz</p>
            </div>
            <div class="glass-effect rounded-xl p-4 text-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-exclamation-triangle text-white text-sm sm:text-base"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-1">{{ $stats['blocked_students'] }}</h3>
                <p class="text-gray-600 text-sm">Étudiants Bloqués</p>
            </div>
        </div>

        <!-- Navigation par onglets -->
        <div class="glass-effect rounded-xl p-4 sm:p-6 mb-6">
            <!-- Sélecteur d'onglets mobile -->
            <div class="block lg:hidden mb-4">
                <select class="mobile-tab-select" onchange="showTab(this.value)">
                    <option value="students">Aperçu des Étudiants</option>
                    <option value="quiz-results">Résultats des Quiz</option>
                    <option value="progress">Suivi de Progression</option>
                    <option value="blocked">Étudiants Bloqués</option>
                </select>
            </div>

            <!-- Navigation par onglets bureau -->
            <div class="hidden lg:flex flex-wrap gap-2 mb-4">
                <button class="tab-button active" onclick="showTab('students')">
                    <i class="fas fa-users mr-2"></i>Aperçu des Étudiants
                </button>
                <button class="tab-button" onclick="showTab('quiz-results')">
                    <i class="fas fa-chart-bar mr-2"></i>Résultats des Quiz
                </button>
                <button class="tab-button" onclick="showTab('progress')">
                    <i class="fas fa-tasks mr-2"></i>Suivi de Progression
                </button>
                <button class="tab-button" onclick="showTab('blocked')">
                    <i class="fas fa-ban mr-2"></i>Étudiants Bloqués
                </button>
            </div>

            <!-- Onglet Aperçu des Étudiants -->
            <div id="students-tab" class="tab-content">
                <div class="mb-4">
                    <div class="flex flex-col gap-4">
                        <h2 class="responsive-heading font-bold text-gray-800">Gestion des Étudiants</h2>
                        <form method="GET" action="{{ route('etudiant.profile') }}" class="search-form flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                            <input type="text" name="search" placeholder="Rechercher des étudiants..." 
                                   value="{{ $search ?? '' }}" 
                                   class="input-field px-3 py-2 rounded-lg focus:outline-none flex-1 min-w-0">
                            
                            <select name="specialty" class="input-field px-3 py-2 rounded-lg focus:outline-none w-full sm:w-auto">
                                <option value="">Toutes les Spécialités</option>
                                @foreach($specialties as $id => $name)
                                    <option value="{{ $id }}" {{ ($specialty ?? '') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <div class="btn-group flex sm:flex-nowrap">
                                <button type="submit" class="btn-primary px-4 py-2 rounded-lg text-sm">
                                    <i class="fas fa-search mr-2"></i>Rechercher
                                </button>
                                
                                @if($search || $specialty)
                                    <a href="{{ route('etudiant.profile') }}" class="btn-danger px-4 py-2 rounded-lg text-sm">
                                        <i class="fas fa-times mr-2"></i>Effacer
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <div class="responsive-table">
                    <div class="table-container">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text">Étudiant</th>
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text hidden sm:table-cell">Spécialité</th>
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text">Progression</th>
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text hidden md:table-cell">Dernière Activité</th>
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text">Statut</th>
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="students-table-body">
                                @foreach($students as $student)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-2 sm:px-4">
                                        <div class="flex items-center space-x-2 sm:space-x-3">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                                                <span class="text-white font-semibold text-sm">{{ substr($student['name'], 0, 1) }}</span>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="font-semibold text-gray-800 responsive-text truncate">{{ $student['name'] }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ $student['email'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2 sm:px-4 hidden sm:table-cell">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            {{ $student['specialty'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-2 sm:px-4">
                                        <div class="flex items-center space-x-2">
                                            <div class="flex-1 bg-gray-200 rounded-full h-2 min-w-[60px]">
                                                <div class="progress-bar h-2 rounded-full" style="width: {{ $student['progress'] }}%"></div>
                                            </div>
                                            <span class="text-xs font-semibold text-gray-700 whitespace-nowrap">{{ $student['progress'] }}%</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2 sm:px-4 text-gray-600 responsive-text hidden md:table-cell">{{ $student['last_activity'] }}</td>
                                    <td class="py-3 px-2 sm:px-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $student['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $student['status'] === 'active' ? 'Actif' : 'Bloqué' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-2 sm:px-4">
                                        <div class="btn-group">
                                            <button class="btn-primary px-2 py-1 sm:px-3 sm:py-1 rounded text-xs" onclick="viewStudent({{ $student['id'] }})">
                                                <i class="fas fa-eye mr-1"></i>Voir
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Onglet Résultats des Quiz -->
            <div id="quiz-results-tab" class="tab-content hidden">
                <div class="mb-4">
                    <h2 class="responsive-heading font-bold text-gray-800">Aperçu des Résultats des Quiz</h2>
                </div>

                <div class="card-grid mb-4">
                    <div class="glass-effect rounded-lg p-4">
                        <h3 class="text-lg font-semibold mb-3">Scores Moyens par Matière</h3>
                        <div class="space-y-2" id="average-scores-container">
                            <div class="text-center text-gray-600">Chargement des scores moyens...</div>
                        </div>
                    </div>

                    <div class="glass-effect rounded-lg p-4">
                        <h3 class="text-lg font-semibold mb-3">Tentatives de Quiz Récentes</h3>
                        <div class="space-y-2">
                            @foreach($quizResults->take(3) as $quiz)
                            <div class="flex justify-between items-center text-sm">
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium truncate">{{ $quiz['student_name'] }}</p>
                                    <p class="text-gray-500 text-xs truncate">{{ $quiz['quiz_name'] }}</p>
                                </div>
                                <span class="font-bold {{ $quiz['score'] >= 70 ? 'text-green-600' : 'text-red-600' }} ml-2">{{ $quiz['score'] }}%</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="responsive-table">
                    <div class="table-container">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text">Étudiant</th>
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text">Quiz</th>
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text">Score</th>
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text hidden sm:table-cell">Tentatives</th>
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text hidden md:table-cell">Date</th>
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="quiz-results-table-body">
                                @foreach($quizResults as $quiz)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-2 sm:px-4">
                                        <div class="font-semibold text-gray-800 responsive-text truncate">{{ $quiz['student_name'] }}</div>
                                    </td>
                                    <td class="py-3 px-2 sm:px-4">
                                        <div class="min-w-0">
                                            <p class="font-medium text-gray-800 responsive-text truncate">{{ $quiz['quiz_name'] }}</p>
                                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $quiz['type'] === 'lesson' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                {{ $quiz['type'] === 'lesson' ? 'Quiz de Leçon' : 'Quiz de Matière' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2 sm:px-4">
                                        <span class="font-bold {{ $quiz['score'] >= 70 ? 'text-green-600' : 'text-red-600' }} responsive-text">{{ $quiz['score'] }}%</span>
                                    </td>
                                    <td class="py-3 px-2 sm:px-4 hidden sm:table-cell">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $quiz['attempts'] > 2 ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $quiz['attempts'] }} tentative{{ $quiz['attempts'] > 1 ? 's' : '' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-2 sm:px-4 text-gray-600 responsive-text hidden md:table-cell">{{ $quiz['date'] }}</td>
                                    <td class="py-3 px-2 sm:px-4">
                                        <div class="btn-group">
                                            <button class="btn-primary px-2 py-1 sm:px-3 sm:py-1 rounded text-xs" onclick="showQuizDetails({{ $quiz['student_id'] }}, {{ $quiz['quiz_id'] }})">
                                                <i class="fas fa-info-circle mr-1"></i>Détails
                                            </button>
                                            @if($quiz['attempts'] >= 3 && $quiz['score'] < 70)
                                            <button class="btn-success px-2 py-1 sm:px-3 sm:py-1 rounded text-xs" onclick="resetQuizAttempts({{ $quiz['student_id'] }}, {{ $quiz['quiz_id'] }}, '{{ $quiz['quiz_name'] }}', '{{ $quiz['student_name'] }}')">
                                                <i class="fas fa-redo mr-1"></i>Réinitialiser
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Onglet Suivi de Progression -->
            <div id="progress-tab" class="tab-content hidden">
                <div class="mb-4">
                    <h2 class="responsive-heading font-bold text-gray-800">Suivi de Progression des Étudiants</h2>
                </div>

                <div class="progress-grid" id="progress-list">
                    @foreach($progressData as $student)
                    <div class="glass-effect rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2 sm:space-x-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white font-semibold text-sm">{{ substr($student['name'], 0, 1) }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-bold text-gray-800 responsive-text truncate">{{ $student['name'] }}</h3>
                                    <p class="text-xs text-gray-600 truncate">{{ $student['specialty'] }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-600">Global</p>
                                <p class="text-lg sm:text-xl font-bold text-blue-600">{{ $student['progress'] }}%</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2 mb-3">
                            <div class="text-center">
                                <p class="text-lg font-bold text-green-600">{{ $student['completed_lessons'] }}/{{ $student['total_lessons'] }}</p>
                                <p class="text-xs text-gray-600">Leçons</p>
                            </div>
                            <div class="text-center">
                                <p class="text-lg font-bold text-purple-600">{{ $student['subject_quizzes_passed'] }}/{{ $student['total_subject_quizzes'] }}</p>
                                <p class="text-xs text-gray-600">Quiz</p>
                            </div>
                            <div class="text-center">
                                <p class="text-lg font-bold text-blue-600">{{ $student['average_score'] }}%</p>
                                <p class="text-xs text-gray-600">Score Moy.</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2 mb-3">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="progress-bar h-2 rounded-full" style="width: {{ $student['progress'] }}%"></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-700 whitespace-nowrap">{{ $student['progress'] }}%</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                {{ $student['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $student['status'] === 'active' ? 'Actif' : 'Bloqué' }}
                            </span>

                            <div class="btn-group">
                                <button class="btn-primary px-3 py-1 rounded text-xs" onclick="viewStudent({{ $student['id'] }})">
                                    <i class="fas fa-eye mr-1"></i>Voir
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Onglet Étudiants Bloqués -->
            <div id="blocked-tab" class="tab-content hidden">
                <div class="mb-4">
                    <h2 class="responsive-heading font-bold text-gray-800">Gestion des Étudiants Bloqués</h2>
                    <p class="text-gray-600 text-sm">Étudiants bloqués dans leur progression en raison d'échecs aux quiz</p>
                </div>

                <div class="responsive-table">
                    <div class="table-container">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text">Étudiant</th>
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text">Bloqué Sur</th>
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text hidden sm:table-cell">Tentatives Échouées</th>
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text hidden md:table-cell">Date de Blocage</th>
                                    <th class="text-left py-3 px-2 sm:px-4 font-semibold text-gray-700 responsive-text">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="blocked-students-table-body">
                                @foreach($blockedStudents as $student)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-3 px-2 sm:px-4">
                                        <div class="flex items-center space-x-2 sm:space-x-3">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-r from-red-500 to-orange-600 flex items-center justify-center flex-shrink-0">
                                                <span class="text-white font-semibold text-sm">{{ substr($student['student_name'], 0, 1) }}</span>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="font-semibold text-gray-800 responsive-text truncate">{{ $student['student_name'] }}</p>
                                                <p class="text-xs text-gray-500 truncate">{{ $student['student_email'] }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2 sm:px-4">
                                        <div class="min-w-0">
                                            <p class="font-medium text-gray-800 responsive-text truncate">{{ $student['quiz_name'] }}</p>
                                            <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">Quiz de Matière</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2 sm:px-4 hidden sm:table-cell">
                                        <span class="blocked-indicator">{{ $student['attempts'] }} tentatives</span>
                                    </td>
                                    <td class="py-3 px-2 sm:px-4 text-gray-600 responsive-text hidden md:table-cell">{{ $student['block_date'] }}</td>
                                    <td class="py-3 px-2 sm:px-4">
                                        <div class="btn-group">
                                            <button class="btn-success px-2 py-1 sm:px-3 sm:py-1 rounded text-xs" onclick="resetQuizAttempts({{ $student['student_id'] }}, {{ $student['quiz_id'] }}, '{{ $student['quiz_name'] }}', '{{ $student['student_name'] }}')">
                                                <i class="fas fa-redo mr-1"></i>Réinitialiser
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modale Détails de l'Étudiant -->
<div id="studentModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('studentModal')">&times;</span>
        <h2 class="text-xl sm:text-2xl font-bold mb-4">Détails de l'Étudiant</h2>
        <div id="studentModalContent">
            <!-- Le contenu sera chargé dynamiquement -->
        </div>
    </div>
</div>

<!-- Modale Détails du Quiz -->
<div id="quizModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('quizModal')">&times;</span>
        <h2 class="text-xl sm:text-2xl font-bold mb-4">Détails du Quiz</h2>
        <div id="quizModalContent">
            <!-- Le contenu sera chargé dynamiquement -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Fonctionnalité des onglets
    function showTab(tabName) {
        // Masquer tous les contenus d'onglets
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Supprimer la classe active de tous les boutons d'onglets
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active');
        });
        
        // Mettre à jour la sélection mobile
        const mobileSelect = document.querySelector('.mobile-tab-select');
        if (mobileSelect) {
            mobileSelect.value = tabName;
        }
        
        // Afficher le contenu de l'onglet sélectionné
        document.getElementById(tabName + '-tab').classList.remove('hidden');
        
        // Ajouter la classe active au bouton cliqué (pour le bureau)
        if (event && event.target.classList.contains('tab-button')) {
            event.target.classList.add('active');
        }

        // Charger les scores moyens lorsque l'onglet des résultats de quiz est affiché
        if (tabName === 'quiz-results') {
            loadAverageScores();
        }
    }

    // Charger les scores moyens par matière
    function loadAverageScores() {
        fetch('{{ route("etudiant.quiz-average-scores") }}')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('average-scores-container');
                container.innerHTML = '';
                
                if (data.length === 0) {
                    container.innerHTML = '<div class="text-center text-gray-600 text-sm">Aucune donnée de quiz disponible</div>';
                    return;
                }

                data.forEach(subject => {
                    const scoreElement = document.createElement('div');
                    scoreElement.className = 'flex justify-between items-center mb-2';
                    scoreElement.innerHTML = `
                        <span class="text-gray-700 text-sm truncate flex-1 mr-2">${subject.subject_name}</span>
                        <span class="font-bold ${subject.average_score >= 70 ? 'text-green-600' : 'text-red-600'} text-sm whitespace-nowrap">
                            ${Math.round(subject.average_score)}%
                        </span>
                    `;
                    container.appendChild(scoreElement);
                });
            })
            .catch(error => {
                console.error('Erreur lors du chargement des scores moyens:', error);
                document.getElementById('average-scores-container').innerHTML = 
                    '<div class="text-center text-red-600 text-sm">Erreur lors du chargement des données</div>';
            });
    }

    // Voir les détails de l'étudiant
    function viewStudent(studentId) {
        fetch(`{{ url('etudiant/students') }}/${studentId}/details`)
            .then(response => response.json())
            .then(data => {
                const modalContent = document.getElementById('studentModalContent');
                modalContent.innerHTML = `
                    <div class="mb-4">
                        <h3 class="text-lg sm:text-xl font-semibold">${data.student.name} ${data.student.prenom}</h3>
                        <p class="text-gray-600 text-sm">${data.student.email}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <h4 class="font-semibold mb-2 text-sm">Résumé de la Progression</h4>
                            <p class="text-sm">Progression Globale: <span class="font-bold">${data.progress.percentage}%</span></p>
                            <p class="text-sm">Leçons Terminées: <span class="font-bold">${data.progress.completed}/${data.progress.total}</span></p>
                            <p class="text-sm">Quiz de Matière Réussis: <span class="font-bold">${data.progress.subject_quizzes_passed}/${data.progress.total_subject_quizzes}</span></p>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-2 text-sm">Historique des Quiz</h4>
                            <div class="max-h-40 overflow-y-auto">
                                ${data.quiz_attempts.length > 0 ? 
                                    data.quiz_attempts.map(attempt => `
                                        <div class="mb-2 p-2 border rounded text-xs">
                                            <p class="font-medium">${attempt.quiz.quizzable?.titre || attempt.quiz.quizzable?.nom || 'Quiz Inconnu'}</p>
                                            <p>Score: <span class="${attempt.score_obtenu >= 70 ? 'text-green-600' : 'text-red-600'} font-bold">${attempt.score_obtenu}%</span></p>
                                            <p>Date: ${new Date(attempt.created_at).toLocaleDateString()}</p>
                                            <p>Statut: <span class="font-bold">${attempt.statut}</span></p>
                                        </div>
                                    `).join('') : 
                                    '<p class="text-gray-600 text-sm">Aucune tentative de quiz pour le moment</p>'
                                }
                            </div>
                        </div>
                    </div>
                    
                    <h4 class="font-semibold mb-2 text-sm">Résultats des Quiz de Matière Finaux</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="p-2 text-left">Matière</th>
                                    <th class="p-2 text-left">Score Final</th>
                                    <th class="p-2 text-left">Statut</th>
                                    <th class="p-2 text-left">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.quiz_attempts.filter(attempt => attempt.quiz.quizzable_type === 'App\\Models\\Matiere')
                                    .map(attempt => `
                                        <tr>
                                            <td class="p-2">${attempt.quiz.quizzable?.nom || 'Inconnu'}</td>
                                            <td class="p-2 font-bold ${attempt.score_obtenu >= 70 ? 'text-green-600' : 'text-red-600'}">${attempt.score_obtenu}%</td>
                                            <td class="p-2">${attempt.statut}</td>
                                            <td class="p-2">${new Date(attempt.created_at).toLocaleDateString()}</td>
                                        </tr>
                                    `).join('') || '<tr><td colspan="4" class="p-2 text-center text-gray-600 text-sm">Aucun résultat de quiz de matière</td></tr>'}
                            </tbody>
                        </table>
                    </div>
                `;
                
                document.getElementById('studentModal').style.display = 'block';
            })
            .catch(error => {
                console.error('Erreur lors du chargement des détails de l\'étudiant:', error);
                alert('Erreur lors du chargement des détails de l\'étudiant');
            });
    }

    // Afficher les détails du quiz
    function showQuizDetails(studentId, quizId) {
        fetch(`{{ url('etudiant/students') }}/${studentId}/quizzes/${quizId}/details`)
            .then(response => response.json())
            .then(data => {
                const modalContent = document.getElementById('quizModalContent');
                modalContent.innerHTML = `
                    <div class="mb-4">
                        <h3 class="text-lg sm:text-xl font-semibold">${data.quiz_name}</h3>
                        <p class="text-gray-600 text-sm">Étudiant: ${data.student_name}</p>
                    </div>
                      
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <h4 class="font-semibold text-sm">Informations du Quiz</h4>
                            <p class="text-sm">Type: ${data.quiz_type}</p>
                            <p class="text-sm">Total des Tentatives: ${data.total_attempts}</p>
                            <p class="text-sm">Meilleur Score: <span class="font-bold ${data.best_score >= 70 ? 'text-green-600' : 'text-red-600'}">${data.best_score}%</span></p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-sm">Historique des Tentatives</h4>
                            <div class="max-h-40 overflow-y-auto">
                                ${data.attempts.map(attempt => `
                                    <div class="mb-2 p-2 border rounded text-xs">
                                        <p>Score: <span class="${attempt.score_obtenu >= 70 ? 'text-green-600' : 'text-red-600'} font-bold">${attempt.score_obtenu}%</span></p>
                                        <p>Date: ${new Date(attempt.created_at).toLocaleDateString()}</p>
                                        <p>Statut: <span class="font-bold">${attempt.statut}</span></p>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;
                
                document.getElementById('quizModal').style.display = 'block';
            })
            .catch(error => {
                console.error('Erreur lors du chargement des détails du quiz:', error);
                alert('Erreur lors du chargement des détails du quiz');
            });
    }

    // Réinitialiser les tentatives de quiz
    function resetQuizAttempts(studentId, quizId, quizName, studentName) {
        if (confirm(`Réinitialiser les tentatives de quiz pour "${quizName}" par ${studentName} ? Cela supprimera toutes leurs tentatives pour ce quiz.`)) {
            fetch(`{{ url('etudiant/students') }}/${studentId}/quizzes/${quizId}/reset`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Tentatives de quiz réinitialisées avec succès');
                    location.reload();
                } else {
                    alert('Erreur lors de la réinitialisation des tentatives de quiz');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la réinitialisation des tentatives de quiz');
            });
        }
    }

    // Fermer la modale
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Fermer les modales en cliquant à l'extérieur
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }

    // Charger les scores moyens au chargement de la page si sur l'onglet des résultats de quiz
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('quiz-results-tab') && !document.getElementById('quiz-results-tab').classList.contains('hidden')) {
            loadAverageScores();
        }
    });
</script>
@endsection