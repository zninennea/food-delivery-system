<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Management - NaNi Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            font-family: 'Playfair Display', serif;
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(10px);
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hover-card {
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-stone-50 text-gray-800 antialiased">

    <nav
        class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('owner.dashboard') }}" class="flex-shrink-0 flex items-center gap-2 group">
                    <img src="https://i.imgur.com/vPOu1H2.png" alt="NaNi Icon"
                        class="h-20 w-auto group-hover:rotate-12 transition-transform duration-300">
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('owner.dashboard') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('owner.orders.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-shopping-cart mr-1"></i> Orders
                    </a>
                    <a href="{{ route('owner.menu.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-utensils mr-1"></i> Menu
                    </a>
                    <a href="{{ route('owner.analytics.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-chart-line mr-1"></i> Analytics
                    </a>
                    <a href="{{ route('owner.reviews.index') }}"
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-star mr-1"></i> Reviews
                    </a>
                    <a href="{{ route('owner.riders.index') }}"
                        class="text-orange-600 bg-orange-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-motorcycle mr-1"></i> Riders
                    </a>

                    <div class="ml-4 pl-4 border-l border-gray-200 flex items-center gap-3">
                        <!-- Profile Button (Active) -->
                        <a href="{{ route('owner.profile.show') }}"
                            class="text-grey-600 hover:text-orange-700 transition-colors">
                            <i class="fas fa-user-circle text-xl"></i>
                        </a>

                        <span class="text-sm font-bold text-gray-700">Admin</span>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors"
                                title="Logout">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="pt-32 pb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8 fade-in">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Rider Management</h1>
                    <p class="text-stone-500">Manage NaNi's delivery riders and their statuses</p>
                </div>
                <a href="{{ route('owner.riders.create') }}"
                    class="px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-bold rounded-xl hover:from-orange-700 hover:to-red-700 transition-all shadow-lg shadow-orange-500/30">
                    <i class="fas fa-plus mr-2"></i>Add New Rider
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 fade-in" style="animation-delay: 0.1s;">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100 hover-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <i class="fas fa-motorcycle text-xl"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $riders->count() }}</h3>
                <p class="text-sm text-stone-500">Total Riders</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100 hover-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $riders->where('status', 'active')->count() }}</h3>
                <p class="text-sm text-stone-500">Active Riders</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-stone-100 hover-card">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-red-50 text-red-600 rounded-xl">
                        <i class="fas fa-times-circle text-xl"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ $riders->where('status', 'inactive')->count() }}
                </h3>
                <p class="text-sm text-stone-500">Inactive Riders</p>
            </div>
        </div>

        <!-- Riders Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden fade-in"
            style="animation-delay: 0.2s;">
            <div class="p-6 border-b border-stone-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900 font-serif">All Riders</h3>
                <div class="text-sm text-stone-500">
                    {{ $riders->count() }} {{ Str::plural('rider', $riders->count()) }}
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-100">
                    <thead class="bg-stone-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Rider</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Vehicle</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                License</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-stone-100">
                        @forelse($riders as $rider)
                            <tr class="hover:bg-stone-50/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        @if($rider->profile_picture)
                                            <img src="{{ asset('storage/' . $rider->profile_picture) }}"
                                                alt="{{ $rider->name }}"
                                                class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-sm">
                                        @else
                                            <div
                                                class="h-12 w-12 bg-gradient-to-br from-stone-200 to-stone-300 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                                                <i class="fas fa-user text-stone-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-bold text-gray-900">{{ $rider->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $rider->email }}</div>
                                    <div class="text-xs text-stone-500">{{ $rider->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="p-2 bg-stone-100 rounded-lg">
                                            <i class="fas fa-motorcycle mr-1"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $rider->vehicle_type }}</div>
                                            <div class="text-xs text-stone-500">{{ $rider->vehicle_plate }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($rider->drivers_license)
                                        <button type="button"
                                            class="view-license-btn inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-blue-100 transition-colors"
                                            data-license-url="{{ asset('storage/' . $rider->drivers_license) }}"
                                            data-rider-name="{{ $rider->name }}">
                                            <i class="fas fa-id-card"></i> View License
                                        </button>
                                    @else
                                        <span class="text-sm text-stone-400 italic">No license</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-full 
                                                                {{ $rider->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($rider->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('owner.riders.edit', $rider) }}"
                                            class="p-2 bg-stone-100 text-stone-600 rounded-lg hover:bg-blue-100 hover:text-blue-600 transition-colors"
                                            title="Edit Rider">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('owner.riders.destroy', $rider) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('{{ $rider->name }}', this)"
                                                class="p-2 bg-stone-100 text-stone-600 rounded-lg hover:bg-red-100 hover:text-red-600 transition-colors"
                                                title="Delete Rider">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-stone-400">
                                        <i class="fas fa-motorcycle text-4xl mb-4"></i>
                                        <p class="text-lg font-medium text-gray-900">No riders found</p>
                                        <p class="text-sm">Get started by adding your first rider</p>
                                        <a href="{{ route('owner.riders.create') }}"
                                            class="mt-4 px-6 py-2 bg-gradient-to-r from-orange-600 to-red-600 text-white font-bold rounded-xl hover:from-orange-700 hover:to-red-700 transition-all">
                                            <i class="fas fa-plus mr-2"></i>Add First Rider
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- License Modal -->
    <div id="licenseModal"
        class="fixed inset-0 bg-black/80 backdrop-blur-md hidden flex items-center justify-center z-50 p-4 opacity-0 transition-opacity duration-300">
        <div
            class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl transform scale-95 transition-transform duration-300 modal-content overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-4 bg-stone-900 text-white flex justify-between items-center">
                <h3 class="font-bold" id="modalTitle">Driver's License</h3>
                <button type="button" id="closeModal" class="text-stone-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 bg-stone-100 flex items-center justify-center">
                <img id="licenseImage" src="" alt="Driver's License"
                    class="max-w-full rounded-lg shadow-md border border-stone-200">
            </div>

            <div class="p-6 bg-white border-t border-stone-100 flex justify-end">
                <button type="button" id="closeModalBtn"
                    class="px-6 py-2.5 bg-stone-200 text-stone-700 font-bold rounded-xl hover:bg-stone-300 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Logout Confirmation
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Logout?',
                        text: "You will be returned to the login screen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#1c1917',
                        cancelButtonColor: '#78716c',
                        confirmButtonText: 'Yes, logout',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            logoutForm.submit();
                        }
                    });
                });
            }

            // Helper to open/close modals with animation
            function toggleModal(modalId, show = true) {
                const modal = document.getElementById(modalId);
                const content = modal.querySelector('.modal-content');

                if (show) {
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        modal.classList.remove('opacity-0');
                        if (content) {
                            content.classList.remove('scale-95');
                            content.classList.add('scale-100');
                        }
                    }, 10);
                } else {
                    modal.classList.add('opacity-0');
                    if (content) {
                        content.classList.remove('scale-100');
                        content.classList.add('scale-95');
                    }
                    setTimeout(() => {
                        modal.classList.add('hidden');
                    }, 300);
                }
            }

            // License Modal
            const licenseModal = document.getElementById('licenseModal');
            const licenseImage = document.getElementById('licenseImage');
            const modalTitle = document.getElementById('modalTitle');
            const closeModal = document.getElementById('closeModal');
            const closeModalBtn = document.getElementById('closeModalBtn');

            // Add event listeners to all view license buttons
            document.querySelectorAll('.view-license-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const licenseUrl = this.getAttribute('data-license-url');
                    const riderName = this.getAttribute('data-rider-name');

                    licenseImage.src = licenseUrl;
                    modalTitle.textContent = `${riderName}'s Driver's License`;
                    toggleModal('licenseModal', true);
                });
            });

            // Close modal buttons
            closeModal.addEventListener('click', function () {
                toggleModal('licenseModal', false);
            });

            closeModalBtn.addEventListener('click', function () {
                toggleModal('licenseModal', false);
            });

            // Close modal when clicking outside
            licenseModal.addEventListener('click', function (e) {
                if (e.target === licenseModal) {
                    toggleModal('licenseModal', false);
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !licenseModal.classList.contains('hidden')) {
                    toggleModal('licenseModal', false);
                }
            });

            // Delete confirmation
            window.confirmDelete = function (riderName, button) {
                Swal.fire({
                    title: 'Delete Rider?',
                    html: `Are you sure you want to delete <strong>${riderName}</strong>?<br>
                           <span class="text-sm text-stone-500">This action cannot be undone.</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#78716c',
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    backdrop: 'rgba(0, 0, 0, 0.3)',
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        button.closest('form').submit();
                    }
                });
            };
        });
    </script>
</body>

</html>