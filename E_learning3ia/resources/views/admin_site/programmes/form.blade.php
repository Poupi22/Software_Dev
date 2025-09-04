@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Formation</label><select name="formation_id" class="form-select" required>@foreach($formations as $f)<option value="{{ $f->id }}" @selected(old('formation_id', $programme->formation_id ?? '') == $f->id)>{{ $f->nom }}</option>@endforeach</select></div>
    <div class="col-md-6 mb-3"><label class="form-label">Qualification</label><select name="qualification_id" class="form-select" required>@foreach($qualifications as $q)<option value="{{ $q->id }}" @selected(old('qualification_id', $programme->qualification_id ?? '') == $q->id)>{{ $q->code }} - {{ $q->nom }}</option>@endforeach</select></div>
    <div class="col-md-6 mb-3"><label class="form-label">Prix (FCFA)</label><input type="number" name="prix" class="form-control" value="{{ old('prix', $programme->prix ?? '') }}" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Durée</label><input type="text" name="duree" class="form-control" value="{{ old('duree', $programme->duree ?? '') }}" required></div>
</div>
<hr>
<h5>Matières du programme (cocher et indiquer le trimestre)</h5>
<div class="row" style="max-height: 400px; overflow-y: auto;">
    @foreach($matieres as $matiere)
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-body">
                @php
                    $isAssociated = false;
                    $trimestreValue = '';
                    if (isset($programme) && $programme->matieres->contains($matiere)) {
                        $isAssociated = true;
                        $trimestreValue = $programme->matieres->find($matiere->id)->pivot->trimestre;
                    }
                @endphp
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="matieres[{{ $matiere->id }}][id]" value="{{ $matiere->id }}" id="m_{{ $matiere->id }}" @checked(old('matieres.' . $matiere->id . '.id', $isAssociated))>
                    <label class="form-check-label" for="m_{{ $matiere->id }}">
                        <b>{{ $matiere->nom }}</b>
                    </label>
                </div>
                <input type="number" name="matieres[{{ $matiere->id }}][trimestre]" class="form-control form-control-sm mt-1" placeholder="Trimestre" value="{{ old('matieres.' . $matiere->id . '.trimestre', $trimestreValue) }}">
            </div>
        </div>
    </div>
    @endforeach
</div>
