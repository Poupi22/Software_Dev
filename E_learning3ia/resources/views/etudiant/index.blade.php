@extends('etudiant.layouts.app')

@section('title', 'Système de Gestion d\'Apprentissage 3IA - Tableau de Bord')

@section('styles')
     <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        /* Styles des cartes */
        .stats-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(209, 213, 219, 0.3);
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .course-card {
            background: white;
            border-radius: 0.75rem;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(209, 213, 219, 0.5);
        }

        .course-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(30, 64, 175, 0.15);
        }

        .progress-bar {
            height: 0.5rem;
            border-radius: 0.25rem;
            background-color: #e5e7eb;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 0.25rem;
            background: linear-gradient(90deg, #3b82f6, #1e40af);
            transition: width 0.6s ease;
        }

        .message-card {
            background: white;
            border-left: 4px solid #3b82f6;
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .message-card:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .deadline-card {
            background: white;
            border-left: 4px solid #ef4444;
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .deadline-card:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Couleurs des icônes */
        .icon-blue {
            color: #3b82f6;
        }
        .icon-green {
            color: #10b981;
        }
        .icon-purple {
            color: #8b5cf6;
        }
        .icon-orange {
            color: #f59e0b;
        }
        .icon-red {
            color: #ef4444;
        }
        .icon-yellow {
            color: #f59e0b;
        }

        /* Couleurs de fond pour les icônes */
        .bg-icon-blue {
            background-color: #dbeafe;
        }
        .bg-icon-green {
            background-color: #d1fae5;
        }
        .bg-icon-purple {
            background-color: #ede9fe;
        }
        .bg-icon-orange {
            background-color: #ffedd5;
        }
        .bg-icon-red {
            background-color: #fee2e2;
        }
        .bg-icon-yellow {
            background-color: #fef3c7;
        }

        /* Indicateurs de priorité */
        .priority-high {
            background-color: #fee2e2;
            color: #ef4444;
        }

        .priority-medium {
            background-color: #fef3c7;
            color: #f59e0b;
        }

        .priority-low {
            background-color: #d1fae5;
            color: #10b981;
        }
    </style>
@endsection

@section('content')
    <main class="main-content">
        <!-- Section Tableau de Bord -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"> <br> <br>
            <div class="mb-8">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-2">Bon retour, {{ Auth::user()->name }} !</h1>
                <p class="text-gray-600 text-lg">Continuez votre parcours en génie logiciel</p>
            </div>

            <!-- Cartes de Statistiques -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Cours Inscrits -->
                <div class="stats-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Cours Inscrits</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $stats['courses_enrolled'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-icon-blue rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-xl icon-blue"></i>
                        </div>
                    </div>
                </div>

                <!-- Cours Terminés -->
                <div class="stats-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Terminés</p>
                            <p class="text-2xl font-bold text-green-600">{{ $stats['completed_courses'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-icon-green rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-xl icon-green"></i>
                        </div>
                    </div>
                </div>

                <!-- Score aux Quiz -->
                <div class="stats-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Score aux Quiz</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $stats['average_quiz_score'] }}%</p>
                        </div>
                        <div class="w-12 h-12 bg-icon-purple rounded-lg flex items-center justify-center">
                            <i class="fas fa-trophy text-xl icon-purple"></i>
                        </div>
                    </div>
                </div>

                <!-- Heures d'Étude -->
                <div class="stats-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Heures d'Étude</p>
                            <p class="text-2xl font-bold text-orange-600">{{ $stats['study_hours'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-icon-orange rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-xl icon-orange"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section des Cartes Supplémentaires -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Carte des Cours -->
                <div class="stats-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold">Vos Cours</h2>
                        <a href="{{ route('etudiant.course.index') }}" class="text-blue-600 text-sm font-medium hover:underline">Voir Tout</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($courses as $course)
                        <div class="course-card p-4">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-icon-blue rounded-lg flex items-center justify-center">
                                    <i class="fas fa-laptop-code text-lg icon-blue"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800">{{ $course['name'] }}</h3>
                                    <p class="text-sm text-gray-600 mb-2">{{ $course['progress'] }}% terminé ({{ $course['completed_lessons'] }}/{{ $course['total_lessons'] }} leçons)</p>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $course['progress'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-gray-500">
                            <p>Aucun cours inscrit pour le moment.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Carte des Messages -->
                <div class="stats-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold">Messages</h2>
                        <a href="{{ route('etudiant.chat.index') }}" class="text-blue-600 text-sm font-medium hover:underline">Voir Tout</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($messages as $message)
                            @php
                                // Obtenir l'autre utilisateur dans la conversation
                                $otherUser = null;
                                if ($message->conversation && $message->conversation->users) {
                                    foreach ($message->conversation->users as $conversationUser) {
                                        if ($conversationUser->id != Auth::id()) {
                                            $otherUser = $conversationUser;
                                            break;
                                        }
                                    }
                                }
                            @endphp

                            @if($otherUser)
                            <div class="message-card p-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-10 h-10 bg-icon-green rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-tie text-lg icon-green"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800">{{ $otherUser->name }} {{ $otherUser->prenom }}</h3>
                                        <p class="text-sm text-gray-600 truncate">{{ Str::limit($message->body, 50) }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @empty
                        <div class="text-center py-4 text-gray-500">
                            <p>Aucun message pour le moment.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Carte des Échéances à Venir (Troisième Carte) -->
                <div class="stats-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold">Échéances à Venir</h2>
                        <span class="text-blue-600 text-sm font-medium">{{ count($upcomingDeadlines) }} élément{{ count($upcomingDeadlines) > 1 ? 's' : '' }}</span>
                    </div>
                    <div class="space-y-3">
                        @forelse($upcomingDeadlines as $deadline)
                        <div class="deadline-card p-4">
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-icon-red rounded-full flex items-center justify-center">
                                    <i class="fas fa-calendar-day text-lg icon-red"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-800">{{ $deadline['title'] }}</h3>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-sm text-gray-600">Échéance : {{ $deadline['due_date'] }}</span>
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold priority-{{ $deadline['priority'] }}">
                                            {{ $deadline['days_left'] }} jour{{ $deadline['days_left'] > 1 ? 's' : '' }} restant{{ $deadline['days_left'] > 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-gray-500">
                            <p>Aucune échéance à venir.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Activité Récente -->
            <div class="stats-card p-6">
                <h2 class="text-xl font-bold mb-4">Activité Récente</h2>
                <div class="space-y-4">
                    @forelse($recentActivity as $activity)
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-10 h-10 bg-icon-{{ $activity['icon_color'] }} rounded-full flex items-center justify-center">
                            <i class="fas fa-{{ $activity['icon'] }} text-lg icon-{{ $activity['icon_color'] }}"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $activity['title'] }}</p>
                            <p class="text-sm text-gray-600">{{ $activity['course'] }} • {{ $activity['time'] }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500">
                        <p>Aucune activité récente.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
@endsection

@section('scripts')
    <!-- Tout JavaScript spécifique à la page peut aller ici -->
@endsection