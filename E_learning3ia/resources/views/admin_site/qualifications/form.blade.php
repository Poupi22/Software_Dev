@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Code (ex: AQP)</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $qualification->code ?? '') }}" required>
    </div>
    <div class="col-md-8 mb-3">
        <label class="form-label">Nom Complet</label>
        <input type="text" name="nom" class="form-control" value="{{ old('nom', $qualification->nom ?? '') }}" required>
    </div>
</div>