@extends('admin_site.layouts.app')

@section('title', 'Profile Settings')

@section('content')
<style>
    .profile-container {
        margin-left: 250px; /* Match sidebar width */
        padding: 2rem;
        transition: all 0.3s;
    }

    @media (max-width: 1199.98px) {
        .profile-container {
            margin-left: 0;
            padding-top: 70px; /* Account for fixed header */
        }
    }

    .header {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(59, 130, 246, 0.1);
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .header h1 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 0.25rem;
    }

    .header p {
        color: #64748b;
        font-size: 1rem;
    }

    .cards-grid {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: 1fr;
    }

    .card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(59, 130, 246, 0.08);
        border: 1px solid #e2e8f0;
    }

    .card-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .card-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        color: white;
        font-size: 1.1rem;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e40af;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.4rem;
        font-weight: 500;
        color: #374151;
        font-size: 0.95rem;
    }

    .form-group input {
        width: 100%;
        padding: 0.65rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background: #f9fafb;
    }

    .form-group input:focus {
        outline: none;
        border-color: #3b82f6;
        background: white;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn {
        padding: 0.65rem 1.25rem;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #4b5563, #374151);
    }

    .danger-zone {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        margin-top: 1rem;
    }

    .danger-zone h4 {
        color: #475569;
        margin-bottom: 0.5rem;
        font-weight: 600;
        font-size: 1rem;
    }

    .danger-zone p {
        color: #64748b;
        margin-bottom: 1rem;
        line-height: 1.5;
        font-size: 0.9rem;
    }

    .success-message {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border: 1px solid #86efac;
        color: #166534;
        padding: 0.8rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .error-message {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        border: 1px solid #fca5a5;
        color: #b91c1c;
        padding: 0.8rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    @media (min-width: 768px) {
        .cards-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (min-width: 1024px) {
        .cards-grid {
            grid-template-columns: 1.5fr 1fr;
        }
        
        .card {
            padding: 1.25rem;
        }
    }

    @media (max-width: 767.98px) {
        .profile-container {
            padding: 1rem;
        }
        
        .header {
            padding: 1.25rem;
        }
        
        .header h1 {
            font-size: 1.5rem;
        }
    }
</style>

<div class="profile-container">
    <div class="header">
        <h1>Profile Settings</h1>
        <p>Manage your account information and preferences</p>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="success-message">
            Profile updated successfully!
        </div>
    @endif

    @if (session('status') === 'password-updated')
        <div class="success-message">
            Password updated successfully!
        </div>
    @endif

    <div class="cards-grid">
        <!-- Personal Information Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon">👤</div>
                <h3 class="card-title">Personal Information</h3>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>

        <!-- Password and Security -->
        <div>
            <!-- Password Card -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon">🔒</div>
                    <h3 class="card-title">Update Password</h3>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
                        @error('current_password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </div>

            <!-- Delete Account Card -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #6b7280, #4b5563);">⚠️</div>
                    <h3 class="card-title" style="color: #475569;">Danger Zone</h3>
                </div>
                
                <div class="danger-zone">
                    <h4>Delete Account</h4>
                    <p>Once you delete your account, there is no going back. Please be certain.</p>
                    
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required placeholder="Enter your password to confirm">
                            @error('password')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                            Delete Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection