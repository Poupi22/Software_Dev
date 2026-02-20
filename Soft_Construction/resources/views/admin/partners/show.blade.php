@extends('admin.layouts.app')
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('admin/assets/css/show.css') }}" rel="stylesheet">
<style>
    /* Partner-specific styling */
    .partner-detail-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .logo-container {
        width: 100%;
        max-height: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f8fafc;
        padding: 40px;
        border-radius: 12px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    
    .logo-container img {
        max-width: 100%;
        max-height: 250px;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    
    .logo-container:hover img {
        transform: scale(1.05);
    }
    
    .logo-label {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 12px 20px;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .logo-label i {
        margin-right: 10px;
    }
    
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .detail-item {
        background: #f8fafc;
        padding: 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .detail-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .detail-label {
        font-size: 16px;
        color: #64748b;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    
    .detail-label i {
        margin-right: 10px;
        color: #3b82f6;
    }
    
    .detail-value {
        font-size: 18px;
        color: #1e293b;
        font-weight: 500;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .status-badge.active {
        background: #f0fdf4;
        color: #15803d;
    }
    
    .status-badge.inactive {
        background: #fee2e2;
        color: #b91c1c;
    }
    
    .status-badge i {
        margin-right: 6px;
    }
    
    .action-buttons {
        display: flex;
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
    
    .btn-edit {
        background: #3b82f6;
        color: white;
    }
    
    .btn-edit:hover {
        background: #2563eb;
    }
    
    .btn-delete {
        background: #fee2e2;
        color: #b91c1c;
    }
    
    .btn-delete:hover {
        background: #fecaca;
    }
    
    /* Modal styling */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    
    .modal-content {
        max-width: 90%;
        max-height: 90%;
    }
    
    .modal-content img {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
    }
    
    .modal-close {
        position: absolute;
        top: 30px;
        right: 30px;
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
            <h1><i class="fas fa-handshake"></i> Partner Details</h1>
            <p>View comprehensive information about this partner</p>
        </div>
        <a href="{{ route('admin.partners.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Partners
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="partner-detail-card">
        <div class="card-body">
            <!-- Logo Section -->
            <div class="logo-container" onclick="openModal('{{ $partner->logo_url }}')">
                <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }} Logo">
                <div class="logo-label">
                    <i class="fas fa-image"></i> Partner Logo
                </div>
            </div>

            <!-- Details Section -->
            <div class="detail-section">
                <div class="detail-grid">
                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-heading"></i> Name
                        </h5>
                        <p class="detail-value">{{ $partner->name }}</p>
                    </div>
                    
                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-align-left"></i> Description
                        </h5>
                        <p class="detail-value">{{ $partner->description ?? 'N/A' }}</p>
                    </div>

                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-globe"></i> Website
                        </h5>
                        <p class="detail-value">
                            @if($partner->website)
                            <a href="{{ $partner->website }}" target="_blank" rel="noopener noreferrer">
                                {{ $partner->website }}
                            </a>
                            @else
                            N/A
                            @endif
                        </p>
                    </div>
                    
                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-sort-numeric-down"></i> Order
                        </h5>
                        <p class="detail-value">{{ $partner->order }}</p>
                    </div>
                    
                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-toggle-on"></i> Status
                        </h5>
                        <span class="status-badge {{ $partner->is_active ? 'active' : 'inactive' }}">
                            <i class="fas {{ $partner->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $partner->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-calendar-alt"></i> Created At
                        </h5>
                        <p class="detail-value">{{ $partner->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-clock"></i> Last Updated
                        </h5>
                        <p class="detail-value">{{ $partner->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('admin.partners.edit', $partner->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i> Edit Partner
                </a>
                
                <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete" onclick="return confirmDelete()">
                        <i class="fas fa-trash"></i> Delete Partner
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Modal for Logo Preview -->
<div class="modal-overlay" id="logoModal" onclick="closeModal()">
    <button class="modal-close" onclick="closeModal()">
        <i class="fas fa-times"></i>
    </button>
    <div class="modal-content">
        <img id="modalLogo" src="" alt="Full Size Logo">
    </div>
</div>

<script>
    // Logo modal functionality
    function openModal(logoUrl) {
        const modal = document.getElementById('logoModal');
        const modalLogo = document.getElementById('modalLogo');
        modalLogo.src = logoUrl;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        const modal = document.getElementById('logoModal');
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
        // Stagger animations for detail items
        const detailItems = document.querySelectorAll('.detail-item');
        detailItems.forEach((item, index) => {
            item.style.animationDelay = `${0.8 + (index * 0.1)}s`;
            item.style.animation = 'fadeInUp 0.6s ease-out both';
        });

        // Add hover effects to logo
        const logoContainer = document.querySelector('.logo-container');
        if (logoContainer) {
            logoContainer.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.querySelector('img').style.transform = 'scale(1.05)';
            });
            logoContainer.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.querySelector('img').style.transform = 'scale(1)';
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