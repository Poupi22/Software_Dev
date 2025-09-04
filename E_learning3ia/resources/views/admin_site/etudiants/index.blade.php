@extends('admin_site.layouts.app')

@section('title', 'Gestion des Étudiants')

@section('content')
<div class="main-content">
    <div class="main-header">
        <div class="container">
            <h1 class="h4 h-md-3 fw-bold mb-1"><i class="fas fa-user-graduate me-2"></i> Gestion des Étudiants</h1>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-3" role="alert"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
        @endif

        <div class="table-container card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom Complet</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($etudiants as $etudiant)
                        <tr>
                            <td>{{ $etudiant->matricule }}</td>
                            <td>{{ $etudiant->name }} {{ $etudiant->prenom }}</td>
                            <td>{{ $etudiant->email }}</td>
                            <td>{{ $etudiant->tel1 }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('dashboard.etud.show', $etudiant->id) }}" class="btn btn-outline-info" title="Voir le dossier"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('dashboard.etud.edit', $etudiant->id) }}" class="btn btn-outline-primary" title="Modifier les informations"><i class="fas fa-user-edit"></i></a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucun étudiant trouvé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
