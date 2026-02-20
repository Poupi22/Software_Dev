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
            <h1><i class="fas fa-edit"></i> Edit Training Program</h1>
            <p>Update training program information and image</p>
        </div>
        <a href="{{ route('admin.trainings.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Trainings
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="form-card">
        <div class="form-body">
            <form id="trainingForm" action="{{ route('admin.trainings.update', $training->id) }}" method="POST" enctype="multipart/form-data">
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
                            <input type="text" class="form-input" id="title" name="title" value="{{ old('title', $training->title) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="duration" class="form-label required">
                                <i class="fas fa-clock"></i> Duration
                            </label>
                            <input type="text" class="form-input" id="duration" name="duration" value="{{ old('duration', $training->duration) }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="order" class="form-label required">
                                <i class="fas fa-sort-numeric-down"></i> Display Order
                            </label>
                            <input type="number" class="form-input" id="order" name="order" value="{{ old('order', $training->order) }}" min="1" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-toggle-on"></i> Status
                            </label>
                            <div class="checkbox-group">
                                <input type="checkbox" class="checkbox-input" id="is_active" name="is_active" value="1" {{ $training->is_active ? 'checked' : '' }}>
                                <label for="is_active" class="checkbox-label">Active Training</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-align-left"></i>
                        Description
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="description" class="form-label required">
                                <i class="fas fa-align-justify"></i> Description
                            </label>
                            <textarea class="form-input" id="description" name="description" rows="6" required>{{ old('description', $training->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Image Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-image"></i>
                        Training Image
                    </h2>

                    <div class="image-upload-section">
                        <div class="image-upload-group">
                            <label class="form-label">
                                <i class="fas fa-camera"></i> Featured Image
                            </label>
                            
                            @if($training->image)
                            <div class="current-image">
                                <div class="image-label">
                                    <i class="fas fa-image"></i> Current Image
                                </div>
                                <img src="{{ asset('storage/' . $training->image) }}" alt="Current training image">
                            </div>
                            @endif

                            <div class="file-upload">
                                <input type="file" id="image" name="image" accept="image/*">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">Click to upload or drag and drop</div>
                                <div class="upload-hint">PNG, JPG up to 2MB (Recommended: 800x600px)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('admin.trainings.index') }}" class="btn-action btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    
                    <button type="submit" class="btn-action btn-save">
                        <i class="fas fa-save"></i> Update Training
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    // Form submission handling
    document.getElementById('trainingForm').addEventListener('submit', function(e) {
        const submitBtn = document.querySelector('.btn-save');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        submitBtn.disabled = true;
    });

    // File upload preview
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const currentImage = document.querySelector('.current-image');
        
        if (imageInput && currentImage) {
            imageInput.addEventListener('change', function(e) {
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

        // Add character counter for description
        const descriptionField = document.getElementById('description');
        if (descriptionField) {
            const charCounter = document.createElement('div');
            charCounter.className = 'char-counter';
            descriptionField.parentNode.appendChild(charCounter);
            
            function updateCounter() {
                const currentLength = descriptionField.value.length;
                charCounter.textContent = `${currentLength} characters`;
                
                if (currentLength > 1000) {
                    charCounter.style.color = '#ef4444';
                } else {
                    charCounter.style.color = '#6b7280';
                }
            }
            
            descriptionField.addEventListener('input', updateCounter);
            updateCounter();
        }
    });
</script>

<style>
    .char-counter {
        font-size: 0.75rem;
        color: #6b7280;
        text-align: right;
        margin-top: 0.25rem;
    }
</style>
@endsection