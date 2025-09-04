<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Private Chat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('etudiant/assets/css/message.css') }}">
    <style>
        /* Additional CSS for edit functionality */
        .edited-indicator {
            font-size: 0.7rem;
            color: var(--grey-500);
            margin-left: 0.5rem;
            font-style: italic;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .modal.active {
            display: flex;
            opacity: 1;
        }
        
        .modal-content {
            background: var(--white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            width: 100%;
            max-width: 400px;
            padding: 1.5rem;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }
        
        .modal.active .modal-content {
            transform: translateY(0);
        }
        
        .modal-title {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: var(--grey-800);
            display: flex;
            align-items: center;
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }
        
        .modal-btn {
            padding: 0.75rem 1.25rem;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: var(--transition-fast);
            border: 1px solid var(--grey-300);
            background: var(--white);
            color: var(--grey-700);
            font-weight: 500;
        }
        
        .edit-message-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--grey-300);
            border-radius: var(--radius-md);
            margin-bottom: 1rem;
            font-family: inherit;
            resize: none;
            min-height: 100px;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Loading Indicator -->
        <div class="loading" id="loadingIndicator">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
        </div>

        <!-- Header -->
        <div class="header">
            <a href="{{ route('etudiant.chat.index') }}" class="header-btn back-btn" aria-label="Go back">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2>
                @foreach($conversation->users as $participant)
                    @if($participant->id !== auth()->id())
                        {{ $participant->name }}
                    @endif
                @endforeach
            </h2>
            <div></div> <!-- Empty div for spacing -->
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Messages Area -->
            <div class="messages-area">
                <!-- Messages Container -->
                <div class="messages-container" id="messagesContainer">
                    @forelse($conversation->messages as $message)
                        <div class="message {{ $message->user_id === auth()->id() ? 'sent' : 'received' }}" data-message-id="{{ $message->id }}">
                            <div class="message-bubble">
                                {{ $message->body }}
                                @if($message->user_id === auth()->id())
                                    <div class="message-actions">
                                        <button class="message-action-btn edit-btn" data-message-id="{{ $message->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="message-action-btn delete-btn" data-message-id="{{ $message->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="message-info">
                                @if($message->user_id === auth()->id())
                                    <span><i class="fas fa-clock"></i> {{ $message->created_at->diffForHumans() }}</span>
                                    @if($message->edited_at)
                                        <span class="edited-indicator">(edited)</span>
                                    @endif
                                    <span>•</span>
                                    <span>You</span>
                                @else
                                    <span><i class="fas fa-user"></i> {{ $message->user->name }}</span>
                                    <span>•</span>
                                    <span><i class="fas fa-clock"></i> {{ $message->created_at->diffForHumans() }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-comment-slash"></i>
                            <h3>No messages yet</h3>
                            <p>Start the conversation now!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Input Container -->
                <div class="input-container">
                    <form action="{{ route('chat.sendMessage', $conversation->id) }}" method="POST" class="message-form" id="messageForm">
                        @csrf
                        <textarea class="message-input" id="messageInput" name="body" placeholder="Type your message..." required></textarea>
                        <button type="submit" class="send-btn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <h3 class="modal-title"><i class="fas fa-exclamation-triangle" style="color: var(--error); margin-right: 0.5rem;"></i>Confirm Deletion</h3>
            <p id="deleteModalText" style="margin-bottom: 2rem; color: var(--grey-600); line-height: 1.6;">Are you sure you want to delete this message? This action cannot be undone.</p>
            
            <div class="modal-actions">
                <button type="button" class="modal-btn cancel-btn" id="cancelDeleteBtn">
                    Cancel
                </button>
                <button type="button" class="modal-btn" id="confirmDeleteBtn" style="background: var(--error); color: var(--white);">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Message Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <h3 class="modal-title"><i class="fas fa-edit" style="color: var(--primary-blue); margin-right: 0.5rem;"></i>Edit Message</h3>
            <textarea class="edit-message-input" id="editMessageInput"></textarea>
            
            <div class="modal-actions">
                <button type="button" class="modal-btn cancel-btn" id="cancelEditBtn">
                    Cancel
                </button>
                <button type="button" class="modal-btn" id="confirmEditBtn" style="background: var(--primary-blue); color: var(--white);">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadingIndicator = document.getElementById('loadingIndicator');
        const deleteModal = document.getElementById('deleteModal');
        const editModal = document.getElementById('editModal');
        const messageInput = document.getElementById('messageInput');
        const messageForm = document.getElementById('messageForm');
        const messagesContainer = document.getElementById('messagesContainer');
        const editMessageInput = document.getElementById('editMessageInput');
        let isSubmitting = false;
        let messageToDelete = null;
        let messageToEdit = null;

        // Initialize auto-resize for message input
        if (messageInput) {
            messageInput.addEventListener('input', function() {
                autoResize(this);
            });
        }

        // Delete modal handling
        document.getElementById('cancelDeleteBtn')?.addEventListener('click', closeDeleteModal);
        document.getElementById('confirmDeleteBtn')?.addEventListener('click', confirmDelete);

        // Edit modal handling
        document.getElementById('cancelEditBtn')?.addEventListener('click', closeEditModal);
        document.getElementById('confirmEditBtn')?.addEventListener('click', confirmEdit);

        // Message action handlers
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-btn')) {
                const messageId = e.target.closest('.delete-btn').getAttribute('data-message-id');
                showDeleteModal('Are you sure you want to delete this message?', messageId);
            }
            
            if (e.target.closest('.edit-btn')) {
                const messageId = e.target.closest('.edit-btn').getAttribute('data-message-id');
                const messageElement = e.target.closest('.message');
                const messageText = messageElement.querySelector('.message-bubble').childNodes[0].nodeValue.trim();
                showEditModal(messageText, messageId, messageElement);
            }
        });

        // Form submission with AJAX
        if (messageForm) {
            messageForm.addEventListener('submit', handleFormSubmit);
        }

        // Handle Ctrl+Enter for message submission
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'Enter' && document.activeElement === document.getElementById('messageInput')) {
                document.getElementById('messageForm')?.dispatchEvent(new Event('submit'));
            }
        });

        // Scroll to bottom on initial load
        scrollToBottom();

        // Utility functions
        function autoResize(textarea) {
            if (textarea) {
                textarea.style.height = 'auto';
                textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
            }
        }
        
        function scrollToBottom() {
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

        function showDeleteModal(text, messageId) {
            document.getElementById('deleteModalText').textContent = text;
            messageToDelete = messageId;
            deleteModal.classList.add('active');
        }

        function closeDeleteModal() {
            deleteModal.classList.remove('active');
            messageToDelete = null;
        }

        function showEditModal(messageText, messageId, messageElement) {
            editMessageInput.value = messageText;
            messageToEdit = {
                id: messageId,
                element: messageElement
            };
            editModal.classList.add('active');
            editMessageInput.focus();
        }

        function closeEditModal() {
            editModal.classList.remove('active');
            messageToEdit = null;
        }

        async function confirmDelete() {
            if (!messageToDelete) return;
            
            showLoading();
            
            try {
                const response = await fetch(`/messages/${messageToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Failed to delete message');
                }

                const data = await response.json();
                document.querySelector(`[data-message-id="${messageToDelete}"]`)?.remove();
                
                // Check if there are no more messages
                if (document.querySelectorAll('.message').length === 0) {
                    messagesContainer.innerHTML = `
                        <div class="empty-state">
                            <i class="fas fa-comment-slash"></i>
                            <h3>No messages yet</h3>
                            <p>Start the conversation now!</p>
                        </div>
                    `;
                }
                
                showNotification(data.message || 'Message deleted successfully');
            } catch (error) {
                console.error('Error deleting message:', error);
                showNotification(error.message || 'Error deleting message', 'error');
            } finally {
                closeDeleteModal();
                hideLoading();
            }
        }

        async function confirmEdit() {
            if (!messageToEdit || !editMessageInput.value.trim()) return;
            
            showLoading();
            
            try {
                const response = await fetch(`/messages/${messageToEdit.id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        body: editMessageInput.value.trim()
                    })
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Failed to update message');
                }

                const data = await response.json();
                
                // Update the message in the DOM
                const messageBubble = messageToEdit.element.querySelector('.message-bubble');
                if (messageBubble) {
                    // Update the message text (first child node)
                    messageBubble.childNodes[0].nodeValue = data.data.body;
                    
                    // Add edited indicator if not already present
                    const messageInfo = messageToEdit.element.querySelector('.message-info');
                    if (messageInfo && !messageInfo.querySelector('.edited-indicator')) {
                        const editedIndicator = document.createElement('span');
                        editedIndicator.className = 'edited-indicator';
                        editedIndicator.textContent = '(edited)';
                        messageInfo.insertBefore(editedIndicator, messageInfo.querySelector('span:nth-child(3)'));
                    }
                }
                
                showNotification(data.message || 'Message updated successfully');
                closeEditModal();
            } catch (error) {
                console.error('Error updating message:', error);
                showNotification(error.message || 'Error updating message', 'error');
            } finally {
                hideLoading();
            }
        }

        async function handleFormSubmit(e) {
            e.preventDefault();
            if (isSubmitting) return;
            
            const form = e.target;
            const formData = new FormData(form);
            
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
                    throw new Error(data.message || 'Failed to send message');
                }
                
                // Remove empty state if exists
                const emptyState = messagesContainer.querySelector('.empty-state');
                if (emptyState) emptyState.remove();
                
                // Add new message at the bottom
                const messageDiv = document.createElement('div');
                messageDiv.className = 'message sent';
                messageDiv.setAttribute('data-message-id', data.data.id);
                messageDiv.innerHTML = `
                    <div class="message-bubble">
                        ${data.data.body}
                        <div class="message-actions">
                            <button class="message-action-btn edit-btn" data-message-id="${data.data.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="message-action-btn delete-btn" data-message-id="${data.data.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="message-info">
                        <span><i class="fas fa-clock"></i> Just now</span>
                        <span>•</span>
                        <span>You</span>
                    </div>
                `;
                
                messagesContainer.appendChild(messageDiv);
                form.reset();
                
                // Scroll to bottom after short delay to allow DOM update
                setTimeout(scrollToBottom, 50);
                
            } catch (error) {
                console.error('Error sending message:', error);
                showNotification(`Error: ${error.message}`, 'error');
            } finally {
                if (messageInput) {
                    messageInput.disabled = false;
                    autoResize(messageInput);
                    messageInput.focus();
                }
                hideLoading();
                isSubmitting = false;
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