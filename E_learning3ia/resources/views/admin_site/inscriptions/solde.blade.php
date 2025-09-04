<div class="modal fade" id="paymentModal{{ $inscription->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Paiements de {{ $inscription->user->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <h6>Historique</h6>
                <ul>
                    @forelse($inscription->paiements as $paiement)
                        <li>{{ $paiement->created_at->format('d/m/Y') }}: {{ number_format($paiement->montant) }} FCFA</li>
                    @empty
                        <li>Aucun paiement.</li>
                    @endforelse
                </ul>
                <hr>
                @if($inscription->reste > 0)
                <form action="{{ route('dashboard.inscription.add_paiement', $inscription->id) }}" method="POST">
                    @csrf
                    <h6>Ajouter un versement</h6>
                    <div class="input-group">
                        <input type="number" name="montant" class="form-control" max="{{ $inscription->reste }}" required>
                        <button class="btn btn-primary" type="submit">Enregistrer</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
