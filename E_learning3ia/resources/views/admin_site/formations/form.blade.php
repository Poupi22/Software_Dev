@if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

<div class="mb-3">
    <label class="form-label required-field">Nom de la formation</label>
    <input type="text" name="nom" class="form-control" value="{{ old('nom', $formation->nom ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label required-field">Code</label>
    <input type="text" name="code" class="form-control" value="{{ old('code', $formation->code ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4">{{ old('description', $formation->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="image" class="form-label">Image de la Formation</label>
    <input class="form-control" type="file" id="image" name="image">
</div>

@if (isset($formation) && $formation->image)
    <div class="mb-3">
        <p>Image actuelle :</p>
        <img src="{{ asset('storage/' . $formation->image) }}" alt="Image de {{ $formation->nom }}" style="max-width: 200px; height: auto;">
    </div>
@endif
