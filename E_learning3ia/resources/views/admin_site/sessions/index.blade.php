@extends('admin_site.layouts.app')
@section('title', 'Liste des Inscriptions')
@section('content')
<div class="main-content">
    <div class="main-header"><div class="container"><h1><i class="fas fa-list-ul me-2"></i> Inscriptions</h1></div></div>
    <div class="container">
        <div class="d-flex justify-content-end mb-3"><a href="{{ route('dashboard.inscription.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Inscrire</a></div>
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Étudiant</th><th>Programme</th><th>Reste à Payer</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($inscriptions as $inscription)
                        <tr>
                            <td>{{ $inscription->user->name }} {{ $inscription->user->prenom }}<br><small class="text-muted">{{ $inscription->user->matricule }}</small></td>
                            <td>{{ $inscription->programmeSession->programme->formation->nom }}</td>
                            <td class="fw-bold {{ $inscription->reste > 0 ? 'text-danger' : 'text-success' }}">{{ number_format($inscription->reste) }} FCFA</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('dashboard.inscription.show', $inscription->id) }}" class="btn btn-outline-info"><i class="fas fa-eye"></i></a>
                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $inscription->id }}"><i class="fas fa-dollar-sign"></i></button>
                                    <a href="{{ route('dashboard.inscription.situation_financiere', $inscription->id) }}" target="_blank" class="btn btn-outline-secondary"><i class="fas fa-print"></i></a>
                                    <a href="{{ route('dashboard.inscription.edit', $inscription->id) }}" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#delete{{ $inscription->id }}"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        @include('admin_site.global.delete-modal', ['id' => $inscription->id, 'itemName' => "l'inscription de " . $inscription->user->name, 'url' => route('dashboard.inscription.destroy', $inscription->id)])
                        @include('admin_site.inscriptions.solde', ['inscription' => $inscription])
                        @empty
                        <tr><td colspan="4" class="text-center">Aucune inscription.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
