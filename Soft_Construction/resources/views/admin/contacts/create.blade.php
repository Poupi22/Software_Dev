@extends('admin.layouts.app')
@section('content')
<link href="{{ asset('admin/assets/css/index.css') }}" rel="stylesheet">

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
                <h1><i class="fas fa-address-book"></i> Create Contact Information</h1>
                <p>Add new contact details for your website</p>
            </div>
            <a href="{{ route('admin.contacts.index') }}" class="add-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Contacts
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="card">
            <form action="{{ route('admin.contacts.store') }}" method="POST" class="contact-form">
                @csrf

                <div class="form-grid">
                    <!-- Left Column -->
                    <div class="form-column">
                        <!-- Address -->
                        <div class="form-group">
                            <label for="address">
                                <i class="fas fa-map-marker-alt"></i> Address
                            </label>
                            <textarea class="form-control" id="address" name="address" placeholder="Enter full address" required>{{ old('address') }}</textarea>
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label for="telephone">
                                <i class="fas fa-phone"></i> Phone Number
                            </label>
                            <input type="text" class="form-control" id="telephone" name="telephone" value="{{ old('telephone') }}" placeholder="Enter phone number" required>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i> Email Address
                            </label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter email address" required>
                        </div>

                        <!-- Opening Hours -->
                        <div class="form-group">
                            <label for="heuredouverture">
                                <i class="fas fa-clock"></i> Opening Hours
                            </label>
                            <textarea class="form-control" id="heuredouverture" name="heuredouverture" placeholder="Enter opening hours" required>{{ old('heuredouverture') }}</textarea>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="form-column">
                        <!-- Social Media Section -->
                        <div class="form-group">
                            <label><i class="fas fa-share-alt"></i> Social Media Links</label>
                            
                            <div class="social-media-grid">
                                <!-- Facebook -->
                                <div class="social-input-group facebook">
                                    <div class="social-icon">
                                        <i class="fab fa-facebook-f"></i>
                                    </div>
                                    <input type="url" class="form-control" id="facebook" name="facebook" value="{{ old('facebook') }}" placeholder="https://facebook.com/yourpage">
                                </div>
                                
                                <!-- Twitter -->
                                <div class="social-input-group twitter">
                                    <div class="social-icon">
                                        <i class="fab fa-twitter"></i>
                                    </div>
                                    <input type="url" class="form-control" id="twitter" name="twitter" value="{{ old('twitter') }}" placeholder="https://twitter.com/yourhandle">
                                </div>
                                
                                <!-- LinkedIn -->
                                <div class="social-input-group linkedin">
                                    <div class="social-icon">
                                        <i class="fab fa-linkedin-in"></i>
                                    </div>
                                    <input type="url" class="form-control" id="linkedin" name="linkedin" value="{{ old('linkedin') }}" placeholder="https://linkedin.com/yourprofile">
                                </div>
                                
                                <!-- Instagram -->
                                <div class="social-input-group instagram">
                                    <div class="social-icon">
                                        <i class="fab fa-instagram"></i>
                                    </div>
                                    <input type="url" class="form-control" id="instagram" name="instagram" value="{{ old('instagram') }}" placeholder="https://instagram.com/yourprofile">
                                </div>
                            </div>
                        </div>

                        <!-- Map Embed -->
                        <div class="form-group">
                            <label for="location">
                                <i class="fas fa-map-marked-alt"></i> Map Embed Code
                            </label>
                            <textarea class="form-control" id="location" name="location" placeholder="Paste your iframe embed code here..." required>{{ old('location') }}</textarea>
                            
                            <!-- Preview Button -->
                            <button type="button" id="preview-map" class="btn-preview">
                                <i class="fas fa-eye"></i> Preview Map
                            </button>
                            
                            <!-- Preview Container -->
                            <div id="map-preview-container" class="mt-3" style="display: none;">
                                <h5 class="preview-title">Map Preview</h5>
                                <div id="map-preview" class="map-preview"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Create Contact
                    </button>
                    <a href="{{ route('admin.contacts.index') }}" class="btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Map Preview Functionality
    const previewBtn = document.getElementById('preview-map');
    const mapPreviewContainer = document.getElementById('map-preview-container');
    const mapPreview = document.getElementById('map-preview');
    const mapEmbedTextarea = document.getElementById('location');

    previewBtn.addEventListener('click', function() {
        const iframeCode = mapEmbedTextarea.value.trim();
        
        if (!iframeCode) {
            Swal.fire('Error', 'Please enter an iframe code first', 'error');
            return;
        }
        
        if (!iframeCode.startsWith('<iframe')) {
            Swal.fire('Error', 'Please enter a valid iframe code', 'error');
            return;
        }
        
        // Clear previous preview
        mapPreview.innerHTML = '';
        
        // Create a temporary div to parse the HTML
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = iframeCode;
        
        // Get the iframe element
        const iframe = tempDiv.querySelector('iframe');
        
        if (iframe) {
            // Ensure the iframe has proper dimensions
            iframe.style.width = '100%';
            iframe.style.height = '400px';
            iframe.style.border = '1px solid #ddd';
            iframe.style.borderRadius = '8px';
            
            // Append to preview container
            mapPreview.appendChild(iframe);
            mapPreviewContainer.style.display = 'block';
            
            // Scroll to preview
            mapPreviewContainer.scrollIntoView({ behavior: 'smooth' });
        } else {
            Swal.fire('Error', 'No valid iframe found in the provided code', 'error');
        }
    });

    // Form validation
    const form = document.querySelector('.contact-form');
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
                
                // Special validation for map embed
                if (field.id === 'location' && field.value.trim() && !field.value.trim().startsWith('<iframe')) {
                    field.classList.add('is-invalid');
                    isValid = false;
                    
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('error-message')) {
                        const error = document.createElement('div');
                        error.className = 'error-message';
                        error.textContent = 'Map embed must be an iframe code';
                        field.parentNode.insertBefore(error, field.nextSibling);
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

    // Social icon hover effects
    const socialIcons = document.querySelectorAll('.social-icon');
    socialIcons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
        });
        icon.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
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

