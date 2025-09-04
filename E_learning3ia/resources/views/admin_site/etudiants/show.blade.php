@extends('admin_site.layouts.app')

@section('title', 'Dossier de l\'Étudiant')

@section('content')
<div class="main-content">
    <div class="main-header">
        <div class="container">
            <h1 class="h4 h-md-3 fw-bold mb-1"><i class="fas fa-folder-open me-2"></i> Dossier de l'Étudiant</h1>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="{{ $etud->photo ? asset('storage/' . $etud->photo) : 'https://via.placeholder.com/150' }}" alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                        <h5 class="my-3">{{ $etud->name }} {{ $etud->prenom }}</h5>
                        <p class="text-muted mb-1">{{ $etud->matricule }}</p>
                        <p class="text-muted mb-4">{{ $etud->ville }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-4">
                     <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Informations Personnelles</h5>
                        <a href="{{ route('dashboard.etud.edit', $etud->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-user-edit me-1"></i> Modifier</a>
                    </div>
                    <div class="card-body">
                        <div class="row"><div class="col-sm-4"><p class="mb-0 fw-bold">Email</p></div><div class="col-sm-8"><p class="text-muted mb-0">{{ $etud->email }}</p></div></div><hr>
                        <div class="row"><div class="col-sm-4"><p class="mb-0 fw-bold">Téléphone</p></div><div class="col-sm-8"><p class="text-muted mb-0">{{ $etud->tel1 }}</p></div></div><hr>
                        <div class="row"><div class="col-sm-4"><p class="mb-0 fw-bold">Date/Lieu Naissance</p></div><div class="col-sm-8"><p class="text-muted mb-0">{{ $etud->date_naissance->format('d/m/Y') }} à {{ $etud->lieu_naissance }}</p></div></div><hr>
                        <div class="row"><div class="col-sm-4"><p class="mb-0 fw-bold">Tuteur</p></div><div class="col-sm-8"><p class="text-muted mb-0">{{ $etud->tuteur }} ({{ $etud->tel_tuteur }})</p></div></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h5 class="mb-0">Historique des Inscriptions</h5></div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Formation</th><th>Date d'inscription</th><th>Statut</th><th>Action</th></tr></thead>
                    <tbody>
                        @forelse($etud->inscriptions as $inscription)
                        <tr>
                            <td>{{ $inscription->programmeSession->programme->qualification->code }} / {{ $inscription->programmeSession->programme->formation->nom }}</td>
                            <td>{{ $inscription->created_at->format('d/m/Y') }}</td>
                            <td><span class="badge bg-{{ $inscription->reste == 0 ? 'success' : 'warning' }}">{{ $inscription->reste == 0 ? 'Soldé' : 'En attente' }}</span></td>
                            <td><a href="{{ route('dashboard.inscription.show', $inscription->id) }}" class="btn btn-sm btn-outline-info">Voir l'inscription</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">Aucune inscription pour cet étudiant.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
