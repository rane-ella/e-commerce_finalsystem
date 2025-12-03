<x-store-layout>
    <div class="container py-12" style="max-width: 1200px; margin: 0 auto; padding: 60px 20px;">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 2.5em; text-align: center; color: #e76f51; margin-bottom: 40px;">
            Admin Dashboard
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- Manage Orders Card -->
            <a href="{{ route('admin.orders.index') }}" class="block p-8 bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 transform hover:-translate-y-1">
                <div class="flex items-center mb-6">
                    <img src="{{ asset('images/admin-orders-icon.png') }}" alt="Orders" class="w-16 h-16 mr-5 object-contain">
                    <h3 class="text-2xl font-serif text-gray-800">Orders</h3>
                </div>
                <p class="text-gray-600 mb-6 leading-relaxed">View and manage customer orders, update statuses, and track sales performance.</p>
                <div class="text-[#e76f51] font-medium flex items-center">
                    Manage Orders 
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </div>
            </a>

            <!-- Manage Products Card -->
            <a href="{{ route('dashboard') }}" class="block p-8 bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 transform hover:-translate-y-1">
                <div class="flex items-center mb-6">
                    <img src="{{ asset('images/admin-products-icon.png') }}" alt="Products" class="w-16 h-16 mr-5 object-contain">
                    <h3 class="text-2xl font-serif text-gray-800">Products</h3>
                </div>
                <p class="text-gray-600 mb-6 leading-relaxed">Add new products, update inventory, manage prices, and upload product images.</p>
                <div class="text-[#e76f51] font-medium flex items-center">
                    Manage Products 
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </div>
            </a>
        </div>
    </div>
</x-store-layout>
