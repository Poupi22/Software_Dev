<!-- Étape 1: Choix du Type d'Inscription -->
<div class="step active">
    <h5 class="mb-3">Étape 1/ : Type d'Inscription</h5>
    <input type="hidden" name="inscription_type" id="inscription_type" value="new">
    <div class="list-group">
        <a href="#" class="list-group-item list-group-item-action inscription-type-choice active" data-type="new">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"><i class="fas fa-user-plus me-2"></i>Inscrire un Nouvel Étudiant</h5>
            </div>
            <p class="mb-1">Remplir le formulaire complet pour créer un nouveau dossier étudiant.</p>
        </a>
        <a href="#" class="list-group-item list-group-item-action inscription-type-choice" data-type="existing">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"><i class="fas fa-search me-2"></i>Inscrire un Étudiant Existant</h5>
            </div>
            <p class="mb-1">Rechercher un étudiant déjà enregistré et l'inscrire à une nouvelle formation.</p>
        </a>
    </div>
    <div class="d-flex justify-content-end mt-4">
        <button class="btn btn-primary" type="button" onclick="nextStep()">Suivant →</button>
    </div>
</div>

<!-- Div qui contiendra les étapes pour un NOUVEL étudiant -->
<div id="new-student-steps">
    <!-- Votre Étape "Informations Personnelles" -->
    <div class="step">
        <h5 class="mb-3">Étape 2/4 : Informations Personnelles</h5>
        {{-- Le code de votre étape 1 originale va ici --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label required-field">Nom</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label required-field">Prénom</label>
                <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror" value="{{ old('prenom') }}">
            </div>
            {{-- ... Ajoutez TOUS les autres champs de cette étape ici --}}
            <div class="col-md-6 mb-3"><label class="form-label required-field">Date de Naissance</label><input type="date" name="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror" value="{{ old('date_naissance') }}"></div>
            <div class="col-md-6 mb-3"><label class="form-label required-field">Lieu de Naissance</label><input type="text" name="lieu_naissance" class="form-control @error('lieu_naissance') is-invalid @enderror" value="{{ old('lieu_naissance') }}"></div>
            <div class="col-md-6 mb-3"><label class="form-label required-field">Sexe</label><select name="sexe" class="form-select @error('sexe') is-invalid @enderror"><option value="Masculin" {{ old('sexe') == 'Masculin' ? 'selected' : '' }}>Masculin</option><option value="Feminin" {{ old('sexe') == 'Feminin' ? 'selected' : '' }}>Feminin</option></select></div>
            <div class="col-md-6 mb-3"><label class="form-label required-field">Nationalité</label><input type="text" name="nationalite" class="form-control @error('nationalite') is-invalid @enderror" value="{{ old('nationalite') }}"></div>
        </div>
        <div class="d-flex justify-content-between">
            <button class="btn btn-secondary" type="button" onclick="previousStep()">← Précédent</button>
            <button class="btn btn-primary" type="button" onclick="nextStep()">Suivant →</button>
        </div>
    </div>

    <!-- Votre Étape "Coordonnées" -->
    <div class="step">
         <h5 class="mb-3">Étape 3/4 : Coordonnées</h5>
        {{-- Le code de votre étape 2 originale va ici --}}
         <div class="row">
             {{-- ... vos champs tel1, tel2, email, ville, tuteur ... --}}
             <div class="col-md-6 mb-3"><label class="form-label required-field">Téléphone 1</label><input type="tel" name="tel1" class="form-control @error('tel1') is-invalid @enderror" value="{{ old('tel1') }}"></div>
             <div class="col-md-6 mb-3"><label class="form-label">Téléphone 2 (Optionnel)</label><input type="tel" name="tel2" class="form-control @error('tel2') is-invalid @enderror" value="{{ old('tel2') }}"></div>
             <div class="col-md-6 mb-3"><label class="form-label required-field">Email</label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"></div>
             <div class="col-md-6 mb-3"><label class="form-label required-field">Ville de résidence</label><input type="text" name="ville" class="form-control @error('ville') is-invalid @enderror" value="{{ old('ville') }}"></div>
             <div class="col-md-6 mb-3"><label class="form-label required-field">Nom du Tuteur</label><input type="text" name="tuteur" class="form-control @error('tuteur') is-invalid @enderror" value="{{ old('tuteur') }}"></div>
             <div class="col-md-6 mb-3"><label class="form-label required-field">Téléphone du Tuteur</label><input type="tel" name="tel_tuteur" class="form-control @error('tel_tuteur') is-invalid @enderror" value="{{ old('tel_tuteur') }}"></div>
         </div>
         <div class="d-flex justify-content-between">
            <button class="btn btn-secondary" type="button" onclick="previousStep()">← Précédent</button>
            <button class="btn btn-primary" type="button" onclick="nextStep()">Suivant →</button>
        </div>
    </div>
</div>

<!-- Étape pour la recherche d'un étudiant EXISTANT -->
<div id="existing-student-step" class="step">
    <h5 class="mb-3">Étape 2/3 : Rechercher un Étudiant Existant</h5>
    <input type="hidden" name="user_id" id="user_id">
    <div class="mb-3 position-relative">
        <label class="form-label">Rechercher par nom, matricule ou email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="student-search-input" class="form-control" autocomplete="off" placeholder="Commencez à taper pour rechercher...">
        </div>
        <div id="student-search-results" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
    </div>
    <div id="selected-student-info" class="alert alert-info" style="display: none;"></div>
    <div class="d-flex justify-content-between mt-4">
        <button class="btn btn-secondary" type="button" onclick="previousStep()">← Précédent</button>
        <button class="btn btn-primary" type="button" onclick="nextStep()">Suivant →</button>
    </div>
</div>


<!-- ÉTAPES COMMUNES (Programme et Documents) -->
<!-- Votre Étape "Choix du Programme" -->
<div class="step">
    <h5 class="mb-3" id="programme-step-title">Étape X/X : Choix du Programme</h5>
    {{-- Le code de votre étape 3 originale va ici --}}
    <div class="row">
        {{-- ... vos champs programme_session_id, duree, montant, versé ... --}}
        <div class="col-md-12 mb-3"><label class="form-label">Programme / Session</label><select name="programme_session_id" id="programme_session_id" class="form-select" required><option value="" data-prix="" data-duree="">-- Choisir une session de programme --</option>@foreach($programmes as $programme) @foreach($programme->sessions as $session)<option value="{{ $session->id }}" data-prix="{{ $programme->prix }}" data-duree="{{ $programme->duree }}" {{ old('programme_session_id') == $session->id ? 'selected' : '' }}>{{ $programme->formation->nom }} - {{ $programme->qualification->nom }} ({{ $session->anneeAcademique->libelle }})</option>@endforeach @endforeach</select></div>
        <div class="col-md-4 mb-3"><label class="form-label">Durée</label><input type="text" id="duree_display" class="form-control" readonly></div>
        <div class="col-md-4 mb-3"><label class="form-label">Montant Total (FCFA)</label><input type="number" id="total_display" class="form-control" readonly></div>
        <div class="col-md-4 mb-3"><label class="form-label">Montant Versé (FCFA)</label><input type="number" name="verse" class="form-control" value="{{ old('verse', 0) }}" required></div>
    </div>
    <div class="d-flex justify-content-between">
        <button class="btn btn-secondary" type="button" onclick="previousStep()">&larr; Précédent</button>
        <button class="btn btn-primary" type="button" onclick="nextStep()">Suivant &rarr;</button>
    </div>
</div>

<!-- Votre Étape "Documents" -->
<div class="step">
    <h5 class="mb-3" id="documents-step-title">Étape Y/Y : Documents à Joindre</h5>
    {{-- Le code de votre étape 4 originale va ici --}}
    <div class="row">
        {{-- ... vos champs photo, cni, demande ... --}}
        <div class="col-md-4 mb-3"><label class="form-label">Photo 4x4 (Optionnel)</label><input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*"></div>
        <div class="col-md-4 mb-3"><label class="form-label">Photocopie CNI (Optionnel)</label><input type="file" name="cni" class="form-control @error('cni') is-invalid @enderror" accept="image/*,application/pdf"></div>
        <div class="col-md-4 mb-3"><label class="form-label">Demande d'admission (Optionnel)</label><input type="file" name="demande" class="form-control @error('demande') is-invalid @enderror" accept="image/*,application/pdf"></div>
    </div>
    <div class="alert alert-info mt-3">Vous êtes sur le point de finaliser l'inscription. Veuillez vérifier les informations avant de soumettre.</div>
    <div class="d-flex justify-content-between">
        <button class="btn btn-secondary" type="button" onclick="previousStep()">← Précédent</button>
        <button class="btn btn-success" type="submit"><i class="fas fa-check"></i> Finaliser l'Inscription</button>
    </div>
</div>
