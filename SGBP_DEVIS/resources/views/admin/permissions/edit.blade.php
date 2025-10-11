
@extends('admin.layouts.app')

@section('title', 'Modifier la Permission')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex items-center text-sm text-gray-600 mb-4">
            <a href="{{ route('admin.permissions.index') }}" class="hover:text-purple-600 transition-colors">Permissions</a>
            <span class="material-icons text-sm mx-2">chevron_right</span>
            <span class="text-gray-900 font-medium">{{ $permission->name }}</span>
        </div>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Modifier la Permission</h1>
                <p class="text-gray-600">{{ $permission->name }}</p>
            </div>
            @if($isCritical)
                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                    <span class="material-icons text-sm mr-2">shield</span>
                    Permission Critique
                </span>
            @endif
        </div>
    </div>

    @if($isCritical)
        <!-- Avertissement pour les permissions critiques -->
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex items-start">
                <span class="material-icons text-red-500 mr-3 mt-0.5">warning</span>
                <div class="text-sm text-red-800">
                    <p class="font-medium mb-1">Permission système critique</p>
                    <p>Cette permission est essentielle au fonctionnement du système et ne peut pas être modifiée pour des raisons de sécurité.</p>
                </div>
            </div>
        </div>

        <!-- Affichage en lecture seule -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nom de la permission</label>
                    <input type="text"
                           value="{{ $permission->name }}"
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed"
                           disabled>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Guard</label>
                    <input type="text"
                           value="{{ $permission->guard_name }}"
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed"
                           disabled>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rôles utilisant cette permission</label>
                    <div class="flex flex-wrap gap-2">
                        @forelse($permission->roles as $role)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-purple-100 text-purple-800">
                                <span class="material-icons text-sm mr-1">shield</span>
                                {{ $role->name }}
                            </span>
                        @empty
                            <span class="text-sm text-gray-500">Aucun rôle n'utilise cette permission</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <a href="{{ route('admin.permissions.index') }}"
                   class="inline-flex items-center px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    <span class="material-icons text-sm mr-2">arrow_back</span>
                    Retour
                </a>
            </div>
        </div>
    @else
        <!-- Formulaire de modification (permissions non critiques) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('admin.permissions.update', $permission) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-6">
                    <!-- Information importante -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <span class="material-icons text-blue-500 mr-3 mt-0.5">info</span>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Format de nommage</p>
                                <p>Les permissions doivent suivre le format : <strong>ressource.action</strong></p>
                                <p class="mt-2 text-xs">Exemples : <code class="bg-blue-100 px-2 py-0.5 rounded">articles.create</code>, <code class="bg-blue-100 px-2 py-0.5 rounded">devis.send</code></p>
                            </div>
                        </div>
                    </div>

                    <!-- Nom de la permission -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom de la permission <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $permission->name) }}"
                               placeholder="Ex: articles.create"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror"
                               required
                               pattern="^[a-z_]+\.[a-z_]+$">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <span class="material-icons text-sm mr-1">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            Format requis : <strong>ressource.action</strong> (lettres minuscules et underscores uniquement)
                        </p>
                    </div>

                    <!-- Suggestions de ressources existantes -->
                    @if($existingGroups->isNotEmpty())
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ressources existantes (suggestions)
                            </label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($existingGroups as $group)
                                    <button type="button"
                                            onclick="fillResourceName('{{ $group }}')"
                                            class="px-3 py-1.5 bg-gray-100 hover:bg-purple-100 text-gray-700 hover:text-purple-700 text-sm rounded-lg transition-colors border border-gray-200 hover:border-purple-300">
                                        {{ ucfirst($group) }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Guard -->
                    <div>
                        <label for="guard_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Guard <span class="text-red-500">*</span>
                        </label>
                        <select name="guard_name"
                                id="guard_name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('guard_name') border-red-500 @enderror"
                                required>
                            <option value="web" {{ old('guard_name', $permission->guard_name) === 'web' ? 'selected' : '' }}>Web</option>
                            <option value="api" {{ old('guard_name', $permission->guard_name) === 'api' ? 'selected' : '' }}>API</option>
                        </select>
                        @error('guard_name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <span class="material-icons text-sm mr-1">error</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Rôles utilisant cette permission -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Rôles utilisant cette permission
                        </label>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            @forelse($permission->roles as $role)
                                <div class="flex items-center justify-between py-2">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-purple-100 text-purple-800">
                                        <span class="material-icons text-sm mr-1">shield</span>
                                        {{ $role->name }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $role->users()->count() }} utilisateur(s)
                                    </span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-2">
                                    Aucun rôle n'utilise actuellement cette permission
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-gray-50 px-8 py-4 border-t border-gray-200 flex items-center justify-between">
                    <a href="{{ route('admin.permissions.index') }}"
                       class="inline-flex items-center px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        <span class="material-icons text-sm mr-2">arrow_back</span>
                        Annuler
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-medium rounded-lg transition-all shadow-lg">
                        <span class="material-icons text-sm mr-2">save</span>
                        Enregistrer les Modifications
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>

<script>
    // Pré-remplir la ressource
    function fillResourceName(resource) {
        const input = document.getElementById('name');
        const currentValue = input.value;
        const parts = currentValue.split('.');

        if (parts.length > 1) {
            input.value = resource + '.' + parts[1];
        } else {
            input.value = resource + '.';
        }

        input.focus();
        input.setSelectionRange(input.value.length, input.value.length);
    }

    // Validation en temps réel
    const nameInput = document.getElementById('name');
    if (nameInput) {
        nameInput.addEventListener('input', function(e) {
            const value = e.target.value;
            const regex = /^[a-z_]+\.[a-z_]+$/;

            if (value && !regex.test(value)) {
                e.target.classList.add('border-yellow-500');
                e.target.classList.remove('border-gray-300');
            } else {
                e.target.classList.remove('border-yellow-500');
                e.target.classList.add('border-gray-300');
            }
        });
    }
</script>
@endsection
