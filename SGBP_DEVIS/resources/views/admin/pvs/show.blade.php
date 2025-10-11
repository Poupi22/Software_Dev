@extends('admin.layouts.app')
@section('title', $pv->numero)
@section('content')

    <div class="min-h-screen bg-gray-50">
        <!-- Top bar -->
        <div class="bg-white border-b px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.pvs.index') }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg">
                        <span class="material-icons">arrow_back</span>
                    </a>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg md:text-2xl font-bold text-gray-800">{{ $pv->numero }}</h2>
                            @php
                                $etats = [
                                    'conforme' => ['label' => 'Conforme', 'class' => 'bg-green-100 text-green-700'],
                                    'reserve_mineure' => [
                                        'label' => 'Réserve mineure',
                                        'class' => 'bg-yellow-100 text-yellow-700',
                                    ],
                                    'reserve_majeure' => [
                                        'label' => 'Réserve majeure',
                                        'class' => 'bg-orange-100 text-orange-700',
                                    ],
                                    'non_conforme' => ['label' => 'Non conforme', 'class' => 'bg-red-100 text-red-700'],
                                ];
                                $etat = $etats[$pv->etat_travaux] ?? [
                                    'label' => $pv->etat_travaux,
                                    'class' => 'bg-gray-100 text-gray-700',
                                ];
                            @endphp
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full {{ $etat['class'] }}">{{ $etat['label'] }}</span>
                        </div>
                        <p class="text-xs text-gray-500">{{ $pv->titre }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @can('pvs.send')
                        <button onclick="document.getElementById('sendModal').classList.remove('hidden')"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center gap-1">
                            <span class="material-icons text-sm">send</span>
                            <span class="hidden md:inline">Envoyer</span>
                        </button>
                    @endcan
                    <a href="{{ route('admin.pvs.pdf', $pv) }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-1">
                        <span class="material-icons text-sm">download</span>
                        <span class="hidden md:inline">PDF</span>
                    </a>
                    @if ($pv->statut === 'brouillon')
                        @can('pvs.update')
                            <a href="{{ route('admin.pvs.edit', $pv) }}"
                                class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-1">
                                <span class="material-icons text-sm">edit</span>
                                <span class="hidden md:inline">Modifier</span>
                            </a>
                            <form action="{{ route('admin.pvs.signer', $pv) }}" method="POST" class="inline"
                                onsubmit="return confirm('Signer ce PV ? Il ne pourra plus être modifié.');">
                                @csrf @method('PATCH')
                                <button
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-1">
                                    <span class="material-icons text-sm">draw</span>
                                    <span class="hidden md:inline">Signer</span>
                                </button>
                            </form>
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

        <div class="p-4 md:p-8 max-w-4xl mx-auto space-y-6">

            <!-- Infos générales -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="material-icons text-purple-600">info</span> Informations
                </h3>
                <div class="grid md:grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Date de réception :</span>
                        <strong>{{ $pv->date_reception ? \Carbon\Carbon::parse($pv->date_reception)->format('d/m/Y') : '—' }}</strong>
                    </div>
                    <div><span class="text-gray-500">Lieu :</span> <strong>{{ $pv->lieu_reception ?? '—' }}</strong></div>
                    <div><span class="text-gray-500">Statut PV :</span> <strong>{{ ucfirst($pv->statut) }}</strong></div>
                    @role('admin|superadmin')
                        <div><span class="text-gray-500">Créé par :</span>
                            <strong>{{ $pv->createdBy->nom_complet ?? '—' }}</strong>
                        </div>
                        @if ($pv->updatedBy)
                            <div><span class="text-gray-500">Modifié par :</span>
                                <strong>{{ $pv->updatedBy->nom_complet }}</strong>
                            </div>
                        @endif
                    @endrole
                </div>
            </div>

            <!-- Facture et client -->
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold mb-4 flex items-center gap-2">
                        <span class="material-icons text-green-600">receipt_long</span> Facture liée
                    </h3>
                    <p class="font-bold text-lg">{{ $pv->facture->devis?->bonCommande?->numero ?? $pv->facture->numero }}</p>
                    <p class="text-sm text-gray-500">{{ $pv->facture->titre }}</p>
                    <p class="text-sm mt-1">{{ number_format($pv->facture->total_ttc, 0, ',', ' ') }}
                        {{ $pv->facture->devise }}</p>
                    <a href="{{ route('admin.factures.show', $pv->facture) }}"
                        class="text-sm text-blue-600 hover:underline mt-2 inline-block">
                        Voir la facture →
                    </a>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold mb-4 flex items-center gap-2">
                        <span class="material-icons text-blue-600">person</span> Client
                    </h3>
                    <p class="font-bold text-lg">{{ $pv->client->nom_complet }}</p>
                    @if ($pv->client->email)
                        <p class="text-sm text-gray-500">{{ $pv->client->email }}</p>
                    @endif
                    <p class="text-sm text-gray-500">{{ $pv->client->telephone_principal }}</p>
                    <a href="{{ route('admin.clients.show', $pv->client) }}"
                        class="text-sm text-blue-600 hover:underline mt-2 inline-block">
                        Voir la fiche client →
                    </a>
                </div>
            </div>

            <!-- État des travaux -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-bold mb-4 flex items-center gap-2">
                    <span class="material-icons text-purple-600">construction</span> État des travaux
                </h3>
                @php
                    $iconEtat = [
                        'conforme' => ['check_circle', 'text-green-500'],
                        'reserve_mineure' => ['warning', 'text-yellow-500'],
                        'reserve_majeure' => ['error', 'text-orange-500'],
                        'non_conforme' => ['cancel', 'text-red-500'],
                    ];
                    $ie = $iconEtat[$pv->etat_travaux] ?? ['help', 'text-gray-400'];
                @endphp
                <div class="flex items-center gap-3">
                    <span class="material-icons text-4xl {{ $ie[1] }}">{{ $ie[0] }}</span>
                    <div>
                        <p class="font-bold text-lg">{{ $etat['label'] }}</p>
                        @if ($pv->reserves)
                            <p class="text-sm text-gray-500 mt-1">{{ $pv->reserves }}</p>
                        @endif
                    </div>
                </div>
            </div>

            @if ($pv->description_travaux)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold mb-3 flex items-center gap-2">
                        <span class="material-icons text-purple-600">description</span> Description des travaux
                    </h3>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $pv->description_travaux }}</p>
                </div>
            @endif

            @if ($pv->observations)
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold mb-3 flex items-center gap-2">
                        <span class="material-icons text-purple-600">visibility</span> Observations
                    </h3>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $pv->observations }}</p>
                </div>
            @endif

            <!-- Danger Zone -->
            @if ($pv->statut === 'brouillon')
                @can('pvs.delete')
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-semibold text-red-600 mb-3">Zone de danger</h3>
                        <form action="{{ route('admin.pvs.destroy', $pv) }}" method="POST"
                            onsubmit="return confirm('Supprimer ce PV ? Cette action est irréversible.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="px-6 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 text-sm">
                                Supprimer le PV
                            </button>
                        </form>
                    </div>
                @endcan
            @endif

        </div>
    </div>

    {{-- Modal envoi email --}}
    @can('pvs.send')
        <div id="sendModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <span class="material-icons text-purple-600">send</span> Envoyer le PV
                        </h3>
                        <button onclick="document.getElementById('sendModal').classList.add('hidden')"
                            class="p-2 hover:bg-gray-100 rounded-lg">
                            <span class="material-icons">close</span>
                        </button>
                    </div>

                    {{-- Aperçu --}}
                    <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <span class="material-icons text-purple-600">verified</span>
                            <div>
                                <p class="font-bold text-gray-800">{{ $pv->numero }}</p>
                                <p class="text-sm text-gray-500">{{ $pv->titre }}</p>
                            </div>
                            <span class="ml-auto text-sm font-medium text-purple-700">{{ ucfirst($pv->statut) }}</span>
                        </div>
                    </div>

                    <form action="{{ route('admin.pvs.send', $pv) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Destinataire *</label>
                                <input type="email" name="email_destinataire" value="{{ $pv->client->email ?? '' }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500"
                                    placeholder="email@exemple.com" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Message personnalisé</label>
                                <textarea name="message_personnalise" rows="4"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 resize-none"
                                    placeholder="Message optionnel à inclure dans l'email..."></textarea>
                                <p class="text-xs text-gray-400 mt-1">Le PV en PDF sera joint automatiquement.</p>
                            </div>
                        </div>
                        <div class="flex gap-3 mt-6">
                            <button type="button" onclick="document.getElementById('sendModal').classList.add('hidden')"
                                class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">
                                Annuler
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium flex items-center justify-center gap-2">
                                <span class="material-icons text-sm">send</span> Envoyer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection
