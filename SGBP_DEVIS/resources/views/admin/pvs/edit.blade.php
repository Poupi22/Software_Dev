@extends('admin.layouts.app')
@section('title', 'Modifier ' . $pv->numero)
@section('content')

    <div class="min-h-screen bg-gray-50">
        <!-- Top bar -->
        <div class="bg-white border-b px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.pvs.show', $pv) }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg">
                    <span class="material-icons">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold">Modifier {{ $pv->numero }}</h2>
                    <p class="text-xs text-gray-500">{{ $pv->titre }}</p>
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

        <form action="{{ route('admin.pvs.update', $pv) }}" method="POST">
            @csrf @method('PUT')
            <div class="p-4 md:p-8 max-w-3xl mx-auto space-y-6">

                <!-- Facture liée (lecture seule) -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-bold mb-3 flex items-center gap-2">
                        <span class="material-icons text-purple-600">receipt_long</span> Facture liée
                    </h3>
                    <div class="p-3 bg-gray-50 rounded-lg border flex items-center gap-3">
                        <span class="material-icons text-green-600">receipt_long</span>
                        <div>
                            <p class="font-medium">{{ $pv->facture->devis?->bonCommande?->numero ?? $pv->facture->numero }}</p>
                            <p class="text-sm text-gray-500">{{ $pv->facture->client->nom_complet }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">La facture liée ne peut pas être modifiée.</p>
                </div>

                <!-- Informations -->
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
                    <h3 class="font-bold text-lg flex items-center gap-2">
                        <span class="material-icons text-purple-600">info</span> Informations
                    </h3>

                    <div>
                        <label class="block text-sm font-medium mb-1">Titre <span class="text-red-500">*</span></label>
                        <input type="text" name="titre" value="{{ old('titre', $pv->titre) }}" required
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-purple-500">
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Date de réception <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="date_reception"
                                value="{{ old('date_reception', $pv->date_reception ? \Carbon\Carbon::parse($pv->date_reception)->format('Y-m-d') : '') }}"
                                required
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Lieu de réception</label>
                            <input type="text" name="lieu_reception"
                                value="{{ old('lieu_reception', $pv->lieu_reception) }}"
                                class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:border-purple-500">
                        </div>
                    </div>

                    <!-- État des travaux -->
                    <div>
                        <label class="block text-sm font-medium mb-2">État des travaux <span
                                class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @php $etats = [
                                    'conforme' => ['label' => 'Conforme', 'icon' => 'check_circle'],
                                    'reserve_mineure' => ['label' => 'Réserve mineure', 'icon' => 'warning'],
                                    'reserve_majeure' => ['label' => 'Réserve majeure', 'icon' => 'error'],
                                    'non_conforme' => ['label' => 'Non conforme', 'icon' => 'cancel'],
                            ]; @endphp
                            @foreach ($etats as $val => $info)
                                <label class="cursor-pointer">
                                    <input type="radio" name="etat_travaux" value="{{ $val }}"
                                        {{ old('etat_travaux', $pv->etat_travaux) === $val ? 'checked' : '' }}
                                        class="sr-only peer" required>
                                    <div
                                        class="p-3 border-2 rounded-xl text-center peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:border-gray-400 transition">
                                        <span class="material-icons text-2xl">{{ $info['icon'] }}</span>
                                        <p class="text-xs font-medium mt-1">{{ $info['label'] }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Détails -->
                <div class="bg-white rounded-xl shadow-sm p-6 space-y-5">
                    <h3 class="font-bold text-lg flex items-center gap-2">
                        <span class="material-icons text-purple-600">description</span> Détails
                    </h3>
                    <div>
                        <label class="block text-sm font-medium mb-1">Description des travaux</label>
                        <textarea name="description_travaux" rows="5"
                            class="w-full px-4 py-3 border rounded-lg resize-none focus:outline-none focus:border-purple-500">{{ old('description_travaux', $pv->description_travaux) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Observations</label>
                        <textarea name="observations" rows="4"
                            class="w-full px-4 py-3 border rounded-lg resize-none focus:outline-none focus:border-purple-500">{{ old('observations', $pv->observations) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Réserves</label>
                        <textarea name="reserves" rows="3"
                            class="w-full px-4 py-3 border rounded-lg resize-none focus:outline-none focus:border-purple-500">{{ old('reserves', $pv->reserves) }}</textarea>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex justify-between">
                    <a href="{{ route('admin.pvs.show', $pv) }}"
                        class="px-6 py-3 border-2 rounded-lg hover:bg-gray-100">Annuler</a>
                    <button type="submit"
                        class="px-8 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-bold">
                        <span class="material-icons text-sm">save</span> Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
