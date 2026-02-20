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
            <h1><i class="fas fa-user-edit"></i> Edit Team Member</h1>
            <p>Update team member information and profile photo</p>
        </div>
        <a href="{{ route('admin.personnels.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Team
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="form-card">
        <div class="form-body">
            <form id="personnelForm" action="{{ route('admin.personnels.update', $personnel->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-user-circle"></i>
                        Personal Information
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label required">
                                <i class="fas fa-user"></i> Full Name
                            </label>
                            <input type="text" class="form-input" id="name" name="name" value="{{ old('name', $personnel->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="position" class="form-label required">
                                <i class="fas fa-briefcase"></i> Position
                            </label>
                            <input type="text" class="form-input" id="position" name="position" value="{{ old('position', $personnel->position) }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="order" class="form-label required">
                                <i class="fas fa-sort-numeric-down"></i> Display Order
                            </label>
                            <input type="number" class="form-input" id="order" name="order" value="{{ old('order', $personnel->order) }}" min="1" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-toggle-on"></i> Status
                            </label>
                            <div class="checkbox-group">
                                <input type="checkbox" class="checkbox-input" id="is_active" name="is_active" value="1" {{ $personnel->is_active ? 'checked' : '' }}>
                                <label for="is_active" class="checkbox-label">Active Member</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-align-left"></i>
                        Professional Bio
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="description" class="form-label required">
                                <i class="fas fa-file-alt"></i> Description
                            </label>
                            <textarea class="form-input" id="description" name="description" rows="6" required>{{ old('description', $personnel->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Profile Photo Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-camera"></i>
                        Profile Photo
                    </h2>

                    <div class="image-upload-section">
                        <div class="image-upload-group">
                            <label class="form-label">
                                <i class="fas fa-portrait"></i> Profile Image
                            </label>
                            
                            @if($personnel->image_path)
                            <div class="current-image">
                                <div class="image-label">
                                    <i class="fas fa-image"></i> Current Photo
                                </div>
                                <img src="{{ $personnel->image_url }}" alt="Current profile photo">
                            </div>
                            @endif

                            <div class="file-upload">
                                <input type="file" id="image" name="image" accept="image/*">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">Click to upload or drag and drop</div>
                                <div class="upload-hint">PNG, JPG up to 5MB (Recommended: 400×400px)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('admin.personnels.index') }}" class="btn-action btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    
                    <button type="submit" class="btn-action btn-save">
                        <i class="fas fa-save"></i> Update Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    // Form submission handling
    document.getElementById('personnelForm').addEventListener('submit', function(e) {
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
    });
</script>
@endsection