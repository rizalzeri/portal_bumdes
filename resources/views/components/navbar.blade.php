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

        <div class="flex items-center gap-4">
            
            @if(Auth::check() && Auth::user()->role === 'user')
                <div x-data="{ notifOpen: false }" class="relative">
                    <button @click="notifOpen = !notifOpen" class="relative p-2 text-gray-500 hover:text-primary transition-colors focus:outline-none">
                        <i class="fa-solid fa-bell text-xl"></i>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                        @endif
                    </button>
                    
                    <div x-show="notifOpen" @click.away="notifOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 w-80 mt-2 origin-top-right bg-white rounded-lg shadow-xl border border-gray-100 z-50 overflow-hidden"
                        style="display: none;">
                        
                        <div class="px-4 py-3 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 text-sm">Notifikasi</h3>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <form action="{{ route('user.notifications.read', Auth::user()->username) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] text-blue-600 hover:text-blue-800 font-semibold bg-blue-50 px-2 py-1 rounded">Tandai sudah dibaca</button>
                                </form>
                            @endif
                        </div>
                        
                        <div class="max-h-80 overflow-y-auto">
                            @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
                                <div class="px-4 py-3 border-b border-gray-50 {{ empty($notification->read_at) ? 'bg-blue-50/50' : 'bg-white' }} hover:bg-gray-50 transition-colors">
                                    <div class="flex gap-3">
                                        <div class="flex-shrink-0 mt-1">
                                            @if(($notification->data['type'] ?? '') == 'warning')
                                                <i class="fa-solid fa-triangle-exclamation text-yellow-500"></i>
                                            @else
                                                <i class="fa-solid fa-bell text-blue-500"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-800">{{ $notification->data['title'] ?? 'Pesan Sistem' }}</p>
                                            <p class="text-xs text-gray-600 mt-1">{{ $notification->data['message'] ?? '' }}</p>
                                            <p class="text-[10px] text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-8 text-center text-gray-500">
                                    <i class="fa-regular fa-bell-slash text-3xl mb-3 text-gray-300 block"></i>
                                    <p class="text-sm">Belum ada notifikasi saat ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

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
