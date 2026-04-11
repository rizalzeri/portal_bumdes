<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal BUMDesa') - Pusat Inspirasi dan Informasi BUMDesa</title>

    <!-- Favicon and Manifest -->
    <link rel="icon" type="image/png" href="{{ asset('logo-leaf.png') }}?v=1">
    <link rel="manifest" href="{{ asset('manifest.json') }}?v=1">
    <meta name="theme-color" content="#1e3a5f">

    <!-- Tailwind CSS (via CDN as requested) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e3a5f',
                        accent: '#f59e0b',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen">

    <!-- Public Navbar -->
    <nav x-data="{ mobileMenuOpen: false }" class="bg-primary text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <i class="fa-solid fa-leaf text-accent text-2xl"></i>
                        <span class="font-bold text-xl tracking-wide">Portal BUMDesa</span>
                    </a>
                </div>
                <!-- Desktop Menu -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('home') }}"
                            class="hover:text-accent px-3 py-2 rounded-md font-medium transition-colors">Beranda</a>
                        <a href="{{ route('public.bumdes.list') }}"
                            class="hover:text-accent px-3 py-2 rounded-md font-medium transition-colors">BUMDesa</a>

                        @auth
                            @if (Auth::user()->role === 'superadmin')
                                <a href="{{ route('superadmin.dashboard') }}"
                                    class="bg-accent text-primary px-4 py-2 rounded-md font-bold hover:bg-yellow-400 object-transition">Dashboard</a>
                            @elseif(Auth::user()->role === 'admin_kabupaten')
                                <a href="{{ route('adminkab.dashboard') }}"
                                    class="bg-accent text-primary px-4 py-2 rounded-md font-bold hover:bg-yellow-400 object-transition">Dashboard</a>
                            @else
                                <a href="{{ route('user.dashboard', ['slug' => Auth::user()->username]) }}"
                                    class="bg-accent text-primary px-4 py-2 rounded-md font-bold hover:bg-yellow-400 object-transition">Dashboard</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="bg-accent text-primary px-4 py-2 rounded-md font-bold hover:bg-yellow-400 transition-colors shadow-sm">Login</a>
                        @endauth
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="-mr-2 flex md:hidden">
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-accent focus:outline-none"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Buka main menu</span>
                        <i class="fa-solid fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                        <i class="fa-solid fa-xmark text-xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden" id="mobile-menu" x-show="mobileMenuOpen" x-transition style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-primary border-t border-blue-800">
                <a href="{{ route('home') }}" class="hover:text-accent block px-3 py-2 rounded-md text-base font-medium">Beranda</a>
                <a href="{{ route('public.bumdes.list') }}" class="hover:text-accent block px-3 py-2 rounded-md text-base font-medium">BUMDesa</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        confirmButtonColor: '#1e3a5f',
                    });
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: "{{ session('error') }}",
                        confirmButtonColor: '#1e3a5f',
                    });
                });
            </script>
        @endif

        @if (session('info'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'Informasi',
                        text: "{{ session('info') }}",
                        confirmButtonColor: '#1e3a5f',
                    });
                });
            </script>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-primary text-white py-6 border-t border-blue-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <!-- Copyright -->
                <div class="text-xs text-gray-400 order-2 md:order-1 flex-1 text-center md:text-left">
                    &copy; {{ date('Y') }} Portal BUMDesa All rights reserved.
                </div>
                
                <!-- Navigation Links -->
                <div class="flex flex-wrap justify-center gap-6 text-sm font-medium text-blue-200 order-1 md:order-2 flex-1 md:justify-center whitespace-nowrap">
                    <a href="{{ route('public.about') }}" class="hover:text-accent transition-colors">Tentang Kami</a>
                    <a href="{{ route('public.services') }}" class="hover:text-accent transition-colors">Layanan dan Produk</a>
                    <a href="{{ route('public.faq') }}" class="hover:text-accent transition-colors">FAQ</a>
                    <a href="{{ route('public.contact') }}" class="hover:text-accent transition-colors">Kontak</a>
                </div>
                
                <!-- Empty spacer to keep navigation exactly in center -->
                <div class="hidden md:block flex-1 order-3"></div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
