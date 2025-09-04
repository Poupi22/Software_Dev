@extends('admin_site.layouts.app')

@section('title', 'Nouvelle Inscription')

@push('scripts_head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<style>
    /* Progress Bar Styles */
    #progressbar {
        width: 100%;
        background-color: #f3f3f3;
        border-radius: 1rem;
        overflow: hidden;
        margin-bottom: 1rem;
    }
    #progress {
        width: 20%;
        height: 10px;
        background-color: #0d6efd;
        transition: width 0.4s ease-in-out;
    }
    .step {
        display: none;
        padding: 1rem 0;
    }
    .step.active {
        display: block;
        animation: fadeIn 0.5s;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Base Styles */
    .main-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem 0;
        margin-bottom: 1.5rem;
        width: 100%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .form-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
    }

    .card-header h5 {
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .card-body {
        padding: 1.5rem;
        background-color: #fff;
    }

    .form-label {
        font-weight: 500;
        color: #334155;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.625rem 1rem;
        transition: all 0.2s;
        width: 100%;
        background-color: #fff;
    }

    .form-control:focus, .form-select:focus {
        border-color: #818cf8;
        box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2);
        outline: none;
    }

    .btn {
        font-weight: 500;
        padding: 0.625rem 1.25rem;
        border-radius: 8px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn i {
        margin-right: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #e2e8f0;
        color: #475569;
    }

    .btn-secondary:hover {
        background: #cbd5e1;
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.4);
    }

    .required-field::after {
        content: " *";
        color: #ef4444;
    }

    /* Form Validation Styles */
    .is-invalid {
        border-color: #ef4444 !important;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Alert Styles */
    .alert {
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .alert-danger {
        background-color: #fee2e2;
        border-color: #fca5a5;
        color: #b91c1c;
    }

    .alert-info {
        background-color: #e0f2fe;
        border-color: #93c5fd;
        color: #0369a1;
    }

    /* File Input Customization */
    .form-control[type="file"] {
        padding: 0.375rem;
    }

    /* Layout Adjustments */
    .main-content {
        margin-left: 250px;
        transition: all 0.3s;
        min-height: calc(100vh - 70px);
        padding-bottom: 2rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 1199.98px) {
        .main-content {
            margin-left: 0;
            padding-top: 70px;
        }
    }

    @media (max-width: 991.98px) {
        .card-body {
            padding: 1rem;
        }

        .form-label {
            font-size: 0.875rem;
        }

        .form-control, .form-select {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 767.98px) {
        .card-header {
            padding: 1rem;
        }

        .card-header h5 {
            font-size: 1.1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .main-header h1 {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 575.98px) {
        .container {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .card-body {
            padding: 0.75rem;
        }

        .row > div {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .mb-3 {
            margin-bottom: 0.75rem !important;
        }

        .d-flex.justify-content-end {
            flex-direction: column;
            gap: 0.5rem;
        }

        .d-flex.justify-content-end .btn {
            width: 100%;
        }

        .main-header h1 {
            font-size: 1.25rem;
        }
    }
</style>

<div class="main-content">
    <div class="main-header">
        <div class="container">
            <h1 class="h4 h-md-3 fw-bold mb-1"><i class="fas fa-user-plus me-2"></i> Nouvelle Inscription</h1>
        </div>
    </div>

    <div class="container">
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <h5 class="alert-heading">Erreur de Validation Détectée !</h5>
                    <p>Le formulaire n'a pas pu être soumis. Voici les erreurs retournées par le serveur :</p>
                    <hr>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li><strong>{{ $error }}</strong></li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card form-card">
                <div class="card-header">
                    <div id="progressbar">
                        <div id="progress"></div>
                    </div>
                </div>
                <form action="{{ route('dashboard.inscription.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                        @endif
                        @include('admin_site.inscriptions.form')
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- VARIABLES GLOBALES ---
    let currentStepIndex = 0;
    let visibleSteps = [];
    const allSteps = Array.from(document.querySelectorAll('.step'));
    const progressBar = document.getElementById('progress');
    const inscriptionTypeInput = document.getElementById('inscription_type');

    // On garde une référence aux étapes communes
    const programmeStep = allSteps[allSteps.length - 2];
    const documentsStep = allSteps[allSteps.length - 1];

    // Sauvegarder les boutons originaux de l'étape "Programme" pour pouvoir les restaurer
    const originalProgrammeStepButtons = programmeStep.querySelector('.d-flex.justify-content-between').innerHTML;

    // --- FONCTION PRINCIPALE POUR METTRE À JOUR L'AFFICHAGE ---
    function updateWizardDisplay() {
        const type = inscriptionTypeInput.value;

        // Restaurer les boutons par défaut avant de décider du parcours
        programmeStep.querySelector('.d-flex.justify-content-between').innerHTML = originalProgrammeStepButtons;

        if (type === 'new') {
            visibleSteps = [
                allSteps[0], // Étape 0 (Choix)
                ...document.querySelectorAll('#new-student-steps .step'),
                programmeStep, // Étape Programme
                documentsStep  // Étape Documents
            ];
            document.getElementById('programme-step-title').textContent = 'Étape 4/5 : Choix du Programme';
            document.getElementById('documents-step-title').textContent = 'Étape 5/5 : Documents à Joindre';

        } else { // 'existing'
            visibleSteps = [
                allSteps[0], // Étape 0 (Choix)
                document.getElementById('existing-student-step'),
                programmeStep // Uniquement l'étape Programme
            ];
            document.getElementById('programme-step-title').textContent = 'Étape 3/3 : Choix du Programme';

            // Transformer le bouton "Suivant" en bouton "Finaliser" car c'est la dernière étape
            const lastStepButtonsContainer = programmeStep.querySelector('.d-flex.justify-content-between');
            if(lastStepButtonsContainer){
                lastStepButtonsContainer.innerHTML = `
                    <button class="btn btn-secondary" type="button" onclick="previousStep()">← Précédent</button>
                    <button class="btn btn-success" type="submit"><i class="fas fa-check"></i> Finaliser l'Inscription</button>
                `;
            }
        }

        // Cacher toutes les étapes puis afficher la bonne
        allSteps.forEach(step => {
            step.classList.remove('active');
            step.style.display = 'none';
        });

        if (visibleSteps[currentStepIndex]) {
            visibleSteps[currentStepIndex].classList.add('active');
            visibleSteps[currentStepIndex].style.display = 'block';
        }

        // Mettre à jour la barre de progression
        const progressPercentage = (visibleSteps.length > 1) ? ((currentStepIndex) / (visibleSteps.length - 1)) * 100 : 0;
        progressBar.style.width = progressPercentage + '%';
    }

    // --- FONCTIONS POUR LA NAVIGATION ---
    window.nextStep = function() {
        if (currentStepIndex < visibleSteps.length - 1) {
            currentStepIndex++;
            updateWizardDisplay();
        }
    }

    window.previousStep = function() {
        if (currentStepIndex > 0) {
            currentStepIndex--;
            updateWizardDisplay();
        }
    }

    // --- GESTION DU CHOIX DU TYPE D'INSCRIPTION ---
    document.querySelectorAll('.inscription-type-choice').forEach(choice => {
        choice.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.inscription-type-choice').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const newType = this.dataset.type;

            // Mettre à jour seulement s'il y a un changement
            if (inscriptionTypeInput.value !== newType) {
                 inscriptionTypeInput.value = newType;
                 currentStepIndex = 0; // Réinitialiser à la première étape
                 updateWizardDisplay();
            }
        });
    });

    // --- GESTION DE LA RECHERCHE AJAX ---
    const searchInput = document.getElementById('student-search-input');
    const searchResults = document.getElementById('student-search-results');
    const selectedInfo = document.getElementById('selected-student-info');
    const userIdInput = document.getElementById('user_id');

    searchInput.addEventListener('keyup', function() {
        const query = this.value;
        if (query.length < 2) { // Rendu un peu plus réactif
            searchResults.innerHTML = '';
            return;
        }
        fetch(`{{ route('dashboard.etud.search') }}?query=${query}`)
            .then(response => {
                if (!response.ok) { throw new Error('Network response was not ok'); }
                return response.json();
            })
            .then(data => {
                let html = '';
                if(data.length > 0) {
                    data.forEach(user => {
                        html += `<a href="#" class="list-group-item list-group-item-action search-result-item" data-id="${user.id}" data-name="${user.name} ${user.prenom}" data-matricule="${user.matricule}">${user.name} ${user.prenom} (${user.matricule})</a>`;
                    });
                } else {
                    html = '<span class="list-group-item text-muted">Aucun étudiant trouvé.</span>';
                }
                searchResults.innerHTML = html;
            })
            .catch(error => {
                console.error('Error fetching students:', error);
                searchResults.innerHTML = '<span class="list-group-item text-danger">Erreur lors de la recherche.</span>';
            });
    });

    searchResults.addEventListener('click', function(e) {
        if (e.target.classList.contains('search-result-item')) {
            e.preventDefault();
            const target = e.target;
            userIdInput.value = target.dataset.id;
            selectedInfo.innerHTML = `Étudiant sélectionné : <strong>${target.dataset.name} (${target.dataset.matricule})</strong>`;
            selectedInfo.style.display = 'block';
            searchInput.value = ''; // Vider pour éviter la confusion
            searchResults.innerHTML = '';
        }
    });

    // --- GESTION DU SELECTEUR DE PROGRAMME ---
    $('#programme_session_id').on('change', function() {
        let selectedOption = $(this).find('option:selected');
        let prix = selectedOption.data('prix') || '';
        let duree = selectedOption.data('duree') || '';
        $('#total_display').val(prix);
        $('#duree_display').val(duree);
    }).trigger('change');

    // --- INITIALISATION AU CHARGEMENT DE LA PAGE ---
    updateWizardDisplay();
});
</script>
@endpush
