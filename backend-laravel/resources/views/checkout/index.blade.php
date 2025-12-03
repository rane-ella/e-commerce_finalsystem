<x-store-layout>
    <div class="bg-white">
        <div class="max-w-2xl mx-auto pt-16 pb-24 px-4 sm:px-6 lg:max-w-7xl lg:px-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl font-serif">Checkout</h1>

            <div class="mt-12 lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start xl:gap-x-16">
                <section class="lg:col-span-7">
                    @if(!Auth::user()->address || !Auth::user()->phone)
                        <div class="rounded-md bg-yellow-50 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Shipping Information Missing</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Please update your address and phone number in your profile before proceeding.</p>
                                    </div>
                                    <div class="mt-4">
                                        <div class="-mx-2 -my-1.5 flex">
                                            <a href="{{ route('profile.show') }}" class="bg-yellow-50 px-2 py-1.5 rounded-md text-sm font-medium text-yellow-800 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-yellow-50 focus:ring-yellow-600">Update Profile</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
                            <h2 class="text-lg font-medium text-gray-900 mb-4 font-serif">Shipping Information</h2>
                            <p class="text-sm text-gray-600"><span class="font-medium">Name:</span> {{ Auth::user()->name }}</p>
                            <p class="text-sm text-gray-600 mt-1"><span class="font-medium">Address:</span> {{ Auth::user()->address }}</p>
                            <p class="text-sm text-gray-600 mt-1"><span class="font-medium">Phone:</span> {{ Auth::user()->phone }}</p>
                            <div class="mt-4">
                                <a href="{{ route('profile.show') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Edit</a>
                            </div>
                        </div>

                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            
                            <!-- Payment -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4 font-serif">Payment Method</h2>
                                <fieldset>
                                    <legend class="sr-only">Payment type</legend>
                                    <div class="space-y-4">
                                        <div class="flex items-center">
                                            <input id="cod" name="payment_method" type="radio" value="cod" checked class="focus:ring-rose-gold h-4 w-4 text-rose-gold border-gray-300">
                                            <label for="cod" class="ml-3 block text-sm font-medium text-gray-700">Cash on Delivery (COD)</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="card" name="payment_method" type="radio" value="card" class="focus:ring-rose-gold h-4 w-4 text-rose-gold border-gray-300">
                                            <label for="card" class="ml-3 block text-sm font-medium text-gray-700">Credit Card</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="gcash" name="payment_method" type="radio" value="gcash" class="focus:ring-rose-gold h-4 w-4 text-rose-gold border-gray-300">
                                            <label for="gcash" class="ml-3 block text-sm font-medium text-gray-700">GCash</label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>

                            <!-- Notes -->
                            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 mb-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Order Notes (Optional)</label>
                                <div class="mt-1">
                                    <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-rose-gold focus:border-rose-gold block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <button type="submit" class="w-full bg-rose-gold border border-transparent rounded-md shadow-sm py-3 px-4 text-base font-medium text-white hover:bg-[#c06b58] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-gold transition-colors duration-200">Confirm Order</button>
                            </div>
                        </form>
                    @endif
                </section>

                <!-- Order Summary -->
                <section class="mt-16 bg-white rounded-lg px-4 py-6 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-5 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-medium text-gray-900 font-serif">Order summary</h2>
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                            <li class="flex py-6">
                                <div class="flex-shrink-0">
                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-16 h-16 rounded-md object-center object-cover">
                                </div>
                                <div class="ml-4 flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex justify-between text-base font-medium text-gray-900">
                                            <h3>{{ $item->product->name }}</h3>
                                            <p class="ml-4">₱{{ number_format($item->product->price, 2) }}</p>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                    </div>
                                    <div class="flex-1 flex items-end justify-between text-sm">
                                        <p class="text-gray-500">Qty {{ $item->quantity }}</p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <dl class="mt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-gray-600">Subtotal</dt>
                            <dd class="text-sm font-medium text-gray-900">₱{{ number_format($subtotal, 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="flex items-center text-sm text-gray-600">Shipping</dt>
                            <dd class="text-sm font-medium text-gray-900">₱{{ number_format($shipping, 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <dt class="text-base font-medium text-gray-900">Order total</dt>
                            <dd class="text-base font-medium text-gray-900">₱{{ number_format($total, 2) }}</dd>
                        </div>
                    </dl>
                </section>
            </div>
        </div>
    </div>
</x-store-layout>
