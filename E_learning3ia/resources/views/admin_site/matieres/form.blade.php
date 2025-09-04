@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nom de la matière <span class="text-danger">*</span></label>
        <input type="text" name="nom" class="form-control" value="{{ old('nom', $matiere->nom ?? '') }}" required>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Code</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $matiere->code ?? '') }}"
            placeholder="Ex: MAT101">
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">Crédit</label>
        <input type="number" name="credit" class="form-control" value="{{ old('credit', $matiere->credit ?? '') }}"
            min="1" max="10" placeholder="Ex: 3">
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Formateur Référent</label>
    <select name="user_id" class="form-select">
        <option value="">-- Aucun --</option>
        @foreach ($formateurs as $formateur)
            <option value="{{ $formateur->id }}" @selected(old('user_id', $matiere->user_id ?? '') == $formateur->id)>{{ $formateur->name }}
                {{ $formateur->prenom }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $matiere->description ?? '') }}</textarea>
</div>
