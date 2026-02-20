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
            <h1><i class="fas fa-project-diagram"></i> Project Details</h1>
            <p>View comprehensive information about this project</p>
        </div>
        <a href="{{ route('admin.projects.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Projects
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="project-detail-card">
        <div class="card-body">
            <!-- Image and Main Info -->
            <div class="project-header">
                @if($project->image)
                <div class="project-image-container" onclick="openModal('{{ asset('storage/' . $project->image) }}')">
                    <img src="{{ asset('storage/' . $project->image) }}" class="project-image" alt="Project Image">
                    <div class="image-label">
                        <i class="fas fa-eye"></i> Click to view
                    </div>
                </div>
                @else
                <div class="project-image-placeholder">
                    <i class="fas fa-building"></i>
                </div>
                @endif
                
                <div class="project-title-section">
                    <h2>{{ $project->title }}</h2>
                    <div class="project-meta">
                        <span class="location-badge">
                            <i class="fas fa-map-marker-alt"></i> {{ $project->location }}
                        </span>
                        <span class="region-badge">
                            <i class="fas fa-map"></i> {{ $project->region }}
                        </span>
                        <span class="featured-badge {{ $project->is_featured ? 'featured' : 'not-featured' }}">
                            <i class="fas {{ $project->is_featured ? 'fa-star' : 'fa-star-o' }}"></i>
                            {{ $project->is_featured ? 'Featured' : 'Regular' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="detail-section">
                <div class="detail-grid">
                    <div class="detail-item full-width">
                        <h5 class="detail-label">
                            <i class="fas fa-align-left"></i> Description
                        </h5>
                        <div class="detail-value">
                            {!! nl2br(e($project->description)) !!}
                        </div>
                    </div>

                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-link"></i> URL Slug
                        </h5>
                        <p class="detail-value">{{ $project->slug }}</p>
                    </div>

                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-calendar-alt"></i> Created At
                        </h5>
                        <p class="detail-value">{{ $project->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-clock"></i> Last Updated
                        </h5>
                        <p class="detail-value">{{ $project->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i> Edit Project
                </a>
                
                <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete" onclick="return confirmDelete()">
                        <i class="fas fa-trash"></i> Delete Project
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
        return confirm('Are you sure you want to delete this project? This action cannot be undone.');
    }

    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
        // Stagger animations for detail items
        const detailItems = document.querySelectorAll('.detail-item');
        detailItems.forEach((item, index) => {
            item.style.animationDelay = `${0.8 + (index * 0.1)}s`;
            item.style.animation = 'fadeInUp 0.6s ease-out both';
        });

        // Add hover effects to image
        const imageContainer = document.querySelector('.project-image-container');
        if (imageContainer) {
            imageContainer.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.05)';
            });
            imageContainer.addEventListener('mouseleave', function() {
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
        .project-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .project-image-container {
            width: 200px;
            height: 150px;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .project-image {
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
        .project-image-container:hover .image-label {
            opacity: 1;
        }
        .project-image-placeholder {
            width: 200px;
            height: 150px;
            border-radius: 12px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #9ca3af;
        }
        .project-title-section {
            flex: 1;
        }
        .project-title-section h2 {
            margin: 0 0 0.5rem 0;
            font-size: 1.8rem;
            color: #111827;
        }
        .project-meta {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .location-badge {
            background: #e0f2fe;
            color: #0369a1;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .region-badge {
            background: #ecfdf5;
            color: #047857;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .featured-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .featured-badge.featured {
            background: #fef3c7;
            color: #92400e;
        }
        .featured-badge.not-featured {
            background: #f3f4f6;
            color: #6b7280;
        }
        .full-width {
            grid-column: 1 / -1;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection