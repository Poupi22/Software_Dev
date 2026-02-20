@extends('admin.layouts.app')
@section('content')
<link href="{{ asset('admin/assets/css/create.css') }}" rel="stylesheet">
<style>
    /* Additional CSS for permission form */
    .form-grid {
        display: grid;
        gap: 24px;
    }
    
    .form-group {
        position: relative;
        margin-bottom: 24px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #374151;
    }
    
    .form-control {
        width: 100%;
        padding: 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    .btn-submit {
        background: linear-gradient(120deg, #4f46e5, #7c3aed);
        color: white;
        border: none;
        padding: 16px 32px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }
    
    .btn-submit i {
        margin-right: 8px;
    }
    
    .btn-cancel {
        background: #f3f4f6;
        color: #374151;
        border: none;
        padding: 16px 32px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        margin-left: 12px;
    }
    
    .btn-cancel:hover {
        background: #e5e7eb;
    }
    
    .btn-cancel i {
        margin-right: 8px;
    }
    
    /* Error Styles */
    .error-container {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
    }
    
    .error-container ul {
        list-style: none;
        color: #dc2626;
    }
    
    .error-container li {
        margin-bottom: 4px;
        display: flex;
        align-items: center;
    }
    
    .error-container li:before {
        content: "•";
        color: #dc2626;
        font-weight: bold;
        margin-right: 8px;
    }
    
    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-start;
        margin-top: 32px;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column;
            gap: 12px;
        }
        
        .btn-cancel {
            margin-left: 0;
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
    <div class="header">
        <div class="header-content">
            <div>
                <h1><i class="fas fa-key"></i> Create Permission</h1>
                <p>Add a new permission to your system</p>
            </div>
            <a href="{{ route('admin.permissions.index') }}" class="add-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Permissions
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="card">
            @if ($errors->any())
                <div class="error-container">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="name">Permission Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Enter permission name" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Create Permission
                    </button>
                    <a href="{{ route('admin.permissions.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Validate required fields
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                        
                        // Add error message if not exists
                        if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('error-message')) {
                            const error = document.createElement('div');
                            error.className = 'error-message';
                            error.textContent = 'This field is required';
                            error.style.color = '#dc2626';
                            error.style.marginTop = '8px';
                            error.style.fontSize = '14px';
                            field.parentNode.insertBefore(error, field.nextSibling);
                        }
                    } else {
                        field.classList.remove('is-invalid');
                        const error = field.nextElementSibling;
                        if (error && error.classList.contains('error-message')) {
                            error.remove();
                        }
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    
                    // Scroll to first error
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        }
    });
</script>
@endsection