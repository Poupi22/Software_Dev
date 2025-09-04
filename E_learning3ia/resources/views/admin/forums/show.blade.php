@extends('admin.layouts.app')

@section('title', 'Détails du Forum')

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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div class="flex items-center mb-4 sm:mb-0">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Détails du Forum</h1>
                    <p class="text-sm text-gray-600">Informations détaillées sur ce forum</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('dashboard1.forum.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </a>
                <a href="{{ route('dashboard1.forum.edit', $forum) }}"
                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg shadow hover:shadow-md transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Modifier
                </a>
            </div>
        </div>

        <!-- Details Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden animate-fade-in">
            <div class="p-6 sm:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Forum Details -->
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Informations de base</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500">Nom</p>
                                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $forum->nom }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Slug</p>
                                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $forum->slug }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Formation</p>
                                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $forum->formation->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Date de création</p>
                                    <p class="mt-1 text-sm font-medium text-gray-900">{{ $forum->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Description</h3>
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            @if($forum->description)
                                <p class="text-gray-700">{{ $forum->description }}</p>
                            @else
                                <p class="text-gray-500 italic">Aucune description</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="mt-8 space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Zone de danger</h3>
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Supprimer ce forum</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Cette action est irréversible. Tous les messages associés seront également supprimés.</p>
                                </div>
                                <div class="mt-4">
                                    <form action="{{ route('dashboard1.forum.destroy', $forum) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce forum ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
