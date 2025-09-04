<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nom" class="form-label required-field">Nom du type de formation</label>
            <input type="text" name="nom" id="nom" value="{{ old('nom', $type_formation->nom ?? '') }}" class="form-control" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="duree" class="form-label required-field">Durée de la formation</label>
            <input type="text" name="duree" id="duree" value="{{ old('duree', $type_formation->duree ?? '') }}" class="form-control" placeholder="Ex: 3 mois" required>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label required-field">Description</label>
    <textarea name="description" id="description" class="form-control" rows="5" required>{{ old('description', $type_formation->description ?? '') }}</textarea>
</div>

<div class="form-check mb-3">
    <input type="hidden" name="statut" value="0">
    <input type="checkbox" name="statut" id="statut" value="1" class="form-check-input" {{ old('statut', isset($type_formation) ? $type_formation->statut : true) ? 'checked' : '' }}>
    <label class="form-check-label" for="statut">Activer ce type de formation</label>
</div>
