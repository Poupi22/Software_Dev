<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SoftConstruction</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #e6f0ff 0%, #d9e8ff 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .login-container {
            display: flex;
            width: 100%;
            max-width: 1000px;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 40px -15px rgba(0, 0, 0, 0.1);
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
        
        .construction-icon {
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
            background: white;
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
        
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #6b7280;
            font-size: 14px;
        }
        
        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }
        
        .divider span {
            padding: 0 15px;
        }
        
        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .social-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .social-btn:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }
        
        .social-icon {
            font-size: 20px;
            color: #4b5563;
        }
        
        .register-link {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }
        
        .register-link a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .register-link a:hover {
            color: #2563eb;
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
            }
            
            .login-left {
                padding: 30px;
                text-align: center;
            }
            
            .construction-icon {
                font-size: 60px;
            }
        }
        
        @media (max-width: 480px) {
            .login-right {
                padding: 30px;
            }
            
            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-left">
            <i class="fas fa-hard-hat construction-icon"></i>
            <h2>Welcome to SoftConstruction</h2>
            <p>Access your dashboard to manage projects, track progress, and collaborate with your team.</p>
            <i class="fas fa-tools decoration"></i>
        </div>
        
        <div class="login-right">
            <div class="logo-container">
                <!-- Enterprise Logo -->
                <img src="{{ asset('home/assets/img/logo.jpg') }}" alt="SoftConstruction Logo" class="logo" onerror="this.style.display='none'; document.getElementById('logo-placeholder').style.display='flex';">
                <div id="logo-placeholder" style="width: 180px; height: 80px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 8px; margin: 0 auto 15px; display: none; align-items: center; justify-content: center;">
                    <i class="fas fa-hard-hat" style="font-size: 40px; color: white;"></i>
                </div>
                <div class="company-name">SOFTCONSTRUCTION</div>
            </div>
            
            <h2 class="form-title">Sign In to Your Account</h2>
            
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
                    <label for="email" class="input-label">Email Address</label>
                    <input id="email" type="email" class="input-field" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="input-group">
                    <label for="password" class="input-label">Password</label>
                    <input id="password" type="password" class="input-field" name="password" required autocomplete="current-password" placeholder="Enter your password">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="remember-forgot">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    
                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="login-button">Sign In</button>
            </form>
            
            <!-- <div class="divider"><span>Or continue with</span></div> -->
            
            <!-- <div class="social-login">
               
                @if(config('services.google.client_id'))
                <a href="{{ url('/auth/google') }}" class="social-btn">
                    <i class="fab fa-google social-icon"></i>
                </a>
                @endif
                
              
                @if(config('services.microsoft.client_id'))
                <a href="{{ url('/auth/microsoft') }}" class="social-btn">
                    <i class="fab fa-microsoft social-icon"></i>
                </a>
                @endif
                
                
                @if(config('services.apple.client_id'))
                <a href="{{ url('/auth/apple') }}" class="social-btn">
                    <i class="fab fa-apple social-icon"></i>
                </a>
                @endif
            </div> -->
            
            <!-- <div class="register-link">
                Don't have an account? 
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register now</a>
                @else
                    <a href="#">Contact administrator</a>
                @endif
            </div> -->
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