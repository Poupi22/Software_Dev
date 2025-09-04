@extends('admin_site.layouts.app')
@section('title', 'Gestion de la Fiche de Préinscription')
@section('content')
<div class="main-content">
    <div class="main-header"><div class="container"><h1><i class="fas fa-file-pdf me-2"></i> Fiche de Préinscription</h1></div></div>
    <div class="container">
        <div class="d-flex justify-content-end mb-3">
            @if($fiches->isEmpty())
                <a href="{{ route('dashboard.fiche_preinscription.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Ajouter la fiche</a>
            @endif
        </div>

        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nom du fichier</th>
                            <th>Mise à jour</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fiches as $fiche)
                        <tr>
                            <td>
                                <a href="{{ asset('storage/' . $fiche->chemin_fichier) }}" target="_blank">{{ $fiche->nom_original }}</a>
                            </td>
                            <td>{{ $fiche->updated_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('dashboard.fiche_preinscription.edit', $fiche->id) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i> Remplacer</a>
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete{{ $fiche->id }}"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        @include('admin_site.global.delete-modal', ['id' => $fiche->id, 'itemName' => 'la fiche de préinscription', 'url' => route('dashboard.fiche_preinscription.destroy', $fiche->id)])
                        @empty
                        <tr>
                            <td colspan="3" class="text-center p-4">
                                <p>Aucune fiche de préinscription n'a été téléversée.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
