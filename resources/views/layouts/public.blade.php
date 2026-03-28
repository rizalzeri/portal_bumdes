<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal BUMDesa') - Pusat Inspirasi dan Informasi BUMDesa</title>

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
    <nav class="bg-primary text-white shadow-lg sticky top-0 z-50">
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
    <footer class="bg-primary text-white pt-8 pb-8 border-t border-blue-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Bottom Line -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-gray-400 text-center md:text-left">
                    &copy; {{ date('Y') }} Portal BUMDesa - Pusat Inspirasi dan Informasi BUMDesa. All rights reserved.
                </p>
                <p class="text-[10px] text-gray-500 font-medium">
                    Powered by <span class="text-accent underline">BumdesPro Official</span>
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
