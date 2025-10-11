@extends('admin.layouts.app')
@section('content')
<div class="min-h-screen pb-20 bg-gray-50">
    <!-- Top bar -->
    <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.roles.index') }}" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition">
                    <span class="material-icons">arrow_back</span>
                </a>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Modifier le Rôle</h2>
                    <p class="text-xs md:text-sm text-gray-500">Mettre à jour les permissions du rôle "{{ ucfirst($role->name) }}"</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Content -->
    <div class="p-4 md:p-8">
        <div class="max-w-5xl mx-auto">
            <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nom du rôle -->
                <div class="bg-white rounded-xl shadow-md p-6 md:p-8 mb-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="material-icons text-blue-600">badge</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Informations du rôle</h3>
                            <p class="text-sm text-gray-600">Modifier le nom du rôle</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nom du rôle <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            placeholder="Ex: Gestionnaire, Commercial, Chef de projet..."
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            value="{{ old('name', $role->name) }}"
                        >
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Permissions -->
                <div class="bg-white rounded-xl shadow-md p-6 md:p-8 mb-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <span class="material-icons text-purple-600">verified_user</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Permissions</h3>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium text-purple-600">{{ count($rolePermissions) }}</span> permissions attribuées
                                </p>
                            </div>
                        </div>

                        <!-- Checkbox "Tout sélectionner" -->
                        <label class="flex items-center gap-2 cursor-pointer px-4 py-2 bg-blue-50 rounded-lg border-2 border-blue-200 hover:bg-blue-100 transition">
                            <input
                                type="checkbox"
                                id="select-all"
                                class="w-5 h-5 text-blue-600 rounded"
                                onchange="toggleAllPermissions(this)"
                                {{ count($rolePermissions) === \Spatie\Permission\Models\Permission::count() ? 'checked' : '' }}
                            >
                            <span class="font-medium text-blue-700">Tout sélectionner</span>
                        </label>
                    </div>

                    <!-- Permissions par resource -->
                    <div class="space-y-6">
                        @foreach($groupedPermissions as $resource => $config)
                        @php
                            $resourcePermissions = $config['permissions']->pluck('name')->toArray();
                            $checkedCount = count(array_intersect($resourcePermissions, $rolePermissions));
                            $allChecked = $checkedCount === count($resourcePermissions);
                        @endphp
                        <div class="border-2 border-gray-200 rounded-xl p-5 hover:border-blue-300 transition">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <span class="material-icons text-gray-600">{{ $config['icon'] }}</span>
                                    <h4 class="font-bold text-gray-800 text-lg">{{ $config['display_name'] }}</h4>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                        {{ $checkedCount }}/{{ count($resourcePermissions) }}
                                    </span>
                                </div>

                                <!-- Checkbox groupe -->
                                <label class="flex items-center gap-2 cursor-pointer px-3 py-1 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                    <input
                                        type="checkbox"
                                        class="group-checkbox w-4 h-4 text-blue-600 rounded"
                                        data-group="{{ $resource }}"
                                        onchange="toggleGroupPermissions(this, '{{ $resource }}')"
                                        {{ $allChecked ? 'checked' : '' }}
                                    >
                                    <span class="text-sm font-medium text-gray-700">Tout</span>
                                </label>
                            </div>

                            <!-- Checkboxes individuelles -->
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach($config['permissions'] as $permission)
                                <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-blue-50 border-2 {{ in_array($permission->name, $rolePermissions) ? 'border-blue-200 bg-blue-50' : 'border-transparent' }} transition">
                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->name }}"
                                        class="permission-checkbox w-4 h-4 text-blue-600 rounded"
                                        data-group="{{ $resource }}"
                                        onchange="updateGroupCheckbox('{{ $resource }}')"
                                        {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                    >
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700">
                                            @if(str_contains($permission->name, 'create'))
                                                <span class="material-icons text-green-600 text-sm">add_circle</span>
                                            @elseif(str_contains($permission->name, 'read'))
                                                <span class="material-icons text-blue-600 text-sm">visibility</span>
                                            @elseif(str_contains($permission->name, 'update'))
                                                <span class="material-icons text-orange-600 text-sm">edit</span>
                                            @elseif(str_contains($permission->name, 'delete'))
                                                <span class="material-icons text-red-600 text-sm">delete</span>
                                            @endif
                                            {{ $permission->display_name ?? ucfirst(str_replace('.', ' ', $permission->name)) }}
                                        </span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <!-- Permissions spéciales -->
                        @if($specialPerms->count() > 0)
                        <div class="border-2 border-indigo-200 rounded-xl p-5 bg-indigo-50">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="material-icons text-indigo-600">stars</span>
                                <h4 class="font-bold text-gray-800 text-lg">Permissions Spéciales</h4>
                                <span class="text-xs bg-indigo-100 text-indigo-600 px-2 py-1 rounded-full">
                                    {{ count(array_intersect($specialPerms->pluck('name')->toArray(), $rolePermissions)) }}/{{ $specialPerms->count() }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($specialPerms as $permission)
                                <label class="flex items-center gap-2 p-3 bg-white rounded-lg cursor-pointer hover:bg-indigo-100 border-2 {{ in_array($permission->name, $rolePermissions) ? 'border-indigo-300 bg-indigo-100' : 'border-transparent' }} transition">
                                    <input
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->name }}"
                                        class="permission-checkbox w-4 h-4 text-indigo-600 rounded"
                                        {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}
                                        onchange="updateSelectAll()"
                                    >
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $permission->display_name ?? config("permissions.special_permissions.{$permission->name}") }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-between gap-3 sticky bottom-0 bg-white p-6 rounded-xl shadow-lg border-2 border-gray-200">
                    <a href="{{ route('admin.roles.index') }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-100 transition">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 transition shadow-md flex items-center gap-2">
                        <span class="material-icons">check_circle</span>
                        <span>Mettre à jour</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Même script JavaScript que create.blade.php
function toggleAllPermissions(checkbox) {
    const allCheckboxes = document.querySelectorAll('.permission-checkbox');
    const groupCheckboxes = document.querySelectorAll('.group-checkbox');

    allCheckboxes.forEach(cb => cb.checked = checkbox.checked);
    groupCheckboxes.forEach(cb => cb.checked = checkbox.checked);
}

function toggleGroupPermissions(checkbox, group) {
    const groupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
    groupCheckboxes.forEach(cb => cb.checked = checkbox.checked);
    updateSelectAll();
}

function updateGroupCheckbox(group) {
    const groupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${group}"]`);
    const groupCheckbox = document.querySelector(`.group-checkbox[data-group="${group}"]`);

    const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
    const someChecked = Array.from(groupCheckboxes).some(cb => cb.checked);

    if (groupCheckbox) {
        groupCheckbox.checked = allChecked;
        groupCheckbox.indeterminate = someChecked && !allChecked;
    }

    updateSelectAll();
}

function updateSelectAll() {
    const allCheckboxes = document.querySelectorAll('.permission-checkbox');
    const selectAllCheckbox = document.getElementById('select-all');

    const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
    const someChecked = Array.from(allCheckboxes).some(cb => cb.checked);

    if (selectAllCheckbox) {
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = someChecked && !allChecked;
    }
}

// Initialiser au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Mettre à jour les checkboxes de groupe
    @foreach(array_keys($groupedPermissions) as $resource)
        updateGroupCheckbox('{{ $resource }}');
    @endforeach

    // Mettre à jour le checkbox global
    updateSelectAll();
});
</script>
@endsection
