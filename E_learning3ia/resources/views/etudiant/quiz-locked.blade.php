@extends('etudiant.layouts.app')

@section('title', 'Quiz Verrouillé - ' . $quiz->titre)

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
                    'crimson-red': '#dc2626',
                    'light-red': '#fef2f2'
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

    .bg-shape-1 { top: 10%; left: 10%; width: 300px; height: 300px; background: #dc2626; animation-delay: 0s; }
    .bg-shape-2 { top: 60%; right: 15%; width: 200px; height: 200px; background: #b91c1c; animation-delay: 2s; }
    .bg-shape-3 { bottom: 20%; left: 20%; width: 250px; height: 250px; background: #ef4444; animation-delay: 4s; }

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
        background: linear-gradient(90deg, #dc2626, #b91c1c);
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

    /* Lock icon animation */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    .shake-animation {
        animation: shake 0.8s cubic-bezier(.36,.07,.19,.97) both;
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

    .status-locked {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #b91c1c;
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

    /* Alert styles */
    .alert-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        border: 1px solid #93c5fd;
        color: #1e40af;
        padding: 1rem;
        border-radius: 0.75rem;
        margin: 1rem 0;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .bg-shape { display: none; }
        .main-container { padding: 1rem; }
        .actions { flex-direction: column; }
        .actions a { width: 100%; justify-content: center; }
    }

    /* Lock icon styling */
    .lock-icon {
        font-size: 5rem;
        color: #dc2626;
        margin-bottom: 1.5rem;
        filter: drop-shadow(0 4px 6px rgba(220, 38, 38, 0.2));
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

    <!-- Quiz Locked Header -->
    <div class="glass-card rounded-2xl p-8 mb-8 slide-up hover-lift">
        <div class="result-header text-center">
            <div class="lock-icon shake-animation">
                <i class="fas fa-lock"></i>
            </div>
            
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Quiz Verrouillé</h1>
            <p class="text-slate-600 mb-6">{{ $quiz->titre }}</p>
            
            <div class="mt-4">
                <span class="status-badge status-locked">
                    <i class="fas fa-ban"></i>
                    Accès Bloqué
                </span>
            </div>
        </div>
    </div>

    <!-- Locked Message -->
    <div class="glass-card rounded-2xl p-8 slide-up">
        <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
            <i class="fas fa-exclamation-triangle text-red-600"></i>
            Tentatives Épuisées
        </h2>
        
        <div class="space-y-4">
            <p class="text-lg text-slate-700">
                Vous avez épuisé toutes vos tentatives pour ce quiz.
            </p>
            
            <p class="text-slate-700">
                Malheureusement, vous avez utilisé vos 3 tentatives sans atteindre le score de passage requis de <strong class="text-red-600">{{ $quiz->seuil_reussite }}%</strong>.
            </p>
            
            <div class="alert-info mt-4">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                    <div>
                        <strong>Important:</strong> Vous ne pouvez plus tenter ce quiz. Veuillez contacter votre formateur pour plus d'informations.
                    </div>
                </div>
            </div>
            
            <!-- Attempts visualization -->
            <div class="mt-6 p-4 bg-red-50 rounded-lg border border-red-200">
                <h3 class="font-semibold text-red-800 mb-3">Vos tentatives:</h3>
                <div class="space-y-2">
                    <div class="flex items-center justify-between p-2 bg-white rounded border border-red-100">
                        <span class="text-red-700">Tentative 1</span>
                        <span class="text-red-600 font-medium">Échec</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-white rounded border border-red-100">
                        <span class="text-red-700">Tentative 2</span>
                        <span class="text-red-600 font-medium">Échec</span>
                    </div>
                    <div class="flex items-center justify-between p-2 bg-white rounded border border-red-100">
                        <span class="text-red-700">Tentative 3</span>
                        <span class="text-red-600 font-medium">Échec</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions mt-8 flex flex-wrap gap-3">
            <a href="{{ route('etudiant.lecon.show', $lecon->id) }}" class="btn-primary">
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
    document.querySelectorAll('.btn-primary, .btn-secondary').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            gsap.to(this, { duration: 0.3, scale: 1.05, ease: "power2.out" });
        });
        
        btn.addEventListener('mouseleave', function() {
            gsap.to(this, { duration: 0.3, scale: 1, ease: "power2.out" });
        });
    });

    // Add shake animation to lock icon on hover
    document.querySelector('.lock-icon').addEventListener('mouseenter', function() {
        this.classList.add('shake-animation');
    });
    
    document.querySelector('.lock-icon').addEventListener('animationend', function() {
        this.classList.remove('shake-animation');
    });
</script>
@endsection