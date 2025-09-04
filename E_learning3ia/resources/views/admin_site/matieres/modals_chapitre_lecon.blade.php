
<div class="modal fade" id="chapitreModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Ajouter un Chapitre</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="{{ route('dashboard.chapitre.store') }}" method="POST">
                @csrf
                <input type="hidden" name="matiere_id" value="{{ $matiere->id }}">
                <div class="modal-body">
                    <div class="mb-3"><label for="nom" class="form-label">Nom du chapitre</label><input type="text" name="nom" class="form-control" required></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button><button type="submit" class="btn btn-primary">Enregistrer</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="leconModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Nouvelle Leçon</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form action="{{ route('dashboard.lecon.store') }}" method="POST">
                @csrf
                <input type="hidden" name="chapitre_id" id="chapitreIdField">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Titre de la nouvelle leçon</label>
                        <input type="text" name="titre" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Créer et gérer les ressources</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal: Modifier un Chapitre -->
<div class="modal fade" id="editChapitreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editChapitreForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Modifier le chapitre</h5></div>
                <div class="modal-body">
                    <label for="chapitreNomField" class="form-label">Nom du chapitre</label>
                    <input type="text" class="form-control" name="nom" id="chapitreNomField" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- NOUVEAU - Modal: Modifier une Leçon -->
<div class="modal fade" id="editLeconModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editLeconForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header"><h5 class="modal-title">Modifier la leçon</h5></div>
                <div class="modal-body">
                    <label for="leconTitreField" class="form-label">Titre de la leçon</label>
                    <input type="text" class="form-control" name="titre" id="leconTitreField" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>


