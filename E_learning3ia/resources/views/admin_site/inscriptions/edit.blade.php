@extends('admin_site.layouts.app')
@section('title', 'Modifier l\'Inscription')
@section('content')
<div class="main-content">
    <div class="main-header"><div class="container"><h1><i class="fas fa-edit me-2"></i> Modifier l'Inscription de {{ $inscription->user->name }}</h1></div></div>
    <div class="container">
        <div class="card">
            <form action="{{ route('dashboard.inscription.update', $inscription->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Programme / Session</label>
                            <select name="programme_session_id" id="programme_session_id" class="form-select" required>
                                @foreach($programmes as $programme)
                                    @foreach($programme->sessions as $session)
                                        <option value="{{ $session->id }}" data-prix="{{ $programme->prix }}" {{ old('programme_session_id', $inscription->programme_session_id) == $session->id ? 'selected' : '' }}>
                                            {{ $programme->formation->nom }} - {{ $programme->qualification->nom }} ({{ $session->anneeAcademique->libelle }})
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Montant Total (FCFA)</label>
                            <input type="number" id="total_display" class="form-control" value="{{ $inscription->programmeSession->programme->prix }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Montant Total Versé (cumul)</label>
                            <input type="number" name="verse" class="form-control" value="{{ old('verse', $inscription->verse) }}" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('dashboard.inscription.index') }}" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-success">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#programme_session_id').on('change', function() {
        let prix = $(this).find('option:selected').data('prix') || '';
        $('#total_display').val(prix);
    });
});
</script>
@endpush
