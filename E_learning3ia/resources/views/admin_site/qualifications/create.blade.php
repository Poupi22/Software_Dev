@extends('admin_site.layouts.app')
@section('title', 'Créer une Qualification')
@section('content')
<div class="main-content">
    <div class="main-header">
        <div class="container">
            <h1 class="h4 h-md-3 fw-bold mb-1"><i class="fas fa-plus-circle me-2"></i> Créer une Qualification</h1>
        </div>
    </div>
    <div class="container">
        <div class="card">
            <form action="{{ route('dashboard.qualification.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                    @endif
                    @include('admin_site.qualifications.form')
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('dashboard.qualification.index') }}" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection