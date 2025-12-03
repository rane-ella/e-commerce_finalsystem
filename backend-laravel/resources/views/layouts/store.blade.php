<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Poppins', sans-serif; }
            h1, h2, h3, h4, h5, h6, .font-serif { font-family: 'Playfair Display', serif; }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-cream">
        <div class="min-h-screen bg-cream flex">
            <!-- Desktop Sidebar -->
            <aside class="hidden lg:flex lg:flex-col lg:w-48 lg:fixed lg:inset-y-0 lg:border-r lg:border-gray-200 lg:bg-white lg:pt-5 lg:pb-4">
                <div class="flex items-center flex-shrink-0 px-4">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-8 w-auto object-contain" />
                        <span class="font-serif text-base font-bold tracking-tight text-gray-900">GlowBabe</span>
                    </a>
                </div>
                <div class="mt-6 flex-1 flex flex-col overflow-y-auto">
                    <nav class="flex-1 px-3 space-y-1">
                        <!-- Main Links -->
                        @if(Auth::check() && Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-900 hover:bg-rose-50 hover:text-rose-gold">
                                Admin Dashboard
                            </a>
                            <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-rose-50 hover:text-rose-gold">
                                Admin Home
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-900 hover:bg-rose-50 hover:text-rose-gold">
                                Home
                            </a>
                            <a href="{{ route('products.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-rose-50 hover:text-rose-gold">
                                Shop
                            </a>
                        @endif
                        
                        <!-- Categories Section -->
                        <div class="pt-4">
                            <p class="px-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Categories</p>
                            <div class="space-y-1">
                                <a href="{{ route('categories.show', 'eyes') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-rose-50 hover:text-rose-gold">
                                    Eyes
                                </a>
                                <a href="{{ route('categories.show', 'lips') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-rose-50 hover:text-rose-gold">
                                    Lips
                                </a>
                                <a href="{{ route('categories.show', 'face') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-rose-50 hover:text-rose-gold">
                                    Face
                                </a>
                                <a href="{{ route('categories.show', 'skincare') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-rose-50 hover:text-rose-gold">
                                    Skincare
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                    <div class="flex items-center w-full">
                        @auth
                            <div class="flex items-center w-full">
                                <div class="h-8 w-8 rounded-full bg-rose-gold/20 flex items-center justify-center text-rose-gold font-bold text-xs">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="ml-2 overflow-hidden">
                                    <p class="text-xs font-medium text-gray-700 truncate group-hover:text-gray-900">{{ Auth::user()->name }}</p>
                                    <a href="{{ route('profile.show') }}" class="text-[10px] font-medium text-gray-500 group-hover:text-gray-700">View Profile</a>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-rose-gold">Log in</a>
                        @endauth
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col lg:pl-48 transition-all duration-300">
    <style>
        /* Custom Header Styles */
        :root {
            --header-bg: #ffffff;
            --primary-accent: #e76f51; /* Terracotta/Coral for Brand Text */
            --icon-color: #583597; /* Purple icon color */
            --logo-height-final: 40px; /* Adjusted to match the visual scale of the screenshot */
            --header-height-final: 65px; /* Compact header height */
            --padding-x: 20px;
            --text-color: #333333;
        }

        .custom-header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: var(--header-height-final); 
            background-color: var(--header-bg);
            padding: 0 var(--padding-x);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); 
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px; 
        }

        .header-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        /* FINAL Logo Image Styling */
        .header-logo img {
            height: var(--logo-height-final); 
            width: auto; 
            object-fit: contain; 
            display: block;
            margin-right: 10px;
            border-radius: 5px; /* Matches the slightly rounded box around the logo in the screenshot */
        }

        /* Brand Text Styling */
        .header-logo span {
            color: var(--primary-accent);
            font-size: 1.5em; 
            font-weight: 700;
            line-height: 1; /* Helps vertical alignment */
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* Search Bar Integration */
        .search-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-container form {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .search-container input[type="text"] {
            padding: 8px 10px 8px 30px; 
            border: 1px solid #cccccc; 
            border-radius: 25px; 
            width: 250px; 
            font-size: 0.9em;
            color: #333;
            transition: border-color 0.3s ease;
        }
        
        .search-container input[type="text"]:focus {
            border-color: var(--primary-accent);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 10px;
            color: var(--icon-color); 
            font-size: 1.1em;
            pointer-events: none; 
            z-index: 1;
        }
        
        .search-container input::placeholder {
            color: #777;
            font-weight: 400;
        }

        /* Utility Icons Styling */
        .icon-btn {
            background: none;
            border: none;
            font-size: 1.8em; 
            color: var(--icon-color); 
            cursor: pointer;
            padding: 5px 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hamburger-icon {
            color: var(--icon-color);
            font-size: 1.5em;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .custom-header {
                height: 60px;
                padding: 0 10px;
            }
            .header-logo img {
                height: 35px;
            }
            .header-logo span {
                font-size: 1.4em;
            }
            .search-container input[type="text"] {
                width: 150px;
            }
        }
        @media (max-width: 550px) {
            .search-container {
                display: none; 
            }
            .header-logo span {
                 display: none; /* Hide text, keep only logo image + hamburger */
            }
        }
    </style>

    <!-- Sticky Top Header (Custom Design) -->
    <header x-data="{ mobileMenuOpen: false, profileDrawerOpen: false }" class="sticky top-0 z-40">
        <div class="custom-header">
            <div class="header-left">
                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = true" class="icon-btn hamburger-icon lg:hidden">☰</button>

                <a href="{{ route('dashboard') }}" class="header-logo">
                    <x-application-logo class="block w-auto object-contain" />
                    <span>GlowBabe</span>
                </a>
            </div>

            <div class="header-right">
                @unless(Auth::check() && Auth::user()->role === 'admin')
                <form action="{{ route('products.index') }}" method="GET" class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}">
                </form>
                
                <a href="{{ route('cart.index') }}" class="icon-btn relative">
                    🛒
                    <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center">0</span>
                </a>
                @endunless
                
                <!-- Profile Dropdown -->
                <div class="relative mr-5" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="icon-btn focus:outline-none">
                        👤
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                         style="display: none;">
                        @auth
                            <div class="px-4 py-2 text-xs text-gray-400 border-b border-gray-100">
                                {{ Auth::user()->name }}
                            </div>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('My Profile') }}
                            </a>
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('Manage Orders') }}
                                </a>
                                <a href="{{ route('admin.products.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('Product List') }}
                                </a>
                            @else
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('My Purchases') }}
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ __('Log Out') }}
                                </a>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('Log In') }}
                            </a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('Register') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Drawer (Categories) -->
        <div class="relative z-50 lg:hidden" role="dialog" aria-modal="true" x-show="mobileMenuOpen" style="display: none;">
            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity" 
                 x-show="mobileMenuOpen"
                 x-transition:enter="ease-in-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileMenuOpen = false"></div>

            <div class="fixed inset-y-0 left-0 z-50 w-full max-w-xs overflow-y-auto bg-white p-6 shadow-xl"
                 x-show="mobileMenuOpen"
                 x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full">
                
                <div class="flex items-center justify-between mb-6">
                    <span class="font-serif text-xl font-bold tracking-tight text-gray-900">Menu</span>
                    <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = false">
                        <span class="sr-only">Close menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flow-root">
                    <div class="-my-6 divide-y divide-gray-200">
                        <div class="space-y-2 py-6">
                            @if(Auth::check() && Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base font-medium leading-7 text-gray-900 hover:bg-gray-50">Admin Dashboard</a>
                                <a href="{{ route('dashboard') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base font-medium leading-7 text-gray-900 hover:bg-gray-50">Admin Home</a>
                            @else
                                <a href="{{ route('dashboard') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base font-medium leading-7 text-gray-900 hover:bg-gray-50">Home</a>
                                <a href="{{ route('products.index') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base font-medium leading-7 text-gray-900 hover:bg-gray-50">Shop</a>
                            @endif
                            
                            <div class="pt-4 pb-2">
                                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Categories</p>
                            </div>
                            <a href="{{ route('categories.show', 'eyes') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base font-medium leading-7 text-gray-900 hover:bg-gray-50">Eyes</a>
                            <a href="{{ route('categories.show', 'lips') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base font-medium leading-7 text-gray-900 hover:bg-gray-50">Lips</a>
                            <a href="{{ route('categories.show', 'face') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base font-medium leading-7 text-gray-900 hover:bg-gray-50">Face</a>
                            <a href="{{ route('categories.show', 'skincare') }}" class="-mx-3 block rounded-lg px-3 py-2 text-base font-medium leading-7 text-gray-900 hover:bg-gray-50">Skincare</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
