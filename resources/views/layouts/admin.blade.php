<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Portal BUMDesa</title>

    <!-- Tailwind CSS (via CDN) -->
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

    <!-- DataTables via CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* DataTable Tailwind Customization */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            color: #fff !important;
            border: 1px solid #1e3a5f !important;
            background-color: #1e3a5f !important;
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #1e3a5f), color-stop(100%, #1e3a5f)) !important;
            background: -webkit-linear-gradient(top, #1e3a5f 0%, #1e3a5f 100%) !important;
            background: -moz-linear-gradient(top, #1e3a5f 0%, #1e3a5f 100%) !important;
            background: -ms-linear-gradient(top, #1e3a5f 0%, #1e3a5f 100%) !important;
            background: -o-linear-gradient(top, #1e3a5f 0%, #1e3a5f 100%) !important;
            background: linear-gradient(to bottom, #1e3a5f 0%, #1e3a5f 100%) !important;
        }
        
        /* Inline editing */
        .editable { cursor: pointer; padding: 4px; border-radius: 4px; transition: background 0.2s; }
        .editable:hover { background: #f3f4f6; }
        .editing { background: #fff; border: 1px solid #d1d5db; padding: 2px 4px; outline: none; box-shadow: 0 0 0 2px rgba(96, 165, 250, 0.4); }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100 text-gray-800 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Component -->
        @include('components.sidebar')

        <!-- Content Area -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            
            <!-- Navbar Component -->
            @include('components.navbar')

            <!-- Main section -->
            <main class="p-4 md:p-6 lg:p-8 w-full max-w-9xl mx-auto">
                
                @if (session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: "{{ session('success') }}",
                                timer: 3000,
                                showConfirmButton: false
                            });
                        });
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: "{{ session('error') }}",
                                timer: 3000,
                                showConfirmButton: false
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
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Only auto-init tables with .datatable class that have no id attribute
            // (Tables with IDs use their own init in @push('scripts'))
            $('.datatable:not([id])').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Lanjut",
                        previous: "Kembali"
                    }
                }
            });
            
            // Setup CSRF token for AJAX globally
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
