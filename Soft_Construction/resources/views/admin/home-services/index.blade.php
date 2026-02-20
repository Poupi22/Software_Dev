@extends('admin.layouts.app')
@section('content')
<link href="{{ asset('admin/assets/css/index.css') }}" rel="stylesheet">
<style>
    /* Additional CSS for enhanced image styling */
    .service-image-container {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
    }
    
    .service-image-container:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-color: #3b82f6;
    }
    
    .service-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    
    .features-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        background: #f8fafc;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .feature-item:hover {
        background: #f1f5f9;
        transform: translateX(3px);
    }
    
    .feature-item i {
        color: #3b82f6;
        font-size: 14px;
    }
    
    .feature-item span {
        font-size: 13px;
        color: #334155;
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
                <h1><i class="fas fa-cogs"></i> Home Services Management</h1>
                <p>Manage your website's home services section with ease</p>
            </div>
            <a href="{{ route('admin.home-services.create') }}" class="add-btn">
                <i class="fas fa-plus"></i>
                Add New Service
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
                <table class="services-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-sort-numeric-down"></i> Order</th>
                            <th><i class="fas fa-heading"></i> Title</th>
                            <th><i class="fas fa-image"></i> Image</th>
                            <th><i class="fas fa-list"></i> Features</th>
                            <th><i class="fas fa-toggle-on"></i> Status</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                        <tr class="service-row">
                            <td class="order-cell">
                                <span class="order-badge">{{ $service->order }}</span>
                            </td>
                            <td class="title-cell">
                                <strong>{{ $service->title }}</strong>
                                @if($service->button_text)
                                <small class="button-hint">{{ $service->button_text }}</small>
                                @endif
                            </td>
                            <td class="image-cell">
                                @if($service->image)
                                <div class="service-image-container">
                                    <img src="{{ asset('storage/' . $service->image) }}" alt="Service image" class="service-image">
                                </div>
                                @else
                                <div class="no-image">
                                    <i class="fas fa-image"></i>
                                    <span>No image</span>
                                </div>
                                @endif
                            </td>
                            <td class="features-cell">
                                <div class="features-list">
                                    <div class="feature-item">
                                        <i class="{{ $service->feature_icon_1 }}"></i>
                                        <span>{{ $service->feature_title_1 }}</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="{{ $service->feature_icon_2 }}"></i>
                                        <span>{{ $service->feature_title_2 }}</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="{{ $service->feature_icon_3 }}"></i>
                                        <span>{{ $service->feature_title_3 }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="status-cell">
                                <span class="status-badge {{ $service->is_active ? 'active' : 'inactive' }}">
                                    <i class="fas {{ $service->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.home-services.show', $service->id) }}" class="btn-action view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.home-services.edit', $service->id) }}" class="btn-action edit" title="Edit Service">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.home-services.destroy', $service->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Delete Service" onclick="return confirmDelete()">
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
<a href="{{ route('admin.home-services.create') }}" class="fab" title="Quick Add">
    <i class="fas fa-plus"></i>
</a>

<script src="{{ asset('admin/assets/js/index.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading animation to table rows
        const tableRows = document.querySelectorAll('.service-row');
        tableRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.1}s`;
            row.classList.add('fadeInUp');
        });

        // Enhanced status badge hover effects
        const badges = document.querySelectorAll('.status-badge');
        badges.forEach(badge => {
            badge.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
                if (this.classList.contains('active')) {
                    this.style.boxShadow = '0 0 0 3px rgba(74, 222, 128, 0.3)';
                } else {
                    this.style.boxShadow = '0 0 0 3px rgba(248, 113, 113, 0.3)';
                }
            });
            badge.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
                this.style.boxShadow = 'none';
            });
        });

        // Image hover effects
        const imageContainers = document.querySelectorAll('.service-image-container');
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
        .order-badge {
            display: inline-block;
            width: 30px;
            height: 30px;
            background: #e0f2fe;
            color: #0369a1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .button-hint {
            display: block;
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }
        .no-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #94a3b8;
            font-size: 12px;
        }
        .no-image i {
            font-size: 24px;
            margin-bottom: 4px;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection