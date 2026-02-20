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
            <h1><i class="fas fa-user-tie"></i> Team Member Details</h1>
            <p>View comprehensive information about this team member</p>
        </div>
        <a href="{{ route('admin.personnels.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Team
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="personnel-detail-card">
        <div class="card-body">
            <!-- Profile and Main Info -->
            <div class="personnel-header">
                @if($personnel->image_path)
                <div class="profile-image-container" onclick="openModal('{{ $personnel->image_url }}')">
                    <img src="{{ $personnel->image_url }}" class="profile-image" alt="Profile Image">
                    <div class="image-label">
                        <i class="fas fa-eye"></i> Click to view
                    </div>
                </div>
                @else
                <div class="profile-image-placeholder">
                    <i class="fas fa-user-tie"></i>
                </div>
                @endif
                
                <div class="personnel-title-section">
                    <h2>{{ $personnel->name }}</h2>
                    <div class="position-badge">{{ $personnel->position }}</div>
                    <span class="status-badge {{ $personnel->is_active ? 'active' : 'inactive' }}">
                        <i class="fas {{ $personnel->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                        {{ $personnel->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <!-- Details Section -->
            <div class="detail-section">
                <div class="detail-grid">
                    <div class="detail-item full-width">
                        <h5 class="detail-label">
                            <i class="fas fa-file-alt"></i> Professional Bio
                        </h5>
                        <div class="detail-value">
                            {!! nl2br(e($personnel->description)) !!}
                        </div>
                    </div>

                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-sort-numeric-down"></i> Display Order
                        </h5>
                        <p class="detail-value">{{ $personnel->order }}</p>
                    </div>
                    
                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-calendar-alt"></i> Created At
                        </h5>
                        <p class="detail-value">{{ $personnel->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-clock"></i> Last Updated
                        </h5>
                        <p class="detail-value">{{ $personnel->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('admin.personnels.edit', $personnel->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i> Edit Member
                </a>
                
                <form action="{{ route('admin.personnels.destroy', $personnel->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete" onclick="return confirmDelete()">
                        <i class="fas fa-trash"></i> Delete Member
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

        // Add hover effects to profile image
        const profileImage = document.querySelector('.profile-image-container');
        if (profileImage) {
            profileImage.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.05)';
            });
            profileImage.addEventListener('mouseleave', function() {
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
        .personnel-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .profile-image-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 4px solid #fff;
        }
        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .image-label {
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
        .profile-image-container:hover .image-label {
            opacity: 1;
        }
        .profile-image-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #9ca3af;
            border: 4px solid #fff;
        }
        .personnel-title-section {
            flex: 1;
        }
        .personnel-title-section h2 {
            margin: 0 0 0.5rem 0;
            font-size: 1.8rem;
            color: #111827;
        }
        .position-badge {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .full-width {
            grid-column: 1 / -1;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection