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
                <h1><i class="fas fa-dumbbell"></i> Create New Training Program</h1>
                <p>Add a new training program to your offerings</p>
            </div>
            <a href="{{ route('admin.trainings.index') }}" class="add-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Trainings
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="card">
            <form action="{{ route('admin.trainings.store') }}" method="POST" enctype="multipart/form-data" class="training-form">
                @csrf

                <div class="form-grid">
                    <!-- Title Group -->
                    <div class="form-group floating">
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder=" " required>
                        <label for="title">Training Title</label>
                        <div class="highlight"></div>
                    </div>

                    <!-- Duration Group -->
                    <div class="form-group floating">
                        <input type="text" class="form-control" id="duration" name="duration" value="{{ old('duration') }}" placeholder=" " required>
                        <label for="duration">Duration</label>
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
                            <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="switch-label">Active Training</span>
                    </div>

                    <!-- Description -->
                    <div class="form-group full-width">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                    </div>

                    <!-- Image Upload -->
                    <div class="form-group file-upload full-width">
                        <label for="image">Training Image</label>
                        <div class="upload-area" id="dropZone">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drag & drop your image here or click to browse</p>
                            <input type="file" id="image" name="image" accept="image/*">
                            <div class="preview-container" id="previewContainer"></div>
                        </div>
                        <small class="hint">Recommended size: 800×600 pixels (4:3 ratio)</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-plus-circle"></i> Create Training
                    </button>
                    <a href="{{ route('admin.trainings.index') }}" class="btn-cancel">
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
    setupFileUpload('image', 'dropZone', 'previewContainer');

    // Add floating label functionality
    const floatingInputs = document.querySelectorAll('.form-group.floating input');
    floatingInputs.forEach(input => {
        // Trigger focus event if there's already a value (like after form validation error)
        if (input.value) {
            input.dispatchEvent(new Event('focus'));
        }
        
        input.addEventListener('focus', function() {
            this.parentNode.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentNode.classList.remove('focused');
            }
        });
    });

    // Add drag and drop functionality
    setupDragAndDrop('dropZone');
});

function setupFileUpload(inputId, dropZoneId, previewId) {
    const fileInput = document.getElementById(inputId);
    const dropZone = document.getElementById(dropZoneId);
    const previewContainer = document.getElementById(previewId);
    
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files, previewContainer, dropZone);
    });
}

function setupDragAndDrop(dropZoneId) {
    const dropZone = document.getElementById(dropZoneId);
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropZone.classList.add('highlight');
    }
    
    function unhighlight() {
        dropZone.classList.remove('highlight');
    }
    
    dropZone.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        const input = dropZone.querySelector('input[type="file"]');
        input.files = files;
        
        // Trigger change event manually
        const event = new Event('change');
        input.dispatchEvent(event);
    });
}

function handleFiles(files, previewContainer, dropZone) {
    if (files.length > 0) {
        const file = files[0];
        if (file.type.match('image.*')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewContainer.innerHTML = '';
                const img = document.createElement('img');
                img.src = e.target.result;
                previewContainer.appendChild(img);
                previewContainer.style.display = 'block';
                
                // Update drop zone text
                const p = dropZone.querySelector('p');
                p.textContent = file.name;
                p.style.color = '#1e40af';
                p.style.fontWeight = '600';
            };
            
            reader.readAsDataURL(file);
        }
    }
}

// Form validation
const form = document.querySelector('.training-form');
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
</script>
@endsection