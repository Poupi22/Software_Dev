@extends('admin.layouts.app')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('admin/assets/css/edit.css') }}" rel="stylesheet">


<!-- Animated Background Particles -->
<div class="particles">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
</div>

<!-- Header -->
<header class="header">
    <div class="header-content">
        <div>
            <h1><i class="fas fa-edit"></i> Edit Home Slide</h1>
            <p>Update slide information and images</p>
        </div>
        <a href="{{ route('admin.home_slides.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Slides
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="form-card">
        <div class="form-body">
            <form id="slideForm" action="{{ route('admin.home_slides.update', $home_slide->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Basic Information
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title" class="form-label required">
                                <i class="fas fa-heading"></i> Title
                            </label>
                            <input type="text" class="form-input" id="title" name="title" value="{{ old('title', $home_slide->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="subtitle" class="form-label">
                                <i class="fas fa-text-height"></i> Subtitle
                            </label>
                            <input type="text" class="form-input" id="subtitle" name="subtitle" value="{{ old('subtitle', $home_slide->subtitle) }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="order" class="form-label required">
                                <i class="fas fa-sort-numeric-down"></i> Display Order
                            </label>
                            <input type="number" class="form-input" id="order" name="order" value="{{ old('order', $home_slide->order) }}" min="1" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-toggle-on"></i> Status
                            </label>
                            <div class="checkbox-group">
                                <input type="checkbox" class="checkbox-input" id="is_active" name="is_active" value="1" {{ $home_slide->is_active ? 'checked' : '' }}>
                                <label for="is_active" class="checkbox-label">Active Slide</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Images Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-images"></i>
                        Slide Images
                    </h2>

                    <div class="image-upload-section three-images">
                        <!-- Main Image -->
                        <div class="image-upload-group">
                            <label class="form-label required">
                                <i class="fas fa-camera"></i> Main Image
                            </label>
                            
                            <div class="current-image">
                                <div class="image-label">
                                    <i class="fas fa-camera"></i> Current Main Image
                                </div>
                                <img src="{{ asset('storage/' . $home_slide->image1) }}" alt="Current main image">
                            </div>

                            <div class="file-upload">
                                <input type="file" id="image1" name="image1" accept="image/*">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">Click to upload or drag and drop</div>
                                <div class="upload-hint">PNG, JPG, GIF up to 10MB</div>
                            </div>
                        </div>

                        <!-- Secondary Images -->
                        <div class="secondary-images">
                            <div class="image-upload-group">
                                <label class="form-label">
                                    <i class="fas fa-images"></i> Secondary Image
                                </label>
                                
                                @if($home_slide->image2)
                                <div class="current-image">
                                    <div class="image-label">
                                        <i class="fas fa-images"></i> Current Secondary
                                    </div>
                                    <img src="{{ asset('storage/' . $home_slide->image2) }}" alt="Current secondary image">
                                </div>
                                @endif

                                <div class="file-upload">
                                    <input type="file" id="image2" name="image2" accept="image/*">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">Upload new image</div>
                                    <div class="upload-hint">Optional</div>
                                </div>
                            </div>

                            <div class="image-upload-group">
                                <label class="form-label">
                                    <i class="fas fa-photo-video"></i> Tertiary Image
                                </label>
                                
                                @if($home_slide->image3)
                                <div class="current-image">
                                    <div class="image-label">
                                        <i class="fas fa-photo-video"></i> Current Tertiary
                                    </div>
                                    <img src="{{ asset('storage/' . $home_slide->image3) }}" alt="Current tertiary image">
                                </div>
                                @endif

                                <div class="file-upload">
                                    <input type="file" id="image3" name="image3" accept="image/*">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="upload-text">Upload new image</div>
                                    <div class="upload-hint">Optional</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('admin.home_slides.index') }}" class="btn-action btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    
                    <button type="submit" class="btn-action btn-save">
                        <i class="fas fa-save"></i> Update Slide
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    // Form submission handling
    document.getElementById('slideForm').addEventListener('submit', function(e) {
        const submitBtn = document.querySelector('.btn-save');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        submitBtn.disabled = true;
    });

    // File upload preview
    function setupFilePreview(inputId, imageContainer) {
        const input = document.getElementById(inputId);
        if (input && imageContainer) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = imageContainer.querySelector('img');
                        if (img) {
                            img.src = e.target.result;
                            
                            // Add animation
                            img.style.transform = 'scale(0.8)';
                            img.style.opacity = '0.5';
                            setTimeout(() => {
                                img.style.transform = 'scale(1)';
                                img.style.opacity = '1';
                            }, 100);
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    // Setup file previews
    document.addEventListener('DOMContentLoaded', function() {
        // Main image preview
        const mainImageContainer = document.querySelector('.image-upload-group:nth-child(1) .current-image');
        setupFilePreview('image1', mainImageContainer);
        
        // Secondary image preview
        const secondaryImageContainer = document.querySelector('.secondary-images .image-upload-group:nth-child(1) .current-image');
        setupFilePreview('image2', secondaryImageContainer);
        
        // Tertiary image preview
        const tertiaryImageContainer = document.querySelector('.secondary-images .image-upload-group:nth-child(2) .current-image');
        setupFilePreview('image3', tertiaryImageContainer);

        // Stagger animations for form sections
        const formSections = document.querySelectorAll('.form-section');
        formSections.forEach((section, index) => {
            section.style.animationDelay = `${0.8 + (index * 0.2)}s`;
            section.style.animation = 'fadeInUp 0.6s ease-out both';
        });

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
    });
</script>
@endsection