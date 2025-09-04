@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Libellé (ex: 2024-2025)</label>
        <input type="text" name="libelle" class="form-control" value="{{ old('libelle', $anneeAcademique->libelle ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Statut</label>
        <select name="statut" class="form-select" required>
            <option value="Future" @selected(old('statut', $anneeAcademique->statut ?? 'Future') == 'Future')>Future</option>
            <option value="Active" @selected(old('statut', $anneeAcademique->statut ?? '') == 'Active')>Active</option>
            <option value="Archivée" @selected(old('statut', $anneeAcademique->statut ?? '') == 'Archivée')>Archivée</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Date de début</label>
        <input type="date" name="date_debut" class="form-control" value="{{ old('date_debut', isset($anneeAcademique) ? $anneeAcademique->date_debut->format('Y-m-d') : '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Date de fin</label>
        <input type="date" name="date_fin" class="form-control" value="{{ old('date_fin', isset($anneeAcademique) ? $anneeAcademique->date_fin->format('Y-m-d') : '') }}" required>
    </div>
</div>
