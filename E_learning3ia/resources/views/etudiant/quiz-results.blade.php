@extends('etudiant.layouts.app')
@section('title', 'Résultats du Quiz - ' . $quiz->titre)

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    'royal-blue': '#2563eb',
                    'deep-blue': '#1e40af',
                    'light-blue': '#3b82f6',
                    'slate-grey': '#64748b',
                    'cool-grey': '#94a3b8'
                },
                fontFamily: {
                    'inter': ['Inter', 'sans-serif']
                },
                backdropBlur: {
                    xs: '2px'
                }
            }
        }
    }
</script>

<style>
    * {
        font-family: 'Inter', sans-serif;
    }
    
    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
        min-height: 100vh;
        position: relative;
        overflow-x: hidden;
    }

    /* Floating background elements */
    .bg-shape {
        position: fixed;
        border-radius: 50%;
        opacity: 0.1;
        z-index: -1;
        animation: float 6s ease-in-out infinite;
    }

    .bg-shape-1 { top: 10%; left: 10%; width: 300px; height: 300px; background: #2563eb; animation-delay: 0s; }
    .bg-shape-2 { top: 60%; right: 15%; width: 200px; height: 200px; background: #1e40af; animation-delay: 2s; }
    .bg-shape-3 { bottom: 20%; left: 20%; width: 250px; height: 250px; background: #3b82f6; animation-delay: 4s; }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    /* Glass morphism effects */
    .glass {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    /* Scroll progress indicator */
    .scroll-progress {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 4px;
        background: linear-gradient(90deg, #2563eb, #1e40af);
        z-index: 1000;
        transition: width 0.3s ease;
    }

    /* Custom animations */
    .slide-up {
        transform: translateY(50px);
        opacity: 0;
        animation: slideUp 0.8s ease forwards;
    }

    @keyframes slideUp {
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .fade-in {
        opacity: 0;
        animation: fadeIn 0.6s ease forwards;
    }

    @keyframes fadeIn {
        to { opacity: 1; }
    }

    /* Hover effects */
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    /* Quiz results specific styles */
    .score-circle { 
        width: 120px; height: 120px; border-radius: 50%; 
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto; font-size: 2rem; font-weight: bold;
        border: 6px solid #e5e7eb;
        background: white;
    }
    .score-passed { 
        border-color: #10B981; 
        color: #10B981;
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    }
    .score-failed { 
        border-color: #EF4444; 
        color: #EF4444;
        background: linear-gradient(135deg, #fee2e2, #fecaca);
    }
    .question-item { 
        margin-bottom: 1.5rem; 
        padding: 1.5rem; 
        border-radius: 1rem; 
        border: 1px solid #e5e7eb;
        background: white;
        transition: all 0.3s ease;
    }
    .question-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    .question-correct { 
        background-color: #F0FDF4; 
        border-color: #BBF7D0;
        border-left: 4px solid #10B981;
    }
    .question-incorrect { 
        background-color: #FEF2F2; 
        border-color: #FECACA;
        border-left: 4px solid #EF4444;
    }
    .question-partial { 
        background-color: #FFFBEB; 
        border-color: #FDE68A;
        border-left: 4px solid #F59E0B;
    }
    .answer-option { 
        padding: 0.75rem; 
        margin: 0.5rem 0; 
        border-radius: 0.5rem; 
        border: 1px solid #e5e7eb;
        background: white;
        transition: all 0.2s ease;
    }
    .answer-option:hover {
        background: #f8fafc;
    }
    .answer-correct { 
        background-color: #D1FAE5; 
        border-color: #10B981;
    }
    .answer-incorrect { 
        background-color: #FEE2E2; 
        border-color: #EF4444;
    }
    .answer-missing { 
        background-color: #EFF6FF; 
        border-color: #93C5FD;
        border-style: dashed;
    }
    .answer-selected { 
        border: 2px solid #3B82F6;
        background: #eff6ff;
    }
    .answer-selected-incorrect {
        border: 2px solid #EF4444;
        background: #FEE2E2;
    }

    /* Button styles */
    .btn-primary {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        margin: 4px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(37, 99, 235, 0.4);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        margin: 4px;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(16, 185, 129, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        margin: 4px;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(100, 116, 139, 0.4);
    }

    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .status-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .status-failed {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #b91c1c;
    }

    .status-partial {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
    }

    .status-in-progress {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
    }

    /* Progress bar */
    .progress-bar {
        height: 8px;
        border-radius: 4px;
        background: #e5e7eb;
        overflow: hidden;
        margin: 1rem 0;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 1s ease-in-out;
    }

    .progress-passed {
        background: linear-gradient(90deg, #10b981, #059669);
    }

    .progress-failed {
        background: linear-gradient(90deg, #ef4444, #dc2626);
    }

    .progress-partial {
        background: linear-gradient(90deg, #f59e0b, #d97706);
    }

    /* Multiple answers indicator */
    .multiple-answers-indicator {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    /* Answer status icons */
    .answer-status {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
    }

    .status-correct {
        background: #d1fae5;
        color: #065f46;
    }

    .status-incorrect {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-missing {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .bg-shape { display: none; }
        .main-container { padding: 1rem; }
        .question-item { padding: 1rem; }
        .score-circle { width: 100px; height: 100px; font-size: 1.5rem; }
        .actions { flex-direction: column; }
        .actions a { width: 100%; justify-content: center; }
    }
    .score-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background-color: #f8f9fa;
    border: 4px solid #17a2b8;
    display: flex;
    align-items: center;
    justify-content: center;
}

.score-text {
    font-size: 24px;
    font-weight: bold;
    color: #17a2b8;
}
</style>
@endsection

@section('content')
<!-- Scroll Progress -->
<div class="scroll-progress" id="scrollProgress"></div>

<!-- Background Shapes -->
<div class="bg-shape bg-shape-1"></div>
<div class="bg-shape bg-shape-2"></div>
<div class="bg-shape bg-shape-3"></div>

<main class="main-container max-w-4xl mx-auto px-6 py-8">
    <!-- Spacer for fixed header -->
    <div class="h-20"></div>

    <!-- Back Button -->
    <div class="mb-8 slide-up">
        <a href="{{ route('etudiant.lecon.show', $lecon->id) }}" class="btn-primary">
            <i class="fas fa-arrow-left"></i> Retour à la leçon
        </a>
    </div>

    <!-- Quiz Results Header -->
    <div class="glass-card rounded-2xl p-8 mb-8 slide-up hover-lift">
        <div class="result-header text-center">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Résultats du Quiz</h1>
            <p class="text-slate-600 mb-6">{{ $quiz->titre }}</p>
            
            <div class="score-circle {{ $attempt->statut === 'reussie' ? 'score-passed' : ($attempt->statut === 'echouee' ? 'score-failed' : 'score-partial') }}">
                {{ $attempt->score_obtenu }}%
            </div>
            
            <div class="mt-6">
                <span class="status-badge {{ $attempt->statut === 'reussie' ? 'status-success' : ($attempt->statut === 'echouee' ? 'status-failed' : 'status-partial') }}">
                    <i class="fas {{ $attempt->statut === 'reussie' ? 'fa-check-circle' : ($attempt->statut === 'echouee' ? 'fa-times-circle' : 'fa-exclamation-circle') }}"></i>
                    {{ ucfirst($attempt->statut) }}
                </span>
            </div>
            
            <p class="text-slate-700 mt-4">Seuil de réussite: {{ $quiz->seuil_reussite }}%</p>
            
            <!-- Progress bar showing score -->
            <div class="progress-bar mt-4 mx-auto max-w-md">
                <div class="progress-fill {{ $attempt->statut === 'reussie' ? 'progress-passed' : ($attempt->statut === 'echouee' ? 'progress-failed' : 'progress-partial') }}" 
                     style="width: {{ $attempt->score_obtenu }}%">
                </div>
            </div>
            
            <p class="text-sm text-slate-500 mt-2">Terminé le: {{ $attempt->updated_at->format('d/m/Y à H:i') }}</p>
            
            <!-- Display number of questions shown vs total available -->
            <div class="mt-4 text-sm text-slate-600">
            </div>
        </div>
    </div>
    @if(!$hasPassed)
<div class="alert alert-info">
    <i class="fas fa-info-circle"></i>
    <strong>Tentatives:</strong> 
    Vous avez utilisé {{ $attemptsCount }} sur 3 tentatives. 
    @if($remainingAttempts > 0)
        Il vous reste {{ $remainingAttempts }} tentative(s).
    @else
        Vous n'avez plus de tentatives disponibles.
    @endif
</div>

@if($remainingAttempts > 0 && $attempt->statut == 'echouee')
<div class="text-center mt-3">
    <a href="{{ route('etudiant.quiz.retry', $quiz->id) }}" class="btn btn-primary">
        <i class="fas fa-redo"></i> Nouvelle Tentative
    </a>
</div>
@endif
@else
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    <strong>Félicitations !</strong> Vous avez réussi ce quiz. Vous pouvez continuer à pratiquer sans affecter votre score.
</div>

<div class="text-center mt-3">
    <a href="{{ route('etudiant.quiz.retry', $quiz->id) }}" class="btn btn-info">
        <i class="fas fa-graduation-cap"></i> Exercice Pratique
    </a>
</div>
@endif

    <!-- Questions Review -->
    <div class="glass-card rounded-2xl p-8 slide-up">
        <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
            <i class="fas fa-list-check text-royal-blue"></i>
            Détail des questions ({{ count($questions) }} sur {{ $quiz->questions()->count() }})
        </h2>
        
        <div class="questions-review">
            @foreach($questions as $index => $question)
            @php
                // Get user answers for this question
                $userAnswerIds = $userAnswers[$question->id] ?? [];
                $correctAnswerIds = $question->reponses->where('est_correcte', 1)->pluck('id')->toArray();
                
                // Calculate question score
                $correctSelected = array_intersect($userAnswerIds, $correctAnswerIds);
                $incorrectSelected = array_diff($userAnswerIds, $correctAnswerIds);
                $missingCorrect = array_diff($correctAnswerIds, $userAnswerIds);
                
                $totalCorrect = count($correctAnswerIds);
                $score = $totalCorrect > 0 ? (count($correctSelected) / $totalCorrect) : 0;
                
                // Determine question status
                $questionStatus = 'incorrect';
                if (count($incorrectSelected) === 0 && count($missingCorrect) === 0) {
                    $questionStatus = 'correct';
                } elseif (count($correctSelected) > 0 && (count($incorrectSelected) > 0 || count($missingCorrect) > 0)) {
                    $questionStatus = 'partial';
                }
            @endphp
            
            <div class="question-item {{ $questionStatus === 'correct' ? 'question-correct' : ($questionStatus === 'partial' ? 'question-partial' : 'question-incorrect') }} fade-in">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center 
                        {{ $questionStatus === 'correct' ? 'bg-green-100 text-green-600' : ($questionStatus === 'partial' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600') }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-slate-800 text-lg">{{ $question->enonce }}</h3>
                        
                        @if(count($correctAnswerIds) > 1)
                        <div class="multiple-answers-indicator mt-2">
                            <i class="fas fa-info-circle"></i>
                            Cette question avait plusieurs réponses correctes
                        </div>
                        @endif
                        
                        @if($questionStatus === 'partial')
                        <div class="text-amber-600 text-sm mt-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            Réponse partiellement correcte
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="answers ml-11">
                    @foreach($question->reponses as $answer)
                    @php
                        $isCorrect = $answer->est_correcte;
                        $isSelected = in_array($answer->id, $userAnswerIds);
                        $isMissingCorrect = $isCorrect && !$isSelected;
                    @endphp
                    
                    <div class="answer-option 
                        {{ $isCorrect ? 'answer-correct' : '' }}
                        {{ $isSelected && !$isCorrect ? 'answer-incorrect' : '' }}
                        {{ $isMissingCorrect ? 'answer-missing' : '' }}
                        {{ $isSelected ? ($isCorrect ? 'answer-selected' : 'answer-selected-incorrect') : '' }}">
                        
                        <div class="flex items-center justify-between">
                            <span>{{ $answer->texte }}</span>
                            <div class="flex items-center gap-2">
                                @if($isCorrect)
                                    <span class="answer-status status-correct">
                                        <i class="fas fa-check"></i> Correct
                                    </span>
                                @endif
                                @if($isSelected && !$isCorrect)
                                    <span class="answer-status status-incorrect">
                                        <i class="fas fa-times"></i> Votre choix
                                    </span>
                                @endif
                                @if($isSelected && $isCorrect)
                                    <span class="answer-status status-correct">
                                        <i class="fas fa-check"></i> Votre choix
                                    </span>
                                @endif
                                @if($isMissingCorrect)
                                    <span class="answer-status status-missing">
                                        <i class="fas fa-exclamation"></i> Manquante
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if(empty($userAnswerIds))
                <p class="text-red-600 mt-2 ml-11 flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i> Aucune réponse fournie
                </p>
                @endif
                
                <!-- Question score breakdown -->
                <div class="mt-3 ml-11 text-sm text-slate-600">
                    <p>
                        Score pour cette question: 
                        <span class="font-semibold {{ $questionStatus === 'correct' ? 'text-green-600' : ($questionStatus === 'partial' ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ round($score * 100) }}%
                        </span>
                        ({{ count($correctSelected) }}/{{ count($correctAnswerIds) }} réponses correctes)
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Actions -->
        <div class="actions mt-8 flex flex-wrap gap-3">
            <a href="{{ route('etudiant.quiz.retry', $quiz->id) }}" class="btn-primary">
                <i class="fas fa-redo"></i> Nouvelle tentative
            </a>
            <a href="{{ route('etudiant.lecon.show', $lecon->id) }}" class="btn-secondary">
                <i class="fas fa-book"></i> Retour à la leçon
            </a>
            
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    // Scroll progress indicator
    function updateScrollProgress() {
        const scrollTop = window.pageYOffset;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrollPercent = (scrollTop / docHeight) * 100;
        document.getElementById('scrollProgress').style.width = scrollPercent + '%';
    }

    window.addEventListener('scroll', updateScrollProgress);

    // GSAP animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animate elements on load
        gsap.from('.slide-up', {
            duration: 0.8,
            y: 50,
            opacity: 0,
            stagger: 0.2,
            ease: "power2.out"
        });

        gsap.from('.fade-in', {
            duration: 0.6,
            opacity: 0,
            stagger: 0.1,
            delay: 0.5,
            ease: "power2.out"
        });

        // Animate progress bar
        gsap.to('.progress-fill', {
            duration: 1.5,
            width: '{{ $attempt->score_obtenu }}%',
            ease: "power2.out",
            delay: 0.5
        });

        // Floating background shapes animation
        gsap.to('.bg-shape-1', {
            duration: 6,
            y: -20,
            rotation: 180,
            yoyo: true,
            repeat: -1,
            ease: "power2.inOut"
        });

        gsap.to('.bg-shape-2', {
            duration: 8,
            y: -30,
            rotation: -180,
            yoyo: true,
            repeat: -1,
            ease: "power2.inOut",
            delay: 2
        });

        gsap.to('.bg-shape-3', {
            duration: 7,
            y: -25,
            rotation: 360,
            yoyo: true,
            repeat: -1,
            ease: "power2.inOut",
            delay: 4
        });
    });

    // Add hover effects to buttons
    document.querySelectorAll('.btn-primary, .btn-success, .btn-secondary').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            gsap.to(this, { duration: 0.3, scale: 1.05, ease: "power2.out" });
        });
        
        btn.addEventListener('mouseleave', function() {
            gsap.to(this, { duration: 0.3, scale: 1, ease: "power2.out" });
        });
    });
</script>
@endsection