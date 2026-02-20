@extends('admin.layouts.app')
@section('content')
<link href="{{ asset('admin/assets/css/create.css') }}" rel="stylesheet">

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
                <h1><i class="fas fa-plus-circle"></i> Create New Service</h1>
                <p>Add a new service to your offerings</p>
            </div>
            <a href="{{ route('admin.services.index') }}" class="add-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Services
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="card">
            <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data" class="slide-form">
                @csrf

                <div class="form-grid">
                    <!-- Title Group -->
                    <div class="form-group floating">
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder=" " required>
                        <label for="title">Title</label>
                        <div class="highlight"></div>
                    </div>

                    <!-- Short Description Group -->
                    <div class="form-group floating">
                        <textarea class="form-control" id="short_description" name="short_description" placeholder=" Short Description" required>{{ old('short_description') }}</textarea>
                        <label for="short_description"></label>
                        <div class="highlight"></div>
                    </div>

                    <!-- Order Group -->
                    <div class="form-group floating">
                        <input type="number" class="form-control" id="order" name="order" value="{{ old('order', 0) }}" placeholder=" ">
                        <label for="order">Display Order</label>
                        <div class="highlight"></div>
                    </div>

                    <!-- Status Toggle -->
                    <div class="form-group switch-container">
                        <label class="switch">
                            <input type="checkbox" id="active" name="active" value="1" {{ old('active', true) ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                        <span class="switch-label">Active Service</span>
                    </div>

                    <!-- Icon Upload -->
                    <div class="form-group file-upload">
                        <label for="icon">Service Icon</label>
                        <div class="upload-area" id="dropZoneIcon">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drag & drop your icon here or click to browse</p>
                            <input type="file" id="icon" name="icon" accept="image/*">
                            <div class="preview-container" id="previewContainerIcon"></div>
                        </div>
                        <small class="hint">Recommended size: 100×100 pixels (square)</small>
                    </div>

                    <!-- Long Description Group -->
                    <div class="form-group full-width">
                        <label for="long_description">Long Description</label>
                        <textarea class="form-control" id="long_description" name="long_description" rows="5">{{ old('long_description') }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-plus-circle"></i> Create Service
                    </button>
                    <a href="{{ route('admin.services.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('admin/assets/js/create.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize file upload preview
        setupFileUpload('icon', 'dropZoneIcon', 'previewContainerIcon');
        
        // Add drag and drop functionality
        setupDragAndDrop('dropZoneIcon');

        // Add floating label functionality
        const floatingInputs = document.querySelectorAll('.form-group.floating input, .form-group.floating textarea');
        floatingInputs.forEach(input => {
            // Trigger focus event if there's already a value (like after form validation error)
            if (input.value) {
                input.dispatchEvent(new Event('focus'));
            }
            
            // For textareas, we need to handle the label differently
            if (input.tagName === 'TEXTAREA') {
                input.addEventListener('input', function() {
                    if (this.value) {
                        this.nextElementSibling.classList.add('active');
                    } else {
                        this.nextElementSibling.classList.remove('active');
                    }
                });
            }
        });

        // Form validation
        const form = document.querySelector('.slide-form');
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