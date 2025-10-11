@extends('admin.layouts.app')
@section('title', 'Nouveau PV de réception')
@section('content')

    <div class="min-h-screen bg-gray-50">
        <!-- Top bar -->
        <div class="bg-white border-b px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.pvs.index') }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg">
                    <span class="material-icons">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold">Nouveau PV de réception</h2>
                    <p class="text-xs text-gray-500">Procès-verbal de réception des travaux</p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="mx-4 mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pvs.store') }}" method="POST">
            @csrf
            <div class="p-4 md:p-8 max-w-3xl mx-auto space-y-6">

                <!-- Facture liée -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                        <span class="material-icons text-purple-600">receipt_long</span> Facture liée
                    </h3>
                    @if ($factures->count() > 0)
                        <select name="facture_id" required
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-purple-500">
                            <option value="">-- Sélectionner une facture --</option>
                            @foreach ($factures as $f)
                                <option value="{{ $f->id }}"
                                    {{ old('facture_id', $selectedFactureId ?? '') == $f->id ? 'selected' : '' }}>
                                    {{ $f->numero }} — {{ $f->titre }} ({{ $f->client->nom_complet }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-2">Seules les factures finales entièrement payées sans PV sont
                            listées.</p>
                    @else
                        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                            <p class="text-yellow-700 text-sm">
                                <span class="material-icons text-sm">warning</span>
                                Aucune facture éligible. Une facture doit être de type <strong>final</strong> et entièrement
                                <strong>payée</strong> pour créer un PV.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Informations principales -->
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
                    <h3 class="font-bold text-lg flex items-center gap-2">
                        <span class="material-icons text-purple-600">info</span> Informations
                    </h3>

                    <div>
                        <label class="block text-sm font-medium mb-1">Titre <span class="text-red-500">*</span></label>
                        <input type="text" name="titre" value="{{ old('titre') }}" required
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-purple-500"
                            placeholder="Ex: PV de réception des travaux de rénovation">
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Date de réception <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="date_reception" value="{{ old('date_reception', date('Y-m-d')) }}"
                                required
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Lieu de réception</label>
                            <input type="text" name="lieu_reception" value="{{ old('lieu_reception') }}"
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-purple-500"
                                placeholder="Ex: Chantier Douala, Bonapriso">
                        </div>
                    </div>

                    <!-- État des travaux -->
                    <div>
                        <label class="block text-sm font-medium mb-2">État des travaux <span
                                class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @php
                                $etats = [
                                    'conforme' => ['label' => 'Conforme', 'color' => 'green', 'icon' => 'check_circle'],
                                    'reserve_mineure' => [
                                        'label' => 'Réserve mineure',
                                        'color' => 'yellow',
                                        'icon' => 'warning',
                                    ],
                                    'reserve_majeure' => [
                                        'label' => 'Réserve majeure',
                                        'color' => 'orange',
                                        'icon' => 'error',
                                    ],
                                    'non_conforme' => ['label' => 'Non conforme', 'color' => 'red', 'icon' => 'cancel'],
                                ];
                            @endphp
                            @foreach ($etats as $val => $info)
                                <label class="cursor-pointer">
                                    <input type="radio" name="etat_travaux" value="{{ $val }}"
                                        {{ old('etat_travaux', 'conforme') === $val ? 'checked' : '' }}
                                        class="sr-only peer" required>
                                    <div
                                        class="p-3 border-2 rounded-xl text-center peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:border-gray-400 transition">
                                        <span
                                            class="material-icons text-{{ $info['color'] }}-500 text-2xl">{{ $info['icon'] }}</span>
                                        <p class="text-xs font-medium mt-1">{{ $info['label'] }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Description et observations -->
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
                    <h3 class="font-bold text-lg flex items-center gap-2">
                        <span class="material-icons text-purple-600">description</span> Détails
                    </h3>

                    <div>
                        <label class="block text-sm font-medium mb-1">Description des travaux réalisés</label>
                        <textarea name="description_travaux" rows="5"
                            class="w-full px-4 py-3 border rounded-lg resize-none focus:outline-none focus:border-purple-500"
                            placeholder="Décrivez les travaux effectués...">{{ old('description_travaux') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Observations</label>
                        <textarea name="observations" rows="4"
                            class="w-full px-4 py-3 border rounded-lg resize-none focus:outline-none focus:border-purple-500"
                            placeholder="Observations générales...">{{ old('observations') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Réserves</label>
                        <textarea name="reserves" rows="3"
                            class="w-full px-4 py-3 border rounded-lg resize-none focus:outline-none focus:border-purple-500"
                            placeholder="Réserves émises lors de la réception (laisser vide si aucune)...">{{ old('reserves') }}</textarea>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex justify-between">
                    <a href="{{ route('admin.pvs.index') }}"
                        class="px-6 py-3 border-2 rounded-lg hover:bg-gray-100">Annuler</a>
                    <button type="submit" {{ $factures->count() === 0 ? 'disabled' : '' }}
                        class="px-8 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-bold disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="material-icons text-sm">check_circle</span> Créer le PV
                    </button>
                </div>

            </div>
        </form>
    </div>
@endsection
