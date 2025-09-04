<div class="modal fade" id="changeStatus{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Modifier le statut de la session</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ $url }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Session pour : <strong>{{ $item->typeFormation->nom }}</strong></p>
                    <div class="mb-3">
                        <label for="statut_{{ $item->id }}" class="form-label">Nouveau statut</label>
                        <select name="statut" id="statut_{{ $item->id }}" class="form-select" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $item->statut == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div><div class="modal fade" id="changeStatus{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Modifier le statut de la session</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ $url }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Session pour : <strong>{{ $item->typeFormation->nom }}</strong></p>
                    <div class="mb-3">
                        <label for="statut_{{ $item->id }}" class="form-label">Nouveau statut</label>
                        <select name="statut" id="statut_{{ $item->id }}" class="form-select" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $item->statut == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
