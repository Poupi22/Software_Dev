@extends('etudiant.layouts.app')

@section('title', '3IA Learning Management System - Contact')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
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
        --dark-grey: #374151;
        --medium-grey: #6b7280;
        --light-grey: #f3f4f6;
        --white: #ffffff;
    }
    
    .glass-effect {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(30, 64, 175, 0.1);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
    }
    
    .gradient-bg {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }
    
    .gradient-blue {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #1e40af, #3b82f6);
        color: white;
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(30, 64, 175, 0.2);
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(30, 64, 175, 0.3);
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6b7280, #374151);
        color: white;
        border: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(107, 114, 128, 0.3);
    }
    
    .input-field {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(30, 64, 175, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .input-field:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background: rgba(255, 255, 255, 1);
    }
    
    .request-type-card {
        border: 2px solid rgba(30, 64, 175, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    
    .request-type-card:hover {
        border-color: #3b82f6;
        transform: translateY(-2px);
    }
    
    .request-type-card.selected {
        border-color: #3b82f6;
        background-color: rgba(219, 234, 254, 0.3);
    }
    
    .confirmation-modal {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    
    .confirmation-modal.active {
        opacity: 1;
        pointer-events: auto;
    }
    
    .attachment-preview {
        display: flex;
        align-items: center;
        background-color: #f3f4f6;
        border-radius: 6px;
        padding: 8px;
        margin-top: 8px;
    }
    
    .attachment-preview .file-icon {
        margin-right: 8px;
        color: #3b82f6;
    }
</style>
@endsection

@section('content')
<!-- Main Content -->
<main class="pt-24 min-h-screen gradient-bg">
    <!-- Section Contact Administrateur -->
    <section>
        <div class="max-w-4xl mx-auto px-6 py-8">
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-bold text-gray-800 mb-2">Contacter les Administrateurs</h1>
                <p class="text-gray-600">Envoyez des messages à l'équipe d'administration pour du support et des demandes</p>
            </div>
            
            <div class="glass-effect rounded-xl p-6 md:p-8">
                <form id="contactForm" action="{{ route('etudiant.email.send') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Informations de l'Étudiant -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                            <input type="text" id="nom" name="nom" required 
                                   class="w-full input-field px-4 py-3 rounded-lg focus:outline-none">
                        </div>
                        <div>
                            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                            <input type="text" id="prenom" name="prenom" required 
                                   class="w-full input-field px-4 py-3 rounded-lg focus:outline-none">
                        </div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresse Email</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full input-field px-4 py-3 rounded-lg focus:outline-none">
                    </div>
                    
                    <!-- Type de Demande -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Type de Demande</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="request-type-card p-4 rounded-lg text-center" data-type="technical">
                                <div class="w-10 h-10 gradient-blue rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-laptop-code text-white"></i>
                                </div>
                                <div class="font-medium">Problème Technique</div>
                            </div>
                            <div class="request-type-card p-4 rounded-lg text-center" data-type="academic">
                                <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-book text-white"></i>
                                </div>
                                <div class="font-medium">Aide Académique</div>
                            </div>
                            <div class="request-type-card p-4 rounded-lg text-center" data-type="account">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-user-cog text-white"></i>
                                </div>
                                <div class="font-medium">Aide Compte</div>
                            </div>
                            <div class="request-type-card p-4 rounded-lg text-center" data-type="other">
                                <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-question text-white"></i>
                                </div>
                                <div class="font-medium">Autre Demande</div>
                            </div>
                        </div>
                        <input type="hidden" id="requestType" name="requestType" value="">
                    </div>
                    
                    <!-- Sujet -->
                    <div>
                        <label for="sujet" class="block text-sm font-medium text-gray-700 mb-2">Sujet</label>
                        <input type="text" id="sujet" name="sujet" required 
                               class="w-full input-field px-4 py-3 rounded-lg focus:outline-none">
                    </div>
                    
                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea id="message" name="message" required 
                                  class="w-full input-field px-4 py-3 rounded-lg focus:outline-none h-40"></textarea>
                    </div>
                    
                    <!-- Pièces Jointes -->
                    <div>
                        <label for="fichier" class="block text-sm font-medium text-gray-700 mb-2">Pièces Jointes (Optionnel)</label>
                        <div class="flex items-center">
                            <label for="fichier" class="btn-secondary px-4 py-2 rounded-lg cursor-pointer">
                                <i class="fas fa-paperclip mr-2"></i>Ajouter des Fichiers
                            </label>
                            <input type="file" id="fichier" name="fichier" class="hidden">
                            <span class="text-sm text-gray-500 ml-3">Max 5MB par fichier</span>
                        </div>
                        <div id="attachmentPreviews" class="mt-2"></div>
                    </div>
                    
                    <!-- Bouton d'Envoi -->
                    <div class="pt-2">
                        <button type="submit" class="btn-primary px-6 py-3 rounded-lg w-full md:w-auto">
                            <i class="fas fa-paper-plane mr-2"></i>Envoyer le Message
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Informations Supplémentaires -->
            <div class="mt-8 glass-effect rounded-xl p-6">
                <h3 class="text-lg font-bold mb-4">Besoin d'Aide Immédiate ?</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-phone text-blue-600"></i>
                            </div>
                            <h4 class="font-medium">Support Téléphonique</h4>
                        </div>
                        <p class="text-sm text-gray-600">Appelez-nous au <span class="font-medium">+237 690981048</span> pendant les heures d'ouverture (9h-17h)</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-comments text-green-600"></i>
                            </div>
                            <h4 class="font-medium">Chat en Direct</h4>
                        </div>
                        <p class="text-sm text-gray-600">Disponible <span class="font-medium">24h/24</span> via notre portail de support</p>
                    </div>
                    <div class="p-4 bg-purple-50 rounded-lg">
                        <div class="flex items-center mb-2">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-question-circle text-purple-600"></i>
                            </div>
                            <h4 class="font-medium">FAQ</h4>
                        </div>
                        <p class="text-sm text-gray-600">Consultez notre <a href="#" class="text-purple-600 hover:underline">section FAQ</a> pour des réponses rapides</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Modal de Confirmation -->
    <div id="confirmationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 confirmation-modal">
        <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-500 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Message Envoyé !</h3>
                <p class="text-gray-600">Votre message a été livré avec succès à l'équipe d'administration.</p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-700">ID de Référence :</span>
                    <span id="referenceId" class="font-medium">REQ-{{ date('Y') }}-{{ rand(10000, 99999) }}</span>
                </div>
                <div class="text-sm text-gray-600">Vous recevrez un email de confirmation sous peu.</div>
            </div>
            
            <button id="closeModalBtn" class="w-full btn-primary py-3 rounded-lg">
                Retour au Tableau de Bord
            </button>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Request type selection
        const requestTypeCards = document.querySelectorAll('.request-type-card');
        const requestTypeInput = document.getElementById('requestType');
        
        requestTypeCards.forEach(card => {
            card.addEventListener('click', () => {
                // Remove selected class from all cards
                requestTypeCards.forEach(c => c.classList.remove('selected'));
                
                // Add selected class to clicked card
                card.classList.add('selected');
                
                // Update hidden input value
                requestTypeInput.value = card.dataset.type;
            });
        });
        
        // Default select first request type
        if (requestTypeCards.length > 0) {
            requestTypeCards[0].click();
        }
        
        // File upload handling
        const fileUpload = document.getElementById('fichier');
        const attachmentPreviews = document.getElementById('attachmentPreviews');
        
        fileUpload.addEventListener('change', (e) => {
            attachmentPreviews.innerHTML = '';
            
            if (e.target.files.length > 0) {
                Array.from(e.target.files).forEach(file => {
                    const preview = document.createElement('div');
                    preview.className = 'attachment-preview';
                    
                    let icon;
                    if (file.type.includes('image/')) {
                        icon = 'fa-image';
                    } else if (file.type.includes('pdf')) {
                        icon = 'fa-file-pdf';
                    } else if (file.type.includes('word') || file.type.includes('document')) {
                        icon = 'fa-file-word';
                    } else if (file.type.includes('excel') || file.type.includes('spreadsheet')) {
                        icon = 'fa-file-excel';
                    } else {
                        icon = 'fa-file';
                    }
                    
                    preview.innerHTML = `
                        <i class="fas ${icon} file-icon"></i>
                        <span class="text-sm truncate flex-1">${file.name}</span>
                        <span class="text-xs text-gray-500">${formatFileSize(file.size)}</span>
                    `;
                    
                    attachmentPreviews.appendChild(preview);
                });
            }
        });
        
        // Form submission
        const contactForm = document.getElementById('contactForm');
        const confirmationModal = document.getElementById('confirmationModal');
        
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!requestTypeInput.value) {
                alert('Please select a request type');
                return;
            }
            
            const subject = document.getElementById('sujet').value;
            const message = document.getElementById('message').value;
            
            if (!subject || !message) {
                alert('Please fill in all required fields');
                return;
            }
            
            // Show confirmation modal
            confirmationModal.classList.add('active');
            
            // Submit the form
            this.submit();
        });
        
        // Close modal
        document.getElementById('closeModalBtn').addEventListener('click', () => {
            confirmationModal.classList.remove('active');
        });
        
        // Helper function to format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    });
</script>
@endsection