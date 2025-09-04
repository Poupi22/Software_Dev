@extends('admin.layouts.app')

@section('title', 'Modifier Rôle')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        50: '#eff6ff',
                        100: '#dbeafe',
                        500: '#3b82f6',
                        600: '#2563eb',
                        700: '#1d4ed8',
                        800: '#1e40af',
                        900: '#1e3a8a'
                    },
                    secondary: {
                        500: '#64748b',
                        600: '#475569',
                        700: '#334155'
                    },
                    success: {
                        500: '#10b981',
                        600: '#059669'
                    },
                    danger: {
                        500: '#ef4444',
                        600: '#dc2626'
                    },
                    warning: {
                        500: '#f59e0b',
                        600: '#d97706'
                    }
                },
                animation: {
                    'fade-in': 'fadeIn 0.3s ease-out',
                    'slide-up': 'slideUp 0.4s ease-out',
                    'bounce-in': 'bounceIn 0.5s ease-out'
                },
                keyframes: {
                    fadeIn: {
                        '0%': { opacity: '0' },
                        '100%': { opacity: '1' }
                    },
                    slideUp: {
                        '0%': { transform: 'translateY(20px)', opacity: '0' },
                        '100%': { transform: 'translateY(0)', opacity: '1' }
                    },
                    bounceIn: {
                        '0%, 20%, 40%, 60%, 80%, 100%': { transitionTimingFunction: 'cubic-bezier(0.215, 0.610, 0.355, 1.000)' },
                        '0%': { opacity: '0', transform: 'scale3d(.3, .3, .3)' },
                        '20%': { transform: 'scale3d(1.1, 1.1, 1.1)' },
                        '40%': { transform: 'scale3d(.9, .9, .9)' },
                        '60%': { opacity: '1', transform: 'scale3d(1.03, 1.03, 1.03)' },
                        '80%': { transform: 'scale3d(.97, .97, .97)' },
                        '100%': { opacity: '1', transform: 'scale3d(1, 1, 1)' }
                    }
                }
            }
        }
    }
</script>

<div class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen pt-4 pb-8">
    <!-- Success Message -->
    @if(session('success'))
    <div id="success-message" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 animate-fade-in">
        <div class="bg-gradient-to-r from-green-400 to-green-600 text-white p-4 rounded-xl shadow-lg">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div class="flex items-center mb-4 sm:mb-0">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Modifier Rôle</h1>
                    <p class="text-sm text-gray-600">Modifiez les détails de ce rôle</p>
                </div>
            </div>
            <a href="{{ route('dashboard1.role.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Retour</span>
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden animate-slide-up">
            <div class="p-6 sm:p-8">
                <form action="{{ route('dashboard1.role.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom du rôle</label>
                            <input type="text" id="name" name="name" required
                                   class="w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition"
                                   value="{{ old('name', $role->name) }}"
                                   placeholder="ex: admin">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Permissions Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($permissions as $permission)
                                <div class="flex items-center">
                                    <input id="permission-{{ $permission->id }}" name="permissions[]" type="checkbox" value="{{ $permission->id }}"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ $role->hasPermissionTo($permission->id) ? 'checked' : '' }}>
                                    <label for="permission-{{ $permission->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('permissions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('dashboard1.role.show', $role->id) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition">
                                Annuler
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow hover:shadow-md transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Mettre à jour
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-hide success message after 5 seconds
    setTimeout(function() {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.transition = 'opacity 0.5s ease-out';
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 500);
        }
    }, 5000);
</script>
@endpush
@endsection
