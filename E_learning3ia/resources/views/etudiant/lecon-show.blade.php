@extends('etudiant.layouts.app')
@section('title', 'Leçon - ' . $lecon->titre)

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

    /* Resource type indicators */
    .resource-document { border-left: 6px solid #10b981; }
    .resource-video { border-left: 6px solid #ef4444; }
    .resource-text { border-left: 6px solid #3b82f6; }
    .resource-quiz { border-left: 6px solid #f59e0b; }

    /* PDF preview styles */
    .pdf-preview-container {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        max-height: 800px;
        overflow-y: auto;
    }

    .pdf-canvas {
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        margin-bottom: 16px;
        display: block;
    }

    .pdf-page {
        margin-bottom: 16px;
        text-align: center;
    }

    /* Loading spinner */
    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #e2e8f0;
        border-top: 5px solid #2563eb;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 20px auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
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

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(245, 158, 11, 0.4);
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
        padding: 6px 12px;
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

    .status-in-progress {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
    }

    /* YouTube video container */
    .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 16px;
    }

    .video-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 16px;
    }

    /* PDF Navigation */
    .pdf-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 16px;
        margin: 16px 0;
        padding: 16px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }

    .pdf-nav button {
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        background: #2563eb;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .pdf-nav button:hover:not(:disabled) {
        background: #1e40af;
        transform: translateY(-2px);
    }

    .pdf-nav button:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        transform: none;
    }

    /* Hidden class */
    .hidden {
        display: none !important;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .bg-shape { display: none; }
        .main-container { padding: 1rem; }
        .resource-actions { flex-direction: column; }
        .resource-actions .btn-primary,
        .resource-actions .btn-success,
        .resource-actions .btn-secondary { margin: 2px 0; }
        .pdf-nav { flex-direction: column; gap: 8px; }
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

<main class="main-container max-w-6xl mx-auto px-6 py-8">
    <!-- Spacer for fixed header -->
    <div class="h-20"></div>

    <!-- Back Button -->
    <div class="mb-8 slide-up">
        <a href="{{ url()->previous() }}" class="btn-primary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Lesson Header -->
    <div class="glass-card rounded-2xl p-8 mb-8 slide-up hover-lift">
        <h1 class="text-4xl font-bold text-slate-800 mb-4">{{ $lecon->titre }}</h1>
        <p class="text-slate-600 text-lg mb-4">Leçon {{ $lecon->ordre }} du chapitre "{{ $lecon->chapitre->nom ?? 'Chapitre sans nom' }}"</p>
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg">
            <i class="fas fa-book text-royal-blue"></i>
            <span class="text-slate-700 font-medium">
                @if($lecon->chapitre && $lecon->chapitre->cours)
                    {{ $lecon->chapitre->cours->nom }}
                @elseif($lecon->chapitre && $lecon->chapitre->matiere)
                    {{ $lecon->chapitre->matiere->nom }}
                @else
                    Cours non spécifié
                @endif
            </span>
        </div>
    </div>

    @if($lecon->ressources && $lecon->ressources->count() > 0)
    <!-- Resources Section -->
    <div class="glass-card rounded-2xl p-8 mb-8 slide-up hover-lift">
        <div class="flex items-center gap-3 mb-8">
            <i class="fas fa-folder-open text-3xl text-royal-blue"></i>
            <h2 class="text-2xl font-bold text-slate-800">Ressources de la leçon</h2>
        </div>

        @foreach($lecon->ressources as $index => $ressource)
            <div class="resource-{{ $ressource->type }} glass rounded-xl p-6 mb-6 fade-in hover-lift" id="resource-{{ $index }}">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br 
                        {{ $ressource->type === 'document' ? 'from-green-400 to-emerald-600' : 
                           ($ressource->type === 'video' ? 'from-red-400 to-red-600' : 
                           ($ressource->type === 'texte' ? 'from-blue-400 to-blue-600' : 
                           'from-yellow-400 to-orange-600')) }} 
                        rounded-lg flex items-center justify-center">
                        <i class="fas {{ $ressource->type === 'document' ? 'fa-file-pdf' : 
                                       ($ressource->type === 'video' ? 'fa-video' : 
                                       ($ressource->type === 'texte' ? 'fa-file-alt' : 
                                       'fa-question-circle')) }} text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800">{{ $ressource->titre }}</h3>
                </div>

                @if($ressource->type === 'document')
                    @php
                        // Generate the correct PDF URL
                        $pdfUrl = asset('storage/' . $ressource->contenu);
                        // For Google Docs viewer, we need the full absolute URL
                        $googleDocsUrl = 'https://docs.google.com/viewer?embedded=true&url=' . urlencode($pdfUrl);
                    @endphp
                    <div class="resource-actions flex flex-wrap gap-2">
                        <button onclick="toggleGoogleDocsPreview('{{ $index }}')" class="btn-primary" id="preview-btn-{{ $index }}">
                            <i class="fas fa-eye"></i> Aperçu Google Docs
                        </button>
                        <button onclick="togglePDFPreview('{{ $index }}', '{{ $pdfUrl }}')" class="btn-warning" id="pdfjs-preview-btn-{{ $index }}">
                            <i class="fas fa-file-pdf"></i> Aperçu PDF.js
                        </button>
                        <a href="{{ $pdfUrl }}" download class="btn-success">
                            <i class="fas fa-download"></i> Télécharger
                        </a>
                        <!-- <a href="{{ $pdfUrl }}" target="_blank" class="btn-secondary">
                            <i class="fas fa-external-link-alt"></i> Ouvrir
                        </a> -->
                    </div>

                    <!-- Google Docs Preview -->
                    <div id="google-docs-preview-{{ $index }}" class="pdf-preview-container mt-6 hidden">
                        <iframe src="{{ $googleDocsUrl }}" 
                                style="width:100%; height:800px; border:none;"
                                frameborder="0"
                                allowfullscreen>
                        </iframe>
                    </div>

                    <!-- PDF.js Preview -->
                    <div id="pdf-preview-{{ $index }}" class="pdf-preview-container mt-6 hidden">
                        <div id="spinner-{{ $index }}" class="spinner" style="display: none;"></div>
                        <div id="pdf-error-{{ $index }}" class="hidden text-center text-red-600 p-4">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p>Impossible de charger le PDF. Veuillez réessayer ou télécharger le fichier.</p>
                        </div>
                        <div id="pdf-container-{{ $index }}"></div>
                        <div class="pdf-nav">
                            <button id="prev-btn-{{ $index }}" onclick="previousPage('{{ $index }}')" disabled>
                                <i class="fas fa-chevron-left"></i> Précédent
                            </button>
                            <span id="page-info-{{ $index }}">Page 1 sur 1</span>
                            <button id="next-btn-{{ $index }}" onclick="nextPage('{{ $index }}')" disabled>
                                Suivant <i class="fas fa-chevron-right"></i>
                            </button>
                            <button onclick="zoomIn('{{ $index }}')">
                                <i class="fas fa-search-plus"></i>
                            </button>
                            <button onclick="zoomOut('{{ $index }}')">
                                <i class="fas fa-search-minus"></i>
                            </button>
                        </div>
                    </div>

                @elseif($ressource->type === 'video')
                    @if(str_contains($ressource->contenu, 'youtube.com') || str_contains($ressource->contenu, 'youtu.be'))
                        @php
                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $ressource->contenu, $matches);
                            $youtubeId = $matches[1] ?? null;
                        @endphp
                        @if($youtubeId)
                            <div class="video-container mt-4">
                                <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen
                                        title="{{ $ressource->titre }}">
                                </iframe>
                            </div>
                        @else
                            <a href="{{ $ressource->contenu }}" target="_blank" class="btn-primary">
                                <i class="fas fa-external-link-alt"></i> Voir la vidéo
                            </a>
                        @endif
                    @else
                        <a href="{{ $ressource->contenu }}" target="_blank" class="btn-primary">
                            <i class="fas fa-external-link-alt"></i> Voir la vidéo
                        </a>
                    @endif
                @elseif($ressource->type === 'texte')
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mt-4 border border-blue-100">
                        {!! nl2br(e($ressource->contenu)) !!}
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    @else
        <div class="glass-card rounded-2xl p-8 mb-8 slide-up hover-lift">
            <div class="no-resources text-center py-8 text-gray-600 text-lg">
                <i class="fas fa-folder-open text-4xl mb-4"></i>
                <p>Aucune ressource disponible pour cette leçon.</p>
            </div>
        </div>
    @endif

    {{-- Section Quiz --}}
    {{-- Section Quiz --}}
