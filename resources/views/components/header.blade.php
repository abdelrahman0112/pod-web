<header class="fixed top-0 left-0 right-0 bg-white shadow-sm z-10">
    <div class="w-full px-4 md:px-8 flex items-center justify-between h-16">
        <!-- Mobile Menu Button (leftmost) -->
        <button onclick="toggleMobileSidebar()" class="md:hidden flex items-center justify-center w-10 h-10 text-slate-600 hover:text-indigo-600 transition-colors">
            <i class="ri-menu-line text-xl"></i>
        </button>
        
        <!-- Logo (clickable, floated left on mobile) -->
        <a href="{{ route('home') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
            <img src="{{ asset('storage/assets/pod-logo.png') }}" alt="People Of Data" class="w-8" />
            <span class="text-xl font-semibold text-slate-800 md:block hidden">People Of Data</span>
        </a>
        
        <!-- Desktop Search Bar -->
        <div class="flex-1 max-w-lg px-4 md:block hidden">
            <div class="relative">
                <input type="text" placeholder="Search..." 
                    class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                <div class="absolute left-3 top-2.5 text-slate-400 w-4 h-4 flex items-center justify-center">
                    <i class="ri-search-line"></i>
                </div>
            </div>
        </div>
        
        <!-- Mobile Search Icon (floated right) -->
        <button onclick="toggleMobileSearch()" class="md:hidden flex items-center justify-center w-10 h-10 text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-lg transition-all duration-200 ml-auto mr-2">
            <i class="ri-search-line text-lg"></i>
        </button>
        
        <!-- Right Side Actions -->
        <div class="flex items-center space-x-3 md:space-x-6">
            <!-- Messages -->
            <a href="{{ url(config('chatify.routes.prefix')) }}" class="relative text-slate-600 hover:text-indigo-600 transition-colors" id="header-messages-link">
                <div class="w-6 h-6 flex items-center justify-center">
                    <i class="ri-message-3-line"></i>
                </div>
                <span id="header-unread-badge" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center hidden"></span>
            </a>
            
            <!-- Notifications -->
            <button class="relative text-slate-600 hover:text-indigo-600 transition-colors">
                <div class="w-6 h-6 flex items-center justify-center">
                    <i class="ri-notification-3-line"></i>
                </div>
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">5</span>
            </button>
            
            <!-- Profile Menu -->
            <div class="relative" id="profile-menu">
                <button class="flex items-center md:space-x-3 space-x-0 hover:bg-slate-50 rounded-lg p-2 transition-colors">
                    <x-avatar 
                        :src="auth()->user()->avatar ?? null"
                        :name="auth()->user()->name ?? 'User'"
                        size="sm"
                        :color="auth()->user()->avatar_color ?? null" />
                    <span class="text-sm font-medium text-slate-700 md:block hidden">{{ auth()->user()->name ?? 'User' }}</span>
                    <div class="w-4 h-4 flex items-center justify-center md:block hidden">
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                </button>
                
                <div class="absolute right-0 top-full mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg py-2 hidden" id="profile-dropdown">
                    <a href="{{ route('profile.show') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                        <div class="w-4 h-4 flex items-center justify-center">
                            <i class="ri-user-line"></i>
                        </div>
                        <span>Profile</span>
                    </a>
                    <div class="h-px bg-slate-200 my-2"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2 text-sm text-red-600 hover:bg-slate-50">
                            <div class="w-4 h-4 flex items-center justify-center">
                                <i class="ri-logout-box-line"></i>
                            </div>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Search Bar -->
    <div id="mobile-search" class="md:hidden hidden bg-white border-t border-slate-200 px-4 py-3">
        <div class="relative">
            <input type="text" placeholder="Search..." 
                class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
            <div class="absolute left-3 top-2.5 text-slate-400 w-4 h-4 flex items-center justify-center">
                <i class="ri-search-line"></i>
            </div>
            <button onclick="closeMobileSearch()" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600">
                <i class="ri-close-line"></i>
            </button>
        </div>
    </div>
</header>