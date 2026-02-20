@extends('admin.layouts.app')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

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
            <h1><i class="fas fa-edit"></i> Edit Contact Information</h1>
            <p>Update your website's contact details</p>
        </div>
        <a href="{{ route('admin.contacts.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Contacts
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="form-card">
        <div class="form-body">
            <form id="contactForm" action="{{ route('admin.contacts.update', $contact->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Contact Information Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-info-circle"></i>
                        Contact Information
                    </h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="address" class="form-label required">
                                <i class="fas fa-map-marker-alt"></i> Address
                            </label>
                            <textarea class="form-input" id="address" name="address" required>{{ old('address', $contact->address) }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="telephone" class="form-label required">
                                <i class="fas fa-phone"></i> Phone Number
                            </label>
                            <input type="text" class="form-input" id="telephone" name="telephone" value="{{ old('telephone', $contact->telephone) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label required">
                                <i class="fas fa-envelope"></i> Email Address
                            </label>
                            <input type="email" class="form-input" id="email" name="email" value="{{ old('email', $contact->email) }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="heuredouverture" class="form-label required">
                                <i class="fas fa-clock"></i> Opening Hours
                            </label>
                            <textarea class="form-input" id="heuredouverture" name="heuredouverture" required>{{ old('heuredouverture', $contact->heuredouverture) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Social Media Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-share-alt"></i>
                        Social Media Links
                    </h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="facebook" class="form-label">
                                <i class="fab fa-facebook-f"></i> Facebook URL
                            </label>
                            <input type="url" class="form-input" id="facebook" name="facebook" value="{{ old('facebook', $contact->facebook) }}" placeholder="https://facebook.com/yourpage">
                        </div>

                        <div class="form-group">
                            <label for="twitter" class="form-label">
                                <i class="fab fa-twitter"></i> Twitter URL
                            </label>
                            <input type="url" class="form-input" id="twitter" name="twitter" value="{{ old('twitter', $contact->twitter) }}" placeholder="https://twitter.com/yourhandle">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="linkedin" class="form-label">
                                <i class="fab fa-linkedin-in"></i> LinkedIn URL
                            </label>
                            <input type="url" class="form-input" id="linkedin" name="linkedin" value="{{ old('linkedin', $contact->linkedin) }}" placeholder="https://linkedin.com/yourprofile">
                        </div>

                        <div class="form-group">
                            <label for="instagram" class="form-label">
                                <i class="fab fa-instagram"></i> Instagram URL
                            </label>
                            <input type="url" class="form-input" id="instagram" name="instagram" value="{{ old('instagram', $contact->instagram) }}" placeholder="https://instagram.com/yourprofile">
                        </div>
                    </div>
                </div>

                <!-- Map Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-map-marked-alt"></i>
                        Map Embed
                    </h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="location" class="form-label required">
                                <i class="fas fa-code"></i> Map Embed Code
                            </label>
                            <textarea class="form-input" id="location" name="location" required>{{ old('location', $contact->location) }}</textarea>
                            
                            <button type="button" id="preview-map" class="btn-preview">
                                <i class="fas fa-eye"></i> Preview Map
                            </button>
                            
                            <div id="map-preview-container" class="mt-3" style="display: none;">
                                <h5 class="preview-title">Map Preview</h5>
                                <div id="map-preview" class="map-preview"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('admin.contacts.index') }}" class="btn-action btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    
                    <button type="submit" class="btn-action btn-save">
                        <i class="fas fa-save"></i> Update Contact
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    // Form submission handling
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        const submitBtn = document.querySelector('.btn-save');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
        submitBtn.disabled = true;
    });

    // Map Preview Functionality
    document.addEventListener('DOMContentLoaded', function() {
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

<style>
    /* Base Styles */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Header Styles */
    .header {
        background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
        color: white;
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .header p {
        margin: 5px 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .back-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .back-btn i {
        margin-right: 8px;
    }

    /* Form Card Styles */
    .form-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .form-body {
        padding: 30px;
    }

    /* Form Section Styles */
    .form-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
        opacity: 0;
    }

    .section-title {
        font-size: 18px;
        color: #3b82f6;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        font-size: 20px;
    }

    /* Form Row Styles */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    /* Form Group Styles */
    .form-group {
        position: relative;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #4b5563;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label.required::after {
        content: '*';
        color: #ef4444;
        margin-left: 4px;
    }

    .form-label i {
        font-size: 16px;
        width: 20px;
        text-align: center;
    }

    .form-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s ease;
        background-color: #f8fafc;
    }

    .form-input:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background-color: white;
    }

    textarea.form-input {
        min-height: 100px;
        resize: vertical;
    }

    /* Map Preview Styles */
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

    /* Action Buttons */
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }

    .btn-action {
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

    .btn-save {
        background: #3b82f6;
        color: white;
        border: none;
    }

    .btn-save:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
        border: none;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
    }

    /* Particles */
    .particles {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        overflow: hidden;
    }

    .particle {
        position: absolute;
        background: rgba(59, 130, 246, 0.1);
        border-radius: 50%;
        animation: float 15s infinite linear;
    }

    .particle:nth-child(1) {
        width: 100px;
        height: 100px;
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }

    .particle:nth-child(2) {
        width: 150px;
        height: 150px;
        top: 60%;
        left: 70%;
        animation-delay: 2s;
    }

    .particle:nth-child(3) {
        width: 80px;
        height: 80px;
        top: 80%;
        left: 30%;
        animation-delay: 4s;
    }

    .particle:nth-child(4) {
        width: 120px;
        height: 120px;
        top: 40%;
        left: 50%;
        animation-delay: 6s;
    }

    .particle:nth-child(5) {
        width: 60px;
        height: 60px;
        top: 10%;
        left: 80%;
        animation-delay: 8s;
    }

    .particle:nth-child(6) {
        width: 90px;
        height: 90px;
        top: 30%;
        left: 20%;
        animation-delay: 1s;
    }

    .particle:nth-child(7) {
        width: 110px;
        height: 110px;
        top: 70%;
        left: 40%;
        animation-delay: 3s;
    }

    .particle:nth-child(8) {
        width: 70px;
        height: 70px;
        top: 50%;
        left: 60%;
        animation-delay: 5s;
    }

    @keyframes float {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(-1000px) rotate(720deg);
            opacity: 0;
        }
    }

    /* Animations */
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

    /* Responsive Styles */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .back-btn {
            margin-top: 15px;
            width: 100%;
            justify-content: center;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection