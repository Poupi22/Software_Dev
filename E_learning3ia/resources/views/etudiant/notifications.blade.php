@extends('etudiant.layouts.app')

@section('title', 'Système de Gestion d\'Apprentissage 3IA - Envoyer des Notifications')

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
        
        .gradient-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 100%);
        }
        
        .gradient-green {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        }
        
        .gradient-orange {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        }
        
        .gradient-red {
            background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
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
        
        .btn-success {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6b7280, #9ca3af);
            color: white;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.2);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(107, 114, 128, 0.3);
        }
        
        .specialty-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid transparent;
            cursor: pointer;
        }
        
        .specialty-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        }
        
        .specialty-card.selected {
            border-color: #3b82f6;
            background: rgba(59, 130, 246, 0.05);
        }
        
        .notification-type-option {
            transition: all 0.3s ease;
            border: 2px solid transparent;
            cursor: pointer;
        }
        
        .notification-type-option.selected {
            border-color: currentColor;
            transform: scale(1.05);
        }
        
        .character-counter {
            transition: color 0.3s ease;
        }
        
        .character-counter.warning {
            color: #f59e0b;
        }
        
        .character-counter.danger {
            color: #ef4444;
        }
        
        .preview-card {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-left: 4px solid #3b82f6;
        }
        
        .urgent-notification {
            animation: pulse 2s infinite;
            border-left-color: #ef4444;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }
        
        .form-group {
            transition: all 0.3s ease;
        }
        
        .form-group:focus-within {
            transform: translateY(-2px);
        }
        
        .stats-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.8));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
        }
        
        .floating-action {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    </style>

@endsection

@section('content')
    <!-- Contenu Principal -->
    <main class="pt-24 min-h-screen gradient-bg">
        <!-- Section Envoi de Notifications -->
        <section>
            <div class="max-w-7xl mx-auto px-6 py-8">
                <!-- En-tête -->
                <div class="mb-8 text-center">
                    <h1 class="text-4xl font-bold text-gray-800 mb-2">Envoyer des Notifications</h1>
                    <p class="text-gray-600 max-w-2xl mx-auto">Diffusez des annonces importantes et des mises à jour aux étudiants de différentes spécialités</p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Colonne de Gauche - Formulaire -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Formulaire de Notification -->
                        <div class="glass-effect rounded-2xl p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-gray-800">Créer une Notification</h2>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                    <span class="text-sm text-green-600 font-medium">En ligne</span>
                                </div>
                            </div>
                            
                            <form id="notificationForm" class="space-y-6">
                                <!-- Type de Notification -->
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Type de Notification</label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="notification-type-option text-blue-600 bg-blue-50 rounded-xl p-4 text-center" data-type="info">
                                            <i class="fas fa-info-circle text-2xl mb-2"></i>
                                            <p class="font-medium">Information</p>
                                        </div>
                                        <div class="notification-type-option text-orange-600 bg-orange-50 rounded-xl p-4 text-center" data-type="warning">
                                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                                            <p class="font-medium">Avertissement</p>
                                        </div>
                                        <div class="notification-type-option text-red-600 bg-red-50 rounded-xl p-4 text-center" data-type="urgent">
                                            <i class="fas fa-bell text-2xl mb-2"></i>
                                            <p class="font-medium">Urgent</p>
                                        </div>
                                        <div class="notification-type-option text-green-600 bg-green-50 rounded-xl p-4 text-center" data-type="success">
                                            <i class="fas fa-check-circle text-2xl mb-2"></i>
                                            <p class="font-medium">Succès</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Titre -->
                                <div class="form-group">
                                    <label for="notificationTitle" class="block text-sm font-medium text-gray-700 mb-2">
                                        Titre <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="notificationTitle" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                           placeholder="Entrez le titre de la notification" maxlength="100">
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-xs text-gray-500">Titre bref et descriptif</span>
                                        <span class="text-xs character-counter" id="titleCounter">0/100</span>
                                    </div>
                                </div>
                                
                                <!-- Message -->
                                <div class="form-group">
                                    <label for="notificationMessage" class="block text-sm font-medium text-gray-700 mb-2">
                                        Message <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="notificationMessage" rows="6"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 resize-none"
                                              placeholder="Entrez le message détaillé de la notification..." maxlength="500"></textarea>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-xs text-gray-500">Explication détaillée de la notification</span>
                                        <span class="text-xs character-counter" id="messageCounter">0/500</span>
                                    </div>
                                </div>
                                
                                <!-- Spécialités Ciblées -->
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Spécialités Ciblées <span class="text-red-500">*</span>
                                    </label>
                                    <div class="space-y-3">
                                        <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                                            <input type="checkbox" id="selectAll" class="rounded text-blue-600 focus:ring-blue-500">
                                            <span class="font-medium text-gray-700">Envoyer à Toutes les Spécialités</span>
                                        </label>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="specialtiesContainer">
                                            <!-- Les spécialités seront peuplées ici -->
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Options Supplémentaires -->
                                <div class="form-group">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Options Supplémentaires</label>
                                    <div class="space-y-3">
                                        <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                                            <input type="checkbox" id="urgentFlag" class="rounded text-red-600 focus:ring-red-500">
                                            <span class="font-medium text-gray-700">Marquer comme Urgent</span>
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Haute Priorité</span>
                                        </label>
                                        
                                        <label class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100 transition-colors">
                                            <input type="checkbox" id="emailCopy" class="rounded text-green-600 focus:ring-green-500">
                                            <span class="font-medium text-gray-700">Envoyer une Copie par Email</span>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Optionnel</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Boutons d'Action -->
                                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                                    <button type="button" id="previewBtn" class="btn-secondary flex-1 py-4 rounded-xl font-semibold">
                                        <i class="fas fa-eye mr-2"></i>Aperçu de la Notification
                                    </button>
                                    <button type="button" id="sendBtn" class="btn-success flex-1 py-4 rounded-xl font-semibold">
                                        <i class="fas fa-paper-plane mr-2"></i>Envoyer la Notification
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Statistiques Rapides -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="stats-card rounded-2xl p-6 text-center">
                                <div class="gradient-blue w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800" id="totalStudents">0</h3>
                                <p class="text-gray-600 text-sm">Étudiants Totaux</p>
                            </div>
                            
                            <div class="stats-card rounded-2xl p-6 text-center">
                                <div class="gradient-purple w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-graduation-cap text-white"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800" id="totalSpecialties">0</h3>
                                <p class="text-gray-600 text-sm">Spécialités</p>
                            </div>
                            
                            <div class="stats-card rounded-2xl p-6 text-center">
                                <div class="gradient-green w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-bell text-white"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-800" id="sentToday">0</h3>
                                <p class="text-gray-600 text-sm">Envoyées Aujourd'hui</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne de Droite - Aperçu & Récent -->
                    <div class="space-y-8">
                        <!-- Carte d'Aperçu -->
                        <div class="glass-effect rounded-2xl p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Aperçu</h3>
                            <div id="previewCard" class="preview-card rounded-xl p-4 min-h-[200px] flex items-center justify-center">
                                <div class="text-center text-gray-500">
                                    <i class="fas fa-eye text-3xl mb-3 opacity-50"></i>
                                    <p>L'aperçu apparaîtra ici</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notifications Récentes -->
                        <div class="glass-effect rounded-2xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-800">Notifications Récentes</h3>
                                <span class="text-sm text-blue-600 font-medium">Dernières 24h</span>
                            </div>
                            <div class="space-y-4" id="recentNotifications">
                                <!-- Les notifications récentes seront peuplées ici -->
                            </div>
                            <div class="text-center py-4" id="noRecentNotifications">
                                <i class="fas fa-history text-2xl text-gray-300 mb-2"></i>
                                <p class="text-gray-500 text-sm">Aucune notification récente</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal de Succès -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-8 max-w-md mx-4 text-center transform transition-all duration-500 scale-95">
            <div class="gradient-green w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-white text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Notification Envoyée !</h3>
            <p class="text-gray-600 mb-6" id="successMessage">Votre notification a été livrée avec succès à toutes les spécialités sélectionnées.</p>
            <button id="closeSuccessModal" class="btn-primary w-full py-3 rounded-xl font-semibold">
                Continuer
            </button>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Données d'exemple
    const specialties = [
        { id: 'web-dev', name: 'Développement Web', students: 45, icon: 'fa-code', color: 'bg-blue-500' },
        { id: 'mobile-dev', name: 'Développement Mobile', students: 32, icon: 'fa-mobile-alt', color: 'bg-purple-500' },
        { id: 'data-science', name: 'Science des Données', students: 28, icon: 'fa-chart-bar', color: 'bg-green-500' },
        { id: 'cybersecurity', name: 'Cybersécurité', students: 24, icon: 'fa-shield-alt', color: 'bg-red-500' },
        { id: 'ai-ml', name: 'IA & Apprentissage Automatique', students: 36, icon: 'fa-brain', color: 'bg-orange-500' },
        { id: 'cloud-computing', name: 'Cloud Computing', students: 29, icon: 'fa-cloud', color: 'bg-indigo-500' }
    ];

    const recentNotifications = [
        {
            title: 'Maintenance du Système',
            type: 'info',
            specialties: ['Toutes'],
            time: 'Il y a 2 heures'
        },
        {
            title: 'Date Limite de Devoir Prolongée',
            type: 'warning',
            specialties: ['Développement Web', 'Développement Mobile'],
            time: 'Il y a 5 heures'
        },
        {
            title: 'Nouveaux Matériels de Cours Disponibles',
            type: 'success',
            specialties: ['Science des Données', 'IA & Apprentissage Automatique'],
            time: 'Il y a 1 jour'
        }
    ];

    // Éléments DOM
    let selectedType = 'info';
    let selectedSpecialties = new Set();

    // Initialiser la page
    document.addEventListener('DOMContentLoaded', () => {
        initializeForm();
        loadSpecialties();
        loadRecentNotifications();
        updateStats();
        
        // Écouteurs d'événements
        setupEventListeners();
    });

    function initializeForm() {
        // Définir le type de notification par défaut
        document.querySelector('.notification-type-option[data-type="info"]').classList.add('selected');
    }

    function loadSpecialties() {
        const container = document.getElementById('specialtiesContainer');
        container.innerHTML = '';
        
        specialties.forEach(specialty => {
            const card = document.createElement('div');
            card.className = 'specialty-card bg-white rounded-xl p-4 border border-gray-200';
            card.innerHTML = `
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="specialty-${specialty.id}" value="${specialty.id}" 
                           class="rounded text-blue-600 focus:ring-blue-500">
                    <div class="${specialty.color} w-8 h-8 rounded-lg flex items-center justify-center">
                        <i class="fas ${specialty.icon} text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <label for="specialty-${specialty.id}" class="font-medium text-gray-700 cursor-pointer">${specialty.name}</label>
                        <p class="text-xs text-gray-500">${specialty.students} étudiants</p>
                    </div>
                </div>
            `;
            
            container.appendChild(card);
        });
    }

    function loadRecentNotifications() {
        const container = document.getElementById('recentNotifications');
        const emptyState = document.getElementById('noRecentNotifications');
        
        if (recentNotifications.length === 0) {
            emptyState.classList.remove('hidden');
            container.innerHTML = '';
            return;
        }
        
        emptyState.classList.add('hidden');
        container.innerHTML = '';
        
        recentNotifications.forEach(notification => {
            const element = document.createElement('div');
            element.className = 'bg-gray-50 rounded-xl p-4';
            element.innerHTML = `
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center ${
                        notification.type === 'info' ? 'bg-blue-500' :
                        notification.type === 'warning' ? 'bg-orange-500' :
                        notification.type === 'success' ? 'bg-green-500' : 'bg-gray-500'
                    }">
                        <i class="fas ${
                            notification.type === 'info' ? 'fa-info' :
                            notification.type === 'warning' ? 'fa-exclamation-triangle' :
                            notification.type === 'success' ? 'fa-check' : 'fa-bell'
                        } text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-800 text-sm">${notification.title}</h4>
                        <p class="text-xs text-gray-600 mt-1">À: ${notification.specialties.join(', ')}</p>
                        <p class="text-xs text-gray-500 mt-1">${notification.time}</p>
                    </div>
                </div>
            `;
            container.appendChild(element);
        });
    }

    function updateStats() {
        document.getElementById('totalStudents').textContent = 
            specialties.reduce((sum, spec) => sum + spec.students, 0);
        document.getElementById('totalSpecialties').textContent = specialties.length;
        document.getElementById('sentToday').textContent = recentNotifications.length;
    }

    function setupEventListeners() {
        // Sélection du type de notification
        document.querySelectorAll('.notification-type-option').forEach(option => {
            option.addEventListener('click', () => {
                document.querySelectorAll('.notification-type-option').forEach(opt => 
                    opt.classList.remove('selected'));
                option.classList.add('selected');
                selectedType = option.dataset.type;
                updatePreview();
            });
        });

        // Sélectionner toutes les spécialités
        document.getElementById('selectAll').addEventListener('change', (e) => {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][value]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = e.target.checked;
                if (e.target.checked) {
                    selectedSpecialties.add(checkbox.value);
                } else {
                    selectedSpecialties.delete(checkbox.value);
                }
            });
            updatePreview();
        });

        // Sélection individuelle des spécialités
        document.addEventListener('change', (e) => {
            if (e.target.matches('input[type="checkbox"][value]')) {
                if (e.target.checked) {
                    selectedSpecialties.add(e.target.value);
                } else {
                    selectedSpecialties.delete(e.target.value);
                    document.getElementById('selectAll').checked = false;
                }
                updatePreview();
            }
        });

        // Compteurs de caractères
        document.getElementById('notificationTitle').addEventListener('input', updateCharacterCounters);
        document.getElementById('notificationMessage').addEventListener('input', updateCharacterCounters);

        // Bouton d'aperçu
        document.getElementById('previewBtn').addEventListener('click', showPreview);

        // Bouton d'envoi
        document.getElementById('sendBtn').addEventListener('click', sendNotification);

        // Modal de succès
        document.getElementById('closeSuccessModal').addEventListener('click', () => {
            document.getElementById('successModal').classList.add('hidden');
        });
    }

    function updateCharacterCounters() {
        const titleInput = document.getElementById('notificationTitle');
        const messageInput = document.getElementById('notificationMessage');
        const titleCounter = document.getElementById('titleCounter');
        const messageCounter = document.getElementById('messageCounter');

        titleCounter.textContent = `${titleInput.value.length}/100`;
        messageCounter.textContent = `${messageInput.value.length}/500`;

        // Mettre à jour les couleurs des compteurs en fonction de la longueur
        if (titleInput.value.length > 80) {
            titleCounter.classList.add('warning');
            titleCounter.classList.remove('danger');
        } else if (titleInput.value.length > 95) {
            titleCounter.classList.remove('warning');
            titleCounter.classList.add('danger');
        } else {
            titleCounter.classList.remove('warning', 'danger');
        }

        if (messageInput.value.length > 400) {
            messageCounter.classList.add('warning');
            messageCounter.classList.remove('danger');
        } else if (messageInput.value.length > 480) {
            messageCounter.classList.remove('warning');
            messageCounter.classList.add('danger');
        } else {
            messageCounter.classList.remove('warning', 'danger');
        }

        updatePreview();
    }

    function updatePreview() {
        // Cette fonction sera appelée pour mettre à jour l'aperçu en temps réel
        // Pour l'instant, nous mettrons à jour uniquement lorsque demandé explicitement via showPreview
    }

    function showPreview() {
        const title = document.getElementById('notificationTitle').value;
        const message = document.getElementById('notificationMessage').value;
        const isUrgent = document.getElementById('urgentFlag').checked;
        
        const previewCard = document.getElementById('previewCard');
        
        if (!title && !message) {
            previewCard.innerHTML = `
                <div class="text-center text-gray-500">
                    <i class="fas fa-eye text-3xl mb-3 opacity-50"></i>
                    <p>L'aperçu apparaîtra ici</p>
                </div>
            `;
            return;
        }

        const typeConfig = {
            info: { icon: 'fa-info-circle', color: 'bg-blue-500', text: 'Information' },
            warning: { icon: 'fa-exclamation-triangle', color: 'bg-orange-500', text: 'Avertissement' },
            urgent: { icon: 'fa-bell', color: 'bg-red-500', text: 'Urgent' },
            success: { icon: 'fa-check-circle', color: 'bg-green-500', text: 'Succès' }
        };

        const config = typeConfig[selectedType];
        const targetSpecialties = selectedSpecialties.size === 0 ? 'Aucune spécialité sélectionnée' : 
            Array.from(selectedSpecialties).map(id => 
                specialties.find(s => s.id === id)?.name || id
            ).join(', ');

        previewCard.innerHTML = `
            <div class="w-full ${isUrgent ? 'urgent-notification' : ''}">
                <div class="flex items-start space-x-4">
                    <div class="${config.color} w-12 h-12 rounded-xl flex items-center justify-center">
                        <i class="fas ${config.icon} text-white text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-gray-800">${title || 'Aucun Titre'}</h4>
                            <span class="px-2 py-1 ${config.color} text-white text-xs rounded-full">${config.text}</span>
                        </div>
                        <p class="text-gray-600 mt-2">${message || 'Aucun contenu de message'}</p>
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-500">
                                <strong>Cible:</strong> ${targetSpecialties}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                <strong>Urgent:</strong> ${isUrgent ? 'Oui' : 'Non'}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function sendNotification() {
        const title = document.getElementById('notificationTitle').value.trim();
        const message = document.getElementById('notificationMessage').value.trim();
        
        // Validation
        if (!title) {
            showError('Veuillez entrer un titre de notification');
            return;
        }
        
        if (!message) {
            showError('Veuillez entrer un message de notification');
            return;
        }
        
        if (selectedSpecialties.size === 0) {
            showError('Veuillez sélectionner au moins une spécialité');
            return;
        }

        // Obtenir les noms des spécialités sélectionnées
        const selectedNames = Array.from(selectedSpecialties).map(id => 
            specialties.find(s => s.id === id)?.name || id
        );

        // Afficher le modal de succès
        const successModal = document.getElementById('successModal');
        const successMessage = document.getElementById('successMessage');
        
        successMessage.textContent = `Votre notification a été envoyée avec succès à ${selectedNames.join(', ')}.`;
        successModal.classList.remove('hidden');

        // Réinitialiser le formulaire
        resetForm();
        
        // Ajouter aux notifications récentes
        addToRecentNotifications(title, selectedNames);
    }

    function showError(message) {
        // Affichage d'erreur simple - vous voudrez peut-être utiliser un système de notification plus sophistiqué
        alert(`Erreur: ${message}`);
    }

    function resetForm() {
        document.getElementById('notificationForm').reset();
        document.querySelectorAll('.notification-type-option').forEach(opt => 
            opt.classList.remove('selected'));
        document.querySelector('.notification-type-option[data-type="info"]').classList.add('selected');
        selectedSpecialties.clear();
        selectedType = 'info';
        updateCharacterCounters();
        showPreview();
    }

    function addToRecentNotifications(title, specialties) {
        recentNotifications.unshift({
            title: title,
            type: selectedType,
            specialties: specialties,
            time: 'À l\'instant'
        });
        
        // Garder seulement les 5 dernières notifications
        if (recentNotifications.length > 5) {
            recentNotifications.pop();
        }
        
        loadRecentNotifications();
        updateStats();
    }
</script>  
@endsection