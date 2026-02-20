@extends('admin.layouts.app')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('admin/assets/css/show.css') }}" rel="stylesheet">

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
            <h1><i class="fas fa-concierge-bell"></i> Service Details</h1>
            <p>View comprehensive information about this service</p>
        </div>
        <a href="{{ route('admin.services.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Services
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="service-detail-card">
        <div class="card-body">
            <!-- Icon and Main Info -->
            <div class="service-header">
                @if($service->icon)
                <div class="service-icon-container" onclick="openModal('{{ asset('storage/' . $service->icon) }}')">
                    <img src="{{ asset('storage/' . $service->icon) }}" class="service-icon" alt="Service Icon">
                    <div class="icon-label">
                        <i class="fas fa-eye"></i> Click to view
                    </div>
                </div>
                @else
                <div class="service-icon-placeholder">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                @endif
                
                <div class="service-title-section">
                    <h2>{{ $service->title }}</h2>
                    <span class="status-badge {{ $service->active ? 'active' : 'inactive' }}">
                        <i class="fas {{ $service->active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $service->active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <!-- Details Section -->
            <div class="detail-section">
                <div class="detail-grid">
                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-align-left"></i> Short Description
                        </h5>
                        <p class="detail-value">{{ $service->short_description }}</p>
                    </div>
                    
                    <div class="detail-item full-width">
                        <h5 class="detail-label">
                            <i class="fas fa-align-justify"></i> Long Description
                        </h5>
                        <div class="detail-value">
                            {!! nl2br(e($service->long_description)) !!}
                        </div>
                    </div>

                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-sort-numeric-down"></i> Display Order
                        </h5>
                        <p class="detail-value">{{ $service->order }}</p>
                    </div>
                    
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
                <a href="{{ route('admin.services.edit', $service->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i> Edit Service
                </a>
                
                <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline">
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

<script>
    // Image modal functionality
    function openModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        modal.style.display = 'block';
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
        return confirm('Are you sure you want to delete this service? This action cannot be undone.');
    }

    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
        // Stagger animations for detail items
        const detailItems = document.querySelectorAll('.detail-item');
        detailItems.forEach((item, index) => {
            item.style.animationDelay = `${0.8 + (index * 0.1)}s`;
            item.style.animation = 'fadeInUp 0.6s ease-out both';
        });

        // Add hover effects to icon
        const icon = document.querySelector('.service-icon-container');
        if (icon) {
            icon.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.05)';
            });
            icon.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
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

    // Add CSS animation for fade out
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
        }
        .service-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .service-icon-container {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .service-icon {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .icon-label {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 0.5rem;
            text-align: center;
            font-size: 0.8rem;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .service-icon-container:hover .icon-label {
            opacity: 1;
        }
        .service-icon-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #9ca3af;
        }
        .service-title-section {
            flex: 1;
        }
        .service-title-section h2 {
            margin: 0 0 0.5rem 0;
            font-size: 1.8rem;
            color: #111827;
        }
        .full-width {
            grid-column: 1 / -1;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection