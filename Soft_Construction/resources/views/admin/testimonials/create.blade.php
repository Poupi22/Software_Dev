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
                <h1><i class="fas fa-plus-circle"></i> Create New Testimonial</h1>
                <p>Add a new customer testimonial</p>
            </div>
            <a href="{{ route('admin.testimonials.index') }}" class="add-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Testimonials
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="card">
            <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="slide-form">
                @csrf

                <div class="form-grid">
                    <!-- Author Name Group -->
                    <div class="form-group floating">
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder=" " required>
                        <label for="name">Author Name</label>
                        <div class="highlight"></div>
                    </div>

                    <!-- Position Group -->
                    <div class="form-group floating">
                        <input type="text" class="form-control" id="position" name="position" value="{{ old('position') }}" placeholder=" " required>
                        <label for="position">Position/Title</label>
                        <div class="highlight"></div>
                    </div>

                    <!-- Rating Group -->
                    <div class="form-group floating">
                        <select class="form-control" id="rating" name="rating" required>
                            <option value="" disabled selected>Select Rating</option>
                            <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 Star</option>
                            <option value="1.5" {{ old('rating') == 1.5 ? 'selected' : '' }}>1.5 Stars</option>
                            <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 Stars</option>
                            <option value="2.5" {{ old('rating') == 2.5 ? 'selected' : '' }}>2.5 Stars</option>
                            <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 Stars</option>
                            <option value="3.5" {{ old('rating') == 3.5 ? 'selected' : '' }}>3.5 Stars</option>
                            <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 Stars</option>
                            <option value="4.5" {{ old('rating') == 4.5 ? 'selected' : '' }}>4.5 Stars</option>
                            <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 Stars</option>
                        </select>
                        <label for="rating">Rating</label>
                        <div class="highlight"></div>
                    </div>

                    <!-- Status Toggle -->
                    <div class="form-group switch-container">
                        <label class="switch">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                        <span class="switch-label">Active Testimonial</span>
                    </div>

                    <!-- Avatar Upload -->
                    <div class="form-group file-upload">
                        <label for="avatar">Author Avatar</label>
                        <div class="upload-area" id="dropZoneAvatar">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Drag & drop your avatar here or click to browse</p>
                            <input type="file" id="avatar" name="avatar" accept="image/*">
                            <div class="preview-container" id="previewContainerAvatar"></div>
                        </div>
                        <small class="hint">Recommended size: 200×200 pixels (square)</small>
                    </div>

                    <!-- Content Group -->
                    <div class="form-group full-width">
                        <label for="content">Testimonial Content</label>
                        <textarea class="form-control" id="content" name="content" rows="5" placeholder="What did the author say about your services?" required>{{ old('content') }}</textarea>
                        <small class="hint">Include quotation marks if desired</small>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-plus-circle"></i> Create Testimonial
                    </button>
                    <a href="{{ route('admin.testimonials.index') }}" class="btn-cancel">
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
        setupFileUpload('avatar', 'dropZoneAvatar', 'previewContainerAvatar');
        
        // Add drag and drop functionality
        setupDragAndDrop('dropZoneAvatar');

        // Add floating label functionality for select elements
        const selectElements = document.querySelectorAll('.form-group.floating select');
        selectElements.forEach(select => {
            select.addEventListener('change', function() {
                if (this.value) {
                    this.nextElementSibling.classList.add('active');
                } else {
                    this.nextElementSibling.classList.remove('active');
                }
            });
            
            // Trigger change event if there's already a value
            if (select.value) {
                select.dispatchEvent(new Event('change'));
            }
        });

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

        // Preview rating stars
        const ratingSelect = document.getElementById('rating');
        if (ratingSelect) {
            const ratingPreview = document.createElement('div');
            ratingPreview.className = 'rating-preview';
            ratingPreview.innerHTML = `
                <span class="stars"></span>
                <span class="value"></span>
            `;
            ratingSelect.parentNode.appendChild(ratingPreview);

            ratingSelect.addEventListener('change', updateRatingPreview);
            updateRatingPreview();

            function updateRatingPreview() {
                const value = parseFloat(ratingSelect.value) || 0;
                const starsContainer = ratingPreview.querySelector('.stars');
                const valueContainer = ratingPreview.querySelector('.value');
                
                starsContainer.innerHTML = '';
                for (let i = 1; i <= 5; i++) {
                    const star = document.createElement('i');
                    if (i <= Math.floor(value)) {
                        star.className = 'fas fa-star';
                    } else if (i - 0.5 <= value) {
                        star.className = 'fas fa-star-half-alt';
                    } else {
                        star.className = 'far fa-star';
                    }
                    starsContainer.appendChild(star);
                }
                
                valueContainer.textContent = value ? `${value}/5` : '';
            }
        }
    });
</script>

<style>
    .rating-preview {
        display: flex;
        align-items: center;
        margin-top: 8px;
    }
    .rating-preview .stars {
        color: #FFD700;
        margin-right: 8px;
    }
    .rating-preview .value {
        font-size: 14px;
        color: #6b7280;
    }
    .form-group.floating select ~ label {
        transform: translateY(-24px) scale(0.75);
        background: #fff;
        padding: 0 5px;
        left: 8px;
        color: #4a5568;
    }
    .form-group.floating select:focus ~ label,
    .form-group.floating select.active ~ label {
        transform: translateY(-50px) scale(0.75);
    }
</style>
@endsection