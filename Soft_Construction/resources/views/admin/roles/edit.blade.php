@extends('admin.layouts.app')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('admin/assets/css/edit.css') }}" rel="stylesheet">
<style>
    /* Additional CSS for role form */
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
    
    .permissions-container {
        margin-top: 16px;
    }
    
    .permissions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 12px;
        margin-top: 12px;
    }
    
    .permission-item {
        display: flex;
        align-items: center;
        padding: 12px;
        background: #f8fafc;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    
    .permission-item:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    
    .permission-item input[type="checkbox"] {
        margin-right: 12px;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    .permission-item label {
        margin-bottom: 0;
        cursor: pointer;
        flex: 1;
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
        
        .permissions-grid {
            grid-template-columns: 1fr;
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
                <h1><i class="fas fa-user-tag"></i> Edit Role</h1>
                <p>Update role details and permissions</p>
            </div>
            <a href="{{ route('admin.roles.index') }}" class="add-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Roles
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

            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Role Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $role->name) }}" placeholder="Enter role name" required>
                </div>

                <div class="form-group">
                    <label>Permissions</label>
                    <div class="permissions-container">
                        <p class="hint-text">Select the permissions to assign to this role</p>
                        
                        <div class="permissions-grid">
                            @foreach($permissions as $permission)
                                <div class="permission-item">
                                    <input type="checkbox" name="permissions[]" id="permission-{{ $permission->id }}" 
                                           value="{{ $permission->id }}" 
                                           {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'checked' : '' }}>
                                    <label for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($permissions->count() === 0)
                            <div class="empty-state">
                                <i class="fas fa-key"></i>
                                <p>No permissions available</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Update Role
                    </button>
                    <a href="{{ route('admin.roles.index') }}" class="btn-cancel">
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
                } else {
                    // Show loading state
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
                    submitBtn.disabled = true;
                    
                    // Revert after 5 seconds if form doesn't submit (as a fallback)
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }, 5000);
                }
            });
        }

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

        // Select all permissions functionality
        const selectAllContainer = document.createElement('div');
        selectAllContainer.className = 'select-all-container';
        selectAllContainer.innerHTML = `
            <div style="margin-bottom: 16px;">
                <input type="checkbox" id="select-all-permissions">
                <label for="select-all-permissions" style="font-weight: 600; cursor: pointer;">
                    Select All Permissions
                </label>
            </div>
        `;
        
        const permissionsContainer = document.querySelector('.permissions-container');
        if (permissionsContainer && document.querySelectorAll('.permission-item').length > 0) {
            permissionsContainer.insertBefore(selectAllContainer, permissionsContainer.firstChild);
            
            const selectAllCheckbox = document.getElementById('select-all-permissions');
            const permissionCheckboxes = document.querySelectorAll('.permission-item input[type="checkbox"]');
            
            // Select all functionality
            selectAllCheckbox.addEventListener('change', function() {
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
            
            // Update select all state when individual permissions change
            permissionCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(permissionCheckboxes).some(cb => cb.checked);
                    
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                });
            });
            
            // Set initial state
            const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(permissionCheckboxes).some(cb => cb.checked);
            
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = someChecked && !allChecked;
        }
    });
</script>
@endsection