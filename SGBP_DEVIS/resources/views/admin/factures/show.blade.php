@extends('admin.layouts.app')
@section('title', $facture->numero)
@section('content')
    <div class="min-h-screen">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.factures.index') }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg md:text-2xl font-bold text-gray-800">{{ $facture->numero }}</h2>
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $facture->type === 'final' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($facture->type) }}
                            </span>
                        </div>
                        <p class="text-xs md:text-sm text-gray-500">{{ $facture->titre }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @can('factures.send')
                        <button onclick="document.getElementById('sendModal').classList.remove('hidden')"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-1">
                            <span class="material-icons text-sm">send</span>
                            <span class="hidden md:inline">Envoyer</span>
                        </button>
                    @endcan
                    <a href="{{ route('admin.factures.pdf', $facture) }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-1">
                        <span class="material-icons text-sm">download</span>
                        <span class="hidden md:inline">PDF</span>
                    </a>
                    @if ($facture->statut === 'brouillon')
                        @can('factures.update')
                            <a href="{{ route('admin.factures.edit', $facture) }}"
                                class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-1">
                                <span class="material-icons text-sm">edit</span>
                                <span class="hidden md:inline">Modifier</span>
                            </a>
                        @endcan
                    @endif
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mx-4 mt-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <p class="text-green-700 flex items-center gap-2"><span
                        class="material-icons">check_circle</span>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="mx-4 mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <p class="text-red-700 flex items-center gap-2"><span
                        class="material-icons">error</span>{{ session('error') }}</p>
            </div>
        @endif

        <div class="p-4 md:p-8">
            <div class="max-w-5xl mx-auto grid lg:grid-cols-3 gap-6">

                <!-- Contenu principal -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Infos générales -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="material-icons text-green-600">info</span> Informations
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Émission :</span>
                                <strong>{{ $facture->date_emission->format('d/m/Y') }}</strong>
                            </div>
                            <div><span class="text-gray-500">Échéance :</span>
                                <strong
                                    class="{{ $facture->date_echeance->isPast() && $facture->statut_paiement !== 'paye' ? 'text-red-600' : '' }}">
                                    {{ $facture->date_echeance->format('d/m/Y') }}
                                </strong>
                            </div>
                            <div><span class="text-gray-500">Type :</span> <strong>{{ ucfirst($facture->type) }}</strong>
                            </div>
                            <div><span class="text-gray-500">Statut :</span>
                                <strong>{{ ucfirst($facture->statut) }}</strong>
                            </div>
                            @if ($facture->devis)
                                <div class="col-span-2">
                                    <span class="text-gray-500">Devis lié :</span>
                                    <a href="{{ route('admin.devis.show', $facture->devis) }}"
                                        class="font-medium text-blue-600 hover:underline">
                                        {{ $facture->devis->numero }}
                                    </a>
                                </div>
                            @endif
                            @if ($facture->conditions_paiement)
                                <div class="col-span-2"><span class="text-gray-500">Conditions :</span>
                                    <strong>{{ $facture->conditions_paiement }}</strong>
                                </div>
                            @endif
                            @if ($facture->notes)
                                <div class="col-span-2 bg-gray-50 rounded-lg p-3"><span
                                        class="text-gray-500 block mb-1">Notes :</span>{{ $facture->notes }}</div>
                            @endif
                        </div>
                        @role('admin|superadmin')
                            <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-3 text-sm">
                                <div><span class="text-gray-500">Créé le :</span>
                                    <strong>{{ $facture->created_at->format('d/m/Y H:i') }}</strong></div>
                                <div><span class="text-gray-500">Modifié le :</span>
                                    <strong>{{ $facture->updated_at->format('d/m/Y H:i') }}</strong></div>
                                @if ($facture->createdBy)
                                    <div><span class="text-gray-500">Créé par :</span>
                                        <strong>{{ $facture->createdBy->nom_complet }}</strong></div>
                                @endif
                                @if ($facture->updatedBy)
                                    <div><span class="text-gray-500">Modifié par :</span>
                                        <strong>{{ $facture->updatedBy->nom_complet }}</strong></div>
                                @endif
                            </div>
                        @endrole
                    </div>

                    <!-- Client -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span class="material-icons text-green-600">person</span> Client
                        </h3>
                        <p class="font-bold text-lg">{{ $facture->client->nom_complet }}</p>
                        @if ($facture->client->email)
                            <p class="text-sm text-gray-600">{{ $facture->client->email }}</p>
                        @endif
                        <p class="text-sm text-gray-600">{{ $facture->client->telephone_principal }}</p>
                        @if ($facture->client->adresse)
                            <p class="text-sm text-gray-600">{{ $facture->client->adresse }},
                                {{ $facture->client->ville ?? '' }}</p>
                        @endif
                        <a href="{{ route('admin.clients.show', $facture->client) }}"
                            class="text-sm text-blue-600 hover:underline mt-2 inline-block">
                            Voir la fiche client
                        </a>
                    </div>

                    <!-- Articles par catégorie -->
                    @foreach ($facture->categories as $cat)
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h4 class="font-bold text-lg mb-4 text-green-700">{{ $cat->nom }}</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="text-left px-3 py-2 rounded-l">Désignation</th>
                                            <th class="text-center px-3 py-2">Unité</th>
                                            <th class="text-center px-3 py-2">Qté</th>
                                            <th class="text-right px-3 py-2">P.U HT</th>
                                            <th class="text-right px-3 py-2">Remise</th>
                                            <th class="text-right px-3 py-2 rounded-r">Total HT</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($cat->articles as $art)
                                            <tr>
                                                <td class="px-3 py-3">{{ $art->designation }}</td>
                                                <td class="text-center px-3 py-3 text-gray-500">{{ $art->unite }}</td>
                                                <td class="text-center px-3 py-3">{{ $art->quantite }}</td>
                                                <td class="text-right px-3 py-3">
                                                    {{ number_format($art->prix_unitaire_ht, 0, ',', ' ') }}</td>
                                                <td class="text-right px-3 py-3 text-gray-500">
                                                    {{ $art->remise_pourcentage > 0 ? $art->remise_pourcentage . '%' : '—' }}
                                                </td>
                                                <td class="text-right px-3 py-3 font-semibold">
                                                    {{ number_format($art->total_ht, 0, ',', ' ') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if ($cat->main_oeuvre > 0)
                                <div
                                    class="mt-2 text-right text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-4 py-2">
                                    Main d'œuvre — {{ $cat->nom }} :
                                    <strong>{{ number_format($cat->main_oeuvre, 0, ',', ' ') }}
                                        {{ $facture->devise }}</strong>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    @php $sansCategorie = $facture->articles()->whereNull('facture_category_id')->get(); @endphp
                    @if ($sansCategorie->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h4 class="font-bold mb-4">Articles divers</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="text-left px-3 py-2">Désignation</th>
                                            <th class="text-center px-3 py-2">Unité</th>
                                            <th class="text-center px-3 py-2">Qté</th>
                                            <th class="text-right px-3 py-2">P.U HT</th>
                                            <th class="text-right px-3 py-2">Remise</th>
                                            <th class="text-right px-3 py-2">Total HT</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($sansCategorie as $art)
                                            <tr>
                                                <td class="px-3 py-3">{{ $art->designation }}</td>
                                                <td class="text-center px-3 py-3 text-gray-500">{{ $art->unite }}</td>
                                                <td class="text-center px-3 py-3">{{ $art->quantite }}</td>
                                                <td class="text-right px-3 py-3">
                                                    {{ number_format($art->prix_unitaire_ht, 0, ',', ' ') }}</td>
                                                <td class="text-right px-3 py-3 text-gray-500">
                                                    {{ $art->remise_pourcentage > 0 ? $art->remise_pourcentage . '%' : '—' }}
                                                </td>
                                                <td class="text-right px-3 py-3 font-semibold">
                                                    {{ number_format($art->total_ht, 0, ',', ' ') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Sidebar -->
                <div class="space-y-6">

                    <!-- Récap financier -->
                    <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl shadow-lg p-6 text-white">
                        <h3 class="font-bold mb-4">Récapitulatif</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between border-b border-green-400 pb-2">
                                <span>Total HT</span>
                                <strong>{{ number_format($facture->total_ht, 0, ',', ' ') }}
                                    {{ $facture->devise }}</strong>
                            </div>
                            <div class="flex justify-between border-b border-green-400 pb-2">
                                <span>TPS ({{ $facture->taux_tps ?? 9.5 }}%)</span>
                                <strong>{{ number_format($facture->total_tps ?? 0, 0, ',', ' ') }} {{ $facture->devise }}</strong>
                            </div>
                            <div class="flex justify-between border-b border-green-400 pb-2">
                                <span>CSS ({{ $facture->taux_css ?? 1 }}%)</span>
                                <strong>{{ number_format($facture->total_css ?? 0, 0, ',', ' ') }} {{ $facture->devise }}</strong>
                            </div>
                            @php $totalMainOeuvre = $facture->main_oeuvre + $facture->categories->sum('main_oeuvre'); @endphp
                            @if ($totalMainOeuvre > 0)
                                <div class="flex justify-between border-b border-green-400 pb-2">
                                    <span>Main d'œuvre</span>
                                    <strong>{{ number_format($totalMainOeuvre, 0, ',', ' ') }}
                                        {{ $facture->devise }}</strong>
                                </div>
                            @endif
                            <div class="flex justify-between pt-2">
                                <span class="font-bold text-base">TOTAL TTC</span>
                                <span
                                    class="text-2xl font-bold">{{ number_format($facture->total_ttc, 0, ',', ' ') }}</span>
                            </div>
                            <p class="text-xs text-green-100 text-right">{{ $facture->devise }}</p>
                        </div>
                        <!-- Badge statut paiement -->
                        <div class="mt-4 text-center">
                            <span
                                class="px-4 py-1 rounded-full text-xs font-bold
                            @if ($facture->statut_paiement === 'paye') bg-white text-green-700
                            @elseif($facture->statut_paiement === 'partiel') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                                @if ($facture->statut_paiement === 'paye')
                                    PAYÉE
                                @elseif($facture->statut_paiement === 'partiel')
                                    PARTIEL
                                @else
                                    NON PAYÉE
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Paiement -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold mb-4">Paiement</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Total TTC</span>
                                <strong>{{ number_format($facture->total_ttc, 0, ',', ' ') }}</strong>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Payé</span>
                                <strong
                                    class="text-green-600">{{ number_format($facture->montant_paye, 0, ',', ' ') }}</strong>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="font-semibold">Reste</span>
                                <strong class="{{ $facture->reste_a_payer > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ number_format($facture->reste_a_payer, 0, ',', ' ') }} {{ $facture->devise }}
                                </strong>
                            </div>
                        </div>
                        @if ($facture->total_ttc > 0)
                            @php $pct = min(100, round(($facture->montant_paye / $facture->total_ttc) * 100)); @endphp
                            <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full transition-all"
                                    style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-xs text-gray-400 text-right mt-1">{{ $pct }}% payé</p>
                        @endif

                        @if ($facture->reste_a_payer > 0)
                            @can('factures.payment')
                                <form action="{{ route('admin.factures.payment', $facture) }}" method="POST" class="mt-4"
                                    onsubmit="return confirm('Enregistrer ce paiement ?')">
                                    @csrf
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Ajouter un paiement</label>
                                    <div class="flex gap-2">
                                        <input type="number" name="montant" placeholder="Montant FCFA" step="1"
                                            min="1" max="{{ $facture->reste_a_payer }}"
                                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500"
                                            required>
                                        <button type="submit"
                                            class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                            <span class="material-icons text-sm">add</span>
                                        </button>
                                    </div>
                                </form>
                            @endcan
                        @endif
                    </div>

                    <!-- PV lié -->
                    @if ($facture->statut_paiement === 'paye' && $facture->type === 'final')
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="font-bold mb-3 flex items-center gap-2">
                                <span class="material-icons text-purple-600 text-lg">verified</span> PV de réception
                            </h3>
                            @if ($facture->pv)
                                <a href="{{ route('admin.pvs.show', $facture->pv) }}"
                                    class="flex items-center gap-2 text-purple-600 hover:underline font-medium">
                                    {{ $facture->pv->numero }}
                                </a>
                            @else
                                @can('pvs.create')
                                    <a href="{{ route('admin.pvs.create', ['facture_id' => $facture->id]) }}"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm">
                                        <span class="material-icons text-sm">add</span> Créer le PV
                                    </a>
                                @endcan
                            @endif
                        </div>
                    @endif

                    <!-- Danger Zone -->
                    @if ($facture->statut === 'brouillon')
                        @can('factures.delete')
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="font-semibold text-red-600 mb-3">Zone de danger</h3>
                                <form action="{{ route('admin.factures.destroy', $facture) }}" method="POST"
                                    onsubmit="return confirm('Supprimer cette facture ? Cette action est irréversible.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-full py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 text-sm">
                                        Supprimer la facture
                                    </button>
                                </form>
                            </div>
                        @endcan
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- ══════════ MODAL D'ENVOI ══════════ --}}
    @can('factures.send')
        <div id="sendModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <span class="material-icons text-green-600">send</span> Envoyer la facture
                        </h3>
                        <button onclick="document.getElementById('sendModal').classList.add('hidden')"
                            class="p-2 hover:bg-gray-100 rounded-lg">
                            <span class="material-icons">close</span>
                        </button>
                    </div>

                    {{-- Aperçu --}}
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <span class="material-icons text-green-600">receipt_long</span>
                            <div>
                                <p class="font-bold text-gray-800">{{ $facture->numero }}</p>
                                <p class="text-sm text-gray-500">{{ $facture->titre }}</p>
                            </div>
                            <span class="ml-auto font-bold text-green-700">
                                {{ number_format($facture->total_ttc, 0, ',', ' ') }} {{ $facture->devise }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('admin.factures.send', $facture) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Destinataire *</label>
                                <input type="email" name="email_destinataire" value="{{ $facture->client->email }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500"
                                    placeholder="email@exemple.com" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Message personnalisé</label>
                                <textarea name="message_personnalise" rows="4"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 resize-none"
                                    placeholder="Message optionnel à inclure dans l'email..."></textarea>
                                <p class="text-xs text-gray-400 mt-1">La facture PDF sera jointe automatiquement.</p>
                            </div>
                        </div>
                        <div class="flex gap-3 mt-6">
                            <button type="button" onclick="document.getElementById('sendModal').classList.add('hidden')"
                                class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">
                                Annuler
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium flex items-center justify-center gap-2">
                                <span class="material-icons text-sm">send</span> Envoyer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.search.includes('send=1')) {
                const modal = document.getElementById('sendModal');
                if (modal) modal.classList.remove('hidden');
                history.replaceState(null, '', window.location.pathname);
            }
        });
    </script>
@endsection
