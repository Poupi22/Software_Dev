@extends('etudiant.layouts.app')

@section('title', 'Mes Cours - ' . auth()->user()->name)

@section('styles')
<link rel="stylesheet" href="{{ asset('etudiant/assets/css/courses.css') }}">
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        50: '#eff6ff',
                        100: '#dbeafe',
                        200: '#bfdbfe',
                        300: '#93c5fd',
                        400: '#60a5fa',
                        500: '#3b82f6',
                        600: '#2563eb',
                        700: '#1d4ed8',
                        800: '#1e40af',
                        900: '#1e3a8a'
                    },
                    grey: {
                        50: '#f9fafb',
                        100: '#f3f4f6',
                        200: '#e5e7eb',
                        300: '#d1d5db',
                        400: '#9ca3af',
                        500: '#6b7280',
                        600: '#4b5563',
                        700: '#374151',
                        800: '#1f2937',
                        900: '#111827'
                    }
                },
                fontFamily: {
                    'inter': ['Inter', 'sans-serif']
                },
                animation: {
                    'fade-in': 'fadeIn 0.6s ease-out',
                    'slide-up': 'slideUp 0.8s cubic-bezier(0.4, 0, 0.2, 1)',
                    'slide-down': 'slideDown 0.6s cubic-bezier(0.4, 0, 0.2, 1)',
                    'scale-in': 'scaleIn 0.5s cubic-bezier(0.4, 0, 0.2, 1)',
                    'bounce-gentle': 'bounceGentle 2s infinite'
                },
                keyframes: {
                    fadeIn: {
                        '0%': { opacity: '0', transform: 'translateY(20px)' },
                        '100%': { opacity: '1', transform: 'translateY(0)' }
                    },
                    slideUp: {
                        '0%': { opacity: '0', transform: 'translateY(60px)' },
                        '100%': { opacity: '1', transform: 'translateY(0)' }
                    },
                    slideDown: {
                        '0%': { opacity: '0', transform: 'translateY(-30px)' },
                        '100%': { opacity: '1', transform: 'translateY(0)' }
                    },
                    scaleIn: {
                        '0%': { opacity: '0', transform: 'scale(0.9)' },
                        '100%': { opacity: '1', transform: 'scale(1)' }
                    },
                    bounceGentle: {
                        '0%, 100%': { transform: 'translateY(-5%)' },
                        '50%': { transform: 'translateY(0)' }
                    }
                }
            }
        }
    }
