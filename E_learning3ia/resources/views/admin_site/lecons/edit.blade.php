@extends('admin_site.layouts.app')
@section('title', 'Gestion de la Leçon')
@section('content')
<div class="main-content">
    <div class="main-header"><div class="container"><h1><i class="fas fa-edit me-2"></i> Leçon : {{ $lecon->titre }}</h1></div></div>
    <div class="container">
        <a href="{{ route('dashboard.matiere.show', $lecon->chapitre->matiere_id) }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Retour au chapitre</a>

        <div class="card mb-4">
            <div class="card-header"><h5>Ressources de la Leçon</h5></div>
            <div class="card-body">
                <ul class="list-group">
                    @forelse($lecon->ressources as $ressource)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $ressource->titre }} <span class="badge bg-info">{{ $ressource->type }}</span>
                        <form action="{{ route('dashboard.ressource.destroy', $ressource->id) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger">X</button></form>
                    </li>
                    @empty
                    <li class="list-group-item">Aucune ressource pour cette leçon.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5>Ajouter une Nouvelle Ressource</h5></div>
            <form action="{{ route('dashboard.ressource.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="lecon_id" value="{{ $lecon->id }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Titre de la ressource</label><input type="text" name="titre" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Type</label><select name="type" id="typeSelector" class="form-select" required><option value="texte">Texte</option><option value="video">Vidéo</option><option value="document">Document</option></select></div>
                    </div>
                    <div id="type-form-fields">
                        <div class="mb-3 type-field" id="field-texte"><label>Contenu Texte</label><textarea name="contenu" class="form-control" rows="5"></textarea></div>
                        <div class="mb-3 type-field" id="field-video" style="display:none;"><label>URL Vidéo</label><input type="url" name="contenu" class="form-control"></div>
                        <div class="mb-3 type-field" id="field-document" style="display:none;"><label>Fichier</label><input type="file" name="fichier" class="form-control"></div>
                    </div>
                </div>
                <div class="card-footer"><button type="submit" class="btn btn-primary">Ajouter la Ressource</button></div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelector = document.getElementById('typeSelector');
    const formFields = document.getElementById('type-form-fields');

    function toggleFields() {
        const selectedType = typeSelector.value;
        formFields.querySelectorAll('.type-field').forEach(field => {
            field.style.display = 'none';
            field.querySelectorAll('input, textarea').forEach(input => input.name = '');
        });

        const activeField = document.getElementById('field-' + selectedType);
        if (activeField) {
            activeField.style.display = 'block';
            if (selectedType === 'document') {
                activeField.querySelector('input').name = 'fichier';
            } else {
                activeField.querySelector('input, textarea').name = 'contenu';
            }
        }
    }

    typeSelector.addEventListener('change', toggleFields);
    toggleFields();
});
</script>
@endpush
