<header class="sticky top-0 z-30 flex border-b bg-white border-gray-200 shadow-sm">
    <div class="flex items-center justify-between flex-1 px-4 py-3 sm:px-6">

        <!-- Mobile hamburger -->
        <div class="flex items-center">
            <button @click="sidebarOpen = true"
                class="p-1 mr-4 text-gray-500 rounded-md md:hidden hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-accent">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-semibold text-gray-800 hidden sm:block">@yield('title', 'Dashboard')</h1>
        </div>

        <div class="flex items-center">
            <div x-data="{ dropdownOpen: false }" class="relative">
                <button @click="dropdownOpen = !dropdownOpen"
                    class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 focus:outline-none transition-colors">
                    <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="hidden md:block">{{ Auth::user()->name }}</span>
                    <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                </button>

                <!-- Dropdown -->
                <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 w-48 mt-2 origin-top-right bg-white rounded-md shadow-xl z-50 ring-1 ring-black ring-opacity-5"
                    style="display: none;">

                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm">Login as:</p>
                        <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                        <p
                            class="text-xs mt-1 text-accen inline-block bg-accent px-2 py-0.5 rounded text-primary font-semibold uppercase">
                            {{ Auth::user()->role }}</p>
                    </div>

                    <a href="{{ route('home') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                        <i class="fa-solid fa-globe w-4 h-4 mr-2"></i> Ke Halaman Publik
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 hover:text-red-900">
                            <i class="fa-solid fa-arrow-right-from-bracket w-4 h-4 mr-2"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
