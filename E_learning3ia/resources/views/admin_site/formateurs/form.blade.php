<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label required-field">Nom</label><input type="text" name="name" class="form-control" value="{{ old('name', $formateur->name ?? '') }}" required></div>
    <div class="col-md-6 mb-3"><label class="form-label required-field">Prénom</label><input type="text" name="prenom" class="form-control" value="{{ old('prenom', $formateur->prenom ?? '') }}" required></div>
    <div class="col-md-6 mb-3"><label class="form-label required-field">Date de Naissance</label><input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', isset($formateur->date_naissance) ? $formateur->date_naissance->format('Y-m-d') : '') }}" required></div>
    <div class="col-md-6 mb-3"><label class="form-label required-field">Lieu de Naissance</label><input type="text" name="lieu_naissance" class="form-control" value="{{ old('lieu_naissance', $formateur->lieu_naissance ?? '') }}" required></div>
    <div class="col-md-6 mb-3"><label class="form-label required-field">Sexe</label><select name="sexe" class="form-select" required><option value="Masculin" @selected(old('sexe', $formateur->sexe ?? '') == 'Masculin')>Masculin</option><option value="Feminin" @selected(old('sexe', $formateur->sexe ?? '') == 'Feminin')>Feminin</option></select></div>
    <div class="col-md-6 mb-3"><label class="form-label required-field">Nationalité</label><input type="text" name="nationalite" class="form-control" value="{{ old('nationalite', $formateur->nationalite ?? '') }}" required></div>
    <div class="col-md-6 mb-3"><label class="form-label required-field">Téléphone 1</label><input type="tel" name="tel1" class="form-control" value="{{ old('tel1', $formateur->tel1 ?? '') }}" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Téléphone 2 (Optionnel)</label><input type="tel" name="tel2" class="form-control" value="{{ old('tel2', $formateur->tel2 ?? '') }}"></div>
    <div class="col-md-6 mb-3"><label class="form-label required-field">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $formateur->email ?? '') }}" required></div>
    <div class="col-md-6 mb-3"><label class="form-label required-field">Ville de résidence</label><input type="text" name="ville" class="form-control" value="{{ old('ville', $formateur->ville ?? '') }}" required></div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Photo (Optionnel)</label>
        @if(isset($formateur) && $formateur->photo)<p><img src="{{ asset('storage/' . $formateur->photo) }}" alt="Photo" height="50"></p>@endif
        <input type="file" name="photo" class="form-control" accept="image/*">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Scan CNI (Optionnel)</label>
        @if(isset($formateur) && $formateur->cni)<p><a href="{{ asset('storage/' . $formateur->cni) }}" target="_blank">Voir le document</a></p>@endif
        <input type="file" name="cni" class="form-control" accept="image/*,application/pdf">
    </div>
</div>
