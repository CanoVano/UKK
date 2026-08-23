<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Warung Mamah') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50 h-screen overflow-hidden">
        <div class="flex h-screen w-full">
            <!-- Left Side: Form Area -->
            <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-8 sm:p-12 lg:p-24 bg-white relative z-10 overflow-y-auto">
                
                <div class="w-full max-w-md">
                    <!-- Logo / Brand -->
                    <div class="flex flex-col items-center justify-center">
                    <a href="/">
                        <img src="{{ asset('images/logo.png') }}" alt="Warung Mamah" class="h-32 sm:h-40 w-auto mb-4 hover:scale-105 transition-transform duration-300 object-contain">
                    </a>
                </div>     <!-- Slot containing the actual form -->
                    <div class="bg-white">
                        {{ $slot }}
                    </div>
                    
                    <!-- Footer -->
                    <div class="mt-12 text-center text-sm text-gray-500">
                        &copy; {{ date('Y') }} Warung Mamah. All rights reserved.
                    </div>
                </div>
            </div>

            <!-- Right Side: Visual/Image -->
            <div class="hidden lg:block w-1/2 relative bg-emerald-900 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-600/80 to-teal-900/90 mix-blend-multiply z-10"></div>
                <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1200&q=80" 
                     alt="Fresh Groceries" 
                     class="absolute inset-0 w-full h-full object-cover object-center z-0 scale-105 hover:scale-110 transition-transform duration-10000">
                
                <!-- Overlay Content -->
                <div class="absolute inset-0 z-20 flex flex-col items-center justify-center p-12 text-white text-center">
                    <svg class="w-20 h-20 mb-6 text-emerald-300 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <h2 class="text-4xl font-bold mb-4">Sayuran Segar Tiap Hari</h2>
                    <p class="text-lg text-emerald-100 max-w-md mx-auto font-light">Kami memastikan bahan makanan terbaik dari pasar segar pagi ke meja makan Anda. Bergabunglah dengan kami sekarang.</p>
                </div>
            </div>
        </div>
    </body>
</html>
