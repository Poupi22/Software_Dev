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
                <h1><i class="fas fa-quote-left"></i> Testimonials Management</h1>
                <p>Manage customer testimonials and reviews</p>
            </div>
            <a href="{{ route('admin.testimonials.create') }}" class="add-btn">
                <i class="fas fa-plus"></i>
                Add New Testimonial
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
                            <th><i class="fas fa-user"></i> Author</th>
                            <th><i class="fas fa-briefcase"></i> Position</th>
                            <th><i class="fas fa-comment"></i> Content</th>
                            <th><i class="fas fa-star"></i> Rating</th>
                            <th><i class="fas fa-image"></i> Avatar</th>
                            <th><i class="fas fa-toggle-on"></i> Status</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($testimonials as $testimonial)
                        <tr>
                            <td><strong>{{ $testimonial->name }}</strong></td>
                            <td>{{ $testimonial->position }}</td>
                            <td>{{ Str::limit($testimonial->content, 50) }}</td>
                            <td>
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
                                    <span class="rating-value">({{ $testimonial->rating }})</span>
                                </div>
                            </td>
                            <td>
                                @if($testimonial->avatar)
                                <div class="avatar-preview">
                                    <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="Author avatar">
                                </div>
                                @else
                                <div class="avatar-preview empty">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge {{ $testimonial->is_active ? 'active' : 'inactive' }}">
                                    <i class="fas {{ $testimonial->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.testimonials.show', $testimonial->id) }}" class="btn-action view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="btn-action edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="d-inline">
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
<a href="{{ route('admin.testimonials.create') }}" class="fab" title="Quick Add">
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
    });

    // Confirmation dialog for delete
    function confirmDelete() {
        return confirm('Are you sure you want to delete this testimonial? This action cannot be undone.');
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
        .avatar-preview {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f3f4f6;
        }
        .avatar-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        .avatar-preview.empty {
            font-size: 24px;
            color: #9ca3af;
        }
        .rating-stars {
            display: flex;
            align-items: center;
            gap: 2px;
        }
        .rating-stars i {
            color: #FFD700;
            font-size: 14px;
        }
        .rating-value {
            margin-left: 5px;
            font-size: 12px;
            color: #6b7280;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection