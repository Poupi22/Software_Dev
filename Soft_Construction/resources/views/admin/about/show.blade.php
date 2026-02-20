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
            <h1><i class="fas fa-info-circle"></i> About Section Details</h1>
            <p>View comprehensive information about this about section</p>
        </div>
        <a href="{{ route('admin.abouts.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to About
        </a>
    </div>
</header>

<!-- Main Content -->
<main class="container">
    <div class="detail-card">
        <div class="card-body">
            <!-- Image and Experience Badge -->
            <div class="image-section">
                <div class="image-container" onclick="openModal('{{ $about->image ? asset('storage/' . $about->image) : asset('admin/assets/img/default-about.jpg') }}')">
                    <div class="image-label">
                        <i class="fas fa-image"></i> About Image
                    </div>
                    <img src="{{ $about->image ? asset('storage/' . $about->image) : asset('admin/assets/img/default-about.jpg') }}" class="about-image" alt="About Section Image">
                    <div class="experience-badge">
                        <h3>{{ $about->experience_years }}</h3>
                        <p>{{ $about->experience_text }}</p>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="detail-section">
                <div class="detail-grid">
                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-heading"></i> Title
                        </h5>
                        <p class="detail-value">{{ $about->title }}</p>
                    </div>
                    
                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-text-height"></i> Subtitle
                        </h5>
                        <p class="detail-value">{{ $about->subtitle }}</p>
                    </div>

                    <div class="detail-item full-width">
                        <h5 class="detail-label">
                            <i class="fas fa-align-left"></i> First Description
                        </h5>
                        <p class="detail-value">{{ $about->description1 }}</p>
                    </div>

                    <div class="detail-item full-width">
                        <h5 class="detail-label">
                            <i class="fas fa-align-left"></i> Second Description
                        </h5>
                        <p class="detail-value">{{ $about->description2 }}</p>
                    </div>
                    
                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-calendar-alt"></i> Created At
                        </h5>
                        <p class="detail-value">{{ $about->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    <div class="detail-item">
                        <h5 class="detail-label">
                            <i class="fas fa-clock"></i> Last Updated
                        </h5>
                        <p class="detail-value">{{ $about->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            @if($about->features && count($about->features) > 0)
            <div class="features-section">
                <h4 class="section-title">
                    <i class="fas fa-list-check"></i> Features
                </h4>
                <div class="features-grid">
                    @foreach($about->features as $feature)
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="{{ $feature['icon'] }}"></i>
                        </div>
                        <div class="feature-content">
                            <h5>{{ $feature['title'] }}</h5>
                            <p>{{ $feature['description'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('admin.abouts.edit', $about->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i> Edit About Section
                </a>
                
                <form action="{{ route('admin.abouts.destroy', $about->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete" onclick="return confirmDelete()">
                        <i class="fas fa-trash"></i> Delete About Section
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
        return confirm('Are you sure you want to delete this about section? This action cannot be undone.');
    }

    // Initialize animations
    document.addEventListener('DOMContentLoaded', function() {
        // Stagger animations for detail items
        const detailItems = document.querySelectorAll('.detail-item');
        detailItems.forEach((item, index) => {
            item.style.animationDelay = `${0.8 + (index * 0.1)}s`;
            item.style.animation = 'fadeInUp 0.6s ease-out both';
        });

        // Add hover effects to feature items
        const featureItems = document.querySelectorAll('.feature-item');
        featureItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
            });
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 2px 5px rgba(0,0,0,0.05)';
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
</script>

<style>
    /* Custom styles for about show page */
    .image-section {
        position: relative;
        margin-bottom: 30px;
    }
    
    .about-image {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .image-container:hover .about-image {
        transform: scale(1.02);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .experience-badge {
        position: absolute;
        bottom: -20px;
        right: 20px;
        background: #1e40af;
        color: white;
        padding: 15px 25px;
        border-radius: 50px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(30, 64, 175, 0.3);
    }
    
    .experience-badge h3 {
        font-size: 2rem;
        margin: 0;
        line-height: 1;
    }
    
    .experience-badge p {
        margin: 0;
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .features-section {
        margin-top: 40px;
    }
    
    .section-title {
        color: #1e40af;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .feature-item {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        display: flex;
        gap: 15px;
        align-items: flex-start;
    }
    
    .feature-icon {
        font-size: 1.5rem;
        color: #1e40af;
        background: rgba(30, 64, 175, 0.1);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .feature-content h5 {
        margin: 0 0 5px 0;
        color: #1e40af;
    }
    
    .feature-content p {
        margin: 0;
        color: #666;
    }
    
    .full-width {
        grid-column: 1 / -1;
    }
</style>
@endsection