@if($lecon->quiz)
<div class="glass-card rounded-2xl p-8 slide-up hover-lift">
    <div class="flex items-center gap-3 mb-6">
        <i class="fas fa-question-circle text-3xl text-yellow-500"></i>
        <h2 class="text-2xl font-bold text-slate-800">Quiz de la leçon</h2>
    </div>

    <div class="resource-quiz glass rounded-xl p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-question-circle text-white text-xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-slate-800">{{ $lecon->quiz->titre }}</h3>
        </div>
        
        <p class="text-slate-700 mb-6">{{ $lecon->quiz->description }}</p>
        
        {{-- Show quiz status if user has attempted --}}
        @if(isset($userQuizAttempt) && $userQuizAttempt)
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 mb-6 border border-blue-100">
                <div class="flex items-center gap-3 mb-3">
                    <span class="font-semibold text-slate-800">Statut:</span>
                    <span class="status-badge {{ $userQuizAttempt->statut === 'reussie' ? 'status-success' : ($userQuizAttempt->statut === 'echouee' ? 'status-failed' : 'status-in-progress') }}">
                        <i class="fas {{ $userQuizAttempt->statut === 'reussie' ? 'fa-check-circle' : ($userQuizAttempt->statut === 'echouee' ? 'fa-times-circle' : 'fa-spinner') }}"></i>
                        {{ ucfirst($userQuizAttempt->statut) }}
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="font-semibold text-slate-700">Score:</span>
                        <span class="{{ $userQuizAttempt->score_obtenu >= $lecon->quiz->seuil_reussite ? 'text-green-600' : 'text-red-600' }} font-bold ml-2">{{ $userQuizAttempt->score_obtenu }}%</span>
                    </div>
                    <div>
                        <span class="font-semibold text-slate-700">Seuil de réussite:</span>
                        <span class="text-slate-600 ml-2">{{ $lecon->quiz->seuil_reussite }}%</span>
                    </div>
                    <div>
                        <span class="font-semibold text-slate-700">Dernière tentative:</span>
                        <span class="text-slate-600 ml-2">{{ $userQuizAttempt->updated_at->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            </div>
        @endif
        
        <div class="quiz-actions flex flex-wrap gap-3">
            {{-- Always show the "Start Quiz" button --}}
            <a href="{{ route('etudiant.quiz.show', $lecon->quiz->id) }}" class="btn-warning">
                <i class="fas fa-play-circle"></i> 
                @if(isset($userQuizAttempt) && $userQuizAttempt)
                    {{ $userQuizAttempt->statut === 'en_cours' ? 'Continuer le quiz' : 'Reprendre le quiz' }}
                @else 
                    Commencer le quiz 
                @endif
            </a>
            
            {{-- Show detailed results only if user has completed the quiz --}}
            @if(isset($userQuizAttempt) && $userQuizAttempt && $userQuizAttempt->statut === 'reussie')
                <a href="{{ route('etudiant.quiz.results', ['quiz' => $lecon->quiz->id, 'attempt' => $userQuizAttempt->id]) }}" class="btn-secondary">
                    <i class="fas fa-chart-bar"></i> Voir les résultats détaillés
                </a>
            @endif
            
            {{-- Show retry button only if user failed --}}
            @if(isset($userQuizAttempt) && $userQuizAttempt && ($userQuizAttempt->statut === 'echouee' || $userQuizAttempt->statut === 'terminee'))
               
            @endif
        </div>
    </div>
</div>
@endif
            
   
</main>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js"></script>
<script>
    // Initialize PDF.js
    pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js";

    // PDF state management
    const pdfStates = {};

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

        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    gsap.fromTo(entry.target, 
                        { y: 30, opacity: 0 },
                        { duration: 0.8, y: 0, opacity: 1, ease: "power2.out" }
                    );
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe elements for scroll animation
        document.querySelectorAll('.hover-lift').forEach(item => {
            observer.observe(item);
        });
    });

    // Google Docs Preview Toggle
    function toggleGoogleDocsPreview(index) {
        const preview = document.getElementById(`google-docs-preview-${index}`);
        const button = document.getElementById(`preview-btn-${index}`);
        const pdfPreview = document.getElementById(`pdf-preview-${index}`);
        const pdfButton = document.getElementById(`pdfjs-preview-btn-${index}`);
        
        // Hide PDF.js preview if open
        if (!pdfPreview.classList.contains('hidden')) {
            pdfPreview.classList.add('hidden');
            pdfButton.innerHTML = '<i class="fas fa-file-pdf"></i> Aperçu PDF.js';
        }
        
        if (preview.classList.contains('hidden')) {
            // Show preview
            preview.classList.remove('hidden');
            button.innerHTML = '<i class="fas fa-eye-slash"></i> Masquer l\'aperçu';
            
            // Animate the preview opening
            gsap.fromTo(preview, 
                { opacity: 0, height: 0 },
                { duration: 0.5, opacity: 1, height: 'auto', ease: "power2.out" }
            );
            
            // Force the iframe to reload (fixes some loading issues)
            const iframe = preview.querySelector('iframe');
            if (iframe) {
                const currentSrc = iframe.src;
                iframe.src = '';
                setTimeout(() => {
                    iframe.src = currentSrc;
                }, 100);
            }
        } else {
            // Hide preview
            button.innerHTML = '<i class="fas fa-eye"></i> Aperçu Google Docs';
            gsap.to(preview, {
                duration: 0.3,
                opacity: 0,
                height: 0,
                ease: "power2.out",
                onComplete: () => preview.classList.add('hidden')
            });
        }
    }

    // PDF.js Preview Functions
    async function togglePDFPreview(index, pdfUrl) {
        const container = document.getElementById(`pdf-preview-${index}`);
        const spinner = document.getElementById(`spinner-${index}`);
        const errorDiv = document.getElementById(`pdf-error-${index}`);
        const pdfContainer = document.getElementById(`pdf-container-${index}`);
        const previewBtn = document.getElementById(`pdfjs-preview-btn-${index}`);
        const googlePreview = document.getElementById(`google-docs-preview-${index}`);
        const googleButton = document.getElementById(`preview-btn-${index}`);

        // Hide Google Docs preview if open
        if (!googlePreview.classList.contains('hidden')) {
            googlePreview.classList.add('hidden');
            googleButton.innerHTML = '<i class="fas fa-eye"></i> Aperçu Google Docs';
        }

        if (container.classList.contains('hidden')) {
            // Show preview
            container.classList.remove('hidden');
            previewBtn.innerHTML = '<i class="fas fa-eye-slash"></i> Masquer l\'aperçu';
            
            gsap.fromTo(container, 
                { opacity: 0, height: 0 },
                { duration: 0.5, opacity: 1, height: 'auto', ease: "power2.out" }
            );

            if (!pdfStates[index]) {
                // First time loading
                spinner.style.display = 'block';
                errorDiv.classList.add('hidden');
                pdfContainer.innerHTML = '';

                try {
                    const loadingTask = pdfjsLib.getDocument(pdfUrl);
                    const pdf = await loadingTask.promise;
                    
                    pdfStates[index] = {
                        pdf: pdf,
                        currentPage: 1,
                        totalPages: pdf.numPages,
                        scale: 1.2
                    };

                    await renderPage(index, 1);
                    updatePageInfo(index);
                    updateNavigationButtons(index);
                    
                } catch (error) {
                    console.error('Error loading PDF:', error);
                    errorDiv.classList.remove('hidden');
                } finally {
                    spinner.style.display = 'none';
                }
            }
        } else {
            // Hide preview
            previewBtn.innerHTML = '<i class="fas fa-file-pdf"></i> Aperçu PDF.js';
            gsap.to(container, {
                duration: 0.3,
                opacity: 0,
                height: 0,
                ease: "power2.out",
                onComplete: () => container.classList.add('hidden')
            });
        }
    }

    async function renderPage(index, pageNumber) {
        const state = pdfStates[index];
        if (!state) return;

        const page = await state.pdf.getPage(pageNumber);
        const viewport = page.getViewport({ scale: state.scale });

        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        canvas.className = 'pdf-canvas';

        const pdfContainer = document.getElementById(`pdf-container-${index}`);
        pdfContainer.innerHTML = '';
        
        const pageDiv = document.createElement('div');
        pageDiv.className = 'pdf-page';
        pageDiv.appendChild(canvas);
        pdfContainer.appendChild(pageDiv);

        await page.render({
            canvasContext: context,
            viewport: viewport
        }).promise;
    }

    function updatePageInfo(index) {
        const state = pdfStates[index];
        if (!state) return;

        const pageInfo = document.getElementById(`page-info-${index}`);
        pageInfo.textContent = `Page ${state.currentPage} sur ${state.totalPages}`;
    }

    function updateNavigationButtons(index) {
        const state = pdfStates[index];
        if (!state) return;

        const prevBtn = document.getElementById(`prev-btn-${index}`);
        const nextBtn = document.getElementById(`next-btn-${index}`);

        prevBtn.disabled = state.currentPage <= 1;
        nextBtn.disabled = state.currentPage >= state.totalPages;
    }

    async function previousPage(index) {
        const state = pdfStates[index];
        if (!state || state.currentPage <= 1) return;

        state.currentPage--;
        await renderPage(index, state.currentPage);
        updatePageInfo(index);
        updateNavigationButtons(index);
    }

    async function nextPage(index) {
        const state = pdfStates[index];
        if (!state || state.currentPage >= state.totalPages) return;

        state.currentPage++;
        await renderPage(index, state.currentPage);
        updatePageInfo(index);
        updateNavigationButtons(index);
    }

    async function zoomIn(index) {
        const state = pdfStates[index];
        if (!state) return;

        state.scale = Math.min(state.scale * 1.25, 3.0);
        await renderPage(index, state.currentPage);
    }

    async function zoomOut(index) {
        const state = pdfStates[index];
        if (!state) return;

        state.scale = Math.max(state.scale * 0.8, 0.5);
        await renderPage(index, state.currentPage);
    }

    // Debug function to test PDF access
    async function testPDFAccess(pdfUrl, index) {
        console.log('Testing PDF access for:', pdfUrl);
        
        try {
            // Test if the file exists
            const response = await fetch(pdfUrl);
            console.log('PDF fetch response:', response.status, response.statusText);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            console.log('PDF file is accessible');
            
            // Test PDF.js loading
            const pdf = await pdfjsLib.getDocument(pdfUrl).promise;
            console.log('PDF loaded successfully:', pdf);
            console.log('Number of pages:', pdf.numPages);
            
            return true;
        } catch (error) {
            console.error('PDF test failed:', error);
            return false;
        }
    }

    // Add hover effects to buttons
    document.querySelectorAll('.btn-primary, .btn-success, .btn-warning, .btn-secondary').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            gsap.to(this, { duration: 0.3, scale: 1.05, ease: "power2.out" });
        });
        
        btn.addEventListener('mouseleave', function() {
            gsap.to(this, { duration: 0.3, scale: 1, ease: "power2.out" });
        });
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close any open PDF previews
            document.querySelectorAll('[id^="pdf-preview-"], [id^="google-docs-preview-"]').forEach(preview => {
                if (!preview.classList.contains('hidden')) {
                    const idParts = preview.id.split('-');
                    const index = idParts[2];
                    const type = idParts[0] === 'pdf' ? 'pdfjs' : 'preview';
                    const button = document.getElementById(`${type}-btn-${index}`);
                    
                    if (button) {
                        button.innerHTML = type === 'pdfjs' 
                            ? '<i class="fas fa-file-pdf"></i> Aperçu PDF.js' 
                            : '<i class="fas fa-eye"></i> Aperçu Google Docs';
                    }
                    
                    preview.classList.add('hidden');
                }
            });
        }
    });

    // Add accessibility enhancements
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing...');
        console.log('PDF.js available:', typeof pdfjsLib !== 'undefined');
        console.log('GSAP available:', typeof gsap !== 'undefined');
        
        // Add focus management for better keyboard navigation
        const focusableElements = document.querySelectorAll('button, a, input, select, textarea, [tabindex]:not([tabindex="-1"])');
        
        focusableElements.forEach(element => {
            element.addEventListener('focus', function() {
                this.style.outline = '2px solid #2563eb';
                this.style.outlineOffset = '2px';
            });
            
            element.addEventListener('blur', function() {
                this.style.outline = '';
                this.style.outlineOffset = '';
            });
        });
    });
</script>
@endsection