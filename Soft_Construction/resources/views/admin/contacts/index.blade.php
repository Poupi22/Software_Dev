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
                <h1><i class="fas fa-address-book"></i> Contact Section Management</h1>
                <p>Manage your website's contact information and settings</p>
            </div>
            <a href="{{ route('admin.contacts.create') }}" class="add-btn">
                <i class="fas fa-plus"></i>
                Add New Contact
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
                            <th><i class="fas fa-map-marker-alt"></i> Address</th>
                            <th><i class="fas fa-phone"></i> Phone</th>
                            <th><i class="fas fa-envelope"></i> Email</th>
                            <th><i class="fas fa-clock"></i> Opening Hours</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($contacts as $contact)
                            <tr>
                                <td>{{ $contact->address }}</td>
                                <td>{{ $contact->phone }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->opening_hours }}</td>

                                <td>
                                    <div class="action-buttons">
                                        <!-- View -->
                                        <a href="{{ route('admin.contacts.show', $contact->id) }}" 
                                           class="btn-action view" 
                                           title="View Contact">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.contacts.edit', $contact->id) }}" 
                                           class="btn-action edit" 
                                           title="Edit Contact">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirmDelete();">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn-action delete" 
                                                    title="Delete Contact">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="fas fa-info-circle"></i> No contacts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Floating Action Button -->
<a href="{{ route('admin.contacts.create') }}" class="fab" title="Quick Add">
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

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
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
        }
        table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 250px;
        }
        table td:nth-child(2) {  /* Phone column */
            white-space: normal;
        }
        table td:nth-child(4) {  /* Opening Hours column */
            white-space: normal;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection