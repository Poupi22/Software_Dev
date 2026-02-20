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
                <p>Add a new service to your homepage services section</p>
            </div>
            <a href="{{ route('admin.home-services.index') }}" class="add-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Services
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="card">
            <form action="{{ route('admin.home-services.store') }}" method="POST" enctype="multipart/form-data" class="service-form">
                @csrf

                <div class="form-grid">
                    <!-- Main Information Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                        
                        <div class="form-group floating">
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder=" " required>
                            <label for="title">Service Title *</label>
                            <div class="highlight"></div>
                        </div>

                        <div class="form-group floating">
                            <textarea class="form-control" id="description" name="description" placeholder=" " required>{{ old('description') }}</textarea>
                            <label for="description">Description *</label>
                            <div class="highlight"></div>
                        </div>

                        <div class="form-group floating">
                            <input type="text" class="form-control" id="button_text" name="button_text" value="{{ old('button_text') }}" placeholder=" ">
                            <label for="button_text">Button Text (optional)</label>
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
                            <span class="switch-label">Active Service</span>
                        </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-image"></i> Service Image</h3>
                        
                        <div class="form-group file-upload">
                            <label for="image">Service Image <span class="required">*</span></label>
                            <div class="upload-area" id="dropZone">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Drag & drop your image here or click to browse</p>
                                <input type="file" id="image" name="image" required accept="image/*">
                                <div class="preview-container" id="previewContainer"></div>
                            </div>
                            <small class="hint">Recommended size: 800×600 pixels (4:3 ratio)</small>
                        </div>
                    </div>

                    <!-- Features Section -->
                    <div class="form-section features-section">
                        <h3><i class="fas fa-star"></i> Service Features</h3>
                        
                        <!-- Feature 1 -->
                        <div class="feature-card">
                            <div class="feature-header">
                                <h4>Feature #1</h4>
                            </div>
                            <div class="form-group floating">
                                <input type="text" class="form-control" id="feature_title_1" name="feature_title_1" value="{{ old('feature_title_1') }}" placeholder=" " required>
                                <label for="feature_title_1">Title *</label>
                                <div class="highlight"></div>
                            </div>
                            <div class="form-group floating">
                                <textarea class="form-control" id="feature_description_1" name="feature_description_1" placeholder=" " required>{{ old('feature_description_1') }}</textarea>
                                <label for="feature_description_1">Description *</label>
                                <div class="highlight"></div>
                            </div>
                            <div class="form-group floating">
                                <input type="text" class="form-control" id="feature_icon_1" name="feature_icon_1" value="{{ old('feature_icon_1', 'fas fa-check') }}" placeholder=" " required>
                                <label for="feature_icon_1">Icon Class *</label>
                                <div class="highlight"></div>
                                <small class="hint">Use Font Awesome icon classes (e.g. fas fa-check)</small>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="feature-card">
                            <div class="feature-header">
                                <h4>Feature #2</h4>
                            </div>
                            <div class="form-group floating">
                                <input type="text" class="form-control" id="feature_title_2" name="feature_title_2" value="{{ old('feature_title_2') }}" placeholder=" " required>
                                <label for="feature_title_2">Title *</label>
                                <div class="highlight"></div>
                            </div>
                            <div class="form-group floating">
                                <textarea class="form-control" id="feature_description_2" name="feature_description_2" placeholder=" " required>{{ old('feature_description_2') }}</textarea>
                                <label for="feature_description_2">Description *</label>
                                <div class="highlight"></div>
                            </div>
                            <div class="form-group floating">
                                <input type="text" class="form-control" id="feature_icon_2" name="feature_icon_2" value="{{ old('feature_icon_2', 'fas fa-check') }}" placeholder=" " required>
                                <label for="feature_icon_2">Icon Class *</label>
                                <div class="highlight"></div>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="feature-card">
                            <div class="feature-header">
                                <h4>Feature #3</h4>
                            </div>
                            <div class="form-group floating">
                                <input type="text" class="form-control" id="feature_title_3" name="feature_title_3" value="{{ old('feature_title_3') }}" placeholder=" " required>
                                <label for="feature_title_3">Title *</label>
                                <div class="highlight"></div>
                            </div>
                            <div class="form-group floating">
                                <textarea class="form-control" id="feature_description_3" name="feature_description_3" placeholder=" " required>{{ old('feature_description_3') }}</textarea>
                                <label for="feature_description_3">Description *</label>
                                <div class="highlight"></div>
                            </div>
                            <div class="form-group floating">
                                <input type="text" class="form-control" id="feature_icon_3" name="feature_icon_3" value="{{ old('feature_icon_3', 'fas fa-check') }}" placeholder=" " required>
                                <label for="feature_icon_3">Icon Class *</label>
                                <div class="highlight"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-plus-circle"></i> Create Service
                    </button>
                    <a href="{{ route('admin.home-services.index') }}" class="btn-cancel">
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

        // Icon preview functionality
        setupIconPreviews();
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

    function setupIconPreviews() {
        const iconInputs = document.querySelectorAll('input[name^="feature_icon_"]');
        
        iconInputs.forEach(input => {
            const previewContainer = document.createElement('div');
            previewContainer.className = 'icon-preview';
            input.parentNode.insertBefore(previewContainer, input.nextSibling);
            
            // Create initial preview
            updateIconPreview(input, previewContainer);
            
            // Update preview on input change
            input.addEventListener('input', function() {
                updateIconPreview(this, previewContainer);
            });
        });
    }

    function updateIconPreview(input, container) {
        container.innerHTML = '';
        if (input.value) {
            const icon = document.createElement('i');
            const classes = input.value.split(' ');
            classes.forEach(cls => icon.classList.add(cls));
            container.appendChild(icon);
        }
    }

    // Form validation
    const form = document.querySelector('.service-form');
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