@extends('admin.layouts.app')

@section('title', 'Créer une Permission')

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
        <!-- Header and Back Button -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div class="flex items-center mb-4 sm:mb-0">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Créer une Permission</h1>
                    <p class="text-sm text-gray-600">Définissez une nouvelle permission d'accès</p>
                </div>
            </div>
            <a href="{{ route('dashboard1.permission.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Retour à la liste</span>
            </a>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden animate-slide-up">
            <div class="p-6 sm:p-8">
                <form action="{{ route('dashboard1.permission.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom de la permission</label>
                            <input type="text" id="name" name="name" required
                                   class="form-input w-full border-2 border-gray-200 rounded-lg px-4 py-3 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition"
                                   placeholder="ex: edit-articles"
                                   value="{{ old('name') }}">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow hover:shadow-md transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                <span>Enregistrer</span>
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
