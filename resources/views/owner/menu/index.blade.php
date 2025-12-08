<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Management - NaNi Owner</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                        class="text-orange-600 bg-orange-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
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
                        class="text-gray-600 hover:text-orange-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-motorcycle mr-1"></i> Riders
                    </a>

                    <div class="ml-4 pl-4 border-l border-gray-200 flex items-center gap-3">
                        <!-- Profile Button (Active) -->
                        <a href="{{ route('owner.profile.show') }}"
                            class="text-grey-600 hover:text-orange-600 transition-colors">
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

        <div class="flex justify-between items-end mb-8 fade-in">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Menu Management</h1>
                <p class="text-stone-500">Manage your restaurant's dishes and pricing.</p>
            </div>
            <a href="{{ route('owner.menu.create') }}"
                class="flex items-center gap-2 bg-orange-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-orange-700 transition-all shadow-lg hover:shadow-orange-500/30 transform hover:-translate-y-0.5">
                <i class="fas fa-plus"></i> Add Item
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-stone-100 overflow-hidden fade-in"
            style="animation-delay: 0.1s;">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-100">
                    <thead class="bg-stone-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Item Details</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Category</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Price</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-stone-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-stone-100">
                        @forelse($menuItems as $item)
                            <tr class="hover:bg-stone-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-16 w-16 bg-stone-100 rounded-xl overflow-hidden border border-stone-200">
                                            @if($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                                    class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center text-stone-300">
                                                    <i class="fas fa-utensils"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="text-sm font-bold text-gray-900 group-hover:text-orange-600 transition-colors">
                                                {{ $item->name }}</div>
                                            <div class="text-xs text-stone-500 mt-1 max-w-xs truncate">
                                                {{ $item->description }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-wide">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">₱{{ number_format($item->price, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('owner.menu.edit', $item) }}"
                                            class="text-stone-400 hover:text-blue-600 transition-colors p-2 bg-stone-50 hover:bg-blue-50 rounded-lg">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('owner.menu.delete', $item) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-stone-400 hover:text-red-600 transition-colors p-2 bg-stone-50 hover:bg-red-50 rounded-lg"
                                                onclick="return confirm('Delete {{ $item->name }}?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-stone-400">
                                        <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                                        <p class="text-lg font-medium text-gray-900">Your menu is empty</p>
                                        <p class="text-sm mb-6">Start adding delicious items for your customers.</p>
                                        <a href="{{ route('owner.menu.create') }}"
                                            class="text-orange-600 font-bold hover:underline">Add First Item</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Auto-refresh logic
        setTimeout(function () {
            window.location.reload();
        }, 60000); // Refresh every minute

        // Logout Confirmation
        const logoutForm = document.getElementById('logout-form');
        if (logoutForm) {
            logoutForm.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Logout Confirmation',
                    html: `<div class="text-center">
                            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-sign-out-alt text-red-600 text-2xl"></i>
                            </div>
                            <p class="text-gray-700">Are you sure you want to logout from your rider account?</p>
                            <p class="text-sm text-gray-500 mt-1">You will be redirected to the login page.</p>
                        </div>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fas fa-sign-out-alt mr-2"></i>Yes, Logout',
                    cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-3 font-medium',
                        cancelButton: 'rounded-xl px-6 py-3 font-medium'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        logoutForm.submit();
                    }
                });
            });
        }
    </script>
</body>

</html>