// Confirmation dialog for cancel
document.querySelector('.btn-cancel').addEventListener('click', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Are you sure?',
        text: "You have unsaved changes that will be lost!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, leave page'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = e.target.closest('a').href;
        }
    });
});
</script>

<style>
    /* Form Layout */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
    
    .form-column {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    /* Form Elements */
    .form-group {
        position: relative;
    }
    
    .form-group label {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
        font-weight: 500;
        color: #3b82f6;
    }
    
    .form-group label i {
        font-size: 18px;
        width: 20px;
        text-align: center;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s ease;
        background-color: #f8fafc;
    }
    
    .form-control:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background-color: white;
    }
    
    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }
    
    /* Social Media Section - New Design */
    .social-media-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .social-input-group {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
        border-radius: 8px;
        padding: 5px;
        transition: all 0.3s ease;
    }
    
    .social-input-group:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .social-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    
    /* Platform-specific colors */
    .facebook .social-icon {
        background: #3b5998;
    }
    
    .twitter .social-icon {
        background: #1da1f2;
    }
    
    .linkedin .social-icon {
        background: #0077b5;
    }
    
    .instagram .social-icon {
        background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);
    }
    
    .social-input-group input {
        flex: 1;
        border: none;
        background: transparent;
        padding-left: 0;
    }
    
    .social-input-group input:focus {
        background: white;
        border: 1px solid #e2e8f0;
        padding: 12px 15px;
        margin-left: -15px;
    }
    
    /* Map Preview */
    .btn-preview {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 8px;
        margin-top: 10px;
        cursor: pointer;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-preview:hover {
        background: #2563eb;
        transform: translateY(-2px);
    }
    
    .map-preview {
        width: 100%;
        height: 400px;
        background: #f5f5f5;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    
    .preview-title {
        color: #3b82f6;
        margin-bottom: 10px;
        font-size: 16px;
    }
    
    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
    
    .btn-submit {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-submit:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }
    
    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .btn-cancel:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }
    
    /* Error Handling */
    .error-message {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection