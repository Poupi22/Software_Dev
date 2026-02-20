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
                <h1><i class="fas fa-info-circle"></i> About Section Management</h1>
                <p>Manage your website's about section content and features</p>
            </div>
            <a href="{{ route('admin.abouts.create') }}" class="add-btn">
                <i class="fas fa-plus"></i>
                Add New Content
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        @if (session('success'))
            <div class="alert">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Data Table Card -->
        <div class="card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-heading"></i> Title</th>
                            <th><i class="fas fa-text-height"></i> Subtitle</th>
                            <th><i class="fas fa-image"></i> Image</th>
                            <th><i class="fas fa-award"></i> Experience</th>
                            <th><i class="fas fa-list"></i> Features</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($abouts as $about)
                        <tr>
                            <td><strong>{{ $about->title }}</strong></td>
                            <td>{{ \Illuminate\Support\Str::limit($about->subtitle, 50) }}</td>
                            <td>
                                <div class="slide-images">
                                    @if($about->image)
                                    <img src="{{ asset('storage/' . $about->image) }}" alt="About image">
                                    @else
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                        <span>No Image</span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="status-badge active">
                                    <i class="fas fa-check-circle"></i>
                                    {{ $about->experience_years }} {{ $about->experience_text }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge active">
                                    <i class="fas fa-list-check"></i>
                                    {{ count($about->features ?? []) }} Features
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.abouts.show', $about->id) }}" class="btn-action view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.abouts.edit', $about->id) }}" class="btn-action edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.abouts.destroy', $about->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Delete" onclick="return confirmDelete()">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<a href="{{ route('admin.abouts.create') }}" class="fab" title="Quick Add">
    <i class="fas fa-plus"></i>
</a>

<script src="{{ asset('admin/assets/js/index.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading shimmer effect on table load
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.1}s`;
            row.classList.add('fadeInUp');
        });

        // Animate status badges
        const badges = document.querySelectorAll('.status-badge');
        badges.forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1) rotate(5deg)';
            });
            badge.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) rotate(0deg)';
            });
        });

        // Add ripple effect to buttons
        const actionButtons = document.querySelectorAll('.btn-action');
        actionButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('div');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.left = '50%';
                ripple.style.top = '50%';
                ripple.style.marginLeft = '-10px';
                ripple.style.marginTop = '-10px';
                ripple.style.width = '20px';
                ripple.style.height = '20px';
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
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

        // Auto-refresh effect for demo
        setInterval(() => {
            const shimmerElements = document.querySelectorAll('th');
            shimmerElements.forEach((element, index) => {
                setTimeout(() => {
                    element.style.animation = 'shimmer 0.8s ease-in-out';
                    setTimeout(() => {
                        element.style.animation = '';
                    }, 800);
                }, index * 100);
            });
        }, 10000);
    });

    // Confirmation dialog for delete
    function confirmDelete() {
        return confirm('Are you sure you want to delete this about section? This action cannot be undone.');
    }

    // Add CSS animation for ripple effect
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(-100px);
            }
        }
        .slide-images {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        .slide-images img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }
        .no-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #999;
            font-size: 12px;
        }
        .no-image i {
            font-size: 20px;
            margin-bottom: 5px;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection