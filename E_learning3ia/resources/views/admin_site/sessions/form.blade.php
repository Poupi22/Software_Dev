<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="type_formation_id" class="form-label required-field">Type de Formation</label>
            <select name="type_formation_id" id="type_formation_id" class="form-select" required>
                <option value="">-- Sélectionner un type de formation --</option>
                @foreach($type_formations as $type_formation)
                    <option value="{{ $type_formation->id }}" {{ old('type_formation_id', $session->type_formation_id ?? '') == $type_formation->id ? 'selected' : '' }}>
                        {{ $type_formation->nom }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="statut" class="form-label required-field">Statut</label>
            <select name="statut" id="statut" class="form-select" required>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ old('statut', $session->statut ?? 'Programmée') == $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="date_debut" class="form-label required-field">Date de début</label>
            <input type="date" name="date_debut" id="date_debut" value="{{ old('date_debut', isset($session->date_debut) ? $session->date_debut->format('Y-m-d') : '') }}" class="form-control" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="date_fin" class="form-label">Date de fin</label>
            <input type="date" name="date_fin" id="date_fin" value="{{ old('date_fin', isset($session->date_fin) ? $session->date_fin->format('Y-m-d') : '') }}" class="form-control">
        </div>
    </div>
</div>
