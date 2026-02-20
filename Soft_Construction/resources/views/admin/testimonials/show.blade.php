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
            <h1><i class="fas fa-quote-left"></i> Testimonial Details</h1>
            <p>View comprehensive information about this testimonial</p>
        </div>
        <a href="{{ route('admin.testimonials.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Testimonials
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="testimonial-detail-card">
        <div class="card-body">
            <!-- Author and Avatar -->
            <div class="testimonial-header">
                @if($testimonial->avatar)
                <div class="testimonial-avatar-container" onclick="openModal('{{ asset('storage/' . $testimonial->avatar) }}')">
                    <img src="{{ asset('storage/' . $testimonial->avatar) }}" class="testimonial-avatar" alt="Author Avatar">
                    <div class="image-label">
                        <i class="fas fa-eye"></i> Click to view
                    </div>
                </div>
                @else
                <div class="testimonial-avatar-placeholder">
                    <i class="fas fa-user-circle"></i>
                </div>
                @endif
                
                <div class="testimonial-author-section">
                    <h2>{{ $testimonial->name }}</h2>
                    <div class="testimonial-meta">
                        <span class="position-badge">
                            <i class="fas fa-briefcase"></i> {{ $testimonial->position }}
                        </span>
                        <span class="status-badge {{ $testimonial->is_active ? 'active' : 'inactive' }}">
                            <i class="fas {{ $testimonial->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->fullStars())
                                    <i class="fas fa-star"></i>
                                @elseif($testimonial->hasHalfStar() && $i == ceil($testimonial->rating))
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                            <span class="rating-value">{{ $testimonial->rating }}/5</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="detail-section">
                <div class="detail-grid">
                    <div class="detail-item full-width">
                        <h5 class="detail-label">
                            <i class="fas fa-comment"></i> Testimonial Content
                        </h5>
                        <div class="detail-value testimonial-content">
                            "{{ $testimonial->content }}"
                        </div>
                    </div>

                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-calendar-alt"></i> Created At
                        </h5>
                        <p class="detail-value">{{ $testimonial->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-clock"></i> Last Updated
                        </h5>
                        <p class="detail-value">{{ $testimonial->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i> Edit Testimonial
                </a>
                
                <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete" onclick="return confirmDelete()">
                        <i class="fas fa-trash"></i> Delete Testimonial
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
        return confirm('Are you sure you want to delete this testimonial? This action cannot be undone.');
    }

    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
        // Stagger animations for detail items
        const detailItems = document.querySelectorAll('.detail-item');
        detailItems.forEach((item, index) => {
            item.style.animationDelay = `${0.8 + (index * 0.1)}s`;
            item.style.animation = 'fadeInUp 0.6s ease-out both';
        });

        // Add hover effects to avatar
        const avatarContainer = document.querySelector('.testimonial-avatar-container');
        if (avatarContainer) {
            avatarContainer.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.05)';
            });
            avatarContainer.addEventListener('mouseleave', function() {
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
        .testimonial-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .testimonial-avatar-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .testimonial-avatar {
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
        .testimonial-avatar-container:hover .image-label {
            opacity: 1;
        }
        .testimonial-avatar-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #9ca3af;
        }
        .testimonial-author-section {
            flex: 1;
        }
        .testimonial-author-section h2 {
            margin: 0 0 0.5rem 0;
            font-size: 1.8rem;
            color: #111827;
        }
        .testimonial-meta {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .position-badge {
            background: #e0f2fe;
            color: #0369a1;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .status-badge.active {
            background: #ecfdf5;
            color: #047857;
        }
        .status-badge.inactive {
            background: #fee2e2;
            color: #b91c1c;
        }
        .rating-stars {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        .rating-stars i {
            color: #FFD700;
            font-size: 1rem;
        }
        .rating-value {
            margin-left: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }
        .testimonial-content {
            font-style: italic;
            font-size: 1.1rem;
            line-height: 1.6;
            color: #374151;
            padding: 1rem;
            background: #f9fafb;
            border-left: 4px solid #4f46e5;
            border-radius: 0 4px 4px 0;
        }
        .full-width {
            grid-column: 1 / -1;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection