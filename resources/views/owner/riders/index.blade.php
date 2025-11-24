<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Management - NaNi Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>


<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <!-- NaNi Logo -->
                    <div class="flex items-center">
                        <img src="{{ asset('images/nani-logo.png') }}" alt="NaNi Logo" class="h-10 w-10 mr-3">
                        <div>
                            <a href="/" class="text-xl font-bold text-gray-800">NaNi</a>
                            <p class="text-xs text-gray-500 -mt-1">Owner Dashboard</p>
                        </div>
                    </div>
                </div>


                <div class="flex items-center space-x-4">
                    <a href="{{ route('owner.dashboard') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('owner.menu.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-utensils mr-1"></i>Menu
                    </a>
                    <a href="{{ route('owner.orders.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-shopping-cart mr-1"></i>Orders
                    </a>
                    <a href="{{ route('owner.analytics.index') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-chart-bar mr-1"></i>Analytics
                    </a>
                    <a href="{{ route('owner.riders.index') }}"
                        class="text-orange-600 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-motorcycle mr-1"></i>Riders
                    </a>
                    <a href="{{ route('owner.profile.show') }}"
                        class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-user mr-1"></i>Profile
                    </a>


                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>


    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Rider Management</h2>
            <a href="{{ route('owner.riders.create') }}"
                class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">
                <i class="fas fa-plus mr-2"></i>Add New Rider
            </a>
        </div>


        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-motorcycle text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Riders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $riders->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Riders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $riders->where('status', 'active')->count() }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <i class="fas fa-times-circle text-red-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Inactive Riders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $riders->where('status', 'inactive')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Riders Table -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">All Riders</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rider
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vehicle
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                License
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($riders as $rider)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($rider->profile_picture)
                                            <img src="{{ asset('storage/' . $rider->profile_picture) }}"
                                                alt="{{ $rider->name }}" class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $rider->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $rider->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $rider->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $rider->vehicle_type }}</div>
                                    <div class="text-sm text-gray-500">{{ $rider->vehicle_plate }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($rider->drivers_license)
                                        <button type="button"
                                                class="view-license-btn bg-blue-100 text-blue-700 px-3 py-1 rounded-md text-sm hover:bg-blue-200 transition-colors"
                                                data-license-url="{{ asset('storage/' . $rider->drivers_license) }}"
                                                data-rider-name="{{ $rider->name }}">
                                            <i class="fas fa-eye mr-1"></i>View License
                                        </button>
                                    @else
                                        <span class="text-sm text-gray-500">No license</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        @if($rider->status == 'active') bg-green-100 text-green-800
                                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($rider->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('owner.riders.edit', $rider) }}"
                                        class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                    <form action="{{ route('owner.riders.destroy', $rider) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure you want to delete this rider?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No riders found. <a href="{{ route('owner.riders.create') }}"
                                        class="text-blue-600 hover:text-blue-900">Add your first rider!</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- License Modal -->
    <div id="licenseModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Driver's License</h3>
                <button type="button" id="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <img id="licenseImage" src="" alt="Driver's License" class="w-full h-auto rounded-md">
            </div>
            <div class="px-6 py-4 border-t bg-gray-50 flex justify-end">
                <button type="button" id="closeModalBtn" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('licenseModal');
            const licenseImage = document.getElementById('licenseImage');
            const modalTitle = document.getElementById('modalTitle');
            const closeModal = document.getElementById('closeModal');
            const closeModalBtn = document.getElementById('closeModalBtn');
           
            // Add event listeners to all view license buttons
            document.querySelectorAll('.view-license-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const licenseUrl = this.getAttribute('data-license-url');
                    const riderName = this.getAttribute('data-rider-name');
                   
                    licenseImage.src = licenseUrl;
                    modalTitle.textContent = `${riderName}'s Driver's License`;
                    modal.classList.remove('hidden');
                });
            });
           
            // Close modal when X is clicked
            closeModal.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
           
            // Close modal when close button is clicked
            closeModalBtn.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
           
            // Close modal when clicking outside the image
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
</body>


</html>