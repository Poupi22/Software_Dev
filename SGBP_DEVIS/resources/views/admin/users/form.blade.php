{{-- Formulaire partagé pour création et modification d'utilisateur --}}
@php
    $isEdit = isset($user) && $user->exists;
    $isCreate = !$isEdit;
@endphp

<form action="{{ $isEdit ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST"
    class="space-y-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <!-- Informations personnelles -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span class="material-icons text-blue-600">person</span>
            Informations personnelles
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Prénom -->
            <div>
                <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">
                    Prénom <span class="text-red-500">*</span>
                </label>
                <input type="text" id="prenom" name="prenom"
                    value="{{ old('prenom', $isEdit ? $user->prenom : '') }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('prenom') border-red-500 @enderror"
                    required>
                @error('prenom')
                    <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                        <span class="material-icons text-sm">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Nom -->
            <div>
                <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nom" name="nom" value="{{ old('nom', $isEdit ? $user->nom : '') }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('nom') border-red-500 @enderror"
                    required>
                @error('nom')
                    <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                        <span class="material-icons text-sm">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span
                        class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">email</span>
                    <input type="email" id="email" name="email"
                        value="{{ old('email', $isEdit ? $user->email : '') }}"
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('email') border-red-500 @enderror"
                        required>
                </div>
                @error('email')
                    <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                        <span class="material-icons text-sm">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Téléphone -->
            <div>
                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-2">
                    Téléphone
                </label>
                <div class="relative">
                    <span
                        class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">phone</span>
                    <input type="text" id="telephone" name="telephone"
                        value="{{ old('telephone', $isEdit ? $user->telephone : '') }}" placeholder="+241 XX XX XX XX"
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('telephone') border-red-500 @enderror">
                </div>
                @error('telephone')
                    <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                        <span class="material-icons text-sm">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Mot de passe -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center gap-2">
            <span class="material-icons text-blue-600">lock</span>
            Mot de passe
            @if ($isEdit)
                <span class="text-xs font-normal text-gray-500">(Laissez vide pour ne pas modifier)</span>
            @endif
        </h3>

        @if ($isCreate)
            <p class="text-sm text-gray-600 mb-4">
                Minimum 8 caractères avec majuscules, minuscules, chiffres et symboles
            </p>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Mot de passe -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $isEdit ? 'Nouveau mot de passe' : 'Mot de passe' }}
                    @if ($isCreate)
                        <span class="text-red-500">*</span>
                    @endif
                </label>
                <div class="relative">
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 @error('password') border-red-500 @enderror"
                        {{ $isCreate ? 'required' : '' }}>
                    <button type="button" onclick="togglePasswordVisibility('password')"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-xl">visibility</span>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                        <span class="material-icons text-sm">error</span>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirmer le mot de passe
                    @if ($isCreate)
                        <span class="text-red-500">*</span>
                    @endif
                </label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                        {{ $isCreate ? 'required' : '' }}>
                    <button type="button" onclick="togglePasswordVisibility('password_confirmation')"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <span class="material-icons text-xl">visibility</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rôles et permissions -->
    @if (!($isEdit && $user->isSuperAdmin()))
        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="material-icons text-blue-600">admin_panel_settings</span>
                Rôles et permissions
                <span class="text-red-500">*</span>
            </h3>

            @error('roles')
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <p class="text-sm text-red-600 flex items-center gap-1">
                        <span class="material-icons text-sm">error</span>
                        {{ $message }}
                    </p>
                </div>
            @enderror

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach ($roles as $role)
                    @php
                        $isChecked = $isEdit ? $user->hasRole($role->name) : false;
                        if (old('roles')) {
                            $isChecked = in_array($role->name, old('roles', []));
                        }
                    @endphp
                    <label
                        class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-blue-300 hover:bg-blue-50 {{ $isChecked ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                            {{ $isChecked ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <div class="ml-3 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="material-icons text-purple-600 text-lg">shield</span>
                                <span class="font-medium text-gray-900">{{ $role->name }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $role->permissions()->count() }} permission(s)
                            </p>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @else
        {{-- Super Admin : affichage read-only --}}
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
            <div class="flex items-start gap-3">
                <span class="material-icons text-yellow-600 mt-0.5">shield</span>
                <div>
                    <p class="font-medium text-yellow-800 mb-1">Rôle Super Admin</p>
                    <p class="text-sm text-yellow-700">
                        Le rôle Super Admin est permanent et ne peut pas être modifié. Il possède toutes les permissions
                        du système.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Statut du compte -->
    @if (!($isEdit && $user->isSuperAdmin()))
        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="material-icons text-blue-600">toggle_on</span>
                Statut du compte
            </h3>

            <label
                class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all hover:border-green-300 hover:bg-green-50 {{ old('actif', $isEdit ? $user->actif : true) ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                <input type="checkbox" name="actif" value="1"
                    {{ old('actif', $isEdit ? $user->actif : true) ? 'checked' : '' }}
                    class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                <div class="ml-3">
                    <div class="flex items-center gap-2">
                        <span class="material-icons text-green-600">check_circle</span>
                        <span class="font-medium text-gray-900">Compte actif</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $isEdit ? 'Décochez pour empêcher l\'utilisateur de se connecter' : 'L\'utilisateur pourra se connecter immédiatement' }}
                    </p>
                </div>
            </label>
        </div>
    @else
        {{-- Super Admin : toujours actif --}}
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
            <div class="flex items-center gap-3">
                <span class="material-icons text-yellow-600">shield</span>
                <p class="text-sm text-yellow-800">
                    Le compte Super Admin est toujours actif et ne peut pas être désactivé.
                </p>
            </div>
        </div>
    @endif

    <!-- Informations complémentaires (en modification) -->
    @if ($isEdit)
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <span class="material-icons text-sm">info</span>
                Informations du compte
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div class="flex items-center gap-2">
                    <span class="material-icons text-gray-400 text-sm">event</span>
                    <span class="text-gray-500">Créé le :</span>
                    <span class="font-medium text-gray-900">{{ $user->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-icons text-gray-400 text-sm">update</span>
                    <span class="text-gray-500">Modifié le :</span>
                    <span class="font-medium text-gray-900">{{ $user->updated_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="material-icons text-gray-400 text-sm">fingerprint</span>
                    <span class="text-gray-500">ID :</span>
                    <span class="font-mono text-xs text-gray-900">{{ $user->id }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Boutons d'action -->
    <div class="flex items-center justify-between gap-4 pt-4 border-t border-gray-200">
        <a href="{{ route('admin.users.index') }}"
            class="flex items-center gap-2 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
            <span class="material-icons text-xl">arrow_back</span>
            <span class="hidden md:inline">Annuler</span>
        </a>
        <button type="submit"
            class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <span class="material-icons text-xl">{{ $isEdit ? 'save' : 'person_add' }}</span>
            <span>{{ $isEdit ? 'Enregistrer' : 'Créer l\'utilisateur' }}</span>
        </button>
    </div>
</form>

<script>
    function togglePasswordVisibility(fieldId) {
        const field = document.getElementById(fieldId);
        const button = field.nextElementSibling;
        const icon = button.querySelector('.material-icons');

        if (field.type === 'password') {
            field.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            field.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    // Highlight du rôle sélectionné
    document.addEventListener('DOMContentLoaded', function() {
        const roleCheckboxes = document.querySelectorAll('input[name="roles[]"]');
        roleCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const label = this.closest('label');
                if (this.checked) {
                    label.classList.add('border-blue-500', 'bg-blue-50');
                    label.classList.remove('border-gray-200');
                } else {
                    label.classList.remove('border-blue-500', 'bg-blue-50');
                    label.classList.add('border-gray-200');
                }
            });
        });

        // Highlight du statut actif
        const statusCheckbox = document.querySelector('input[name="is_active"]');
        if (statusCheckbox) {
            statusCheckbox.addEventListener('change', function() {
                const label = this.closest('label');
                if (this.checked) {
                    label.classList.add('border-green-500', 'bg-green-50');
                    label.classList.remove('border-gray-200');
                } else {
                    label.classList.remove('border-green-500', 'bg-green-50');
                    label.classList.add('border-gray-200');
                }
            });
        }
    });
</script>
