@extends('admin_site.layouts.app')
@section('title', 'Ajouter la Fiche de Préinscription')
@section('content')


<div class="main-content">
    <div class="main-header"><div class="container"><h1><i class="fas fa-plus-circle me-2"></i> Ajouter la Fiche</h1></div></div>
    <div class="container">
        <div class="card">
            <form action="{{ route('dashboard.fiche_preinscription.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    @include('admin_site.fiche_preinscription.form')
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('dashboard.fiche_preinscription.index') }}" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
