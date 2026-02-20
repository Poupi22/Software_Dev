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
            <h1><i class="fas fa-edit"></i> Edit Service</h1>
            <p>Update service information and icon</p>
        </div>
        <a href="{{ route('admin.services.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Services
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="form-card">
        <div class="form-body">
            <form id="serviceForm" action="{{ route('admin.services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
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
                            <input type="text" class="form-input" id="title" name="title" value="{{ old('title', $service->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="order" class="form-label required">
                                <i class="fas fa-sort-numeric-down"></i> Display Order
                            </label>
                            <input type="number" class="form-input" id="order" name="order" value="{{ old('order', $service->order) }}" min="1" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-toggle-on"></i> Status
                            </label>
                            <div class="checkbox-group">
                                <input type="checkbox" class="checkbox-input" id="active" name="active" value="1" {{ $service->active ? 'checked' : '' }}>
                                <label for="active" class="checkbox-label">Active Service</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-align-left"></i>
                        Descriptions
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="short_description" class="form-label required">
                                <i class="fas fa-text-height"></i> Short Description
                            </label>
                            <textarea class="form-input" id="short_description" name="short_description" required>{{ old('short_description', $service->short_description) }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="long_description" class="form-label">
                                <i class="fas fa-align-justify"></i> Long Description
                            </label>
                            <textarea class="form-input" id="long_description" name="long_description" rows="4">{{ old('long_description', $service->long_description) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Icon Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-image"></i>
                        Service Icon
                    </h2>

                    <div class="image-upload-section">
                        <div class="image-upload-group">
                            <label class="form-label">
                                <i class="fas fa-camera"></i> Icon Image
                            </label>
                            
                            @if($service->icon)
                            <div class="current-image">
                                <div class="image-label">
                                    <i class="fas fa-image"></i> Current Icon
                                </div>
                                <img src="{{ asset('storage/' . $service->icon) }}" alt="Current service icon">
                            </div>
                            @endif

                            <div class="file-upload">
                                <input type="file" id="icon" name="icon" accept="image/*">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">Click to upload or drag and drop</div>
                                <div class="upload-hint">PNG, JPG, GIF up to 10MB</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('admin.services.index') }}" class="btn-action btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    
                    <button type="submit" class="btn-action btn-save">
                        <i class="fas fa-save"></i> Update Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    // Form submission handling
    document.getElementById('serviceForm').addEventListener('submit', function(e) {
        const submitBtn = document.querySelector('.btn-save');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        submitBtn.disabled = true;
    });

    // File upload preview
    document.addEventListener('DOMContentLoaded', function() {
        const iconInput = document.getElementById('icon');
        const currentImage = document.querySelector('.current-image');
        
        if (iconInput && currentImage) {
            iconInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = currentImage.querySelector('img');
                        if (img) {
                            img.src = e.target.result;
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