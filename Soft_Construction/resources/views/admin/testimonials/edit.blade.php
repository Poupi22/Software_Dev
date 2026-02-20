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
            <h1><i class="fas fa-edit"></i> Edit Testimonial</h1>
            <p>Update testimonial information and avatar</p>
        </div>
        <a href="{{ route('admin.testimonials.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Testimonials
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="form-card">
        <div class="form-body">
            <form id="testimonialForm" action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Author Information Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-user"></i>
                        Author Information
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label required">
                                <i class="fas fa-signature"></i> Author Name
                            </label>
                            <input type="text" class="form-input" id="name" name="name" value="{{ old('name', $testimonial->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="position" class="form-label required">
                                <i class="fas fa-briefcase"></i> Position/Title
                            </label>
                            <input type="text" class="form-input" id="position" name="position" value="{{ old('position', $testimonial->position) }}" required>
                        </div>
                    </div>
                </div>

                <!-- Testimonial Content Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-quote-left"></i>
                        Testimonial Content
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="content" class="form-label required">
                                <i class="fas fa-comment"></i> Testimonial Text
                            </label>
                            <textarea class="form-input" id="content" name="content" rows="4" required>{{ old('content', $testimonial->content) }}</textarea>
                            <small class="form-hint">Include quotation marks if desired</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="rating" class="form-label required">
                                <i class="fas fa-star"></i> Rating
                            </label>
                            <select class="form-input" id="rating" name="rating" required>
                                <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>1 Star</option>
                                <option value="1.5" {{ old('rating', $testimonial->rating) == 1.5 ? 'selected' : '' }}>1.5 Stars</option>
                                <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>2 Stars</option>
                                <option value="2.5" {{ old('rating', $testimonial->rating) == 2.5 ? 'selected' : '' }}>2.5 Stars</option>
                                <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>3 Stars</option>
                                <option value="3.5" {{ old('rating', $testimonial->rating) == 3.5 ? 'selected' : '' }}>3.5 Stars</option>
                                <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>4 Stars</option>
                                <option value="4.5" {{ old('rating', $testimonial->rating) == 4.5 ? 'selected' : '' }}>4.5 Stars</option>
                                <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>5 Stars</option>
                            </select>
                            <div class="rating-preview">
                                <div class="stars-preview">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor(old('rating', $testimonial->rating)))
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= old('rating', $testimonial->rating))
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="rating-value">{{ old('rating', $testimonial->rating) }}/5</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-toggle-on"></i> Status
                            </label>
                            <div class="checkbox-group">
                                <input type="checkbox" class="checkbox-input" id="is_active" name="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="checkbox-label">Active Testimonial</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avatar Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-portrait"></i>
                        Author Avatar
                    </h2>

                    <div class="image-upload-section">
                        <div class="image-upload-group">
                            <label class="form-label">
                                <i class="fas fa-camera"></i> Profile Image
                            </label>
                            
                            @if($testimonial->avatar)
                            <div class="current-image">
                                <div class="image-label">
                                    <i class="fas fa-user-circle"></i> Current Avatar
                                </div>
                                <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="Current author avatar">
                                <div class="image-actions">
                                    <label class="btn-action btn-replace">
                                        <i class="fas fa-sync-alt"></i> Replace
                                        <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;">
                                    </label>
                                    <button type="button" class="btn-action btn-remove" id="removeAvatar">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                                <input type="hidden" name="remove_avatar" id="removeAvatarFlag" value="0">
                            </div>
                            @else
                            <div class="file-upload">
                                <input type="file" id="avatar" name="avatar" accept="image/*">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">Click to upload or drag and drop</div>
                                <div class="upload-hint">PNG, JPG up to 5MB (Square recommended)</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('admin.testimonials.index') }}" class="btn-action btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    
                    <button type="submit" class="btn-action btn-save">
                        <i class="fas fa-save"></i> Update Testimonial
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form submission handling
        document.getElementById('testimonialForm').addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('.btn-save');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            submitBtn.disabled = true;
        });

        // Rating preview update
        const ratingSelect = document.getElementById('rating');
        if (ratingSelect) {
            ratingSelect.addEventListener('change', function() {
                const value = parseFloat(this.value);
                const starsContainer = document.querySelector('.stars-preview');
                const valueContainer = document.querySelector('.rating-value');
                
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
                
                valueContainer.textContent = `${value}/5`;
            });
        }

        // Avatar image replacement preview
        const avatarInput = document.getElementById('avatar');
        if (avatarInput) {
            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.querySelector('.current-image img');
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

        // Remove avatar functionality
        const removeAvatarBtn = document.getElementById('removeAvatar');
        if (removeAvatarBtn) {
            removeAvatarBtn.addEventListener('click', function() {
                const currentImage = document.querySelector('.current-image');
                const fileUpload = document.createElement('div');
                fileUpload.className = 'file-upload';
                fileUpload.innerHTML = `
                    <input type="file" id="avatar" name="avatar" accept="image/*">
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text">Click to upload or drag and drop</div>
                    <div class="upload-hint">PNG, JPG up to 5MB (Square recommended)</div>
                `;
                
                currentImage.parentNode.replaceChild(fileUpload, currentImage);
                document.getElementById('removeAvatarFlag').value = '1';
                
                // Re-attach event listener to new file input
                fileUpload.querySelector('input').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('removeAvatarFlag').value = '0';
                        };
                        reader.readAsDataURL(file);
                    }
                });
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

<style>
    .rating-preview {
        display: flex;
        align-items: center;
        margin-top: 8px;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #e9ecef;
    }
    .stars-preview {
        display: flex;
        margin-right: 10px;
    }
    .stars-preview i {
        color: #FFD700;
        font-size: 16px;
        margin-right: 2px;
    }
    .rating-value {
        font-size: 14px;
        font-weight: 500;
        color: #495057;
    }
    .current-image {
        text-align: center;
        margin-bottom: 20px;
    }
    .current-image img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e9ecef;
        transition: all 0.3s ease;
    }
    .image-label {
        margin-bottom: 10px;
        font-weight: 500;
        color: #495057;
    }
    .image-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 15px;
    }
    .btn-replace, .btn-remove {
        padding: 8px 15px;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-replace {
        background: #4e73df;
        color: white;
        border: none;
    }
    .btn-replace:hover {
        background: #3a5ec0;
    }
    .btn-remove {
        background: #e74a3b;
        color: white;
        border: none;
    }
    .btn-remove:hover {
        background: #d62c1a;
    }
</style>
@endsection