<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Gestion Devis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; }
        .tab-button.active {
            background: #EFF6FF;
            color: #2563EB;
            border-left: 3px solid #2563EB;
        }
        @media (max-width: 768px) {
            .mobile-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }
            .content-with-mobile-nav { padding-bottom: 80px; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Mobile Navigation -->
    <div class="mobile-nav md:hidden">
        <div class="grid grid-cols-5 gap-1 px-2 py-2">
            <a href="#" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <span class="material-icons text-2xl">dashboard</span>
                <span class="text-xs">Accueil</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <span class="material-icons text-2xl">description</span>
                <span class="text-xs">Devis</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <span class="material-icons text-2xl">receipt</span>
                <span class="text-xs">Factures</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-1 py-2 text-gray-500">
                <span class="material-icons text-2xl">people</span>
                <span class="text-xs">Clients</span>
            </a>
            <button class="flex flex-col items-center gap-1 py-2 text-blue-600">
                <span class="material-icons text-2xl">account_circle</span>
                <span class="text-xs font-medium">Profil</span>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="min-h-screen">
        <!-- Top bar -->
        <div class="bg-white border-b border-gray-200 px-4 md:px-8 py-3 md:py-4 sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button onclick="history.back()" class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg">
                        <span class="material-icons">arrow_back</span>
                    </button>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-gray-800">Mon Profil</h2>
                        <p class="text-xs md:text-sm text-gray-500">Gérez vos informations personnelles</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-with-mobile-nav p-4 md:p-8">
            <div class="max-w-6xl mx-auto">
                <!-- Profile Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl shadow-lg p-6 md:p-8 mb-6 text-white">
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        <div class="relative">
                            <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-full flex items-center justify-center shadow-lg">
                                <span class="material-icons text-blue-600" style="font-size: 64px;">account_circle</span>
                            </div>
                            <button class="absolute bottom-0 right-0 w-10 h-10 bg-white text-blue-600 rounded-full flex items-center justify-center shadow-lg hover:bg-gray-100">
                                <span class="material-icons">photo_camera</span>
                            </button>
                        </div>
                        <div class="text-center md:text-left flex-1">
                            <h1 class="text-2xl md:text-3xl font-bold mb-2">Jean Dupont</h1>
                            <p class="text-blue-100 mb-2">admin@entreprise.cm</p>
                            <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                                <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">Administrateur</span>
                                <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">Actif</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-blue-100 mb-1">Membre depuis</p>
                            <p class="text-lg font-bold">Janvier 2024</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <span class="material-icons text-blue-600">description</span>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800">156</p>
                                <p class="text-xs text-gray-500">Devis créés</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <span class="material-icons text-green-600">receipt</span>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800">98</p>
                                <p class="text-xs text-gray-500">Factures émises</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <span class="material-icons text-purple-600">people</span>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800">45</p>
                                <p class="text-xs text-gray-500">Clients ajoutés</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                <span class="material-icons text-orange-600">schedule</span>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-800">234h</p>
                                <p class="text-xs text-gray-500">Temps actif</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Sidebar Tabs -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-xl shadow-sm p-2 space-y-1">
                            <button onclick="showTab('informations')" class="tab-button active w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left transition-all">
                                <span class="material-icons text-xl">person</span>
                                <span class="font-medium">Informations</span>
                            </button>
                            <button onclick="showTab('securite')" class="tab-button w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left text-gray-600 hover:bg-gray-50 transition-all">
                                <span class="material-icons text-xl">lock</span>
                                <span class="font-medium">Sécurité</span>
                            </button>
                            <button onclick="showTab('preferences')" class="tab-button w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left text-gray-600 hover:bg-gray-50 transition-all">
                                <span class="material-icons text-xl">tune</span>
                                <span class="font-medium">Préférences</span>
                            </button>
                            <button onclick="showTab('activite')" class="tab-button w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left text-gray-600 hover:bg-gray-50 transition-all">
                                <span class="material-icons text-xl">history</span>
                                <span class="font-medium">Activité</span>
                            </button>
                            <button onclick="showTab('notifications')" class="tab-button w-full flex items-center gap-3 px-4 py-3 rounded-lg text-left text-gray-600 hover:bg-gray-50 transition-all">
                                <span class="material-icons text-xl">notifications</span>
                                <span class="font-medium">Notifications</span>
                            </button>
                        </div>
                    </div>

                    <!-- Content Area -->
                    <div class="lg:col-span-3">
                        <!-- Tab: Informations -->
                        <div id="tab-informations" class="tab-content">
                            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                                <h3 class="text-2xl font-bold text-gray-800 mb-6">Informations personnelles</h3>
                                
                                <form class="space-y-6">
                                    <!-- Photo de profil -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-3">Photo de profil</label>
                                        <div class="flex items-center gap-6">
                                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="material-icons text-blue-600 text-4xl">account_circle</span>
                                            </div>
                                            <div>
                                                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium mb-2">
                                                    Changer la photo
                                                </button>
                                                <p class="text-sm text-gray-500">Format JPG ou PNG, max 2 MB</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Nom et Prénom -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                                            <input type="text" value="Dupont" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                                            <input type="text" value="Jean" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                        </div>
                                    </div>

                                    <!-- Email et Téléphone -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                            <input type="email" value="admin@entreprise.cm" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                                            <input type="tel" value="+237 6 XX XX XX XX" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                        </div>
                                    </div>

                                    <!-- Fonction et Département -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Fonction</label>
                                            <input type="text" value="Administrateur Système" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Département</label>
                                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                                <option>Administration</option>
                                                <option>Commercial</option>
                                                <option>Comptabilité</option>
                                                <option>Direction</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Adresse -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                                        <input type="text" value="Douala, Cameroun" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                    </div>

                                    <!-- Bio -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Biographie</label>
                                        <textarea rows="4" placeholder="Parlez-nous de vous..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"></textarea>
                                    </div>

                                    <!-- Save Button -->
                                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                                        <button type="button" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                                            Annuler
                                        </button>
                                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 flex items-center gap-2">
                                            <span class="material-icons">save</span>
                                            <span>Enregistrer</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Tab: Sécurité -->
                        <div id="tab-securite" class="tab-content hidden">
                            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                                <h3 class="text-2xl font-bold text-gray-800 mb-6">Sécurité du compte</h3>
                                
                                <!-- Changer mot de passe -->
                                <div class="mb-8">
                                    <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                        <span class="material-icons text-blue-600">lock</span>
                                        <span>Changer le mot de passe</span>
                                    </h4>
                                    <form class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe actuel *</label>
                                            <input type="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nouveau mot de passe *</label>
                                            <input type="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                            <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères avec majuscules, minuscules et chiffres</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer le nouveau mot de passe *</label>
                                            <input type="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                        </div>
                                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                                            Mettre à jour le mot de passe
                                        </button>
                                    </form>
                                </div>

                                <!-- Authentification à deux facteurs -->
                                <div class="mb-8 p-6 border border-gray-200 rounded-xl">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h4 class="font-bold text-gray-800 mb-2">Authentification à deux facteurs (2FA)</h4>
                                            <p class="text-sm text-gray-600">Ajoutez une couche de sécurité supplémentaire à votre compte</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                    <p class="text-sm text-gray-500">Statut : <span class="text-red-600 font-medium">Désactivée</span></p>
                                </div>

                                <!-- Sessions actives -->
                                <div>
                                    <h4 class="font-bold text-gray-800 mb-4">Sessions actives</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                                    <span class="material-icons text-green-600">computer</span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-800">Windows • Chrome</p>
                                                    <p class="text-sm text-gray-500">Douala, Cameroun • Session actuelle</p>
                                                    <p class="text-xs text-gray-400">Dernière activité : maintenant</p>
                                                </div>
                                            </div>
                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Actif</span>
                                        </div>
                                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <span class="material-icons text-gray-600">phone_android</span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-800">Android • Chrome Mobile</p>
                                                    <p class="text-sm text-gray-500">Douala, Cameroun</p>
                                                    <p class="text-xs text-gray-400">Dernière activité : il y a 2 heures</p>
                                                </div>
                                            </div>
                                            <button class="text-red-600 hover:text-red-800 text-sm font-medium">Révoquer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Préférences -->
                        <div id="tab-preferences" class="tab-content hidden">
                            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                                <h3 class="text-2xl font-bold text-gray-800 mb-6">Préférences</h3>
                                
                                <div class="space-y-6">
                                    <!-- Langue -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Langue</label>
                                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                            <option selected>Français</option>
                                            <option>English</option>
                                            <option>Español</option>
                                        </select>
                                    </div>

                                    <!-- Fuseau horaire -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Fuseau horaire</label>
                                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                            <option selected>Africa/Douala (GMT+1)</option>
                                            <option>Europe/Paris (GMT+1)</option>
                                            <option>America/New_York (GMT-5)</option>
                                        </select>
                                    </div>

                                    <!-- Format de date -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Format de date</label>
                                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                            <option>DD/MM/YYYY</option>
                                            <option>MM/DD/YYYY</option>
                                            <option>YYYY-MM-DD</option>
                                        </select>
                                    </div>

                                    <!-- Format de devise -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Devise par défaut</label>
                                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                                            <option selected>FCFA - Franc CFA</option>
                                            <option>EUR - Euro</option>
                                            <option>USD - Dollar</option>
                                        </select>
                                    </div>

                                    <!-- Save Button -->
                                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                                        <button type="button" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 flex items-center gap-2">
                                            <span class="material-icons">save</span>
                                            <span>Enregistrer</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab: Activité -->
                        <div id="tab-activite" class="tab-content hidden">
                            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                                <h3 class="text-2xl font-bold text-gray-800 mb-6">Activité récente</h3>
                                
                                <div class="space-y-4">
                                    <div class="flex gap-4 p-4 hover:bg-gray-50 rounded-lg">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                                <span class="material-icons text-green-600">check_circle</span>
                                            </div>
                                            <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800">Facture finalisée</p>
                                            <p class="text-sm text-gray-600">FACT-2026-015 pour Kamga SA</p>
                                            <p class="text-xs text-gray-500">Il y a 2 heures</p>
                                        </div>
                                    </div>

                                    <div class="flex gap-4 p-4 hover:bg-gray-50 rounded-lg">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="material-icons text-blue-600">description</span>
                                            </div>
                                            <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800">Devis créé</p>
                                            <p class="text-sm text-gray-600">DEV-2026-042 pour Dupont Travaux</p>
                                            <p class="text-xs text-gray-500">Il y a 5 heures</p>
                                        </div>
                                    </div>

                                    <div class="flex gap-4 p-4 hover:bg-gray-50 rounded-lg">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                <span class="material-icons text-purple-600">person_add</span>
                                            </div>
                                            <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800">Nouveau client ajouté</p>
                                            <p class="text-sm text-gray-600">Martin Jean (Particulier)</p>
                                            <p class="text-xs text-gray-500">Hier à 14:30</p>
                                        </div>
                                    </div>

                                    <div class="flex gap-4 p-4 hover:bg-gray-50 rounded-lg">
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                                <span class="material-icons text-orange-600">edit</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-800">Devis modifié</p>
                                            <p class="text-sm text-gray-600">DEV-PROV-2026-038</p>
                                            <p class="text-xs text-gray-500">Hier à 10:15</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                        <!-- Tab: Notifications -->
                        <div id="tab-notifications" class="tab-content hidden">
                            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
                                <h3 class="text-2xl font-bold text-gray-800 mb-6">Paramètres de notification</h3>
                                
                                <div class="space-y-6">
                                    <!-- Notifications par email -->
                                    <div class="p-4 border border-gray-200 rounded-xl">
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                <h4 class="font-bold text-gray-800 mb-2">Notifications par email</h4>
                                                <p class="text-sm text-gray-600">Recevez des notifications importantes par email</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="sr-only peer" checked>
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                            </label>
                                        </div>
                                        <p class="text-sm text-gray-500">Vous recevrez des alertes pour les activités importantes de votre compte.</p>
                                    </div>
                                    <!-- Notifications push -->
                                    <div class="p-4 border border-gray-200 rounded-xl">
                                        <div class="flex items-center justify-between mb-4">
                                            <div>
                                                <h4 class="font-bold text-gray-800 mb-2">Notifications push</h4>
                                                <p class="text-sm text-gray-600">Recevez des notifications directement sur votre appareil</p>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="sr-only peer">
                                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                            </label>
                                        </div>
                                        <p class="text-sm text-gray-500">Activez les notifications push pour rester informé en temps réel.</p>
                                    </div>
                                    <!-- Save Button -->
                                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                                        <button type="button" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 flex items-center gap-2">
                                            <span class="material-icons">save</span>
                                            <span>Enregistrer</span>
                                        </button>   
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- End Main Content -->
        <!-- Sidebar Overlay for Mobile -->
        <div id="sidebarOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40" onclick="toggleSidebar()"></div>
    </div>
    <!-- End Sidebar -->
    <div class="sidebar fixed inset-y-0 left-0 w-64 bg-white shadow-md z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300">
        <div class="h-full flex flex-col justify-between">
            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="nav-item active flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium">
                    <span class="material-icons text-xl">dashboard</span>
                    <span>Tableau de bord</span>
                </a>
                <a href="{{ route('devi.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                    <span class="material-icons text-xl">description</span>
                    <span>Devis</span>
                </a>
                <a href="{{ route('facture.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                    <span class="material-icons text-xl">receipt</span>
                    <span>Factures</span>
                </a>
                <a href="{{ route('client.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                    <span class="material-icons text-xl">people</span>
                    <span>Clients</span>
                </a>
                <a href="{{ route('pv.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                    <span class="material-icons text-xl">verified</span>
                    <span>PV Réception</span>
                </a>
                <a href="{{ route('articles.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                    <span class="material-icons text-xl">inventory_2</span>
                    <span>Articles</span>
                </a>
                <a href="{{ route('categorie.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                    <span class="material-icons text-xl">category</span>
                    <span>Catégories</span>
                </a>
                <a href="{{ route('prospect.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                    <span class="material-icons text-xl">person_add</span>
                    <span>Prospects</span>
                </a>
                <a href="{{ route('parametre.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50">
                    <span class="material-icons text-xl">settings</span>
                    <span>Paramètres</span>
                </a>
            </nav>
            <div class="p-4 border-t border-gray-200">
                <a href="{{ route('admin.profil') }}" class="flex items-center gap-3 hover:bg-gray-50 p-2 rounded-lg">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="material-icons text-blue-600 text-3xl">account_circle</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">Jean Dupont</p>
                        <p class="text-sm text-gray-500">Administrateur</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- End Sidebar -->
    <script>
        // Function to show the selected tab and hide others
        function showTab(tabId) {
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => {
                if (tab.id === 'tab-' + tabId) {
                    tab.classList.remove('hidden');
                } else {
                    tab.classList.add('hidden');
                }
            });
        }

        // Sidebar toggle for mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>
</html>