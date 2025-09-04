<div class="modal fade" id="statut{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="exampleModalLabel">Changement de statut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="p-3">
                <form action="{{ $url }}" method="POST">
                    @csrf
                    <div class="pb-3">
                        @if ($item->statut)
                          <h5>Voulez-vous vraiment désactiver "{{ $item->nom }}" ?</h5>
                        @else
                          <h5>Voulez-vous vraiment activer "{{ $item->nom }}" ?</h5>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        @if ($item->statut)
                            <button type="submit" class="btn btn-warning">Désactiver</button>
                        @else
                            <button type="submit" class="btn btn-success">Activer</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
