@extends('admin.layouts.app')
@section('content')
<link href="{{ asset('admin/assets/css/index.css') }}" rel="stylesheet">
<style>
    /* Partner-specific styling */
    .partner-logo-container {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
    }
    
    .partner-logo-container:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-color: #3b82f6;
    }
    
    .partner-logo-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .description-cell {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .description-cell:hover {
        white-space: normal;
        overflow: visible;
        position: absolute;
        background: white;
        z-index: 10;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        max-width: 400px;
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
    
    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .status-badge.active {
        background: #f0fdf4;
        color: #15803d;
    }
    
    .status-badge.inactive {
        background: #fee2e2;
        color: #b91c1c;
    }
    
    /* Order cell styling */
    .order-cell {
        text-align: center;
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
                <h1><i class="fas fa-handshake"></i> Partners Management</h1>
                <p>Manage your organization's partners and collaborations</p>
            </div>
            <a href="{{ route('admin.partners.create') }}" class="add-btn">
                <i class="fas fa-plus"></i>
                Add New Partner
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
                <table class="partners-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-sort-numeric-down"></i> Order</th>
                            <th><i class="fas fa-image"></i> Logo</th>
                            <th><i class="fas fa-heading"></i> Name</th>
                            <th><i class="fas fa-align-left"></i> Description</th>
                            <th><i class="fas fa-toggle-on"></i> Status</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($partners as $partner)
                        <tr class="partner-row">
                            <td class="order-cell">
                                <span class="order-badge">{{ $partner->order }}</span>
                            </td>
                            <td class="logo-cell">
                                <div class="partner-logo-container">
                                    @if($partner->logo_url)
                                    <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="partner-logo">
                                    @else
                                    <i class="fas fa-handshake" style="font-size: 24px; color: #94a3b8;"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="name-cell">
                                <strong>{{ $partner->name }}</strong>
                            </td>
                            <td class="description-cell">
                                {{ $partner->description }}
                            </td>
                            <td class="status-cell">
                                <span class="status-badge {{ $partner->is_active ? 'active' : 'inactive' }}">
                                    <i class="fas {{ $partner->is_active ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $partner->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.partners.show', $partner->id) }}" class="btn-action view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.partners.edit', $partner->id) }}" class="btn-action edit" title="Edit Partner">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Delete Partner" onclick="return confirmDelete()">
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
<a href="{{ route('admin.partners.create') }}" class="fab" title="Quick Add">
    <i class="fas fa-plus"></i>
</a>

<script src="{{ asset('admin/assets/js/index.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading animation to table rows
        const tableRows = document.querySelectorAll('.partner-row');
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

        // Logo hover effects
        const logoContainers = document.querySelectorAll('.partner-logo-container');
        logoContainers.forEach(container => {
            container.addEventListener('mouseenter', function() {
                const img = this.querySelector('img');
                if (img) img.style.transform = 'scale(1.1)';
            });
            container.addEventListener('mouseleave', function() {
                const img = this.querySelector('img');
                if (img) img.style.transform = 'scale(1)';
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
    `;
    document.head.appendChild(style);
</script>
@endsection