@extends('etudiant.layouts.app')

@section('title', 'Forums et Utilisateurs')

@section('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        :root {
            --primary-blue: #1e40af;
            --secondary-blue: #3b82f6;
            --light-blue: #dbeafe;
            --accent-purple: #a855f7;
            --accent-green: #10b981;
        }

        body {
            background-color: #f8fafc;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(209, 213, 219, 0.3);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }

        .gradient-blue {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
        }

        .gradient-purple {
            background: linear-gradient(135deg, #7e22ce 0%, var(--accent-purple) 100%);
        }

        .online-indicator {
            width: 10px;
            height: 10px;
            background: var(--accent-green);
            border: 2px solid white;
            animation: pulse 2s infinite;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .contact-item, .forum-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .contact-item:hover, .forum-item:hover {
            background-color: rgba(59, 130, 246, 0.08);
            transform: translateX(4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .contact-item.active, .forum-item.active {
            background-color: rgba(59, 130, 246, 0.15);
            border-left: 3px solid var(--secondary-blue);
        }

        .hidden-item {
            display: none !important;
        }

        .unread-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background-color: #ef4444;
            color: white;
            border-radius: 9999px;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
        }

        .search-input {
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--secondary-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .message-preview {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .avatar-container {
            position: relative;
            flex-shrink: 0;
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .avatar-initials {
            color: white;
            font-weight: bold;
            font-size: 0.875rem;
        }
    </style>
@endsection

@section('content')

<main class="pt-24 min-h-screen">
    <section>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-8 text-center sm:text-left">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-2">Communauté d'Apprentissage</h1>
                <p class="text-gray-600 text-lg">Connectez-vous avec vos collègues et instructeurs</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Section Forums -->
                <div class="slide-in">
                    <div class="glass-effect rounded-xl p-6 h-full">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                            <h3 class="text-lg font-bold text-gray-800">Forums de Formation</h3>
                            <div class="relative w-full sm:w-64">
                                <input type="text" id="forumSearch" placeholder="Rechercher des forums..."
                                       class="search-input w-full bg-white px-4 py-2 rounded-lg pl-10 focus:outline-none border border-gray-200 text-sm">
                                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                            </div>
                        </div>

                        @if($forums->isEmpty())
                            <div class="text-center py-8">
                                <i class="fas fa-users-slash text-3xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500">Aucun forum disponible pour le moment</p>
                            </div>
                        @else
                            <div class="space-y-3" id="forumsList">
                                @foreach($forums as $forum)
                                <a href="{{ route('etudiant.message', $forum->id) }}" class="block forum-list-item">
                                    <div class="forum-item p-4 rounded-lg hover:bg-blue-50">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 gradient-blue rounded-lg flex items-center justify-center shadow-md">
                                                <i class="fas fa-users text-white text-lg"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-gray-800 forum-name truncate">{{ $forum->name }}</h4>
                                                <p class="text-sm text-gray-600 forum-description mt-1 truncate">{{ $forum->description ?? 'Forum de discussion' }}</p>
                                                <div class="flex items-center mt-1">
                                                    <span class="text-xs text-gray-500">
                                                        <i class="fas fa-comments mr-1"></i>
                                                        {{ $forum->threads_count }} discussion{{ $forum->threads_count > 1 ? 's' : '' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Section Utilisateurs -->
                <div class="slide-in">
                    <div class="glass-effect rounded-xl p-6 h-full">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                            <h3 class="text-lg font-bold text-gray-800">Tous les Utilisateurs</h3>
                            <div class="relative w-full sm:w-64">
                                <input type="text" id="userSearch" placeholder="Rechercher des utilisateurs..."
                                       class="search-input w-full bg-white px-4 py-2 rounded-lg pl-10 focus:outline-none border border-gray-200 text-sm">
                                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                            </div>
                        </div>

                        @if($users->isEmpty())
                            <div class="text-center py-8">
                                <i class="fas fa-user-slash text-3xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500">Aucun utilisateur disponible pour le moment</p>
                            </div>
                        @else
                            <div class="space-y-3" id="usersList">
                                @foreach($users as $user)
                                <a href="{{ route('chat.with', $user->id) }}" class="block user-list-item">
                                    <div class="contact-item p-4 rounded-lg hover:bg-blue-50 {{ $user->unread_messages > 0 ? 'bg-blue-50' : '' }}">
                                        <div class="flex items-center space-x-4">
                                            <!-- Avatar -->
                                            <div class="avatar-container">
                                                <div class="w-12 h-12 gradient-purple rounded-lg flex items-center justify-center shadow-md overflow-hidden">
                                                    @if($user->photo && file_exists(public_path('storage/' . $user->photo)))
                                                        <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->name }}" class="avatar-img">
                                                    @else
                                                        <div class="avatar-initials">
                                                            {{ substr($user->name, 0, 1) }}{{ substr(strstr($user->name, ' ') ?: '', 1, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                @if($user->is_online)
                                                <span class="online-indicator absolute -top-1 -right-1 rounded-full"></span>
                                                @endif
                                                @if($user->unread_messages > 0)
                                                <span class="unread-badge">{{ $user->unread_messages }}</span>
                                                @endif
                                            </div>

                                            <!-- Info -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center min-w-0">
                                                        <h4 class="font-bold text-gray-800 user-name truncate">{{ $user->name }}</h4>
                                                        @if($user->is_online)
                                                            <span class="ml-2 bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full whitespace-nowrap">En ligne</span>
                                                        @else
                                                            <span class="ml-2 bg-gray-100 text-gray-800 text-xs px-2 py-0.5 rounded-full whitespace-nowrap">Hors ligne</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <p class="text-sm text-gray-600 user-role mt-1 truncate">
                                                    @if($user->role_name)
                                                        {{ $user->role_name }}
                                                    @else
                                                        {{ $user->role === 'admin' ? 'Administrateur' : ($user->role === 'formateur' ? 'Formateur' : ($user->role === 'etudiant' ? 'Étudiant' : 'Utilisateur')) }}
                                                    @endif
                                                </p>

                                                @if(!empty($user->last_message_preview))
                                                <p class="text-xs text-gray-500 mt-1 message-preview">
                                                    <i class="fas fa-comment-dots mr-1 text-gray-400"></i>
                                                    <span class="italic">"{{ \Illuminate\Support\Str::limit($user->last_message_preview, 60) }}"</span>
                                                </p>
                                                @else
                                                <p class="text-xs text-gray-400 mt-1">
                                                    <i class="fas fa-comment mr-1"></i>
                                                    Aucun message échangé
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ajouter l'animation d'apparition
        const slideElements = document.querySelectorAll('.slide-in');
        slideElements.forEach((el, index) => {
            setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, index * 150);
        });

        // Fonctionnalité de recherche des forums
        const forumSearch = document.getElementById('forumSearch');
        if (forumSearch) {
            forumSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const forumItems = document.querySelectorAll('.forum-list-item');

                forumItems.forEach(item => {
                    const name = item.querySelector('.forum-name').textContent.toLowerCase();
                    const description = item.querySelector('.forum-description').textContent.toLowerCase();

                    if (name.includes(searchTerm) || description.includes(searchTerm)) {
                        item.classList.remove('hidden-item');
                    } else {
                        item.classList.add('hidden-item');
                    }
                });
            });
        }

        // Fonctionnalité de recherche des utilisateurs
        const userSearch = document.getElementById('userSearch');
        if (userSearch) {
            userSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const userItems = document.querySelectorAll('.user-list-item');

                userItems.forEach(item => {
                    const name = item.querySelector('.user-name').textContent.toLowerCase();
                    const role = item.querySelector('.user-role').textContent.toLowerCase();

                    if (name.includes(searchTerm) || role.includes(searchTerm)) {
                        item.classList.remove('hidden-item');
                    } else {
                        item.classList.add('hidden-item');
                    }
                });
            });
        }

        // Définir les styles initiaux pour l'animation
        const slideIns = document.querySelectorAll('.slide-in');
        slideIns.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.4s ease-out';
        });

        // Ajouter un indicateur de chargement pendant le chargement des pages
        const links = document.querySelectorAll('a[href]');
        links.forEach(link => {
            link.addEventListener('click', function() {
                // Vous pouvez ajouter un indicateur de chargement ici si nécessaire
                console.log('Navigation vers: ' + this.href);
            });
        });
    });
</script>
@endsection