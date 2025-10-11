{{-- Formulaire partagé pour création et modification de client --}}
@php
    $isEdit = isset($client) && $client->exists;
    $isCreate = !$isEdit;
    $clientType = old('type', $isEdit ? $client->type : 'particulier');
@endphp

<style>
    .form-section {
        display: none;
    }

    .form-section.active {
        display: block;
    }
</style>

<form id="clientForm" action="{{ $isEdit ? route('admin.clients.update', $client) : route('admin.clients.store') }}"
    method="POST" class="space-y-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <!-- Type Selection Card -->
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8 fade-in">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <span class="material-icons text-blue-600">person_add</span>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">Type de client</h3>
                <p class="text-sm text-gray-600">
                    @if ($isEdit)
                        {{ $client->type_display }} (non modifiable)
                    @else
                        Sélectionnez le type de client
                    @endif
                </p>
            </div>
        </div>

        @if ($isEdit)
            <!-- Type non modifiable en édition -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-start gap-3">
                    <span class="material-icons text-blue-600 mt-0.5">info</span>
                    <p class="text-sm text-blue-800">
                        Le type de client ne peut pas être modifié après la création. Si vous devez changer le type,
                        veuillez créer un nouveau client.
                    </p>
                </div>
            </div>
            <input type="hidden" name="type" value="{{ $client->type }}">
        @else
            <!-- Sélection du type en création -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label id="label-particulier"
                    class="relative flex items-center p-5 border-2 {{ $clientType === 'particulier' ? 'border-blue-600 bg-blue-50' : 'border-gray-300' }} rounded-xl cursor-pointer transition hover:shadow-md">
                    <input type="radio" name="type" value="particulier"
                        {{ $clientType === 'particulier' ? 'checked' : '' }} class="sr-only"
                        onchange="toggleClientType()">
                    <div class="flex items-center gap-4 flex-1">
                        <div
                            class="w-14 h-14 {{ $clientType === 'particulier' ? 'bg-gradient-to-br from-blue-500 to-blue-600 shadow-md' : 'bg-gray-200' }} rounded-full flex items-center justify-center">
                            <span
                                class="material-icons {{ $clientType === 'particulier' ? 'text-white' : 'text-gray-600' }} text-2xl">person</span>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-lg">Particulier</p>
                            <p class="text-sm text-gray-600">Personne physique</p>
                        </div>
                    </div>
                    <span
                        class="material-icons {{ $clientType === 'particulier' ? 'text-blue-600' : 'text-gray-300 hidden' }} text-2xl">check_circle</span>
                </label>

                <label id="label-societe"
                    class="relative flex items-center p-5 border-2 {{ $clientType === 'societe' ? 'border-blue-600 bg-blue-50' : 'border-gray-300' }} rounded-xl cursor-pointer transition hover:shadow-md">
                    <input type="radio" name="type" value="societe"
                        {{ $clientType === 'societe' ? 'checked' : '' }} class="sr-only" onchange="toggleClientType()">
                    <div class="flex items-center gap-4 flex-1">
                        <div
                            class="w-14 h-14 {{ $clientType === 'societe' ? 'bg-gradient-to-br from-blue-500 to-blue-600 shadow-md' : 'bg-gray-200' }} rounded-full flex items-center justify-center">
                            <span
                                class="material-icons {{ $clientType === 'societe' ? 'text-white' : 'text-gray-600' }} text-2xl">business</span>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-lg">Société</p>
                            <p class="text-sm text-gray-600">Personne morale</p>
                        </div>
                    </div>
                    <span
                        class="material-icons {{ $clientType === 'societe' ? 'text-blue-600' : 'text-gray-300 hidden' }} text-2xl">check_circle</span>
                </label>
            </div>
        @endif
    </div>

    <!-- PARTICULIER FORM -->
    <div id="particulierForm" class="form-section {{ $clientType === 'particulier' ? 'active' : '' }} space-y-6">
        @include('admin.clients.partials.particulier-fields')
    </div>

    <!-- SOCIÉTÉ FORM -->
    <div id="societeForm" class="form-section {{ $clientType === 'societe' ? 'active' : '' }} space-y-6">
        @include('admin.clients.partials.societe-fields')
    </div>

    <!-- Statut (si modification) -->
    @if ($isEdit)
        <div class="bg-white rounded-xl shadow-md p-6 md:p-8 fade-in">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                    <span class="material-icons text-gray-600">toggle_on</span>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Statut du client</h3>
                    <p class="text-sm text-gray-600">Activer ou désactiver</p>
                </div>
            </div>

            <label
                class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-green-300 hover:bg-green-50 {{ old('actif', $client->actif) ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                <input type="hidden" name="actif" value="0">
                <input type="checkbox" name="actif" value="1"
                    {{ old('actif', $client->actif) ? 'checked' : '' }}
                    class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                <div class="ml-3">
                    <div class="flex items-center gap-2">
                        <span class="material-icons text-green-600">check_circle</span>
                        <span class="font-medium text-gray-900">Client actif</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Décochez pour désactiver ce client</p>
                </div>
            </label>
        </div>

        <!-- Informations système -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span class="material-icons text-sm">info</span>
                Informations système
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                <div class="flex items-center gap-2">
                    <span class="material-icons text-gray-400 text-sm">event</span>
                    <span class="text-gray-500">Créé le :</span>
                    <span class="font-medium text-gray-900">{{ $client->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-icons text-gray-400 text-sm">update</span>
                    <span class="text-gray-500">Modifié le :</span>
                    <span class="font-medium text-gray-900">{{ $client->updated_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-icons text-gray-400 text-sm">fingerprint</span>
                    <span class="text-gray-500">ID :</span>
                    <span class="font-mono text-xs text-gray-900">{{ $client->id }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div
        class="flex items-center justify-between gap-3 sticky bottom-0 bg-white p-6 rounded-xl shadow-lg border-2 border-gray-200">
        <a href="{{ route('admin.clients.index') }}"
            class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-100 transition flex items-center gap-2">
            <span class="material-icons">close</span>
            <span class="hidden md:inline">Annuler</span>
        </a>
        <button type="submit"
            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 transition shadow-md flex items-center gap-2">
            <span class="material-icons">{{ $isEdit ? 'save' : 'check_circle' }}</span>
            <span>{{ $isEdit ? 'Enregistrer les modifications' : 'Enregistrer' }}</span>
        </button>
    </div>
</form>

<script>
    function setSectionEnabled(section, enabled) {
        if (!section) return;

        section.querySelectorAll('input, select, textarea').forEach(function(field) {
            field.disabled = !enabled;
        });
    }

    function toggleClientType() {
        const radioButtons = document.querySelectorAll('input[name="type"]');
        const particulierForm = document.getElementById('particulierForm');
        const societeForm = document.getElementById('societeForm');

        radioButtons.forEach((radio) => {
            const label = radio.closest('label');
            const iconBg = label.querySelector('div > div');
            const icon = iconBg.querySelector('.material-icons');
            const checkIcon = label.querySelector('.material-icons:last-child');

            if (radio.checked) {
                label.classList.add('border-blue-600', 'bg-blue-50');
                label.classList.remove('border-gray-300');
                iconBg.classList.remove('bg-gray-200');
                iconBg.classList.add('bg-gradient-to-br', 'from-blue-500', 'to-blue-600', 'shadow-md');
                icon.classList.remove('text-gray-600');
                icon.classList.add('text-white');
                checkIcon.classList.remove('hidden', 'text-gray-300');
                checkIcon.classList.add('text-blue-600');
            } else {
                label.classList.remove('border-blue-600', 'bg-blue-50');
                label.classList.add('border-gray-300');
                iconBg.classList.add('bg-gray-200');
                iconBg.classList.remove('bg-gradient-to-br', 'from-blue-500', 'to-blue-600', 'shadow-md');
                icon.classList.add('text-gray-600');
                icon.classList.remove('text-white');
                checkIcon.classList.add('hidden');
            }
        });

        const selectedType = document.querySelector('input[name="type"]:checked')?.value ||
            document.querySelector('input[name="type"][type="hidden"]')?.value ||
            'particulier';

        if (selectedType === 'particulier') {
            particulierForm.classList.add('active');
            societeForm.classList.remove('active');
            setSectionEnabled(particulierForm, true);
            setSectionEnabled(societeForm, false);
        } else {
            particulierForm.classList.remove('active');
            societeForm.classList.add('active');
            setSectionEnabled(particulierForm, false);
            setSectionEnabled(societeForm, true);
        }

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // Init au chargement
    document.addEventListener('DOMContentLoaded', function() {
        toggleClientType();
    });
</script>
