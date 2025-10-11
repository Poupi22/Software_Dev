@extends('admin.layouts.app')

@section('title', 'Créer une Permission')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-3xl">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex items-center text-sm text-gray-600 mb-4">
            <a href="{{ route('admin.permissions.index') }}" class="hover:text-purple-600 transition-colors">Permissions</a>
            <span class="material-icons text-sm mx-2">chevron_right</span>
            <span class="text-gray-900 font-medium">Nouvelle permission</span>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Créer une Permission</h1>
        <p class="text-gray-600">Ajoutez une nouvelle permission au système</p>
    </div>

    <!-- Formulaire -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf

            <div class="p-8 space-y-6">
                <!-- Information importante -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                    <div class="flex items-start">
                        <span class="material-icons text-blue-500 mr-3 mt-0.5">info</span>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Format de nommage</p>
                            <p>Les permissions doivent suivre le format : <strong>ressource.action</strong></p>
                            <p class="mt-2 text-xs">Exemples : <code class="bg-blue-100 px-2 py-0.5 rounded">articles.create</code>, <code class="bg-blue-100 px-2 py-0.5 rounded">devis.send</code>, <code class="bg-blue-100 px-2 py-0.5 rounded">factures.payment</code></p>
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
                           value="{{ old('name') }}"
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
                        <p class="mt-2 text-xs text-gray-500">
                            Cliquez sur une ressource pour la pré-remplir
                        </p>
                    </div>
                @endif

                <!-- Suggestions d'actions courantes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Actions courantes (suggestions)
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['viewAny', 'view', 'create', 'update', 'delete', 'restore', 'forceDelete'] as $action)
                            <button type="button"
                                    onclick="fillActionName('{{ $action }}')"
                                    class="px-3 py-1.5 bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 text-sm rounded-lg transition-colors border border-gray-200 hover:border-blue-300">
                                {{ $action }}
                            </button>
                        @endforeach
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Cliquez sur une action pour la pré-remplir
                    </p>
                </div>

                <!-- Guard -->
                <div>
                    <label for="guard_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Guard <span class="text-red-500">*</span>
                    </label>
                    <select name="guard_name"
                            id="guard_name"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('guard_name') border-red-500 @enderror"
                            required>
                        <option value="web" {{ old('guard_name', 'web') === 'web' ? 'selected' : '' }}>Web</option>
                        <option value="api" {{ old('guard_name') === 'api' ? 'selected' : '' }}>API</option>
                    </select>
                    @error('guard_name')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <span class="material-icons text-sm mr-1">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">
                        Sélectionnez "Web" pour les permissions du back-office
                    </p>
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
                    Créer la Permission
                </button>
            </div>
        </form>
    </div>

    <!-- Exemples de permissions -->
    <div class="mt-6 bg-gradient-to-br from-purple-50 to-blue-50 border border-purple-200 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <span class="material-icons text-purple-600 mr-2">lightbulb</span>
            Exemples de permissions
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="font-medium text-gray-700 mb-2">Gestion CRUD :</p>
                <ul class="space-y-1 text-gray-600">
                    <li><code class="bg-white px-2 py-0.5 rounded border">articles.viewAny</code></li>
                    <li><code class="bg-white px-2 py-0.5 rounded border">articles.create</code></li>
                    <li><code class="bg-white px-2 py-0.5 rounded border">articles.update</code></li>
                    <li><code class="bg-white px-2 py-0.5 rounded border">articles.delete</code></li>
                </ul>
            </div>
            <div>
                <p class="font-medium text-gray-700 mb-2">Actions spéciales :</p>
                <ul class="space-y-1 text-gray-600">
                    <li><code class="bg-white px-2 py-0.5 rounded border">devis.send</code></li>
                    <li><code class="bg-white px-2 py-0.5 rounded border">devis.convert</code></li>
                    <li><code class="bg-white px-2 py-0.5 rounded border">factures.payment</code></li>
                    <li><code class="bg-white px-2 py-0.5 rounded border">prospects.convert</code></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Pré-remplir la ressource
    function fillResourceName(resource) {
        const input = document.getElementById('name');
        const currentValue = input.value;
        const parts = currentValue.split('.');

        if (parts.length > 1) {
            // Si une action existe déjà, la garder
            input.value = resource + '.' + parts[1];
        } else {
            // Sinon, juste mettre la ressource
            input.value = resource + '.';
        }

        input.focus();
        input.setSelectionRange(input.value.length, input.value.length);
    }

    // Pré-remplir l'action
    function fillActionName(action) {
        const input = document.getElementById('name');
        const currentValue = input.value;
        const parts = currentValue.split('.');

        if (parts.length > 0 && parts[0]) {
            // Si une ressource existe, la garder
            input.value = parts[0] + '.' + action;
        } else {
            // Sinon, juste mettre l'action
            input.value = '.' + action;
        }

        input.focus();
        input.setSelectionRange(input.value.length, input.value.length);
    }

    // Validation en temps réel
    document.getElementById('name').addEventListener('input', function(e) {
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
</script>
@endsection
