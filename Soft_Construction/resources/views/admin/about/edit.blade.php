@extends('admin.layouts.app')
@section('content')
<link href="{{ asset('admin/assets/css/create.css') }}" rel="stylesheet">
<style>
    /* About-specific styling */
    .about-image-upload {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        border: 2px dashed #cbd5e1;
        transition: all 0.3s ease;
    }
    
    .about-image-upload:hover {
        border-color: #3b82f6;
        background: #f0f7ff;
    }
    
    .about-image-upload.highlight {
        border-color: #3b82f6;
        background: #e0f2fe;
    }
    
    .image-preview-container {
        width: 300px;
        height: 200px;
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
    
    .image-preview-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }
    
    .image-placeholder {
        text-align: center;
        color: #94a3b8;
    }
    
    .image-placeholder i {
        font-size: 40px;
        margin-bottom: 10px;
        display: block;
    }
    
    /* Features specific styling */
    .feature-item {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    
    .feature-item:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        border-color: #cbd5e1;
    }
    
    .feature-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .feature-number {
        background: #3b82f6;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }
    
    .remove-feature {
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 6px 12px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .remove-feature:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }
    
    .add-feature-btn {
        background: #10b981;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .add-feature-btn:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    /* About-specific form adjustments */
    .about-form .form-section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    
    .about-form h3 {
        color: #1e40af;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .about-form .form-group {
        margin-bottom: 20px;
    }
    
    .experience-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    .current-image-preview {
        text-align: center;
        margin-top: 15px;
    }
    
    .current-image-preview img {
        max-width: 100%;
        max-height: 300px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    @media (max-width: 768px) {
        .experience-grid {
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
                <h1><i class="fas fa-edit"></i> Edit About Section</h1>
                <p>Update your company's about section content</p>
            </div>
            <a href="{{ route('admin.abouts.index') }}" class="add-btn">
                <i class="fas fa-arrow-left"></i>
                Back to About
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="card">
            <form action="{{ route('admin.abouts.update', $about->id) }}" method="POST" enctype="multipart/form-data" class="about-form">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <!-- Main Information Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                        
                        <div class="form-group floating">
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $about->title) }}" placeholder=" " required>
                            <label for="title">Title *</label>
                            <div class="highlight"></div>
                        </div>

                        <div class="form-group floating">
                            <input type="text" class="form-control" id="subtitle" name="subtitle" value="{{ old('subtitle', $about->subtitle) }}" placeholder=" " required>
                            <label for="subtitle">Subtitle *</label>
                            <div class="highlight"></div>
                        </div>

                        <div class="form-group floating">
                            <textarea class="form-control" id="description1" name="description1" placeholder=" " rows="4" required>{{ old('description1', $about->description1) }}</textarea>
                            <label for="description1">First Description *</label>
                            <div class="highlight"></div>
                        </div>

                        <div class="form-group floating">
                            <textarea class="form-control" id="description2" name="description2" placeholder=" " rows="4" required>{{ old('description2', $about->description2) }}</textarea>
                            <label for="description2">Second Description *</label>
                            <div class="highlight"></div>
                        </div>
                    </div>

                    <!-- Experience Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-chart-line"></i> Experience Information</h3>
                        
                        <div class="experience-grid">
                            <div class="form-group floating">
                                <input type="text" class="form-control" id="experience_years" name="experience_years" value="{{ old('experience_years', $about->experience_years) }}" placeholder=" " required>
                                <label for="experience_years">Experience Years *</label>
                                <div class="highlight"></div>
                            </div>

                            <div class="form-group floating">
                                <input type="text" class="form-control" id="experience_text" name="experience_text" value="{{ old('experience_text', $about->experience_text) }}" placeholder=" " required>
                                <label for="experience_text">Experience Text *</label>
                                <div class="highlight"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-image"></i> About Image</h3>
                        
                        <div class="form-group">
                            <label for="image">About Image</label>
                            
                            @if($about->image)
                                <div class="current-image-preview">
                                    <p><strong>Current Image:</strong></p>
                                    <img src="{{ asset('storage/' . $about->image) }}" alt="Current about image">
                                </div>
                            @endif
                            
                            <div class="about-image-upload" id="dropZone">
                                <div class="image-preview-container" id="previewContainer">
                                    <div class="image-placeholder">
                                        <i class="fas fa-image"></i>
                                        <p>Select new image to replace</p>
                                    </div>
                                </div>
                                <div class="upload-instructions">
                                    <p>Drag & drop your image here or click to browse</p>
                                    <input type="file" id="image" name="image" accept="image/*">
                                    <button type="button" class="btn-browse" onclick="document.getElementById('image').click()">
                                        <i class="fas fa-folder-open"></i> Browse Files
                                    </button>
                                </div>
                                <small class="hint">Recommended size: 600×400 pixels (JPG, PNG, or WebP)</small>
                            </div>
                        </div>
                    </div>

                    <!-- Features Section -->
                    <div class="form-section">
                        <h3><i class="fas fa-star"></i> Features</h3>
                        
                        <div id="features-container">
                            @foreach($about->features as $index => $feature)
                                <div class="feature-item" data-index="{{ $index }}">
                                    <div class="feature-header">
                                        <div class="feature-number">{{ $index + 1 }}</div>
                                        <button type="button" class="remove-feature" onclick="removeFeature(this)">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                    <div class="form-group floating">
                                        <input type="text" class="form-control" name="features[{{ $index }}][icon]" value="{{ old("features.$index.icon", $feature['icon']) }}" placeholder=" " required>
                                        <label>Icon Class (Font Awesome) *</label>
                                        <div class="highlight"></div>
                                        <small class="hint">e.g., fas fa-check, fas fa-award, fas fa-users</small>
                                    </div>
                                    <div class="form-group floating">
                                        <input type="text" class="form-control" name="features[{{ $index }}][title]" value="{{ old("features.$index.title", $feature['title']) }}" placeholder=" " required>
                                        <label>Feature Title *</label>
                                        <div class="highlight"></div>
                                    </div>
                                    <div class="form-group floating">
                                        <input type="text" class="form-control" name="features[{{ $index }}][description]" value="{{ old("features.$index.description", $feature['description']) }}" placeholder=" " required>
                                        <label>Feature Description *</label>
                                        <div class="highlight"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <button type="button" class="add-feature-btn" onclick="addFeature()">
                            <i class="fas fa-plus"></i> Add Feature
                        </button>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Update About Section
                    </button>
                    <a href="{{ route('admin.abouts.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('admin/assets/js/create.js') }}"></script>
<script>
    let featureCount = {{ count($about->features) }};
    
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
                
                // Trigger initial resize
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            }
        });

        // Add drag and drop functionality
        setupDragAndDrop('dropZone');

        // Load existing features from old input if any (for validation errors)
        @if(old('features'))
            @foreach(old('features') as $index => $feature)
                @if($index >= count($about->features))
                    addFeature('{{ $feature['icon'] }}', '{{ $feature['title'] }}', '{{ $feature['description'] }}');
                @endif
            @endforeach
        @endif
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
                    
                    const p = dropZone.querySelector('.upload-instructions p');
                    p.textContent = file.name;
                    p.style.color = '#1e40af';
                    p.style.fontWeight = '600';
                };
                
                reader.readAsDataURL(file);
            }
        }
    }

    function addFeature(icon = '', title = '', description = '') {
        const container = document.getElementById('features-container');
        const newFeature = document.createElement('div');
        newFeature.className = 'feature-item';
        newFeature.setAttribute('data-index', featureCount);
        
        newFeature.innerHTML = `
            <div class="feature-header">
                <div class="feature-number">${featureCount + 1}</div>
                <button type="button" class="remove-feature" onclick="removeFeature(this)">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="form-group floating">
                <input type="text" class="form-control" name="features[${featureCount}][icon]" value="${icon}" placeholder=" " required>
                <label>Icon Class (Font Awesome) *</label>
                <div class="highlight"></div>
                <small class="hint">e.g., fas fa-check, fas fa-award, fas fa-users</small>
            </div>
            <div class="form-group floating">
                <input type="text" class="form-control" name="features[${featureCount}][title]" value="${title}" placeholder=" " required>
                <label>Feature Title *</label>
                <div class="highlight"></div>
            </div>
            <div class="form-group floating">
                <input type="text" class="form-control" name="features[${featureCount}][description]" value="${description}" placeholder=" " required>
                <label>Feature Description *</label>
                <div class="highlight"></div>
            </div>
        `;
        
        container.appendChild(newFeature);
        featureCount++;
        updateFeatureNumbers();
    }

    function removeFeature(button) {
        const featureItem = button.closest('.feature-item');
        const container = document.getElementById('features-container');
        
        // Don't remove if it's the last feature
        if (container.children.length > 1) {
            featureItem.remove();
            updateFeatureNumbers();
            reindexFeatures();
        } else {
            // Show message or alert that at least one feature is required
            alert('At least one feature is required');
        }
    }

    function updateFeatureNumbers() {
        const features = document.querySelectorAll('.feature-item');
        features.forEach((feature, index) => {
            const numberElement = feature.querySelector('.feature-number');
            numberElement.textContent = index + 1;
        });
    }

    function reindexFeatures() {
        const features = document.querySelectorAll('.feature-item');
        featureCount = 0;
        
        features.forEach((feature, index) => {
            feature.setAttribute('data-index', index);
            const inputs = feature.querySelectorAll('input');
            inputs[0].name = `features[${index}][icon]`;
            inputs[1].name = `features[${index}][title]`;
            inputs[2].name = `features[${index}][description]`;
            featureCount++;
        });
    }

    // Form validation
    const form = document.querySelector('.about-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
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
</script>
@endsection