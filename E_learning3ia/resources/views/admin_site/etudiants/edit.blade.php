@extends('admin_site.layouts.app')

@section('title', 'Modifier un Étudiant')

@section('content')
<div class="main-content">
    <div class="main-header">
        <div class="container">
            <h1 class="h4 h-md-3 fw-bold mb-1"><i class="fas fa-user-edit me-2"></i> Modifier l'Étudiant</h1>
        </div>
    </div>
    <div class="container">
        <form action="{{ route('dashboard.etud.update', $etud->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Carte Photo --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-camera me-2"></i>Photo de l'Étudiant</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            @if($etud->photo)
                                <img src="{{ asset('storage/' . $etud->photo) }}"
                                     alt="Photo de {{ $etud->name }}"
                                     id="photo-preview"
                                     class="rounded-circle border"
                                     style="width:120px;height:120px;object-fit:cover;">
                            @else
                                <div id="photo-preview-placeholder"
                                     class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto"
                                     style="width:120px;height:120px;">
                                    <i class="fas fa-user text-white" style="font-size:3rem;"></i>
                                </div>
                                <img src="" alt="" id="photo-preview"
                                     class="rounded-circle border d-none"
                                     style="width:120px;height:120px;object-fit:cover;">
                            @endif
                        </div>
                        <div class="col-md-9">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Changer la photo</label>
                                <input type="file" name="photo" id="photo-input"
                                       class="form-control @error('photo') is-invalid @enderror"
                                       accept="image/jpeg,image/png,image/jpg,image/gif">
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Formats acceptés : JPG, PNG, GIF — Max 2 Mo</div>
                            </div>
                            @if($etud->photo)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="supprimer_photo" id="supprimer_photo" value="1">
                                <label class="form-check-label text-danger" for="supprimer_photo">
                                    <i class="fas fa-trash me-1"></i>Supprimer la photo actuelle
                                </label>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Informations Personnelles</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                    @endif
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Nom</label><input type="text" name="name" class="form-control" value="{{ old('name', $etud->name) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Prénom</label><input type="text" name="prenom" class="form-control" value="{{ old('prenom', $etud->prenom) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Date de Naissance</label><input type="date" name="date_naissance" class="form-control" value="{{ old('date_naissance', $etud->date_naissance->format('Y-m-d')) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Lieu de Naissance</label><input type="text" name="lieu_naissance" class="form-control" value="{{ old('lieu_naissance', $etud->lieu_naissance) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Sexe</label><select name="sexe" class="form-select" required><option value="Masculin" @selected(old('sexe', $etud->sexe) == 'Masculin')>Masculin</option><option value="Feminin" @selected(old('sexe', $etud->sexe) == 'Feminin')>Feminin</option></select></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Nationalité</label><input type="text" name="nationalite" class="form-control" value="{{ old('nationalite', $etud->nationalite) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Téléphone 1</label><input type="tel" name="tel1" class="form-control" value="{{ old('tel1', $etud->tel1) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Téléphone 2 (Optionnel)</label><input type="tel" name="tel2" class="form-control" value="{{ old('tel2', $etud->tel2) }}"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $etud->email) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Ville de résidence</label><input type="text" name="ville" class="form-control" value="{{ old('ville', $etud->ville) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Nom du Tuteur</label><input type="text" name="tuteur" class="form-control" value="{{ old('tuteur', $etud->tuteur) }}" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Téléphone du Tuteur</label><input type="tel" name="tel_tuteur" class="form-control" value="{{ old('tel_tuteur', $etud->tel_tuteur) }}" required></div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('dashboard.etud.index') }}" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i>Enregistrer les modifications</button>
                </div>
            </div>
        </form>

        <script>
            // Prévisualisation de la photo avant upload
            document.getElementById('photo-input').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        const preview = document.getElementById('photo-preview');
                        const placeholder = document.getElementById('photo-preview-placeholder');
                        preview.src = ev.target.result;
                        preview.classList.remove('d-none');
                        if (placeholder) placeholder.classList.add('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });
        </script>
    </div>
</div>
@endsection