</script>
<style>
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .glass-effect {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .semester-card {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .semester-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px rgba(59, 130, 246, 0.3);
    }

    .course-card {
        background: white;
        border-left: 4px solid #3b82f6;
        transition: all 0.3s ease;
    }

    .course-card:hover {
        transform: translateX(8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border-left-color: #1d4ed8;
    }

    .floating-shapes {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: -1;
    }

    .shape {
        position: absolute;
        opacity: 0.05;
        animation: float 6s ease-in-out infinite;
    }

    .shape:nth-child(1) {
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }

    .shape:nth-child(2) {
        top: 60%;
        right: 10%;
        animation-delay: 2s;
    }

    .shape:nth-child(3) {
        bottom: 20%;
        left: 20%;
        animation-delay: 4s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        33% { transform: translateY(-20px) rotate(120deg); }
        66% { transform: translateY(20px) rotate(240deg); }
    }

    .progress-bar {
        background: linear-gradient(90deg, #3b82f6, #1d4ed8);
        height: 3px;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        transition: width 0.3s ease;
    }

    .chapter-progress {
        background: linear-gradient(90deg, #10b981, #059669);
    }

    .quiz-completed-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        background: #D1FAE5;
        color: #065F46;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 0.75rem;
    }

    .locked-content {
        position: relative;
    }

    .locked-content::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(2px);
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .locked-content::before {
        content: '🔒';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 2rem;
        z-index: 10;
    }

    .subject-quiz-card {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border: none;
        color: white;
    }

    .subject-quiz-card:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(245, 158, 11, 0.3);
    }

    .lesson-completed {
        border-left: 4px solid #10b981;
    }

    .lesson-in-progress {
        border-left: 4px solid #f59e0b;
    }

    .lesson-locked {
        border-left: 4px solid #9ca3af;
    }
</style>
@endsection

@section('content')
<!-- Barre de Progression -->
<div class="progress-bar" id="progressBar" style="width: 0%"></div>

<!-- Formes Flottantes en Arrière-plan -->
<div class="floating-shapes">
    <div class="shape w-32 h-32 bg-primary-500 rounded-full"></div>
    <div class="shape w-24 h-24 bg-primary-600 rounded-lg"></div>
    <div class="shape w-40 h-40 bg-primary-400 rounded-full"></div>
</div>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Section de Bienvenue -->
    <section class="mb-12 animate-fade-in">
        <div class="text-center mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-grey-800 mb-4"> <br> <br>
                Bienvenue <span class="text-primary-600">{{ auth()->user()->prenom }} {{ auth()->user()->name }}</span>
            </h1>
            <p class="text-xl text-grey-600 mb-2">Matricule: <strong class="text-primary-600">{{ auth()->user()->matricule }}</strong></p>
            @if (!empty($formations))
                <div class="inline-flex items-center px-4 py-2 bg-primary-50 rounded-full">
                    <i class="fas fa-graduation-cap text-primary-600 mr-2"></i>
                    <span class="text-primary-700 font-medium">Formation: {{ implode(', ', $formations) }}</span>
                </div>
            @endif
        </div>
    </section>

    @if(isset($coursInstance) && isset($chapitres))
        <!-- Vue détaillée d'un cours avec chapitres et leçons -->
        <a href="{{ route('etudiant.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors mb-6">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux cours
        </a>

        <div class="glass-effect rounded-xl p-8 mb-8">
            <h2 class="text-3xl font-bold text-grey-800 mb-4">{{ $coursInstance->matiere->nom }}</h2>
            <p class="text-grey-600 mb-4 text-lg">{{ $coursInstance->matiere->description ?? 'Aucune description disponible' }}</p>
            <div class="flex items-center text-primary-600 text-lg">
                <i class="fas fa-chalkboard-teacher mr-2"></i>
                <span class="font-medium">Formateur : {{ $formateur }}</span>
            </div>
        </div>

        @if($chapitres->count() > 0)
            <div>
                <h3 class="text-2xl font-bold text-grey-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-folder-open text-primary-600"></i>
                    Chapitres et leçons
                </h3>

                @foreach($chapitres as $chapitreIndex => $chapitre)
                    @php
                        $isChapterAccessible = $chapitre->is_accessible;
                        $isChapterCompleted = $chapitre->is_completed;

                        if (!$isChapterAccessible) {
                            $chapterCardClass = 'locked-content';
                            $chapterBorderColor = 'border-grey-300';
                        } elseif ($isChapterCompleted) {
                            $chapterCardClass = '';
                            $chapterBorderColor = 'border-green-500';
                        } else {
                            $chapterCardClass = '';
                            $chapterBorderColor = 'border-primary-500';
                        }
                    @endphp

                    <div class="glass-effect rounded-xl p-6 shadow-lg border-l-4 {{ $chapterBorderColor }} mb-6 {{ $chapterCardClass }}">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-grey-800 flex items-center">
                                <i class="fas fa-bookmark {{ $isChapterAccessible ? 'text-primary-600' : 'text-grey-400' }} mr-3"></i>
                                Chapitre {{ $chapitre->ordre }}: {{ $chapitre->nom }}

                                @if($isChapterCompleted)
                                    <span class="quiz-completed-badge">
                                        <i class="fas fa-check-circle"></i> Chapitre complété
                                    </span>
                                @elseif(!$isChapterAccessible)
                                    <span class="quiz-completed-badge bg-grey-100 text-grey-600">
                                        <i class="fas fa-lock"></i> Chapitre verrouillé
                                    </span>
                                @endif
                            </h3>
                            <div class="flex items-center text-sm {{ $isChapterAccessible ? 'text-grey-500' : 'text-grey-400' }}">
                                <i class="fas fa-list mr-1"></i>
                                {{ $chapitre->lecons->count() }} leçon{{ $chapitre->lecons->count() > 1 ? 's' : '' }}
                            </div>
                        </div>

                        @if($chapitre->lecons->count() > 0)
                            <div class="space-y-3 mb-4">
                                @foreach($chapitre->lecons as $leconIndex => $lecon)
                                    @php
                                        $isLessonAccessible = $isChapterAccessible && $lecon->is_accessible;
                                        $isContentAccessible = $isChapterAccessible && $lecon->is_content_accessible;
                                        $quizStatus = $lecon->quiz_status ?? null;

                                        if ($isLessonAccessible) {
                                            if ($quizStatus && $quizStatus['has_passed']) {
                                                $cardClass = 'lesson-completed';
                                                $buttonText = 'Réviser';
                                                $buttonClass = 'bg-green-50 hover:bg-green-100 text-green-600';
                                                $iconClass = 'fa-check text-sm';
                                                $iconBgClass = 'bg-green-100 text-green-600';
                                                $statusText = 'Quiz réussi';
                                                $statusColor = 'text-green-600';
                                            } elseif ($quizStatus && $quizStatus['all_failed']) {
                                                $cardClass = 'quiz-blocked';
                                                $buttonText = 'Quiz Bloqué';
                                                $buttonClass = 'bg-red-50 hover:bg-red-100 text-red-600 cursor-not-allowed opacity-70';
                                                $iconClass = 'fa-lock text-sm';
                                                $iconBgClass = 'bg-red-100 text-red-600';
                                                $statusText = 'Quiz bloqué (3 tentatives échouées)';
                                                $statusColor = 'text-red-600';
                                            } elseif ($quizStatus && $quizStatus['total_attempts'] > 0) {
                                                $cardClass = 'lesson-in-progress';
                                                $buttonText = 'Continuer';
                                                $buttonClass = 'bg-amber-50 hover:bg-amber-100 text-amber-600';
                                                $iconClass = 'fa-redo text-sm';
                                                $iconBgClass = 'bg-amber-100 text-amber-600';
                                                $statusText = $quizStatus['total_attempts'] . '/3 tentatives';
                                                $statusColor = 'text-amber-600';
                                            } else {
                                                $cardClass = '';
                                                $buttonText = 'Commencer';
                                                $buttonClass = 'bg-primary-50 hover:bg-primary-100 text-primary-600';
                                                $iconClass = 'fa-play text-sm';
                                                $iconBgClass = 'bg-primary-100 text-primary-600';
                                                $statusText = 'Quiz à compléter';
                                                $statusColor = 'text-primary-600';
                                            }
                                        } else {
                                            $cardClass = 'lesson-locked locked-content';
                                            $buttonText = 'Verrouillé';
                                            $buttonClass = 'bg-grey-100 text-grey-400 cursor-not-allowed opacity-70';
                                            $iconClass = 'fa-lock text-sm';
                                            $iconBgClass = 'bg-grey-200 text-grey-500';
                                            $statusText = $isChapterAccessible ? 'Complétez la leçon précédente' : 'Complétez le chapitre précédent';
                                            $statusColor = 'text-grey-500';
                                        }
                                    @endphp

                                    <div class="flex items-center justify-between p-4 bg-grey-50 rounded-lg border transition-all duration-200 hover:bg-primary-50 hover:border-primary-200 {{ $cardClass }}">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 {{ $iconBgClass }}">
                                                <i class="fas {{ $iconClass }}"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-medium text-grey-800">Leçon {{ $lecon->ordre }}: {{ $lecon->titre }}</h4>
                                                @if($lecon->quiz)
                                                    <p class="text-sm {{ $statusColor }} mt-1">
                                                        <i class="fas
                                                            @if(str_contains($statusColor, 'green')) fa-check-circle
                                                            @elseif(str_contains($statusColor, 'red')) fa-times-circle
                                                            @elseif(str_contains($statusColor, 'amber')) fa-redo
                                                            @elseif(str_contains($statusColor, 'primary')) fa-question-circle
                                                            @else fa-lock
                                                            @endif mr-1"></i>
                                                        {{ $statusText }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex space-x-2">
                                            <!-- Bouton Contenu de la Leçon (Toujours accessible si la leçon est accessible) -->
                                            @if($isContentAccessible)
                                                <a href="{{ route('etudiant.lecon.show', $lecon->id) }}" class="px-4 py-2 text-sm font-medium bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors">
                                                    Voir leçon
                                                </a>
                                            @else
                                                 <a href="{{ route('etudiant.lecon.show', $lecon->id) }}" class="px-4 py-2 text-sm font-medium bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors">
                                                    Voir leçon
                                                </a>
                                            @endif

                                            <!-- Bouton Quiz (Bloqué si échec 3 fois) -->
                                            @if(!$isLessonAccessible || ($quizStatus && $quizStatus['all_failed']))
                                                <span class="px-4 py-2 text-sm font-medium {{ $buttonClass }} rounded-lg">
                                                    {{ $buttonText }}
                                                </span>
                                            @else

                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-grey-500 italic">Aucune leçon disponible pour ce chapitre.</p>
                        @endif

                        @if(!$isChapterAccessible)
                            <div class="mt-4 p-3 bg-grey-100 rounded-lg text-grey-600 text-sm">
                                <i class="fas fa-info-circle mr-1"></i>
                                Complétez le chapitre précédent pour déverrouiller ce chapitre
                            </div>
                        @endif
                    </div>
                @endforeach

                <!-- Carte Quiz de la Matière -->
                @if($subjectQuiz)
                    <div class="subject-quiz-card rounded-xl p-6 shadow-lg mb-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-white mb-2">Quiz Final: {{ $coursInstance->matiere->nom }}</h3>
                                <p class="text-amber-100">Testez vos connaissances sur l'ensemble de la matière</p>

                                @if($subjectQuizStatus)
                                    <div class="mt-2 text-amber-100 text-sm">
                                        @if($subjectQuizStatus['has_passed'])
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Vous avez réussi ce quiz!
                                        @elseif($subjectQuizStatus['all_failed'])
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Quiz bloqué après 3 tentatives
                                        @elseif($subjectQuizStatus['total_attempts'] > 0)
                                            <i class="fas fa-redo mr-1"></i>
                                            {{ $subjectQuizStatus['total_attempts'] }}/3 tentatives utilisées
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center">
                                @if($subjectQuizAvailable)
                                    @php
                                        if ($subjectQuizStatus['has_passed']) {
                                            $buttonText = 'Mode Pratique';
                                            $buttonClass = 'bg-white bg-opacity-20 text-white hover:bg-white hover:bg-opacity-30';
                                            $icon = 'fa-graduation-cap';
                                        } elseif ($subjectQuizStatus['all_failed']) {
                                            $buttonText = 'Quiz Bloqué';
                                            $buttonClass = 'bg-red-500 text-white cursor-not-allowed opacity-70';
                                            $icon = 'fa-lock';
                                        } elseif ($subjectQuizStatus['total_attempts'] > 0) {
                                            $buttonText = 'Continuer';
                                            $buttonClass = 'bg-amber-500 text-white hover:bg-amber-600';
                                            $icon = 'fa-redo';
                                        } else {
                                            $buttonText = 'Commencer le Quiz';
                                            $buttonClass = 'bg-white text-amber-600 hover:bg-amber-50';
                                            $icon = 'fa-play';
                                        }
                                    @endphp

                                    <a href="{{ route('etudiant.quiz.show', $subjectQuiz->id) }}"
                                       class="px-6 py-3 {{ $buttonClass }} font-medium rounded-lg transition-all duration-200 flex items-center justify-center
                                       {{ $subjectQuizStatus['all_failed'] ? 'cursor-not-allowed' : 'hover:opacity-90' }}">
                                        <i class="fas {{ $icon }} mr-2"></i>
                                        {{ $buttonText }}
                                    </a>
                                @else
                                    <span class="px-6 py-3 bg-white bg-opacity-20 text-white font-medium rounded-lg cursor-not-allowed opacity-70 flex items-center justify-center">
                                        <i class="fas fa-lock mr-2"></i>
                                        Quiz verrouillé
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 text-amber-100 text-sm">
                            <i class="fas fa-info-circle mr-1"></i>
                            @if($subjectQuizAvailable)
                                @if($subjectQuizStatus['has_passed'])
                                    Félicitations! Vous pouvez refaire ce quiz en mode pratique.
                                @elseif($subjectQuizStatus['all_failed'])
                                    Vous avez épuisé toutes vos tentatives. Contactez votre formateur.
                                @else
                                    Complétez ce quiz final pour valider la matière.
                                    {{ 3 - $subjectQuizStatus['total_attempts'] }} tentative(s) restante(s).
                                @endif
                            @else
                                Complétez tous les chapitres pour déverrouiller le quiz final
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="glass-effect rounded-xl p-8 text-center">
                <i class="fas fa-folder-open text-grey-400 text-5xl mb-4"></i>
                <h3 class="text-xl font-semibold text-grey-700 mb-2">Aucun chapitre disponible</h3>
                <p class="text-grey-500">Aucun chapitre n'est disponible pour ce cours pour le moment.</p>
            </div>
        @endif

    @else
        <!-- Fil d'Ariane de Navigation -->
        <nav class="mb-8" id="breadcrumb">
            <div class="flex items-center space-x-2 text-sm">
                <span class="text-primary-600">
                    <i class="fas fa-home mr-1"></i> Accueil
                </span>
                <i class="fas fa-chevron-right text-grey-400"></i>
                <span class="text-grey-600">Semestres</span>
            </div>
        </nav>

        <!-- Grille des Semestres -->
        <section id="semestersGrid" class="animate-slide-up">
            <h2 class="text-3xl font-bold text-grey-800 mb-8 text-center">
                Choisissez votre <span class="text-primary-600">semestre</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($matieresParTrimestre as $trimestre => $matieres)
                <div class="semester-card rounded-2xl p-8 text-white cursor-pointer animate-bounce-gentle"
                     data-trimestre="{{ $trimestre }}">

                    <div class="text-center">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-book-open text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Semestre {{ $trimestre }}</h3>
                        <p class="opacity-90 mb-4">
                            @if($trimestre == 1)
                                Septembre - janvier
                            @elseif($trimestre == 2)
                                Mars - août
                            @else
                                Mai - août
                            @endif
                        </p>
                        <div class="flex justify-center space-x-4 text-sm">
                            <span><i class="fas fa-layer-group mr-1"></i> {{ count($matieres) }} Cours</span>
                            <span><i class="fas fa-clock mr-1"></i> {{ count($matieres) * 30 }}h</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <!-- Section des Cours -->
        <section id="coursesSection" style="display: none;" class="animate-slide-down">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-grey-800">
                    Cours du <span class="text-primary-600" id="semesterTitle">Semestre</span>
                </h2>
                <button onclick="showSemesters()"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour aux semestres
                </button>
            </div>

            <div id="coursesList" class="space-y-6">
                <!-- Les cours seront remplis par JavaScript -->
            </div>
        </section>
    @endif
</main>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
    const coursesData = {
    @foreach($matieresParTrimestre as $trimestre => $matieres)
    {{ $trimestre }}: [
        @foreach($matieres as $index => $matiereData)
        {
            id: {{ $matiereData['cours_instance_id'] }},
            name: {!! json_encode($matiereData['matiere']->nom) !!},
            description: {!! json_encode($matiereData['matiere']->description ?? 'Aucune description disponible') !!},
            instructor: {!! json_encode($matiereData['formateur']) !!},
            progress: {{ $matiereData['progress'] }},
            completed_lessons: {{ $matiereData['completed_lessons'] }},
            total_lessons: {{ $matiereData['total_lessons'] }},
            subject_quiz_passed: {{ $matiereData['subject_quiz_passed'] ? 'true' : 'false' }},
            has_subject_quiz: {{ $matiereData['has_subject_quiz'] ? 'true' : 'false' }},
            chapters_count: {{ $matiereData['chapters_count'] }},
            lessons_count: {{ $matiereData['lessons_count'] }}
        }@if(!$loop->last),@endif
        @endforeach
    ]@if(!$loop->last),@endif
    @endforeach
    };

    let currentView = 'semesters';
    let currentSemester = null;

    function showSemesters() {
        currentView = 'semesters';
        currentSemester = null;

        document.getElementById('semestersGrid').style.display = 'block';
        document.getElementById('coursesSection').style.display = 'none';

        document.getElementById('breadcrumb').innerHTML = `
            <div class="flex items-center space-x-2 text-sm">
                <span class="text-primary-600">
                    <i class="fas fa-home mr-1"></i> Accueil
                </span>
                <i class="fas fa-chevron-right text-grey-400"></i>
                <span class="text-grey-600">Semestres</span>
            </div>
        `;

        gsap.from('#semestersGrid .semester-card', {
            duration: 0.8, y: 50, opacity: 0, stagger: 0.2, ease: 'power2.out'
        });
    }

    function showCourses(semesterNumber) {
        currentView = 'courses';
        currentSemester = semesterNumber;

        document.getElementById('semestersGrid').style.display = 'none';
        document.getElementById('coursesSection').style.display = 'block';
        document.getElementById('semesterTitle').textContent = `Semestre ${semesterNumber}`;

        document.getElementById('breadcrumb').innerHTML = `
            <div class="flex items-center space-x-2 text-sm">
                <button onclick="window.showSemesters()" class="text-primary-600 hover:text-primary-800 transition-colors">
                    <i class="fas fa-home mr-1"></i> Accueil
                </button>
                <i class="fas fa-chevron-right text-grey-400"></i>
                <span class="text-grey-600">Semestre ${semesterNumber}</span>
            </div>
        `;

        const coursesList = document.getElementById('coursesList');
        const courses = coursesData[semesterNumber] || [];

        coursesList.innerHTML = courses.map(course => `
            <div class="course-card rounded-xl p-6 shadow-lg cursor-pointer" data-course-id="${course.id}">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-grey-800 mb-2">${course.name}</h3>
                        <p class="text-grey-600 mb-3">${course.description}</p>
                        <div class="flex items-center text-primary-600 mb-4">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>
                            <span class="font-medium">${course.instructor}</span>
                        </div>
                        <div class="text-sm text-grey-600 mb-2">
                            ${course.subject_quiz_passed ?
                                '<span class="text-green-600"><i class="fas fa-check-circle mr-1"></i> Quiz final réussi - Cours terminé</span>' :
                                course.all_lessons_completed ?
                                `<span class="text-amber-600"><i class="fas fa-exclamation-circle mr-1"></i> Toutes les leçons terminées - Quiz final requis</span>` :
                                `<i class="fas fa-check-circle mr-1 ${course.completed_lessons > 0 ? 'text-green-500' : 'text-grey-400'}"></i>
                                ${course.completed_lessons}/${course.total_lessons} leçons complétées`
                            }
                        </div>
                    </div>
                    <div class="flex flex-col items-center ml-4">
                        <div class="w-16 h-16 rounded-full ${course.subject_quiz_passed ? 'bg-green-100 text-green-600' : 'bg-primary-50 text-primary-600'} flex items-center justify-center mb-2">
                            <span class="font-bold">${course.progress}%</span>
                        </div>
                        <div class="w-12 h-2 bg-grey-200 rounded-full overflow-hidden">
                            <div class="h-full ${course.subject_quiz_passed ? 'bg-green-500' : 'bg-primary-500'} rounded-full transition-all duration-300"
                                style="width: ${course.progress}%"></div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex space-x-4 text-sm text-grey-500">
                        <span><i class="fas fa-book mr-1"></i> ${course.chapters_count} chapitres</span>
                        <span><i class="fas fa-play-circle mr-1"></i> ${course.lessons_count} leçons</span>
                        ${course.has_subject_quiz ? '<span><i class="fas fa-trophy mr-1"></i> Quiz final</span>' : ''}
                    </div>
                    <div class="flex items-center ${course.subject_quiz_passed ? 'text-green-600' : 'text-primary-600'}">
                        <span class="mr-2">${course.subject_quiz_passed ? 'Terminé' : 'Continuer'}</span>
                        <i class="fas ${course.subject_quiz_passed ? 'fa-check' : 'fa-arrow-right'}"></i>
                    </div>
                </div>
            </div>
        `).join('');

        document.querySelectorAll('.course-card').forEach(card => {
            card.addEventListener('click', function () {
                window.location.href = `/etudiant/course/${this.dataset.courseId}`;
            });
        });

        gsap.from('.course-card', {
            duration: 0.6, y: 30, opacity: 0, stagger: 0.1, ease: 'power2.out'
        });
    }

    // ✅ window.* APRÈS les déclarations
    window.showSemesters = showSemesters;
    window.showCourses = showCourses;

    function initPage() {
        document.querySelectorAll('.semester-card').forEach(card => {
            card.addEventListener('click', function () {
                showCourses(this.dataset.trimestre);
            });
        });

        if (typeof gsap !== 'undefined') {
            gsap.from('.animate-bounce-gentle', {
                duration: 1, y: 50, opacity: 0, stagger: 0.2, ease: 'power2.out'
            });
        }

        window.addEventListener('scroll', function () {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const bar = document.getElementById('progressBar');
            if (bar) bar.style.width = ((winScroll / height) * 100) + '%';
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPage);
    } else {
        initPage();
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && currentView === 'courses') showSemesters();
    });
</script>
@endsection