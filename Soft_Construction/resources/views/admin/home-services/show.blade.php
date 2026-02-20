@extends('admin.layouts.app')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('admin/assets/css/show.css') }}" rel="stylesheet">
<style>
    /* Enhanced Image Styling */
    .service-image-container {
        width: 100%;
        max-width: 400px;
        height: 250px;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
        margin-right: 30px;
    }
    
    .service-image-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        border-color: #3b82f6;
    }
    
    .service-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transition: transform 0.5s ease;
    }
    
    .service-image-container:hover .service-image {
        transform: scale(1.05);
    }
    
    .image-label {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 8px 15px;
        font-size: 14px;
        display: flex;
        align-items: center;
    }
    
    .image-label i {
        margin-right: 8px;
    }
    
    /* Service Overview Layout */
    .service-overview {
        display: flex;
        align-items: flex-start;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    
    .service-basic-info {
        flex: 1;
        min-width: 300px;
    }
    
    .service-title {
        font-size: 28px;
        margin-bottom: 15px;
        color: #1e293b;
    }
    
    .service-meta {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    
    .order-badge {
        background: #e0f2fe;
        color: #0369a1;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
    }
    
    .order-badge i {
        margin-right: 6px;
    }
    
    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
    }
    
    .status-badge.active {
        background: #dcfce7;
        color: #15803d;
    }
    
    .status-badge.inactive {
        background: #fee2e2;
        color: #b91c1c;
    }
    
    .status-badge i {
        margin-right: 6px;
    }
    
    .service-description {
        background: #f8fafc;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .service-description h5 {
        margin-bottom: 10px;
        color: #475569;
        display: flex;
        align-items: center;
    }
    
    .service-description h5 i {
        margin-right: 8px;
        color: #3b82f6;
    }
    
    .service-description p {
        color: #334155;
        line-height: 1.6;
    }
    
    .button-preview button {
        padding: 8px 16px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
    }
    
    .button-preview button i {
        margin-right: 8px;
    }
    
    /* Features Section */
    .features-section {
        margin-bottom: 30px;
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
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .feature-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        display: flex;
        align-items: flex-start;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    .feature-icon {
        width: 50px;
        height: 50px;
        background: #e0f2fe;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: #0369a1;
        font-size: 20px;
        flex-shrink: 0;
    }
    
    .feature-content h4 {
        margin-bottom: 8px;
        color: #1e293b;
    }
    
    .feature-content p {
        color: #64748b;
        font-size: 14px;
        line-height: 1.5;
    }
    
    /* Additional Details */
    .additional-details {
        margin-bottom: 30px;
    }
    
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
    }
    
    .detail-item {
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    
    .detail-label {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
    }
    
    .detail-label i {
        margin-right: 8px;
        color: #3b82f6;
    }
    
    .detail-value {
        color: #1e293b;
        font-weight: 500;
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .btn-action {
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
    }
    
    .btn-action i {
        margin-right: 8px;
    }
    
    .btn-edit {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    
    .btn-edit:hover {
        background: #dcfce7;
    }
    
    .btn-delete {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }
    
    .btn-delete:hover {
        background: #fecaca;
    }
    
    /* Image Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.8);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        cursor: pointer;
    }
    
    .modal-content {
        max-width: 90%;
        max-height: 90%;
        position: relative;
        cursor: default;
    }
    
    .modal-content img {
        max-width: 100%;
        max-height: 80vh;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }
    
    .modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255,255,255,0.2);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: white;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .modal-close:hover {
        background: rgba(255,255,255,0.3);
        transform: rotate(90deg);
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .service-overview {
            flex-direction: column;
        }
        
        .service-image-container {
            margin-right: 0;
            margin-bottom: 20px;
            width: 100%;
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
            <h1><i class="fas fa-cogs"></i> Service Details</h1>
            <p>View comprehensive information about this home service</p>
        </div>
        <a href="{{ route('admin.home-services.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Services
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="service-detail-card">
        <div class="card-body">
            <!-- Service Overview -->
            <div class="service-overview">
                @if($service->image)
                <div class="service-image-container" onclick="openModal('{{ asset('storage/' . $service->image) }}')">
                    <div class="image-label">
                        <i class="fas fa-image"></i> Service Image
                    </div>
                    <img src="{{ asset('storage/' . $service->image) }}" class="service-image" alt="Service Image">
                </div>
                @endif
                
                <div class="service-basic-info">
                    <h2 class="service-title">{{ $service->title }}</h2>
                    
                    <div class="service-meta">
                        <span class="order-badge">
                            <i class="fas fa-sort-numeric-down"></i> Order: {{ $service->order }}
                        </span>
                        <span class="status-badge {{ $service->is_active ? 'active' : 'inactive' }}">
                            <i class="fas {{ $service->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $service->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div class="service-description">
                        <h5><i class="fas fa-align-left"></i> Description</h5>
                        <p>{{ $service->description }}</p>
                    </div>
                    
                    @if($service->button_text)
                    <div class="button-preview">
                        <button class="btn btn-primary">
                            <i class="fas fa-hand-pointer"></i> {{ $service->button_text }}
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Features Section -->
            <div class="features-section">
                <h3 class="section-title">
                    <i class="fas fa-star"></i> Service Features
                </h3>
                
                <div class="features-grid">
                    <!-- Feature 1 -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="{{ $service->feature_icon_1 }}"></i>
                        </div>
                        <div class="feature-content">
                            <h4>{{ $service->feature_title_1 }}</h4>
                            <p>{{ $service->feature_description_1 }}</p>
                        </div>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="{{ $service->feature_icon_2 }}"></i>
                        </div>
                        <div class="feature-content">
                            <h4>{{ $service->feature_title_2 }}</h4>
                            <p>{{ $service->feature_description_2 }}</p>
                        </div>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="{{ $service->feature_icon_3 }}"></i>
                        </div>
                        <div class="feature-content">
                            <h4>{{ $service->feature_title_3 }}</h4>
                            <p>{{ $service->feature_description_3 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="additional-details">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i> Additional Information
                </h3>
                
                <div class="details-grid">
                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-calendar-alt"></i> Created At
                        </h5>
                        <p class="detail-value">{{ $service->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    
                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-clock"></i> Last Updated
                        </h5>
                        <p class="detail-value">{{ $service->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('admin.home-services.edit', $service->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i> Edit Service
                </a>
                
                <form action="{{ route('admin.home-services.destroy', $service->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete" onclick="return confirmDelete()">
                        <i class="fas fa-trash"></i> Delete Service
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Modal for Image Preview -->
<div class="modal-overlay" id="imageModal" onclick="closeModal()">
    <button class="modal-close" onclick="closeModal()">
        <i class="fas fa-times"></i>
    </button>
    <div class="modal-content">
        <img id="modalImage" src="" alt="Full Size Image">
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Image modal functionality
    function openModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Close modal with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Confirmation dialog for delete
    function confirmDelete() {
        return Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            return result.isConfirmed;
        });
    }

    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animate feature cards
        const featureCards = document.querySelectorAll('.feature-card');
        featureCards.forEach((card, index) => {
            card.style.animationDelay = `${0.5 + (index * 0.2)}s`;
            card.style.opacity = '0';
            card.style.animation = 'fadeInUp 0.6s ease-out forwards';
        });

        // Add hover effects to elements
        const hoverElements = document.querySelectorAll('.feature-card, .service-image-container');
        hoverElements.forEach(el => {
            el.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
            });
            el.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.05)';
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
    `;
    document.head.appendChild(style);
</script>
@endsection