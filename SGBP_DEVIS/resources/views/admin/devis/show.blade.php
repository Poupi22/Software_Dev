@extends('admin.layouts.app')
@section('content')
    <div class="min-h-screen">
        <div class="bg-white border-b px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.devis.index') }}"><span class="material-icons">arrow_back</span></a>
                    <div>
                        <h2 class="text-lg md:text-2xl font-bold">{{ $devis->numero }}</h2>
                        <p class="text-xs md:text-sm text-gray-500">{{ $devis->titre }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    {{-- Modifier : brouillon (tous types) ou provisoire non terminé --}}
                    @if ($devis->type === 'provisoire' && !in_array($devis->statut, ['accepte', 'refuse', 'expire']))
                        @can('devis.update')
                            <a href="{{ route('admin.devis.edit', $devis) }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center gap-1 text-sm">
                                <span class="material-icons text-sm">edit</span> Modifier
                            </a>
                        @endcan
                    @endif

                    {{-- Envoyer par email --}}
                    @can('devis.send')
                        <button onclick="document.getElementById('sendModal').classList.remove('hidden')"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg flex items-center gap-1 text-sm">
                            <span class="material-icons text-sm">send</span> Envoyer
                        </button>
                    @endcan

                    {{-- Finaliser : provisoire → final (ouvre le modal) --}}
                    @if ($devis->type === 'provisoire')
                        @can('devis.update')
                            <button onclick="document.getElementById('finaliserModal').classList.remove('hidden')"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg flex items-center gap-1 text-sm">
                                <span class="material-icons text-sm">check_circle</span> Finaliser
                            </button>
                        @endcan
                    @endif

                    {{-- Accepter : devis final (sur place ou après réponse client) --}}
                    @if ($devis->type === 'final' && !in_array($devis->statut, ['accepte']))
                        @can('devis.update')
                            <form action="{{ route('admin.devis.accept', $devis) }}" method="POST" class="inline">
                                @csrf
                                <button onclick="return confirm('Marquer ce devis comme accepté par le client ?')"
                                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg flex items-center gap-1 text-sm">
                                    <span class="material-icons text-sm">thumb_up</span> Accepter
                                </button>
                            </form>
                        @endcan
                    @endif

                    {{-- Convertir en facture : accepté sans facture --}}
                    @if ($devis->statut === 'accepte' && !$devis->facture_id)
                        @can('devis.convert')
                            <form action="{{ route('admin.devis.convert', $devis) }}" method="POST" class="inline">
                                @csrf
                                <button onclick="return confirm('Convertir ce devis en facture ?')"
                                    class="px-4 py-2 bg-purple-600 text-white rounded-lg flex items-center gap-1 text-sm">
                                    <span class="material-icons text-sm">receipt_long</span> Convertir en facture
                                </button>
                            </form>
                        @endcan
                    @endif

                    {{-- PDF --}}
                    <a href="{{ route('admin.devis.pdf', $devis) }}"
                        class="px-4 py-2 border rounded-lg flex items-center gap-1 text-sm">
                        <span class="material-icons text-sm">download</span> PDF
                    </a>

                    {{-- PDF sans cachet (pour signature manuelle) --}}
                    <a href="{{ route('admin.devis.pdf-sans-cachet', $devis) }}"
                        class="px-4 py-2 border border-orange-300 text-orange-700 rounded-lg flex items-center gap-1 text-sm">
                        <span class="material-icons text-sm">download</span> PDF sans cachet
                    </a>

                    {{-- Supprimer : brouillon uniquement --}}
                    @if ($devis->statut === 'brouillon')
                        @can('devis.delete')
                            <form action="{{ route('admin.devis.destroy', $devis) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Supprimer définitivement ce devis ?')"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg flex items-center gap-1 text-sm">
                                    <span class="material-icons text-sm">delete</span> Supprimer
                                </button>
                            </form>
                        @endcan
                    @endif
                </div>
            </div>
        </div>

        <div class="p-4 md:p-8">
            <div class="max-w-5xl mx-auto grid lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Infos -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold mb-4">Informations</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Date création :</span>
                                <strong>{{ $devis->created_at->format('d/m/Y') }}</strong>
                            </div>
                            <div><span class="text-gray-500">Validité :</span>
                                <strong>{{ $devis->validite_mois ?? 1 }} mois</strong>
                            </div>
                            <div><span class="text-gray-500">Type :</span> <strong>{{ ucfirst($devis->type) }}</strong>
                            </div>
                            <div><span class="text-gray-500">Statut :</span> <strong>{{ ucfirst($devis->statut) }}</strong>
                            </div>
                        </div>
                        @role('admin|superadmin')
                            <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-3 text-sm">
                                <div><span class="text-gray-500">Créé le :</span>
                                    <strong>{{ $devis->created_at->format('d/m/Y H:i') }}</strong>
                                </div>
                                <div><span class="text-gray-500">Modifié le :</span>
                                    <strong>{{ $devis->updated_at->format('d/m/Y H:i') }}</strong>
                                </div>
                                @if ($devis->createdBy)
                                    <div><span class="text-gray-500">Créé par :</span>
                                        <strong>{{ $devis->createdBy->nom_complet }}</strong>
                                    </div>
                                @endif
                                @if ($devis->updatedBy)
                                    <div><span class="text-gray-500">Modifié par :</span>
                                        <strong>{{ $devis->updatedBy->nom_complet }}</strong>
                                    </div>
                                @endif
                            </div>
                        @endrole
                    </div>

                    <!-- Client -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold mb-4">Client</h3>
                        <p class="font-bold text-lg">{{ $devis->client->nom_complet }}</p>
                        <p class="text-sm text-gray-600">{{ $devis->client->email }}</p>
                        <p class="text-sm text-gray-600">{{ $devis->client->telephone_principal }}</p>
                    </div>

                    <!-- Catégories -->
                    @foreach ($devis->categories as $cat)
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h4 class="font-bold text-lg mb-4 text-blue-600">{{ $cat->nom }}</h4>
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left px-3 py-2">Article</th>
                                        <th class="text-center px-3 py-2">Qté</th>
                                        <th class="text-right px-3 py-2">Prix HT</th>
                                        <th class="text-right px-3 py-2">Remise</th>
                                        <th class="text-right px-3 py-2">Total HT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cat->articles as $art)
                                        <tr class="border-b">
                                            <td class="px-3 py-3">{{ $art->designation }}</td>
                                            <td class="text-center px-3 py-3">{{ $art->quantite }}</td>
                                            <td class="text-right px-3 py-3">
                                                {{ number_format($art->prix_unitaire_ht, 0, ',', ' ') }}</td>
                                            <td class="text-right px-3 py-3">
                                                {{ $art->remise_pourcentage > 0 ? number_format($art->remise_pourcentage, 0) . '%' : '—' }}
                                            </td>
                                            <td class="text-right px-3 py-3 font-bold">
                                                {{ number_format($art->total_ht, 0, ',', ' ') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($cat->main_oeuvre > 0)
                                <div class="mt-4 p-3 bg-yellow-50 rounded flex justify-between">
                                    <span>Main d'œuvre</span>
                                    <strong>{{ number_format($cat->main_oeuvre, 0, ',', ' ') }}
                                        {{ $devis->devise }}</strong>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <!-- Articles SANS catégorie -->
                    @php $articlesSansCat = $devis->articles()->whereNull('devis_category_id')->get(); @endphp
                    @if ($articlesSansCat->count() > 0)
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h4 class="font-bold mb-4">Articles sans catégorie</h4>
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left px-3 py-2">Article</th>
                                        <th class="text-center px-3 py-2">Qté</th>
                                        <th class="text-right px-3 py-2">Prix HT</th>
                                        <th class="text-right px-3 py-2">Remise</th>
                                        <th class="text-right px-3 py-2">Total HT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($articlesSansCat as $art)
                                        <tr class="border-b">
                                            <td class="px-3 py-3">{{ $art->designation }}</td>
                                            <td class="text-center px-3 py-3">{{ $art->quantite }}</td>
                                            <td class="text-right px-3 py-3">
                                                {{ number_format($art->prix_unitaire_ht, 0, ',', ' ') }}</td>
                                            <td class="text-right px-3 py-3">
                                                {{ $art->remise_pourcentage > 0 ? number_format($art->remise_pourcentage, 0) . '%' : '—' }}
                                            </td>
                                            <td class="text-right px-3 py-3 font-bold">
                                                {{ number_format($art->total_ht, 0, ',', ' ') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl shadow-lg p-6 text-white">
                        <h3 class="font-bold mb-4">Récapitulatif</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between border-b border-blue-400 pb-2">
                                <span class="text-sm">Total HT</span>
                                <strong>{{ number_format($devis->total_ht, 0, ',', ' ') }}</strong>
                            </div>
                            <div class="flex justify-between border-b border-blue-400 pb-2">
                                <span class="text-sm">TPS ({{ $devis->taux_tps ?? 9.5 }}%)</span>
                                <strong>{{ number_format($devis->total_tps ?? 0, 0, ',', ' ') }}</strong>
                            </div>
                            <div class="flex justify-between border-b border-blue-400 pb-2">
                                <span class="text-sm">CSS ({{ $devis->taux_css ?? 1 }}%)</span>
                                <strong>{{ number_format($devis->total_css ?? 0, 0, ',', ' ') }}</strong>
                            </div>
                            <div class="flex justify-between border-b border-blue-400 pb-2">
                                <span class="text-sm">Main d'œuvre</span>
                                <strong>{{ number_format($devis->main_oeuvre + $devis->categories->sum('main_oeuvre'), 0, ',', ' ') }}</strong>
                            </div>
                            <div class="flex justify-between pt-2">
                                <span class="font-bold">TOTAL TTC</span>
                                <span class="text-2xl font-bold">{{ number_format($devis->total_ttc, 0, ',', ' ') }}</span>
                            </div>
                            <p class="text-xs text-blue-100 text-center">{{ $devis->devise }}</p>
                        </div>
                    </div>

                    {{-- Bon de commande (affiché si devis finalisé et BC existant) --}}
                    @if ($devis->bonCommande)
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="font-bold mb-4 flex items-center gap-2">
                                <span class="material-icons text-green-600">receipt</span>
                                Bon de commande
                            </h3>
                            <div class="space-y-2 text-sm">
                                @if ($devis->bonCommande->numero)
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Numéro BC</span>
                                        <strong>{{ $devis->bonCommande->numero }}</strong>
                                    </div>
                                @endif
                                @if ($devis->bonCommande->fichier_path)
                                    <div class="mt-3">
                                        <a href="{{ route('admin.devis.bc.download', $devis) }}"
                                            class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition text-gray-700">
                                            <span class="material-icons text-base text-green-600">download</span>
                                            <span class="truncate text-xs">{{ $devis->bonCommande->fichier_nom }}</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════ MODAL ENVOI EMAIL ══════════ --}}
    <div id="sendModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
            <div class="bg-blue-600 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2 text-white">
                    <span class="material-icons">email</span>
                    <h3 class="font-bold text-lg">Envoyer le devis par email</h3>
                </div>
                <button onclick="document.getElementById('sendModal').classList.add('hidden')"
                    class="text-white/80 hover:text-white">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <form action="{{ route('admin.devis.send', $devis) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label for="email_destinataire" class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="material-icons text-sm align-middle mr-1">person</span>Destinataire
                    </label>
                    <input type="email" name="email_destinataire" id="email_destinataire"
                        value="{{ $devis->client->email }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="email@client.com">
                    <p class="text-xs text-gray-500 mt-1">Client : {{ $devis->client->nom_complet }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3 text-sm">
                    <div class="flex items-center gap-2 text-gray-600 mb-2">
                        <span class="material-icons text-sm">info</span>
                        <span class="font-medium">Le devis PDF sera joint automatiquement</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-500">
                        <span>Devis : <strong class="text-gray-700">{{ $devis->numero }}</strong></span>
                        <span>Type : <strong class="text-gray-700">{{ ucfirst($devis->type) }}</strong></span>
                        <span>Total : <strong class="text-gray-700">{{ number_format($devis->total_ttc, 0, ',', ' ') }} {{ $devis->devise }}</strong></span>
                        <span>Validité : <strong class="text-gray-700">{{ $devis->validite_mois ?? 1 }} mois</strong></span>
                    </div>
                </div>
                <div>
                    <label for="message_personnalise" class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="material-icons text-sm align-middle mr-1">edit_note</span>Message personnalisé
                        <span class="text-gray-400 font-normal">(optionnel)</span>
                    </label>
                    <textarea name="message_personnalise" id="message_personnalise" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Ajoutez un message au client... (laissez vide pour le message par défaut)"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('sendModal').classList.add('hidden')"
                        class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 flex items-center gap-2">
                        <span class="material-icons text-sm">send</span> Envoyer le devis
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════ MODAL FINALISER ══════════ --}}
    <div id="finaliserModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">

            {{-- Header --}}
            <div class="bg-blue-600 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-2 text-white">
                    <span class="material-icons">check_circle</span>
                    <h3 class="font-bold text-lg">Finaliser le devis</h3>
                </div>
                <button onclick="document.getElementById('finaliserModal').classList.add('hidden')"
                    class="text-white/80 hover:text-white">
                    <span class="material-icons">close</span>
                </button>
            </div>

            {{-- Body --}}
            <form action="{{ route('admin.devis.finaliser', $devis) }}" method="POST"
                enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                @method('PATCH')

                {{-- Avertissement --}}
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-sm text-amber-800 flex gap-2">
                    <span class="material-icons text-base mt-0.5 shrink-0">warning</span>
                    <span>Une fois finalisé, ce devis <strong>ne pourra plus être modifié</strong>. Le bon de commande est facultatif.</span>
                </div>

                {{-- Numéro BC --}}
                <div>
                    <label for="numero_bc" class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="material-icons text-sm align-middle mr-1">tag</span>
                        Numéro du bon de commande
                        <span class="text-gray-400 font-normal">(optionnel)</span>
                    </label>
                    <input type="text" name="numero_bc" id="numero_bc"
                        value="{{ $devis->bonCommande?->numero }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Ex : BC-2025-001">
                </div>

                {{-- Fichier BC --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <span class="material-icons text-sm align-middle mr-1">attach_file</span>
                        Fichier du bon de commande
                        <span class="text-gray-400 font-normal">(PDF ou image — optionnel)</span>
                    </label>

                    {{-- Fichier existant --}}
                    @if ($devis->bonCommande?->fichier_nom)
                        <div class="mb-2 flex items-center gap-2 text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2 border">
                            <span class="material-icons text-base text-green-600">description</span>
                            <span class="truncate">{{ $devis->bonCommande->fichier_nom }}</span>
                            <span class="text-gray-400 shrink-0">(existant)</span>
                        </div>
                    @endif

                    {{-- Zone de drop --}}
                    <div class="border-2 border-dashed border-gray-300 rounded-lg px-4 py-6 text-center cursor-pointer hover:border-green-400 transition"
                        onclick="document.getElementById('fichier_bc').click()">
                        <span class="material-icons text-3xl text-gray-300 mb-1">cloud_upload</span>
                        <p class="text-sm text-gray-500">Cliquez pour sélectionner un fichier</p>
                        <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG — max 5 Mo</p>
                        <p id="fichier_bc_nom" class="text-sm font-medium text-green-600 mt-2 hidden"></p>
                    </div>
                    <input type="file" name="fichier_bc" id="fichier_bc" class="hidden"
                        accept=".pdf,.jpg,.jpeg,.png"
                        onchange="
                            const el = document.getElementById('fichier_bc_nom');
                            el.textContent = this.files[0]?.name ?? '';
                            el.classList.toggle('hidden', !this.files[0]);
                        ">
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button"
                        onclick="document.getElementById('finaliserModal').classList.add('hidden')"
                        class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 flex items-center gap-2">
                        <span class="material-icons text-sm">check_circle</span> Confirmer la finalisation
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Auto-ouvrir le modal send si ?send=1 --}}
    <script>
        if (new URLSearchParams(window.location.search).get('send') === '1') {
            document.getElementById('sendModal').classList.remove('hidden');
            history.replaceState(null, '', window.location.pathname);
        }
    </script>

@endsection