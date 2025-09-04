@extends('admin_site.layouts.app')
@section('title', 'Remplacer la Fiche de Préinscription')
@section('content')
<div class="main-content">
    <div class="main-header"><div class="container"><h1><i class="fas fa-edit me-2"></i> Remplacer la Fiche</h1></div></div>
    <div class="container">
        <div class="card">
            <form action="{{ route('dashboard.fiche_preinscription.update', $fichePreinscription->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @include('admin_site.fiche_preinscription.form')
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('dashboard.fiche_preinscription.index') }}" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-success">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
