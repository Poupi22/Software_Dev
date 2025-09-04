<div class="modal fade" id="delete{{ $id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px">
        <div class="modal-content position-relative">
            <div class="position-absolute top-0 end-0 mt-2 me-2 z-index-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ $url }}" method="POST">
                <center>
                    @csrf
                    @method('DELETE')
                    <div class="modal-body p-0">
                        <div class="modal-header px-5 position-relative modal-shape-header bg-danger">
                            <div class="position-relative z-index-1 light">
                              <h4 class="mb-0 text-white" id="authentication-modal-label">Confirmer la suppression</h4>
                            </div>
                        </div>
                        <div class="p-4 pb-0">
                            <h5>Cette action est irréversible.</h5>
                            <h6>Voulez-vous vraiment supprimer "{{ $itemName ?? 'cet élément' }}" ?</h6>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-falcon-primary" type="button" data-bs-dismiss="modal">Annuler</button>
                        <button class="btn btn-falcon-danger" type="submit">Supprimer</button>
                    </div>
                </center>
            </form>
        </div>
    </div>
</div>
