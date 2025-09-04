@extends('etudiant.layouts.app')
@section('title', 'Quiz - ' . $quiz->titre)

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
                    'cool-grey': '#94a3b8',
                    'warning': '#f59e0b',
                    'danger': '#ef4444'
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

    /* Quiz specific styles */
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #2563eb, #1e40af);
        color: white;
        border-radius: 0.75rem;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
        margin-bottom: 2rem;
    }

    .back-button:hover {
        background: linear-gradient(135deg, #1e40af, #1e3a8a);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(37, 99, 235, 0.3);
    }

    .quiz-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 0.5rem;
        position: relative;
        padding-bottom: 0.75rem;
    }

    .quiz-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #4F46E5, #6366F1);
        border-radius: 2px;
    }

    .quiz-description {
        color: #6B7280;
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .quiz-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 2rem;
    }

    .meta-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .meta-questions {
        background: linear-gradient(135deg, #EFF6FF, #DBEAFE);
        color: #1E40AF;
    }

    .meta-threshold {
        background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
        color: #065F46;
    }
    
    .meta-time {
        background: linear-gradient(135deg, #FEF3C7, #FDE68A);
        color: #92400E;
    }

    .question-item {
        margin-bottom: 2.5rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #E5E7EB;
    }

    .question-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .question-text {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 1.25rem;
        position: relative;
        padding-left: 2.5rem;
    }

    .question-number {
        position: absolute;
        left: 0;
        top: 0;
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #4F46E5, #6366F1);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .answer-options {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .answer-label {
        display: flex;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border: 2px solid #E5E7EB;
        border-radius: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        background: white;
    }

    .answer-label:hover {
        border-color: #4F46E5;
        background: #F5F3FF;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
    }

    /* Custom checkbox styling */
    .answer-input[type="checkbox"] {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        width: 22px;
        height: 22px;
        border: 2px solid #D1D5DB;
        border-radius: 6px;
        margin-right: 1rem;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
    }

    .answer-input[type="checkbox"]:checked {
        background-color: #4F46E5;
        border-color: #4F46E5;
    }

    .answer-input[type="checkbox"]:checked::after {
        content: '';
        position: absolute;
        left: 6px;
        top: 2px;
        width: 6px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    .answer-input[type="checkbox"]:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }

    .answer-text {
        font-size: 1rem;
        color: #374151;
        flex: 1;
    }

    .submit-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1.25rem 2.5rem;
        background: linear-gradient(135deg, #10B981, #059669);
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        border: none;
        border-radius: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
        margin-top: 1.5rem;
        width: 100%;
    }

    .submit-button:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(16, 185, 129, 0.4);
    }

    .submit-container {
        text-align: center;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 1px dashed #D1D5DB;
    }
    
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #4F46E5, #6366F1);
        color: white;
        font-weight: 500;
        border: none;
        border-radius: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(79, 70, 229, 0.3);
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #4338CA, #4F46E5);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(79, 70, 229, 0.4);
    }
    
    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #6B7280, #9CA3AF);
        color: white;
        font-weight: 500;
        border: none;
        border-radius: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(107, 114, 128, 0.3);
    }
    
    .btn-secondary:hover {
        background: linear-gradient(135deg, #4B5563, #6B7280);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(107, 114, 128, 0.4);
    }

    /* Progress indicator */
    .quiz-progress {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 1rem;
        backdrop-filter: blur(10px);
    }

    .progress-text {
        font-weight: 600;
        color: #4B5563;
    }

    .progress-bar {
        flex: 1;
        height: 8px;
        background: #E5E7EB;
        border-radius: 4px;
        overflow: hidden;
        margin: 0 1rem;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4F46E5, #6366F1);
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    /* Timer styling */
    .quiz-timer {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #FEF3C7;
        color: #92400E;
        border-radius: 9999px;
        font-weight: 600;
    }
    
    .quiz-timer.warning {
        background: #FEF3C7;
        color: #92400E;
        animation: pulse 2s infinite;
    }
    
    .quiz-timer.danger {
        background: #FEE2E2;
        color: #DC2626;
        animation: pulse 1s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }

    
   

    /* Multiple answers notification */
    .multiple-answers-note {
        background: linear-gradient(135deg, #FEF3C7, #FDE68A);
        color: #92400E;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .multiple-answers-note i {
        font-size: 1.1rem;
    }

    /* Refresh warning modal */
    .refresh-warning-modal .modal-content {
        max-width: 500px;
    }
    
    .refresh-warning-modal .modal-title {
        color: #DC2626;
    }
    
    .refresh-warning-modal .modal-message {
        font-size: 1.1rem;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .bg-shape { display: none; }
        .main-container { padding: 1rem; }
        
        .quiz-card {
            padding: 1.5rem;
        }
        
        .quiz-title {
            font-size: 1.5rem;
        }
        
        .question-text {
            font-size: 1.125rem;
            padding-left: 2.25rem;
        }
        
        .question-number {
            width: 28px;
            height: 28px;
        }
        
        .answer-label {
            padding: 1rem;
        }
        
        .quiz-meta {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .quiz-progress {
            flex-direction: column;
            gap: 1rem;
        }
        
        .progress-bar {
            width: 100%;
            margin: 0;
        }
        
        .modal-content {
            padding: 1.5rem;
            margin: 1rem;
        }
        
        .modal-buttons {
            flex-direction: column;
        }
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
        <a href="{{ route('etudiant.lecon.show', $lecon->id) }}" id="backButton" class="back-button">
            <i class="fas fa-arrow-left"></i> Retour à la leçon
        </a>
    </div>

    <!-- Quiz Progress -->
    <div class="quiz-progress glass-card slide-up">
        <div class="progress-text">
            Question <span id="current-question">1</span> sur {{ count($randomQuestions) }}
        </div>
        <div class="progress-bar">
            <div class="progress-fill" id="progress-fill" style="width: 5%"></div>
        </div>
        @if($quiz->duree_minutes)
        <div class="quiz-timer" id="quiz-timer-container">
            <i class="fas fa-clock"></i>
            <span id="quiz-timer">00:00</span>
        </div>
        @endif
    </div>

    <!-- Quiz Card -->
    <div class="quiz-card glass-card slide-up hover-lift">
        <h1 class="quiz-title">{{ $quiz->titre }}</h1>
        <p class="quiz-description">{{ $quiz->description }}</p>

        <div class="quiz-meta">
            <span class="meta-badge meta-questions">
                <i class="fas fa-list-ol"></i> {{ count($randomQuestions) }} questions
            </span>
            <span class="meta-badge meta-threshold">
                <i class="fas fa-trophy"></i> Seuil: {{ $quiz->seuil_reussite }}%
            </span>
            @if($quiz->duree_minutes)
            <span class="meta-badge meta-time">
                <i class="fas fa-stopwatch"></i> Durée: {{ $quiz->duree_minutes }} min
            </span>
            @endif
        </div>

        <form action="{{ route('etudiant.quiz.submit', $quiz->id) }}" method="POST" id="quiz-form">
            @csrf
            <input type="hidden" name="attempt_id" value="{{ $activeAttempt->id }}">

            @foreach($randomQuestions as $index => $question)
                @php
                    $hasMultipleCorrect = $question->reponses->where('est_correcte', 1)->count() > 1;
                @endphp
                
                <div class="question-item fade-in" id="question-{{ $index+1 }}" 
                     style="{{ $index > 0 ? 'display: none;' : '' }}">
                    <h3 class="question-text">
                        <span class="question-number">{{ $index+1 }}</span>
                        {{ $question->enonce }}
                    </h3>

                    @if($hasMultipleCorrect)
                    <div class="multiple-answers-note">
                        <i class="fas fa-info-circle"></i>
                        Cette question peut avoir plusieurs réponses correctes
                    </div>
                    @endif

                    <div class="answer-options">
                        @foreach($question->reponses as $reponse)
                            <label class="answer-label">
                                <input type="checkbox" 
                                    name="answers[{{ $question->id }}][]" 
                                    value="{{ $reponse->id }}" 
                                    class="answer-input"
                                    onchange="saveAnswer({{ $question->id }}, {{ $reponse->id }}, this.checked); updateProgress({{ $index+1 }}, {{ count($randomQuestions) }})">
                                <span class="answer-text">{{ $reponse->texte }}</span>
                            </label>
                        @endforeach
                    </div>
                    
                    <!-- Navigation buttons -->
                    <div class="flex justify-between mt-6">
                        @if($index > 0)
                            <button type="button" class="btn-secondary" onclick="showQuestion({{ $index }})">
                                <i class="fas fa-arrow-left"></i> Précédent
                            </button>
                        @else
                            <div></div>
                        @endif
                        
                        @if($index < count($randomQuestions) - 1)
                            <button type="button" class="btn-primary" onclick="showQuestion({{ $index+2 }})">
                                Suivant <i class="fas fa-arrow-right"></i>
                            </button>
                        @else
                            <button type="submit" class="submit-button">
                                <i class="fas fa-paper-plane"></i> Terminer le quiz
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </form>
    </div>
</main>
@endsection

@section('scripts')
<script>
    // Quiz configuration
    const quizConfig = {
        quizId: {{ $quiz->id }},
        attemptId: {{ $activeAttempt->id }},
        checkTimeUrl: "{{ route('etudiant.quiz.check-time', ['quiz' => $quiz->id, 'attempt' => $activeAttempt->id]) }}",
        autoSubmitUrl: "{{ route('etudiant.quiz.auto-submit', ['quiz' => $quiz->id, 'attempt' => $activeAttempt->id]) }}",
        saveAnswerUrl: "{{ route('etudiant.quiz.save-answer', $quiz->id) }}",
        resultsUrl: "{{ route('etudiant.quiz.results', ['quiz' => $quiz->id, 'attempt' => $activeAttempt->id]) }}",
        backUrl: "{{ route('etudiant.lecon.show', $lecon->id) }}"
    };
    
    let timerInterval;
    let isSubmitting = false;

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

        // Start checking server time if time limit is set
        if ({{ $quiz->duree_minutes ? 'true' : 'false' }}) {
            startTimeChecker();
        }
        
        // Load saved answers
        loadSavedAnswers();
    });

    // Check server time periodically
    function startTimeChecker() {
        checkTime();
        
        // Check time every 5 seconds for more immediate response
        timerInterval = setInterval(checkTime, 5000);
    }

    // Check if time has expired on server
    function checkTime() {
        fetch(quizConfig.checkTimeUrl, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.expired) {
                clearInterval(timerInterval);
                // Time expired - submit immediately
                submitQuizImmediately();
            } else {
                updateTimerDisplay(data.remaining_time);
            }
        })
        .catch(error => {
            console.error('Error checking time:', error);
        });
    }

    function updateTimerDisplay(remainingTime) {
        const minutes = Math.floor(remainingTime / 60);
        const seconds = remainingTime % 60;
        
        document.getElementById('quiz-timer').textContent = 
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
        // Change timer color when time is running out
        const timerContainer = document.getElementById('quiz-timer-container');
        if (remainingTime <= 60) {
            timerContainer.classList.add('danger');
            timerContainer.classList.remove('warning');
        } else if (remainingTime <= 300) {
            timerContainer.classList.add('warning');
            timerContainer.classList.remove('danger');
        } else {
            timerContainer.classList.remove('warning', 'danger');
        }
    }

    // Submit quiz immediately when time expires
    function submitQuizImmediately() {
        if (isSubmitting) return;
        isSubmitting = true;
        
        // Show submitting message
        const overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
        overlay.style.color = 'white';
        overlay.style.display = 'flex';
        overlay.style.justifyContent = 'center';
        overlay.style.alignItems = 'center';
        overlay.style.zIndex = '10000';
        overlay.style.fontSize = '1.5rem';
        overlay.style.fontWeight = 'bold';
        overlay.innerHTML = '<div>Temps écoulé ! Soumission de votre quiz en cours...</div>';
        
        document.body.appendChild(overlay);
        
        // Create a form to submit the quiz
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = quizConfig.autoSubmitUrl;
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add attempt ID
        const attemptId = document.createElement('input');
        attemptId.type = 'hidden';
        attemptId.name = 'attempt_id';
        attemptId.value = quizConfig.attemptId;
        form.appendChild(attemptId);
        
        // Add to document and submit
        document.body.appendChild(form);
        form.submit();
    }

    // Quiz navigation
    function showQuestion(questionNumber) {
        // Hide all questions
        document.querySelectorAll('.question-item').forEach(item => {
            item.style.display = 'none';
        });
        
        // Show selected question
        document.getElementById('question-' + questionNumber).style.display = 'block';
        
        // Update progress indicator
        document.getElementById('current-question').textContent = questionNumber;
        
        // Update progress bar
        const progressPercent = (questionNumber / {{ count($randomQuestions) }}) * 100;
        document.getElementById('progress-fill').style.width = progressPercent + '%';
        
        // Scroll to top of question
        window.scrollTo({
            top: document.getElementById('question-' + questionNumber).offsetTop - 100,
            behavior: 'smooth'
        });
    }

    // Update progress bar
    function updateProgress(currentQuestion, totalQuestions) {
        const progressPercent = (currentQuestion / totalQuestions) * 100;
        document.getElementById('progress-fill').style.width = progressPercent + '%';
    }
    
    // Save answer via AJAX
    function saveAnswer(questionId, answerId, isChecked) {
        fetch(quizConfig.saveAnswerUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                question_id: questionId,
                answer_id: answerId,
                is_checked: isChecked
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.expired) {
                // Time expired during answer saving
                submitQuizImmediately();
            }
        });
    }
    
    // Load saved answers from session
    function loadSavedAnswers() {
        // This will be handled by the server-side session data
        // The checkboxes will be pre-checked based on the session data
    }

    // Form submission
    document.getElementById('quiz-form').addEventListener('submit', function(e) {
        isSubmitting = true;
        clearInterval(timerInterval);
    });
</script>
@endsection
