@extends('admin.layouts.app')
@section('content')
<link href="{{ asset('admin/assets/css/index.css') }}" rel="stylesheet">
<!-- <style>
    /* Additional CSS for enhanced styling */
    .permission-badge {
        display: inline-block;
        padding: 6px 12px;
        background: #e0f2fe;
        color: #0369a1;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
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
        border: none;
        cursor: pointer;
    }
    
    .btn-action:hover {
        transform: scale(1.1);
    }
    
    /* Table enhancements */
    table {
        border-collapse: separate;
        border-spacing: 0 12px;
        width: 100%;
    }
    
    thead th {
        background: #f1f5f9;
        padding: 16px 20px;
        font-weight: 600;
        color: #334155;
        text-align: left;
    }
    
    thead th i {
        margin-right: 8px;
        color: #64748b;
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
        padding: 16px 20px;
        vertical-align: middle;
    }
    
    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }
    
    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .empty-state p {
        font-size: 18px;
    }
    
    /* Pagination styling */
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 20px 0;
        gap: 8px;
    }
    
    .pagination li {
        display: inline-block;
    }
    
    .pagination a, .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .pagination a {
        background: white;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    
    .pagination a:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    
    .pagination .active span {
        background: #4f46e5;
        color: white;
        border: 1px solid #4f46e5;
    }
    
    /* Modal styling */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }
    
    .modal-content {
        background-color: white;
        padding: 24px;
        border-radius: 12px;
        width: 500px;
        max-width: 90%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .modal-title {
        font-size: 20px;
        font-weight: 600;
        color: #1e293b;
    }
    
    .close-modal {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #64748b;
    }
    
    .permission-detail {
        margin-bottom: 16px;
    }
    
    .detail-label {
        font-weight: 500;
        color: #64748b;
        margin-bottom: 4px;
    }
    
    .detail-value {
        color: #1e293b;
        font-size: 16px;
    }
</style> -->

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
                <h1><i class="fas fa-key"></i> Permissions Management</h1>
                <p>Manage user permissions and access controls</p>
            </div>
            <a href="{{ route('admin.permissions.create') }}" class="add-btn">
                <i class="fas fa-plus"></i>
                Create New Permission
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
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-key"></i> Permission Name</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permissions as $permission)
                        <tr class="data-row">
                            <td class="id-cell">{{ $permission->id }}</td>
                            <td class="name-cell">
                                <span class="permission-badge">{{ $permission->name }}</span>
                            </td>
                            <td class="actions-cell">
                                <div class="action-buttons">
                                    <button class="btn-action view" title="View Details" onclick="showPermissionDetails({{ $permission->id }}, '{{ $permission->name }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn-action edit" title="Edit Permission">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete" title="Delete Permission" onclick="return confirmDelete()">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <i class="fas fa-key"></i>
                                    <p>No permissions found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($permissions->hasPages())
            <div class="pagination-wrapper">
                {{ $permissions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Permission Details Modal -->
<div id="permissionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Permission Details</h3>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="permission-detail">
                <div class="detail-label">ID</div>
                <div class="detail-value" id="modal-permission-id"></div>
            </div>
            <div class="permission-detail">
                <div class="detail-label">Name</div>
                <div class="detail-value" id="modal-permission-name"></div>
            </div>
            <div class="permission-detail">
                <div class="detail-label">Created At</div>
                <div class="detail-value" id="modal-permission-created"></div>
            </div>
            <div class="permission-detail">
                <div class="detail-label">Last Updated</div>
                <div class="detail-value" id="modal-permission-updated"></div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<a href="{{ route('admin.permissions.create') }}" class="fab" title="Quick Add">
    <i class="fas fa-plus"></i>
</a>

<script src="{{ asset('admin/assets/js/index.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading animation to table rows
        const tableRows = document.querySelectorAll('.data-row');
        tableRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.1}s`;
            row.classList.add('fadeInUp');
        });

        // Confirmation dialog for delete
        window.confirmDelete = function() {
            return confirm('Are you sure you want to delete this permission?');
        }
    });

    // Show permission details in modal
    function showPermissionDetails(id, name) {
        document.getElementById('modal-permission-id').textContent = id;
        document.getElementById('modal-permission-name').textContent = name;
        
        // For demo purposes, using current date - in a real app you'd fetch these from your data
        const now = new Date();
        document.getElementById('modal-permission-created').textContent = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();
        document.getElementById('modal-permission-updated').textContent = now.toLocaleDateString() + ' ' + now.toLocaleTimeString();
        
        document.getElementById('permissionModal').style.display = 'flex';
    }

    // Close modal
    function closeModal() {
        document.getElementById('permissionModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('permissionModal');
        if (event.target === modal) {
            closeModal();
        }
    }

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