@extends('admin.layouts.app')

@section('title', __('Profil - SoftConstruction'))

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        .profile-container {
            background: linear-gradient(135deg, #e6f0ff 0%, #d9e8ff 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .profile-card-container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 40px -15px rgba(0, 0, 0, 0.1);
        }
        
        .profile-sidebar {
            flex: 0 0 300px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .profile-icon {
            font-size: 80px;
            margin-bottom: 30px;
            color: white;
            opacity: 0.9;
            text-align: center;
        }
        
        .profile-sidebar h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .profile-sidebar p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
            text-align: center;
        }
        
        .profile-decoration {
            position: absolute;
            bottom: -30px;
            right: -30px;
            font-size: 200px;
            opacity: 0.05;
            transform: rotate(45deg);
        }
        
        .profile-content {
            flex: 1;
            padding: 40px;
        }
        
        .profile-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .profile-logo {
            width: 180px;
            height: auto;
            margin-bottom: 15px;
        }
        
        .profile-company {
            font-size: 24px;
            font-weight: 700;
            color: #2563eb;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        
        .profile-title {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 30px;
        }
        
        .form-section {
            margin-bottom: 40px;
            padding: 30px;
            border-radius: 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        
        .form-section-title {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-section-title i {
            color: #3b82f6;
        }
        
        .input-group {
            margin-bottom: 20px;
        }
        
        .input-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }
        
        .input-field {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        
        .error-message {
            color: #ef4444;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .form-button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
            margin-top: 10px;
        }
        
        .form-button:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
        }
        
        .form-button.delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 6px rgba(239, 68, 68, 0.2);
        }
        
        .form-button.delete:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            box-shadow: 0 6px 12px rgba(239, 68, 68, 0.3);
        }
        
        .status-message {
            padding: 12px 16px;
            background: #dcfce7;
            color: #16a34a;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        
        .status-message.error {
            background: #fee2e2;
            color: #dc2626;
        }
        
        .status-icon {
            margin-right: 10px;
        }
        
        @media (max-width: 900px) {
            .profile-card-container {
                flex-direction: column;
            }
            
            .profile-sidebar {
                flex: 1;
                padding: 30px;
                text-align: center;
            }
            
            .profile-icon {
                font-size: 60px;
            }
        }
        
        @media (max-width: 480px) {
            .profile-content {
                padding: 20px;
            }
            
            .form-section {
                padding: 20px;
            }
        }
    </style>

    <div class="profile-container">
        <div class="profile-card-container">
            <div class="profile-sidebar">
                <i class="fas fa-user-circle profile-icon"></i>
                <h2>Gestion du Profil</h2>
                <p>Mettez à jour vos informations personnelles, modifiez votre mot de passe et gérez vos paramètres de compte.</p>
                <i class="fas fa-cog profile-decoration"></i>
            </div>
            
            <div class="profile-content">
                <div class="profile-header">
                    <div class="profile-company">SOFTCONSTRUCTION</div>
                    <h2 class="profile-title">Votre Profil</h2>
                </div>
                
                <!-- Update Profile Information Form -->
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-user-edit"></i> Informations du Profil
                    </h3>
                    
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')
                        
                        <div class="input-group">
                            <label for="name" class="input-label">Nom</label>
                            <input id="name" name="name" type="text" class="input-field" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @error('name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="input-group">
                            <label for="email" class="input-label">Email</label>
                            <input id="email" name="email" type="email" class="input-field" value="{{ old('email', $user->email) }}" required autocomplete="email">
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="form-button">Enregistrer les modifications</button>
                    </form>
                </div>
                
                <!-- Update Password Form -->
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-lock"></i> Mise à jour du Mot de Passe
                    </h3>
                    
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')
                        
                        <div class="input-group">
                            <label for="current_password" class="input-label">Mot de passe actuel</label>
                            <input id="current_password" name="current_password" type="password" class="input-field" autocomplete="current-password">
                            @error('current_password')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="input-group">
                            <label for="password" class="input-label">Nouveau mot de passe</label>
                            <input id="password" name="password" type="password" class="input-field" autocomplete="new-password">
                            @error('password')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="input-group">
                            <label for="password_confirmation" class="input-label">Confirmer le mot de passe</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="input-field" autocomplete="new-password">
                            @error('password_confirmation')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="form-button">Mettre à jour le mot de passe</button>
                    </form>
                </div>
                
                <!-- Delete User Form -->
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-trash-alt"></i> Supprimer le Compte
                    </h3>
                    
                    <p class="input-label">Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.</p>
                    
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')
                        
                        <div class="input-group">
                            <label for="password" class="input-label">Mot de passe</label>
                            <input id="delete_password" name="password" type="password" class="input-field" placeholder="Entrez votre mot de passe pour confirmer">
                            @error('password')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="form-button delete">Supprimer le compte</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Simple animation for input fields
        document.querySelectorAll('.input-field').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (this.value === '') {
                    this.parentElement.classList.remove('focused');
                }
            });
        });
        
        // Check if there are validation errors to display status message
        document.addEventListener('DOMContentLoaded', function() {
            const validationErrors = document.querySelectorAll('.error-message');
            validationErrors.forEach(error => {
                if (error.textContent.trim() !== '') {
                    error.style.display = 'block';
                }
            });
        });
    </script>
@endsection