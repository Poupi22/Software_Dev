<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - 3IA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            /* Removed overflow: hidden to allow scrolling */
        }

        /* Floating background elements */
        .bg-shape {
            position: fixed;
            border-radius: 50%;
            opacity: 0.1;
            z-index: -1;
            animation: float 6s ease-in-out infinite;
        }

        .bg-shape-1 { top: 10%; left: 10%; width: 300px; height: 300px; background: #2563eb; animation-delay: 0s; }
        .bg-shape-2 { top: 60%; right: 15%; width: 200px; height: 200px; background: #1e40af; animation-delay: 2s; }
        .bg-shape-3 { bottom: 20%; left: 20%; width: 250px; height: 250px; background: #3b82f6; animation-delay: 4s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .login-container {
            display: flex;
            width: 100%;
            max-width: 1000px;
            max-height: 90vh; /* Limit height to allow scrolling */
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .ai-icon {
            font-size: 80px;
            margin-bottom: 30px;
            color: white;
            opacity: 0.9;
        }

        .login-left h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .login-left p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
        }

        .decoration {
            position: absolute;
            bottom: -30px;
            right: -30px;
            font-size: 200px;
            opacity: 0.05;
            transform: rotate(45deg);
        }

        .login-right {
            flex: 1;
            padding: 50px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            overflow-y: auto; /* Enable vertical scrolling */
        }

        .logo-container {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            width: 180px;
            height: auto;
            margin-bottom: 15px;
        }

        .company-name {
            font-size: 24px;
            font-weight: 700;
            color: #2563eb;
            letter-spacing: 0.5px;
        }

        .form-title {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 30px;
            text-align: center;
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
            background: rgba(255, 255, 255, 0.8);
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

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }

        .remember {
            display: flex;
            align-items: center;
        }

        .remember input {
            margin-right: 8px;
            accent-color: #3b82f6;
        }

        .forgot-link {
            color: #3b82f6;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #2563eb;
        }

        .login-button {
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
        }

        .login-button:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
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
            .login-container {
                flex-direction: column;
                max-width: 450px;
                max-height: 85vh; /* Adjusted for mobile */
            }

            .login-left {
                padding: 30px;
                text-align: center;
            }

            .ai-icon {
                font-size: 60px;
            }
            
            .login-right {
                padding: 30px;
                overflow-y: auto; /* Ensure scrolling on mobile */
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px; /* Reduced padding on very small screens */
            }
            
            .login-container {
                max-height: 95vh; /* Use more screen space on mobile */
            }
            
            .login-right {
                padding: 25px;
            }

            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .login-left {
                padding: 25px;
            }
        }

        /* Custom animations */
        .slide-up {
            transform: translateY(50px);
            opacity: 0;
            animation: slideUp 0.8s ease forwards;
        }

        @keyframes slideUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .fade-in {
            opacity: 0;
            animation: fadeIn 0.6s ease forwards;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        /* Hover effects */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>
    <!-- Background Shapes -->
    <div class="bg-shape bg-shape-1"></div>
    <div class="bg-shape bg-shape-2"></div>
    <div class="bg-shape bg-shape-3"></div>

    <div class="login-container slide-up">
        <div class="login-left">
            <i class="fas fa-brain ai-icon"></i>
            <h2>Bienvenue à 3IA</h2>
            <p>Accédez à votre tableau de bord pour gérer vos cours, suivre votre progression et interagir avec la communauté.</p>
            <i class="fas fa-microchip decoration"></i>
        </div>

        <div class="login-right">
            <div class="logo-container">
                <!-- 3IA Logo -->
                <img src="{{ asset('admin/assets/images/3ia.png') }}" alt="3IA Logo" class="logo" onerror="this.style.display='none'; document.getElementById('logo-placeholder').style.display='flex';">
                <div id="logo-placeholder" style="width: 180px; height: 80px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 8px; margin: 0 auto 15px; display: none; align-items: center; justify-content: center;">
                    <i class="fas fa-brain" style="font-size: 40px; color: white;"></i>
                </div>
                <div class="company-name">3IA INSTITUTE</div>
            </div>

            <h2 class="form-title">Connectez-vous à votre compte</h2>

            <!-- Session Status -->
            @if(session('status'))
            <div class="status-message">
                <i class="fas fa-info-circle status-icon"></i>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
            <div class="status-message error">
                <i class="fas fa-exclamation-circle status-icon"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="input-group">
                    <label for="email" class="input-label">Adresse Email</label>
                    <input id="email" type="email" class="input-field" name="email" value="{{ old('email') }}" required autofocus placeholder="Entrez votre email">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="input-group">
                    <label for="password" class="input-label">Mot de passe</label>
                    <input id="password" type="password" class="input-field" name="password" required autocomplete="current-password" placeholder="Entrez votre mot de passe">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="remember-forgot">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        <span>Se souvenir de moi</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            Mot de passe oublié?
                        </a>
                    @endif
                </div>

                <button type="submit" class="login-button">Se connecter</button>
            </form>
        </div>
    </div>

    <script>
        // GSAP animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate elements on load
            gsap.from('.slide-up', {
                duration: 0.8,
                y: 50,
                opacity: 0,
                stagger: 0.2,
                ease: "power2.out"
            });

            // Floating background shapes animation
            gsap.to('.bg-shape-1', {
                duration: 6,
                y: -20,
                rotation: 180,
                yoyo: true,
                repeat: -1,
                ease: "power2.inOut"
            });

            gsap.to('.bg-shape-2', {
                duration: 8,
                y: -30,
                rotation: -180,
                yoyo: true,
                repeat: -1,
                ease: "power2.inOut",
                delay: 2
            });

            gsap.to('.bg-shape-3', {
                duration: 7,
                y: -25,
                rotation: 360,
                yoyo: true,
                repeat: -1,
                ease: "power2.inOut",
                delay: 4
            });

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
            const validationErrors = document.querySelector('.status-message.error');
            const sessionStatus = document.querySelector('.status-message:not(.error)');

            if (validationErrors && validationErrors.textContent.trim() !== '') {
                validationErrors.style.display = 'flex';
            }

            if (sessionStatus && sessionStatus.textContent.trim() !== '') {
                sessionStatus.style.display = 'flex';
            }
        });
    </script>
</body>
</html>