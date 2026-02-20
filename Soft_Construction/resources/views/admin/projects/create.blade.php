@extends('admin.layouts.app')
@section('content')
<link href="{{ asset('admin/assets/css/create.css') }}" rel="stylesheet">
<style>
    /* Additional project-specific styles */
    .region-selector {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
        margin-top: 15px;
    }
    
    .region-option {
        padding: 10px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }
    
    .region-option:hover {
        border-color: #3b82f6;
        background: #f0f7ff;
    }
    
    .region-option.selected {
        border-color: #3b82f6;
        background: #3b82f6;
        color: white;
    }
    
    .category-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }
    
    .category-option {
        padding: 8px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .category-option:hover {
        border-color: #3b82f6;
        background: #f0f7ff;
    }
    
    .category-option.selected {
        border-color: #3b82f6;
        background: #3b82f6;
        color: white;
    }
    
    .featured-switch {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 20px;
    }
    
    .featured-label {
        font-weight: 500;
        color: #334155;
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
                <h1><i class="fas fa-project-diagram"></i> Create New Project</h1>
                <p>Add a new project to showcase your work</p>
            </div>
            <a href="{{ route('admin.projects.index') }}" class="add-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Projects
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="card">
            <div class="container">
    <div class="card">
        <!-- Add this error display section -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mx-4 mt-4">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h5><i class="fas fa-exclamation-circle"></i> Form Errors</h5>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-4 mt-4">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="project-form">
            @csrf

                <div class="form-grid">
                    <!-- Main Information Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-info-circle"></i> Project Details</h3>
                        
                        <div class="form-group floating">
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder=" " required>
                            <label for="title">Project Title *</label>
                            <div class="highlight"></div>
                        </div>

                        <div class="form-group floating">
                            <textarea class="form-control" id="description" name="description" placeholder=" ">{{ old('description') }}</textarea>
                            <label for="description">Description</label>
                            <div class="highlight"></div>
                        </div>

                        <div class="form-group floating">
                            <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" placeholder=" " required>
                            <label for="location">Location *</label>
                            <div class="highlight"></div>
                        </div>

                        <div class="form-group">
                            <label>Region *</label>
                            <div class="region-selector">
                                @foreach(['Ouest', 'Littoral', 'Centre', 'Nord', 'Sud', 'Est', 'Nord-Ouest', 'Sud-Ouest', 'Adamaoua', 'Extrême-Nord'] as $region)
                                    <div class="region-option {{ old('region') == $region ? 'selected' : '' }}" data-value="{{ $region }}">
                                        {{ $region }}
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="region" id="region" value="{{ old('region') }}" required>
                        </div>

                       

                        <div class="featured-switch">
                            <label class="switch">
                                <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                            <span class="featured-label">Featured Project</span>
                        </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-image"></i> Project Image</h3>
                        
                        <div class="form-group file-upload">
                            <label for="image">Project Image <span class="required">*</span></label>
                            <div class="upload-area" id="dropZone">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Drag & drop your image here or click to browse</p>
                                <input type="file" id="image" name="image" required accept="image/*">
                                <div class="preview-container" id="previewContainer"></div>
                            </div>
                            <small class="hint">Recommended size: 1200×800 pixels (3:2 ratio)</small>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-plus-circle"></i> Create Project
                    </button>
                    <a href="{{ route('admin.projects.index') }}" class="btn-cancel">
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
            if (input.value) {
                input.dispatchEvent(new Event('focus'));
            }
            
            if (input.tagName === 'TEXTAREA') {
                input.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
            }
        });

        // Add drag and drop functionality
        setupDragAndDrop('dropZone');

        // Region selection
        const regionOptions = document.querySelectorAll('.region-option');
        regionOptions.forEach(option => {
            option.addEventListener('click', function() {
                regionOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('region').value = this.dataset.value;
            });
        });

        // Category selection
        const categoryOptions = document.querySelectorAll('.category-option');
        categoryOptions.forEach(option => {
            option.addEventListener('click', function() {
                categoryOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('category').value = this.dataset.value;
            });
        });

        // Form validation
        const form = document.querySelector('.project-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Validate required fields
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                        
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
                    
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        }
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
                    
                    const p = dropZone.querySelector('p');
                    p.textContent = file.name;
                    p.style.color = '#1e40af';
                    p.style.fontWeight = '600';
                };
                
                reader.readAsDataURL(file);
            }
        }
    }
</script>
@endsection