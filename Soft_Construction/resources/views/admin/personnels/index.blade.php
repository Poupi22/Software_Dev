@extends('admin.layouts.app')
@section('content')
<link href="{{ asset('admin/assets/css/index.css') }}" rel="stylesheet">
<style>
    /* Additional CSS for enhanced image styling */
    .team-image-container {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        overflow: hidden;
        position: relative;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
    }
    
    .team-image-container:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-color: #3b82f6;
    }
    
    .team-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    
    .position-badge {
        display: inline-block;
        padding: 4px 8px;
        background: #e0f2fe;
        color: #0369a1;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .description-preview {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 13px;
        color: #64748b;
        line-height: 1.4;
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
                <h1><i class="fas fa-users"></i> Team Members Management</h1>
                <p>Manage your company's leadership team members</p>
            </div>
            <a href="{{ route('admin.personnels.create') }}" class="add-btn">
                <i class="fas fa-plus"></i>
                Add New Member
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
                <table class="team-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-sort-numeric-down"></i> Order</th>
                            <th><i class="fas fa-user"></i> Member</th>
                            <th><i class="fas fa-briefcase"></i> Position</th>
                            <th><i class="fas fa-align-left"></i> Description</th>
                            <th><i class="fas fa-toggle-on"></i> Status</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personnels as $personnel)
                        <tr class="team-row">
                            <td class="order-cell">
                                <span class="order-badge">{{ $personnel->order }}</span>
                            </td>
                            <td class="member-cell">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div class="team-image-container">
                                        <img src="{{ $personnel->image_url }}" alt="{{ $personnel->name }}" class="team-image">
                                    </div>
                                    <strong>{{ $personnel->name }}</strong>
                                </div>
                            </td>
                            <td class="position-cell">
                                <span class="position-badge">{{ $personnel->position }}</span>
                            </td>
                            <td class="description-cell">
                                <div class="description-preview">
                                    {{ $personnel->description }}
                                </div>
                            </td>
                            <td class="status-cell">
                                <span class="status-badge {{ $personnel->is_active ? 'active' : 'inactive' }}">
                                    <i class="fas {{ $personnel->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $personnel->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.personnels.show', $personnel->id) }}" class="btn-action view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.personnels.edit', $personnel->id) }}" class="btn-action edit" title="Edit Member">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.personnels.destroy', $personnel->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Delete Member" onclick="return confirmDelete()">
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
<a href="{{ route('admin.personnels.create') }}" class="fab" title="Quick Add">
    <i class="fas fa-plus"></i>
</a>

<script src="{{ asset('admin/assets/js/index.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading animation to table rows
        const tableRows = document.querySelectorAll('.team-row');
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
        const imageContainers = document.querySelectorAll('.team-image-container');
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
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .status-badge.active {
            background: #dcfce7;
            color: #166534;
        }
        .status-badge.inactive {
            background: #fee2e2;
            color: #991b1b;
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