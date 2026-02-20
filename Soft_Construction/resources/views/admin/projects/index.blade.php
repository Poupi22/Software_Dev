@extends('admin.layouts.app')

@section('content')
<link href="{{ asset('admin/assets/css/index.css') }}" rel="stylesheet">
<style>
    /* Additional CSS for enhanced image styling */
    .project-image-container {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
    }
    
    .project-image-container:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-color: #3b82f6;
    }
    
    .project-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    .btn-action.view {
        background: #e0f2fe;
        color: #0369a1;
    }
    
    .btn-action.edit {
        background: #f0fdf4;
        color: #15803d;
    }
    
    .btn-action.delete {
        background: #fee2e2;
        color: #b91c1c;
    }
    
    .btn-action:hover {
        transform: scale(1.1);
    }
    
    /* Table enhancements */
    table {
        border-collapse: separate;
        border-spacing: 0 12px;
    }
    
    thead th {
        background: #f1f5f9;
        padding: 12px 16px;
        font-weight: 600;
        color: #334155;
    }
    
    tbody tr {
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.2s ease;
    }
    
    tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    tbody td {
        padding: 16px;
        vertical-align: middle;
    }
    
    .featured-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .featured-badge.featured {
        background: #dcfce7;
        color: #166534;
    }
    
    .featured-badge.not-featured {
        background: #e2e8f0;
        color: #475569;
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
                <h1><i class="fas fa-project-diagram"></i> Projects Management</h1>
                <p>Manage your website's projects section</p>
            </div>
            <a href="{{ route('admin.projects.create') }}" class="add-btn">
                <i class="fas fa-plus"></i>
                Add New Project
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
                <button type="button" class="close-alert" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Data Table Card -->
        <div class="card">
            <div class="table-container">
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-image"></i> Image</th>
                            <th><i class="fas fa-heading"></i> Title</th>
                            <th><i class="fas fa-map-marker-alt"></i> Location</th>
                            <th><i class="fas fa-map"></i> Region</th>
                            <th><i class="fas fa-star"></i> Featured</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                        <tr class="project-row">
                            <td class="image-cell">
                                <div class="project-image-container">
                                    <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" class="project-image">
                                </div>
                            </td>
                            <td class="title-cell">
                                <strong>{{ $project->title }}</strong>
                                @if($project->description)
                                <small class="description-hint">{{ Str::limit($project->description, 50) }}</small>
                                @endif
                            </td>
                            <td class="location-cell">
                                {{ $project->location }}
                            </td>
                            <td class="region-cell">
                                {{ $project->region }}
                            </td>
                            <td class="featured-cell">
                                <span class="featured-badge {{ $project->is_featured ? 'featured' : 'not-featured' }}">
                                    <i class="fas {{ $project->is_featured ? 'fa-star' : 'fa-star-o' }}"></i>
                                    {{ $project->is_featured ? 'Featured' : 'Regular' }}
                                </span>
                            </td>
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.projects.show', $project->id) }}" class="btn-action view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn-action edit" title="Edit Project">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Delete Project" onclick="return confirmDelete()">
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
<a href="{{ route('admin.projects.create') }}" class="fab" title="Quick Add">
    <i class="fas fa-plus"></i>
</a>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading animation to table rows
        const tableRows = document.querySelectorAll('.project-row');
        tableRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.1}s`;
            row.classList.add('fadeInUp');
        });

        // Image hover effects
        const imageContainers = document.querySelectorAll('.project-image-container');
        imageContainers.forEach(container => {
            container.addEventListener('mouseenter', function() {
                const img = this.querySelector('img');
                img.style.transform = 'scale(1.1)';
            });
            container.addEventListener('mouseleave', function() {
                const img = this.querySelector('img');
                img.style.transform = 'scale(1)';
            });
        });

        // Confirmation dialog for delete
        window.confirmDelete = function() {
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
    });

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fadeInUp {
            animation: fadeInUp 0.4s ease-out forwards;
            opacity: 0;
        }
        .description-hint {
            display: block;
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection