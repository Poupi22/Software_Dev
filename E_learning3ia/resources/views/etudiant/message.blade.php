<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $forum->name }} - Discussion</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link rel="stylesheet" href="{{ asset('etudiant/assets/css/message.css') }}">
</head>
<body>
    <div class="app-container">
        <!-- Indicateur de Chargement -->
        <div class="loading" id="loadingIndicator">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
        </div>

        <!-- En-tête -->
        <div class="header">
            <a href="{{ route('etudiant.chat.index') }}" class="header-btn back-btn" aria-label="Retour">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2>{{ $forum->name }}</h2>
            <button class="header-btn" id="openModal">
                <i class="fas fa-plus"></i> 
                <span class="new-thread-text">Nouvelle Discussion</span>
            </button>
        </div>

        <!-- Contenu Principal -->
        <div class="main-content">
            <!-- Barre Latérale des Discussions (Bureau) -->
            <div class="threads-sidebar">
                <div class="threads-header">
                    <h3 class="threads-title">Discussions</h3>
                    <button class="header-btn" id="openModalDesktop">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                
                <div class="thread-search">
                    <input type="text" class="search-input" placeholder="Rechercher des discussions..." id="sidebarSearch">
                    <i class="fas fa-search search-icon"></i>
                </div>
                
                <div class="thread-list" id="threadList">
                    @foreach($forumThreads as $forumThread)
                        <a href="{{ route('etudiant.message', ['forum' => $forum->id, 'thread' => $forumThread->id]) }}" 
                           class="thread-item {{ isset($thread) && $thread->id == $forumThread->id ? 'active' : '' }}" 
                           data-thread-id="{{ $forumThread->id }}">
                            <div class="thread-content">
                                <h4 class="thread-title">{{ $forumThread->title }}</h4>
                                <div class="thread-meta">
                                    <span class="thread-author"><i class="fas fa-user"></i> {{ $forumThread->user->name }}</span>
                                    <span class="thread-time"><i class="fas fa-clock"></i> {{ $forumThread->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="thread-stats">
                                <span class="thread-replies"><i class="fas fa-comment"></i> {{ $forumThread->posts_count }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Zone des Messages -->
            <div class="messages-area">
                <!-- Navigation des Discussions (Mobile) -->
                <div class="threads-nav">
                    <div class="threads-horizontal" id="threadsHorizontal">
                        @foreach($forumThreads as $forumThread)
                            <a href="{{ route('etudiant.message', ['forum' => $forum->id, 'thread' => $forumThread->id]) }}" 
                               class="thread-item-horizontal {{ isset($thread) && $thread->id == $forumThread->id ? 'active' : '' }}" 
                               data-thread-id="{{ $forumThread->id }}">
                                <h4 class="thread-title-horizontal">{{ $forumThread->title }}</h4>
                                <div class="thread-meta-horizontal">
                                    <span><i class="fas fa-comments"></i> {{ $forumThread->posts_count }}</span>
                                    <span><i class="fas fa-clock"></i> {{ $forumThread->created_at->shortRelativeDiffForHumans() }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Conteneur des Messages -->
                <div class="messages-container" id="messagesContainer">
                    @if(!isset($thread))
                        <div class="empty-state">
                            <i class="fas fa-info-circle"></i>
                            <h4>Veuillez sélectionner une discussion</h4>
                            <p>Choisissez une discussion dans la liste pour voir ou participer à la conversation.</p>
                        </div>
                    @elseif($posts->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-comment-slash"></i>
                            <h3>Aucun message pour le moment</h3>
                            <p>Soyez le premier à démarrer la conversation !</p>
                        </div>
                    @else
                        @foreach($posts as $post)
                            <div class="message {{ $post->user_id == auth()->id() ? 'sent' : 'received' }}" data-post-id="{{ $post->id }}">
                                <div class="message-bubble">
                                    {{ $post->body }}
                                    @if($post->user_id == auth()->id())
                                        <div class="message-actions">
                                            <button class="message-action-btn delete-btn" data-post-id="{{ $post->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div class="message-info">
                                    @if($post->user_id == auth()->id())
                                        <span><i class="fas fa-clock"></i> {{ $post->created_at->diffForHumans() }}</span>
                                        <span>•</span>
                                        <span>Vous</span>
                                    @else
                                        <span><i class="fas fa-user"></i> {{ $post->user->name }}</span>
                                        <span>•</span>
                                        <span><i class="fas fa-clock"></i> {{ $post->created_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Conteneur de Saisie -->
                <div class="input-container" id="inputContainer">
                    @if(isset($thread))
                        <form method="POST" action="{{ route('etudiant.posts.store', $thread->id) }}" class="message-form" id="messageForm">
                            @csrf
                            <textarea class="message-input" id="messageInput" name="body" placeholder="Tapez votre message..." required></textarea>
                            <button type="submit" class="send-btn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    @else
                        <div class="empty-state disabled">
                            <i class="fas fa-comment-alt"></i>
                            <p>Sélectionnez une discussion pour envoyer des messages</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Nouvelle Discussion -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <h3 class="modal-title">Créer une Nouvelle Discussion</h3>
            <form id="threadForm" method="POST" action="{{ route('etudiant.threads.store') }}">
                @csrf
                <input type="hidden" name="forum_id" value="{{ $forum->id }}">
                
                <input type="text" class="modal-input" id="threadTitle" name="title" placeholder="Titre de la discussion" required>
                <textarea class="modal-input modal-textarea" name="content" placeholder="Contenu de votre premier message" rows="4" required></textarea>
                
                <div class="modal-actions">
                    <button type="button" class="modal-btn cancel-btn" id="cancelBtn">
                        Annuler
                    </button>
                    <button type="submit" class="modal-btn create-btn">
                        Créer la Discussion
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Confirmation de Suppression -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <h3 class="modal-title"><i class="fas fa-exclamation-triangle" style="color: var(--error); margin-right: 0.5rem;"></i>Confirmer la Suppression</h3>
            <p id="deleteModalText" style="margin-bottom: 2rem; color: var(--grey-600); line-height: 1.6;">Êtes-vous sûr de vouloir supprimer cet élément ? Cette action ne peut pas être annulée.</p>
            
            <div class="modal-actions">
                <button type="button" class="modal-btn cancel-btn" id="cancelDeleteBtn">
                    Annuler
                </button>
                <button type="button" class="modal-btn" id="confirmDeleteBtn" style="background: var(--error); color: var(--white);">
                    Supprimer
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadingIndicator = document.getElementById('loadingIndicator');
            const modal = document.getElementById('modal');
            const deleteModal = document.getElementById('deleteModal');
            const messageInput = document.getElementById('messageInput');
            const messageForm = document.getElementById('messageForm');
            const sidebarSearch = document.getElementById('sidebarSearch');
            const threadDropdownBtn = document.getElementById('threadDropdownBtn');
            const threadDropdown = document.getElementById('threadDropdown');
            const threadSearch = document.getElementById('threadSearch');
            let isSubmitting = false;
            let itemToDelete = null;

            // Initialiser le redimensionnement automatique pour la saisie de message
            if (messageInput) {
                messageInput.addEventListener('input', function() {
                    autoResize(this);
                });
            }

            // Gestion des modales
            document.getElementById('openModal')?.addEventListener('click', openModal);
            document.getElementById('openModalDesktop')?.addEventListener('click', openModal);
            document.getElementById('cancelBtn')?.addEventListener('click', closeModal);
            
            document.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
                if (e.target === deleteModal) {
                    closeDeleteModal();
                }
            });

            // Gestion de la modale de suppression
            document.getElementById('cancelDeleteBtn')?.addEventListener('click', closeDeleteModal);
            document.getElementById('confirmDeleteBtn')?.addEventListener('click', confirmDelete);

            // Fonctionnalité de recherche dans la barre latérale
            if (sidebarSearch) {
                sidebarSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    document.querySelectorAll('.thread-item').forEach(item => {
                        const title = item.querySelector('.thread-title').textContent.toLowerCase();
                        item.style.display = title.includes(searchTerm) ? 'block' : 'none';
                    });
                });
            }

            // Menu déroulant des discussions
            if (threadDropdownBtn) {
                threadDropdownBtn.addEventListener('click', function() {
                    threadDropdown.classList.toggle('active');
                    this.classList.toggle('active');
                    if (threadDropdown.classList.contains('active')) {
                        threadSearch.focus();
                    }
                });
                
                if (threadSearch) {
                    threadSearch.addEventListener('input', function() {
                        const searchTerm = this.value.toLowerCase();
                        document.querySelectorAll('.dropdown-thread-item').forEach(item => {
                            const title = item.querySelector('.dropdown-thread-title').textContent.toLowerCase();
                            item.style.display = title.includes(searchTerm) ? 'block' : 'none';
                        });
                    });
                }
            }

            // Sélection de discussion
            document.querySelectorAll('.thread-item, .thread-item-horizontal, .dropdown-thread-item').forEach(link => {
                link.addEventListener('click', async function(e) {
                    e.preventDefault();
                    if (isSubmitting) return;
                    showLoading();
                    
                    try {
                        window.history.pushState(null, '', this.href);
                        const response = await fetch(this.href, {
                            headers: { 
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'text/html'
                            }
                        });
                        
                        if (!response.ok) {
                            const error = await response.text();
                            throw new Error(error || 'La réponse du réseau n\'est pas correcte');
                        }
                        
                        const html = await response.text();
                        const parser = new DOMParser();
                        const newDoc = parser.parseFromString(html, 'text/html');
                        
                        // Mettre à jour les conteneurs
                        document.getElementById('messagesContainer').innerHTML = 
                            newDoc.getElementById('messagesContainer').innerHTML;
                        document.getElementById('inputContainer').innerHTML = 
                            newDoc.getElementById('inputContainer').innerHTML;
                        
                        // Mettre à jour la discussion active
                        document.querySelectorAll('.thread-item, .thread-item-horizontal').forEach(item => {
                            item.classList.remove('active');
                        });
                        this.classList.add('active');
                        
                        // Réinitialiser le formulaire
                        const form = document.getElementById('messageForm');
                        if (form) {
                            form.addEventListener('submit', handleFormSubmit);
                        }
                        
                        scrollToBottom();
                    } catch (error) {
                        console.error('Erreur lors du chargement de la discussion:', error);
                        alert('Erreur lors du chargement de la discussion. Veuillez réessayer.');
                    } finally {
                        hideLoading();
                    }
                });
            });

            // Soumission du formulaire
            async function handleFormSubmit(e) {
                e.preventDefault();
                if (isSubmitting) return;
                
                const form = e.target;
                const formData = new FormData(form);
                const messageInput = document.getElementById('messageInput');
                
                if (!messageInput.value.trim()) return;
                
                isSubmitting = true;
                showLoading();
                messageInput.disabled = true;
                
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();
                    
                    if (!response.ok) {
                        throw new Error(data.message || 'Échec de l\'envoi');
                    }
                    
                    const messagesContainer = document.getElementById('messagesContainer');
                    
                    // Supprimer l'état vide s'il existe
                    const emptyState = messagesContainer.querySelector('.empty-state');
                    if (emptyState) emptyState.remove();
                    
                    // Ajouter le nouveau message en bas
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'message sent';
                    messageDiv.setAttribute('data-post-id', data.post.id);
                    messageDiv.innerHTML = `
                        <div class="message-bubble">
                            ${data.post.body}
                            <div class="message-actions">
                                <button class="message-action-btn delete-btn" data-post-id="${data.post.id}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="message-info">
                            <span><i class="fas fa-clock"></i> À l'instant</span>
                            <span>•</span>
                            <span>Vous</span>
                        </div>
                    `;
                    
                    messagesContainer.appendChild(messageDiv);
                    form.reset();
                    
                    // Défiler vers le bas après un court délai pour permettre la mise à jour du DOM
                    setTimeout(scrollToBottom, 50);
                    
                } catch (error) {
                    console.error('Erreur lors de l\'envoi du message:', error);
                    alert(`Erreur: ${error.message}`);
                } finally {
                    if (messageInput) {
                        messageInput.disabled = false;
                        autoResize(messageInput);
                    }
                    hideLoading();
                    isSubmitting = false;
                }
            }

            // Gestionnaires de suppression
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-btn')) {
                    const postId = e.target.closest('.delete-btn').getAttribute('data-post-id');
                    showDeleteModal(`Êtes-vous sûr de vouloir supprimer ce message ?`, 'message', postId);
                }
            });

            // Gérer Ctrl+Entrée pour l'envoi de message
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'Enter' && document.activeElement === document.getElementById('messageInput')) {
                    document.getElementById('messageForm')?.dispatchEvent(new Event('submit'));
                }
            });

            // Initialiser le formulaire s'il existe au chargement de la page
            const initialForm = document.getElementById('messageForm');
            if (initialForm) {
                initialForm.addEventListener('submit', handleFormSubmit);
            }

            // Initialiser le défilement vers le bas
            scrollToBottom();

            // Fonctions utilitaires
            function autoResize(textarea) {
                if (textarea) {
                    textarea.style.height = 'auto';
                    textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
                }
            }
            
            function scrollToBottom() {
                const messagesContainer = document.getElementById('messagesContainer');
                if (messagesContainer) {
                    messagesContainer.scrollTo({
                        top: messagesContainer.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            }
            
            function showLoading() {
                loadingIndicator.style.display = 'block';
            }
            
            function hideLoading() {
                loadingIndicator.style.display = 'none';
            }

            function openModal() {
                modal.classList.add('active');
                document.getElementById('threadTitle').focus();
            }

            function closeModal() {
                modal.classList.remove('active');
                document.getElementById('threadForm').reset();
            }

            function showDeleteModal(text, type, itemId) {
                document.getElementById('deleteModalText').textContent = text;
                itemToDelete = { type, id: itemId };
                deleteModal.classList.add('active');
            }

            function closeDeleteModal() {
                deleteModal.classList.remove('active');
                itemToDelete = null;
            }

         function confirmDelete() {
    if (!itemToDelete) return;
    
    showLoading();
    
    if (itemToDelete.type === 'message') {
        fetch(`/elearning/posts/${itemToDelete.id}`, {  // Note le préfixe /elearning/
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            document.querySelector(`[data-post-id="${itemToDelete.id}"]`)?.remove();
            showNotification(data.message || 'Message supprimé avec succès');
        })
        .catch(error => {
            console.error('Erreur lors de la suppression du message:', error);
            showNotification(error.message || 'Erreur lors de la suppression du message', 'error');
        })
        .finally(() => {
            closeDeleteModal();
            hideLoading();
        });
    }
}

            function showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;
                notification.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    <span>${message}</span>
                `;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.classList.add('show');
                }, 10);
                
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 3000);
            }
        });
    </script>
</body>
</html>