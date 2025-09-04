@extends('etudiant.layouts.app')

@section('title', 'Résultats de l\'Exercice Pratique - ' . $quiz->titre)

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
    .score-practice {
        border-color: #3B82F6;
        color: #3B82F6;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
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

    .status-practice {
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

    .progress-practice {
        background: linear-gradient(90deg, #3b82f6, #2563eb);
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

    /* Responsive design */
    @media (max-width: 768px) {
        .bg-shape { display: none; }
        .main-container { padding: 1rem; }
        .score-circle { width: 100px; height: 100px; font-size: 1.5rem; }
        .actions { flex-direction: column; }
        .actions a { width: 100%; justify-content: center; }
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

    <!-- Exercise Practice Results Header -->
    <div class="glass-card rounded-2xl p-8 mb-8 slide-up hover-lift">
        <div class="result-header text-center">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Résultats de l'Exercice Pratique</h1>
            <p class="text-slate-600 mb-6">{{ $quiz->titre }}</p>
            
            <div class="score-circle score-practice">
                {{ $score }}%
            </div>
            
            <div class="mt-6">
                <span class="status-badge status-practice">
                    <i class="fas fa-graduation-cap"></i>
                    Exercice Pratique
                </span>
            </div>
            
            <div class="alert alert-info mt-4 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
                <i class="fas fa-info-circle"></i> 
                Ceci était un exercice pratique. Votre score n'a pas été enregistré car vous avez déjà réussi ce quiz.
            </div>
            
            <p class="text-slate-700 mt-4">Seuil de réussite: {{ $quiz->seuil_reussite }}%</p>
            
            <!-- Progress bar showing score -->
            <div class="progress-bar mt-4 mx-auto max-w-md">
                <div class="progress-fill progress-practice" 
                     style="width: {{ $score }}%">
                </div>
            </div>
        </div>
    </div>

    <!-- Results Summary -->
    <div class="glass-card rounded-2xl p-8 slide-up">
        <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
            <i class="fas fa-chart-bar text-royal-blue"></i>
            Résumé de l'Exercice
        </h2>
        
        <div class="results-summary">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h6 class="text-lg font-medium text-slate-700 mb-2">Score Obtenu</h6>
                    <h4 class="text-3xl font-bold {{ $score >= $quiz->seuil_reussite ? 'text-green-600' : 'text-red-600' }}">
                        {{ $score }}%
                    </h4>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h6 class="text-lg font-medium text-slate-700 mb-2">Seuil de Réussite</h6>
                    <h4 class="text-3xl font-bold text-blue-600">{{ $quiz->seuil_reussite }}%</h4>
                </div>
            </div>
            
            <div class="mt-6">
                @if($score < $quiz->seuil_reussite)
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-exclamation-triangle"></i>
                        Vous n'avez pas atteint le seuil de réussite lors de cet exercice. Continuez à pratiquer !
                    </div>
                @else
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                        <i class="fas fa-check-circle"></i>
                        Félicitations ! Vous avez dépassé le seuil de réussite lors de cet exercice.
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="actions mt-8 flex flex-wrap gap-3">
            <a href="{{ route('etudiant.quiz.show', $quiz->id) }}" class="btn-primary">
                <i class="fas fa-redo"></i> Nouvel Exercice Pratique
            </a>
            <a href="{{ route('etudiant.lecon.show', $lecon->id) }}" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la leçon
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

        // Animate progress bar
        gsap.to('.progress-fill', {
            duration: 1.5,
            width: '{{ $score }}%',
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