@extends('admin_site.layouts.app')
@section('title', 'Gérer le Contenu : ' . $contenuAdditionnel->titre)
@section('content')
<div class="main-content">
    <div class="main-header"><div class="container"><h1><i class="fas fa-edit me-2"></i> Contenu : {{ $contenuAdditionnel->titre }}</h1></div></div>
    <div class="container">
        <a href="{{ route('dashboard.programme_session.show', $contenuAdditionnel->programme_session_id) }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Retour à la session</a>

        <div class="card mb-4">
            <div class="card-header"><h5>Ressources</h5></div>
            @if ($errors->any())<div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            <div class="card-body">
                <ul class="list-group">
                    @forelse($contenuAdditionnel->ressources as $ressource)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $ressource->titre }} <span class="badge bg-info">{{ $ressource->type }}</span>
                        <form action="{{ route('dashboard.ressource-additionnelle.destroy', $ressource->id) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-danger">X</button></form>
                    </li>
                    @empty
                    <li class="list-group-item">Aucune ressource pour ce contenu.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h5>Ajouter une Nouvelle Ressource</h5></div>
            <form action="{{ route('dashboard.ressource-additionnelle.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="contenu_additionnel_id" value="{{ $contenuAdditionnel->id }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label>Titre de la ressource</label><input type="text" name="titre" class="form-control" required></div>
                        <div class="col-md-6 mb-3"><label>Type</label><select name="type" id="typeSelector" class="form-select" required><option value="page_texte">Texte</option><option value="video_youtube">Vidéo YouTube</option><option value="lien_externe">Lien Externe</option><option value="fichier_pdf">Fichier PDF</option></select></div>
                    </div>
                    <div id="type-form-fields">
                        <div class="mb-3 type-field" id="field-page_texte"><label>Contenu</label><textarea name="contenu_texte" class="form-control" rows="5"></textarea></div>
                        <div class="mb-3 type-field" id="field-video_youtube" style="display:none;"><label>URL de la vidéo YouTube</label><input type="url" name="contenu_video" class="form-control"></div>
                        <div class="mb-3 type-field" id="field-lien_externe" style="display:none;"><label>URL du lien</label><input type="url" name="contenu_lien" class="form-control"></div>
                        <div class="mb-3 type-field" id="field-fichier_pdf" style="display:none;"><label>Fichier PDF</label><input type="file" name="fichier" class="form-control"></div>
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
            if (selectedType === 'fichier_pdf') {
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
