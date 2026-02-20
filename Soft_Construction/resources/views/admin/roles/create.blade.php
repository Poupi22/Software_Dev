@extends('admin.layouts.app')

@section('title', 'Créer un Rôle - SoftConstruction')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Base Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    /* Animated Background Particles */
    .particles {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 1;
    }

    .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: linear-gradient(45deg, #3b82f6, #10b981);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
        opacity: 0.3;
    }

    .particle:nth-child(1) { top: 20%; left: 20%; animation-delay: 0s; }
    .particle:nth-child(2) { top: 60%; left: 80%; animation-delay: 2s; }
    .particle:nth-child(3) { top: 40%; left: 60%; animation-delay: 4s; }
    .particle:nth-child(4) { top: 80%; left: 30%; animation-delay: 1s; }
    .particle:nth-child(5) { top: 10%; left: 70%; animation-delay: 3s; }

    @keyframes float {
        0%, 100% { transform: translateY(0px) scale(1); }
        50% { transform: translateY(-20px) scale(1.1); }
    }

    /* Container Styles */
    .container-fluid {
        padding: 1.25rem;
        position: relative;
        z-index: 2;
    }

    /* Header Section */
    .header-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        animation: fadeInUp 0.8s ease-out;
    }

    .header-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .header-title i {
        color: #3b82f6;
    }

    /* Form Card */
    .form-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid #e2e8f0;
        animation: fadeInUp 0.8s ease-out 0.2s both;
        max-width: 600px;
        margin: 0 auto;
    }

    .form-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    /* Form Styling */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.9rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 1rem;
        background: #f9fafb;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        background: #ffffff;
    }

    .form-input:hover {
        border-color: #93c5fd;
    }

    /* Checkbox Styling */
    .permissions-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        max-height: 200px;
        overflow-y: auto;
        padding: 0.5rem;
        background: #f9fafb;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .permission-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: #374151;
    }

    .permission-checkbox input[type="checkbox"] {
        accent-color: #10b981;
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .permission-checkbox input[type="checkbox"]:hover {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }

    /* Error Messages */
    .error-message {
        color: #ef4444;
        font-size: 0.8rem;
        margin-top: 0.5rem;
        display: block;
    }

    .alert-error {
        background: #fee2e2;
        color: #b91c1c;
        padding: 0.75rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: fadeInUp 0.5s ease-out;
    }

    .alert-error i {
        color: #b91c1c;
    }

    /* Buttons */
    .action-button {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
    }

    .action-button:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
    }

    .action-button.back {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        box-shadow: 0 4px 6px rgba(107, 114, 128, 0.2);
    }

    .action-button.back:hover {
        background: linear-gradient(135deg, #4b5563, #374151);
        box-shadow: 0 6px 12px rgba(107, 114, 128, 0.3);
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0.75rem;
        }

        .header-section {
            padding: 1rem;
        }

        .header-title {
            font-size: 1.3rem;
        }

        .form-card {
            padding: 1rem;
        }

        .form-input {
            padding: 0.8rem;
            font-size: 0.9rem;
        }

        .action-button {
            padding: 0.7rem 1.25rem;
            font-size: 0.9rem;
        }

        .permission-checkbox {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 576px) {
        .header-title {
            font-size: 1.2rem;
        }

        .form-card {
            padding: 0.75rem;
        }

        .form-input {
            padding: 0.7rem;
            font-size: 0.85rem;
        }

        .action-button {
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
        }

        .particle {
            width: 3px;
            height: 3px;
        }
    }
</style>

<!-- Animated Background Particles -->
<div class="particles">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
</div>

<div class="container-fluid">
    <!-- Header -->
    <div class="header-section">
        <h1 class="header-title"><i class="fas fa-user-tag"></i> Créer un Nouveau Rôle</h1>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        @if ($errors->any())
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label"><i class="fas fa-user-tag"></i> Nom du Rôle</label>
                <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fas fa-key"></i> Permissions</label>
                <div class="permissions-list">
                    @foreach($permissions as $permission)
                        <label class="permission-checkbox">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}">
                            <span>{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('permissions')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <a href="{{ route('admin.roles.index') }}" class="action-button back">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
                <button type="submit" class="action-button">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add fade-in animation to form card
        const formCard = document.querySelector('.form-card');
        formCard.classList.add('fadeInUp');

        // Ripple effect for buttons
        const buttons = document.querySelectorAll('.action-button');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('div');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.left = '50%';
                ripple.style.top = '50%';
                ripple.style.marginLeft = '-20px';
                ripple.style.marginTop = '-20px';
                ripple.style.width = '40px';
                ripple.style.height = '40px';
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });

        // Parallax effect for particles
        document.addEventListener('mousemove', function(e) {
            const particles = document.querySelectorAll('.particle');
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;

            particles.forEach((particle, index) => {
                const speed = (index + 1) * 0.5;
                particle.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endsection