@extends('admin.layouts.app')
@section('content')
<link href="{{ asset('admin/assets/css/create.css') }}" rel="stylesheet">
<style>
    /* Partner-specific styling */
    .partner-logo-upload {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        border: 2px dashed #cbd5e1;
        transition: all 0.3s ease;
    }
    
    .partner-logo-upload:hover {
        border-color: #3b82f6;
        background: #f0f7ff;
    }
    
    .partner-logo-upload.highlight {
        border-color: #3b82f6;
        background: #e0f2fe;
    }
    
    .logo-preview-container {
        width: 200px;
        height: 120px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        position: relative;
    }
    
    .logo-preview-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .logo-placeholder {
        text-align: center;
        color: #94a3b8;
    }
    
    .logo-placeholder i {
        font-size: 40px;
        margin-bottom: 10px;
        display: block;
    }
    
    /* Partner-specific form adjustments */
    .partner-form .form-section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    
    .partner-form h3 {
        color: #1e40af;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .partner-form .form-group {
        margin-bottom: 20px;
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
                <h1><i class="fas fa-handshake"></i> Create New Partner</h1>
                <p>Add a new partner to your organization's collaborations</p>
            </div>
            <a href="{{ route('admin.partners.index') }}" class="add-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Partners
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="card">
            <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data" class="partner-form">
                @csrf

                <div class="form-grid">
                    <!-- Main Information Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-info-circle"></i> Partner Information</h3>
                        
                        <div class="form-group floating">
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder=" " required>
                            <label for="name">Partner Name *</label>
                            <div class="highlight"></div>
                        </div>

                        <div class="form-group floating">
                            <textarea class="form-control" id="description" name="description" placeholder=" ">{{ old('description') }}</textarea>
                            <label for="description">Description</label>
                            <div class="highlight"></div>
                        </div>

                        <div class="form-group floating">
                            <input type="url" class="form-control" id="website" name="website" value="{{ old('website') }}" placeholder=" ">
                            <label for="website">Website URL</label>
                            <div class="highlight"></div>
                        </div>

                        <div class="form-group floating">
                            <input type="number" class="form-control" id="order" name="order" value="{{ old('order', 0) }}" placeholder=" " required>
                            <label for="order">Display Order *</label>
                            <div class="highlight"></div>
                        </div>

                        <div class="form-group switch-container">
                            <label class="switch">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <span class="switch-label">Active Partner</span>
                        </div>
                    </div>

                    <!-- Logo Upload Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-image"></i> Partner Logo</h3>
                        
                        <div class="form-group">
                            <label for="logo">Logo <span class="required">*</span></label>
                            <div class="partner-logo-upload" id="dropZone">
                                <div class="logo-preview-container" id="previewContainer">
                                    <div class="logo-placeholder">
                                        <i class="fas fa-handshake"></i>
                                        <p>No logo selected</p>
                                    </div>
                                </div>
                                <div class="upload-instructions">
                                    <p>Drag & drop your logo here or click to browse</p>
                                    <input type="file" id="logo" name="logo" required accept="image/*">
                                    <button type="button" class="btn-browse" onclick="document.getElementById('logo').click()">
                                        <i class="fas fa-folder-open"></i> Browse Files
                                    </button>
                                </div>
                                <small class="hint">Recommended size: 300×150 pixels (transparent PNG preferred)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-plus-circle"></i> Create Partner
                    </button>
                    <a href="{{ route('admin.partners.index') }}" class="btn-cancel">
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
        setupFileUpload('logo', 'dropZone', 'previewContainer');

        // Add floating label functionality
        const floatingInputs = document.querySelectorAll('.form-group.floating input, .form-group.floating textarea');
        floatingInputs.forEach(input => {
            // Trigger focus event if there's already a value (like after form validation error)
            if (input.value) {
                input.dispatchEvent(new Event('focus'));
            }
            
            // Handle textarea height
            if (input.tagName === 'TEXTAREA') {
                input.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            }
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
                    
                    // Update drop zone text
                    const p = dropZone.querySelector('.upload-instructions p');
                    p.textContent = file.name;
                    p.style.color = '#1e40af';
                    p.style.fontWeight = '600';
                };
                
                reader.readAsDataURL(file);
            }
        }
    }

    // Form validation
    const form = document.querySelector('.partner-form');
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