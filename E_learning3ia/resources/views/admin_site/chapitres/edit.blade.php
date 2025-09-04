@extends('admin_site.layouts.app')
@section('title', 'Modifier le Chapitre')
@section('content')
<div class="main-content">
    <div class="main-header">
        <div class="container">
            <h1><i class="fas fa-edit me-2"></i> Modifier le Chapitre</h1>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <form action="{{ route('dashboard.chapitre.update', $chapitre->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du chapitre</label>
                        <input type="text" id="nom" name="nom" class="form-control" value="{{ old('nom', $chapitre->nom) }}" required>
                    </div>

                    <p class="text-muted">Ce chapitre appartient à la matière : <strong>{{ $chapitre->matiere->nom }}</strong></p>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('dashboard.matiere.show', $chapitre->matiere_id) }}" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
