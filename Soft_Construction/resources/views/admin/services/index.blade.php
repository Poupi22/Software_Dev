@extends('admin.layouts.app')
<link href="{{ asset('admin/assets/css/index.css') }}" rel="stylesheet">
@section('content')


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
                <h1><i class="fas fa-concierge-bell"></i> Services Management</h1>
                <p>Manage your website's services section</p>
            </div>
            <a href="{{ route('admin.services.create') }}" class="add-btn">
                <i class="fas fa-plus"></i>
                Add New Service
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
                            <th><i class="fas fa-sort-numeric-down"></i> Order</th>
                            <th><i class="fas fa-image"></i> Icon</th>
                            <th><i class="fas fa-heading"></i> Title</th>
                            <th><i class="fas fa-align-left"></i> Short Description</th>
                            <th><i class="fas fa-toggle-on"></i> Status</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                        <tr>
                            <td><strong>{{ $service->order }}</strong></td>
                            <td>
                                @if($service->icon)
                                <div class="service-icon">
                                    <img src="{{ asset('storage/' . $service->icon) }}" alt="Service icon">
                                </div>
                                @else
                                <div class="service-icon empty">
                                    <i class="fas fa-image"></i>
                                </div>
                                @endif
                            </td>
                            <td><strong>{{ $service->title }}</strong></td>
                            <td>{{ Str::limit($service->short_description, 50) }}</td>
                            <td>
                                <span class="status-badge {{ $service->active ? 'active' : 'inactive' }}">
                                    <i class="fas {{ $service->active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $service->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.services.show', $service->id) }}" class="btn-action view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.services.edit', $service->id) }}" class="btn-action edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline">
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
<a href="{{ route('admin.services.create') }}" class="fab" title="Quick Add">
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
        return confirm('Are you sure you want to delete this service? This action cannot be undone.');
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
        .service-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f3f4f6;
        }
        .service-icon img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .service-icon.empty {
            font-size: 20px;
            color: #9ca3af;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection