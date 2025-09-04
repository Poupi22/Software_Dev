@extends('admin_site.layouts.app')

@section('title', 'Gestion des témoignages')

@section('content')
<style>
    /* Styles identiques à vos précédentes vues */
    .main-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem 0;
        margin-bottom: 1rem;
        width: 100%;
    }
    
    .stats-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: none;
        transition: transform 0.2s ease-in-out;
        margin-bottom: 1rem;
    }
    
    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow-x: auto;
    }
    
    .temoignage-photo {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 50%;
    }
    
    .star-rating {
        color: #fbbf24;
    }
</style>

<div class="main-content">
    <div class="main-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="h4 h-md-3 fw-bold mb-1">
                        <i class="fas fa-quote-left me-2"></i>
                        Gestion des témoignages
                    </h1>
                    <p class="mb-0 opacity-75 small">Liste des témoignages clients</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="stats-card d-inline-block p-2 p-md-3">
                        <div class="text-dark small">
                            <i class="fas fa-layer-group text-primary me-1"></i>
                            <strong>{{ $temoignages->count() }}</strong> témoignages
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('dashboard.temoignage.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Ajouter
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <div class="small">{{ session('success') }}</div>
        </div>
        @endif

        <div class="table-container">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Profession</th>
                        <th>Note</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($temoignages as $temoignage)
                    <tr>
                        <td>
                            @if($temoignage->photo)
                                <img src="{{ asset('storage/' . $temoignage->photo) }}" class="temoignage-photo" alt="Photo">
                            @else
                                <div class="temoignage-photo bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $temoignage->nom }}</td>
                        <td>{{ $temoignage->profession ?? 'N/A' }}</td>
                        <td>
                            @if($temoignage->note)
                                <div class="star-rating">
                                    @for($i = 0; $i < $temoignage->note; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $temoignage->publie ? 'success' : 'secondary' }}">
                                {{ $temoignage->publie ? 'Publié' : 'Brouillon' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('dashboard.temoignage.show', $temoignage->id) }}" class="btn btn-outline-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('dashboard.temoignage.edit', $temoignage->id) }}" class="btn btn-outline-primary" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('dashboard.temoignage.destroy', $temoignage->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer ce témoignage ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($temoignages->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $temoignages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection