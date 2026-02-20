@extends('admin.layouts.app')
<link href="{{ asset('admin/assets/css/index.css') }}" rel="stylesheet">
@section('content')

<style>
    /* Training Image and Avatar */
.training-image, .avatar-preview {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f3f4f6;
    transition: transform 0.3s, box-shadow 0.3s;
}

.training-image img, .avatar-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
}

.avatar-preview.empty {
    font-size: 24px;
    color: #9ca3af;
}

/* Hover Effect */
.training-image:hover, .avatar-preview:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Dark Theme Support */
@media (prefers-color-scheme: dark) {
    .training-image, .avatar-preview {
        background-color: #4b5563;
    }

    .avatar-preview.empty {
        color: #9ca3af;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .training-image, .avatar-preview {
        width: 40px;
        height: 40px;
    }

    .avatar-preview.empty {
        font-size: 20px;
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

<div class="container-fluid">
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div>
                <h1><i class="fas fa-dumbbell"></i> Training Programs Management</h1>
                <p>Manage your website's training programs and courses</p>
            </div>
            <a href="{{ route('admin.trainings.create') }}" class="add-btn">
                <i class="fas fa-plus"></i>
                Add New Training
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
                            <th><i class="fas fa-heading"></i> Title</th>
                            <th><i class="fas fa-align-left"></i> Description</th>
                            <th><i class="fas fa-clock"></i> Duration</th>
                            <th><i class="fas fa-image"></i> Image</th>
                            <th><i class="fas fa-toggle-on"></i> Status</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trainings as $training)
                        <tr>
                            <td><strong>{{ $training->order }}</strong></td>
                            <td><strong>{{ $training->title }}</strong></td>
                            <td class="truncate">{{ Str::limit($training->description, 100) }}</td>
                            <td>{{ $training->duration }}</td>
                            <td>
                                @if($training->image)
                                <div class="training-image">
                                    <img src="{{ asset('storage/' . $training->image) }}" alt="Training image">
                                </div>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge {{ $training->is_active ? 'active' : 'inactive' }}">
                                    <i class="fas {{ $training->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $training->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.trainings.show', $training->id) }}" class="btn-action view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.trainings.edit', $training->id) }}" class="btn-action edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.trainings.destroy', $training->id) }}" method="POST" class="d-inline">
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
<a href="{{ route('admin.trainings.create') }}" class="fab" title="Quick Add">
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
    return confirm('Are you sure you want to delete this training program? This action cannot be undone.');
}
</script>
@endsection