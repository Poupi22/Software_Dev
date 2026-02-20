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
                <h1><i class="fas fa-plus-circle"></i> Create New Slide</h1>
                <p>Add a new slide to your homepage carousel</p>
            </div>
            <a href="{{ route('admin.home_slides.index') }}" class="add-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Slides
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="card">
            <form action="{{ route('admin.home_slides.store') }}" method="POST" enctype="multipart/form-data" class="slide-form">
                @csrf

                <div class="form-grid">
                    <!-- Title Group -->
                    <div class="form-group floating">
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder=" " required>
                        <label for="title">Title</label>
                        <div class="highlight"></div>
                    </div>

                    <!-- Subtitle Group -->
                    <div class="form-group floating">
                        <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle') }}" placeholder=" ">
                        <label for="subtitle">Subtitle</label>
                        <div class="highlight"></div>
                    </div>

                    <!-- Order Group -->
                    <div class="form-group floating">
                        <input type="number" class="form-control" id="order" name="order" value="{{ old('order', 0) }}" placeholder=" " required>
                        <label for="order">Display Order</label>
                        <div class="highlight"></div>
                    </div>

                    <!-- Status Toggle -->
                    <div class="form-group switch-container">
                        <label class="switch">
                            <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="switch-label">Active Slide</span>
                    </div>

                    <!-- Image Uploads -->
                    <div class="form-group file-upload">
                        <label for="image1">Main Image <span class="required">*</span></label>
                        <div class="upload-area" id="dropZone1">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drag & drop your image here or click to browse</p>
                            <input type="file" id="image1" name="image1" required accept="image/*">
                            <div class="preview-container" id="previewContainer1"></div>
                        </div>
                        <small class="hint">Recommended size: 1920×1080 pixels (16:9 ratio)</small>
                    </div>

                    <div class="form-group file-upload">
                        <label for="image2">Secondary Image</label>
                        <div class="upload-area" id="dropZone2">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drag & drop your image here or click to browse</p>
                            <input type="file" id="image2" name="image2" accept="image/*">
                            <div class="preview-container" id="previewContainer2"></div>
                        </div>
                    </div>

                    <div class="form-group file-upload">
                        <label for="image3">Tertiary Image</label>
                        <div class="upload-area" id="dropZone3">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drag & drop your image here or click to browse</p>
                            <input type="file" id="image3" name="image3" accept="image/*">
                            <div class="preview-container" id="previewContainer3"></div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-plus-circle"></i> Create Slide
                    </button>
                    <a href="{{ route('admin.home_slides.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('admin/assets/js/create.js') }}">
    document.addEventListener('DOMContentLoaded', function() {
    // Initialize file upload previews
    setupFileUpload('image1', 'dropZone1', 'previewContainer1');
    setupFileUpload('image2', 'dropZone2', 'previewContainer2');
    setupFileUpload('image3', 'dropZone3', 'previewContainer3');

    // Add floating label functionality
    const floatingInputs = document.querySelectorAll('.form-group.floating input');
    floatingInputs.forEach(input => {
        // Trigger focus event if there's already a value (like after form validation error)
        if (input.value) {
            input.dispatchEvent(new Event('focus'));
        }
    });

    // Add drag and drop functionality
    setupDragAndDrop('dropZone1');
    setupDragAndDrop('dropZone2');
    setupDragAndDrop('dropZone3');
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
</script>
@endsection