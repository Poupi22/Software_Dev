@extends('admin.layouts.app')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('admin/assets/css/create.css') }}" rel="stylesheet">
<style>
    /* Enhanced Form Styling */
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .form-body {
        padding: 30px;
    }
    
    .form-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .section-title {
        font-size: 20px;
        margin-bottom: 20px;
        color: #1e293b;
        display: flex;
        align-items: center;
    }
    
    .section-title i {
        margin-right: 10px;
        color: #3b82f6;
    }
    
    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .form-group {
        flex: 1;
        min-width: 250px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #334155;
        display: flex;
        align-items: center;
    }
    
    .form-label i {
        margin-right: 8px;
    }
    
    .form-label.required:after {
        content: '*';
        color: #ef4444;
        margin-left: 4px;
    }
    
    .form-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    
    .form-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    textarea.form-input {
        min-height: 120px;
        resize: vertical;
    }
    
    /* Image Upload Section */
    .image-upload-section {
        margin-top: 20px;
    }
    
    .current-image {
        width: 100%;
        height: 200px;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 15px;
        position: relative;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .current-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .image-label {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 8px 15px;
        font-size: 13px;
        display: flex;
        align-items: center;
    }
    
    .image-label i {
        margin-right: 8px;
    }
    
    .file-upload {
        border: 2px dashed #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .file-upload:hover {
        border-color: #3b82f6;
        background: #f8fafc;
    }
    
    .upload-icon {
        font-size: 24px;
        color: #94a3b8;
        margin-bottom: 10px;
    }
    
    .upload-text {
        font-weight: 500;
        margin-bottom: 5px;
        color: #334155;
    }
    
    .upload-hint {
        font-size: 12px;
        color: #94a3b8;
    }
    
    /* Features Section */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .feature-form-group {
        background: #f8fafc;
        padding: 15px;
        border-radius: 8px;
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
    }
    
    .btn-action {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
    }
    
    .btn-action i {
        margin-right: 8px;
    }
    
    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
    }
    
    .btn-cancel:hover {
        background: #e2e8f0;
    }
    
    .btn-save {
        background: #3b82f6;
        color: white;
    }
    
    .btn-save:hover {
        background: #2563eb;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 15px;
        }
        
        .features-grid {
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

<!-- Header -->
<header class="header">
    <div class="header-content">
        <div>
            <h1><i class="fas fa-edit"></i> Edit Home Service</h1>
            <p>Update service information and features</p>
        </div>
        <a href="{{ route('admin.home-services.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Services
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="form-card">
        <div class="form-body">
            <form id="serviceForm" action="{{ route('admin.home-services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
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
                            <input type="number" class="form-input" id="order" name="order" value="{{ old('order', $service->order) }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label required">
                            <i class="fas fa-align-left"></i> Description
                        </label>
                        <textarea class="form-input" id="description" name="description" required>{{ old('description', $service->description) }}</textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="button_text" class="form-label">
                                <i class="fas fa-hand-pointer"></i> Button Text
                            </label>
                            <input type="text" class="form-input" id="button_text" name="button_text" value="{{ old('button_text', $service->button_text) }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-toggle-on"></i> Status
                            </label>
                            <div style="padding-top: 8px;">
                                <label class="switch">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                    <span class="switch-label">Active Service</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-image"></i>
                        Service Image
                    </h2>

                    <div class="image-upload-section">
                        <div class="form-group">
                            @if($service->image)
                            <div class="current-image">
                                <div class="image-label">
                                    <i class="fas fa-image"></i> Current Image
                                </div>
                                <img src="{{ asset('storage/' . $service->image) }}" alt="Current service image">
                            </div>
                            @endif

                            <div class="file-upload">
                                <input type="file" id="image" name="image" accept="image/*">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <div class="upload-text">Click to upload or drag and drop</div>
                                <div class="upload-hint">PNG, JPG up to 5MB</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-star"></i>
                        Service Features
                    </h2>

                    <div class="features-grid">
                        <!-- Feature 1 -->
                        <div class="feature-form-group">
                            <h4>Feature #1</h4>
                            <div class="form-group">
                                <label for="feature_title_1" class="form-label required">Title</label>
                                <input type="text" class="form-input" id="feature_title_1" name="feature_title_1" 
                                       value="{{ old('feature_title_1', $service->feature_title_1) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="feature_description_1" class="form-label required">Description</label>
                                <textarea class="form-input" id="feature_description_1" name="feature_description_1" 
                                          required>{{ old('feature_description_1', $service->feature_description_1) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="feature_icon_1" class="form-label required">Icon Class</label>
                                <input type="text" class="form-input" id="feature_icon_1" name="feature_icon_1" 
                                       value="{{ old('feature_icon_1', $service->feature_icon_1) }}" required>
                                <small class="hint">Example: fas fa-check</small>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="feature-form-group">
                            <h4>Feature #2</h4>
                            <div class="form-group">
                                <label for="feature_title_2" class="form-label required">Title</label>
                                <input type="text" class="form-input" id="feature_title_2" name="feature_title_2" 
                                       value="{{ old('feature_title_2', $service->feature_title_2) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="feature_description_2" class="form-label required">Description</label>
                                <textarea class="form-input" id="feature_description_2" name="feature_description_2" 
                                          required>{{ old('feature_description_2', $service->feature_description_2) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="feature_icon_2" class="form-label required">Icon Class</label>
                                <input type="text" class="form-input" id="feature_icon_2" name="feature_icon_2" 
                                       value="{{ old('feature_icon_2', $service->feature_icon_2) }}" required>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="feature-form-group">
                            <h4>Feature #3</h4>
                            <div class="form-group">
                                <label for="feature_title_3" class="form-label required">Title</label>
                                <input type="text" class="form-input" id="feature_title_3" name="feature_title_3" 
                                       value="{{ old('feature_title_3', $service->feature_title_3) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="feature_description_3" class="form-label required">Description</label>
                                <textarea class="form-input" id="feature_description_3" name="feature_description_3" 
                                          required>{{ old('feature_description_3', $service->feature_description_3) }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="feature_icon_3" class="form-label required">Icon Class</label>
                                <input type="text" class="form-input" id="feature_icon_3" name="feature_icon_3" 
                                       value="{{ old('feature_icon_3', $service->feature_icon_3) }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('admin.home-services.index') }}" class="btn-action btn-cancel">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Form submission handling
    document.getElementById('serviceForm').addEventListener('submit', function(e) {
        const submitBtn = document.querySelector('.btn-save');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        submitBtn.disabled = true;
    });

    // File upload preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const currentImage = document.querySelector('.current-image');
                if (!currentImage) {
                    // Create image container if it doesn't exist
                    const container = document.createElement('div');
                    container.className = 'current-image';
                    container.innerHTML = `
                        <div class="image-label">
                            <i class="fas fa-image"></i> New Image Preview
                        </div>
                        <img src="${e.target.result}" alt="Preview">
                    `;
                    document.querySelector('.image-upload-section').insertBefore(container, document.querySelector('.file-upload'));
                } else {
                    // Update existing image
                    const img = currentImage.querySelector('img');
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

    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animate form sections
        const formSections = document.querySelectorAll('.form-section');
        formSections.forEach((section, index) => {
            section.style.animationDelay = `${index * 0.1}s`;
            section.style.opacity = '0';
            section.style.animation = 'fadeInUp 0.6s ease-out forwards';
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

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }
        input:checked + .slider {
            background-color: #3b82f6;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        .slider.round {
            border-radius: 34px;
        }
        .slider.round:before {
            border-radius: 50%;
        }
        .switch-label {
            margin-left: 10px;
            vertical-align: middle;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection