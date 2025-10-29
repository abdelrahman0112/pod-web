@props([
    'name' => 'user_id',
    'label' => 'Select User',
    'placeholder' => 'Search and select a user...',
    'required' => false,
    'selectedUser' => null,
    'roles' => ['superadmin', 'admin', 'client'],
    'searchUrl' => null
])

@php
    $searchUrl = $searchUrl ?? route('api.users.search');
    $users = \App\Models\User::whereIn('role', $roles)->get()->map(function($user) {
        return [
            'id' => $user->id,
            'name' => $user->name ?: $user->email,
            'email' => $user->email,
            'role' => $user->role,
            'avatar' => $user->avatar
        ];
    });
@endphp

<div>
    <label class="block text-sm font-medium text-slate-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <div class="relative" x-data="userSelect('{{ $name }}', '{{ $searchUrl }}')">
        <!-- Selected User Display -->
        <div class="relative">
            <button type="button" 
                    @click="toggleDropdown()"
                    class="w-full px-3 py-2 text-left border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white">
                <div x-show="!selectedUser" class="text-slate-500">
                    {{ $placeholder }}
                </div>
                <div x-show="selectedUser" class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                        <img x-show="selectedUser.avatar" 
                             :src="selectedUser.avatar" 
                             :alt="selectedUser.name"
                             class="w-8 h-8 rounded-full object-cover" />
                        <i x-show="!selectedUser.avatar" class="ri-user-line text-indigo-600"></i>
                    </div>
                    <div>
                        <div class="font-medium text-slate-900" x-text="selectedUser.name"></div>
                        <div class="text-sm text-slate-500" x-text="selectedUser.email"></div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2">
                    <i class="ri-arrow-down-s-line text-slate-400" 
                       :class="{ 'rotate-180': isOpen }"></i>
                </div>
            </button>
        </div>
        
        <!-- Dropdown -->
        <div x-show="isOpen" 
             @click.away="closeDropdown()"
             x-transition
             class="absolute z-10 w-full mt-1 bg-white border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-auto">
            
            <!-- Search Input -->
            <div class="p-3 border-b border-slate-200">
                <input type="text" 
                       x-model="searchQuery"
                       @input="searchUsers()"
                       placeholder="Search users..."
                       class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm" />
            </div>
            
            <!-- Loading State -->
            <div x-show="isLoading" class="p-4 text-center text-slate-500">
                <i class="ri-loader-4-line animate-spin mr-2"></i>
                Searching...
            </div>
            
            <!-- Users List -->
            <div x-show="!isLoading">
                <template x-for="user in filteredUsers" :key="user.id">
                    <div @click="selectUser(user)"
                         class="flex items-center space-x-3 p-3 hover:bg-slate-50 cursor-pointer border-b border-slate-100 last:border-b-0">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center">
                            <img x-show="user.avatar" 
                                 :src="user.avatar" 
                                 :alt="user.name"
                                 class="w-10 h-10 rounded-full object-cover" />
                            <i x-show="!user.avatar" class="ri-user-line text-slate-600"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-slate-900 truncate" x-text="user.name"></div>
                            <div class="text-sm text-slate-500 truncate" x-text="user.email"></div>
                            <div class="text-xs text-slate-400 capitalize" x-text="user.role"></div>
                        </div>
                        <div x-show="selectedUser && selectedUser.id === user.id" 
                             class="text-indigo-600">
                            <i class="ri-check-line"></i>
                        </div>
                    </div>
                </template>
                
                <!-- No Results -->
                <div x-show="!isLoading && filteredUsers.length === 0" 
                     class="p-4 text-center text-slate-500">
                    <i class="ri-user-search-line text-2xl mb-2 block"></i>
                    No users found
                </div>
            </div>
        </div>
        
        <!-- Hidden Input -->
        <input type="hidden" 
               name="{{ $name }}" 
               x-model="selectedUser ? selectedUser.id : ''" />
    </div>
</div>

<script>
function userSelect(name, searchUrl) {
    return {
        isOpen: false,
        isLoading: false,
        searchQuery: '',
        selectedUser: @json($selectedUser),
        users: @json($users),
        filteredUsers: [],
        
        init() {
            this.filteredUsers = this.users;
            if (this.selectedUser) {
                this.selectedUser = this.users.find(u => u.id === this.selectedUser.id) || this.selectedUser;
            }
        },
        
        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.filteredUsers = this.users;
                this.searchQuery = '';
            }
        },
        
        closeDropdown() {
            this.isOpen = false;
        },
        
        selectUser(user) {
            this.selectedUser = user;
            this.isOpen = false;
        },
        
        async searchUsers() {
            if (this.searchQuery.length < 2) {
                this.filteredUsers = this.users;
                return;
            }
            
            this.isLoading = true;
            
            try {
                const response = await fetch(`${searchUrl}?q=${encodeURIComponent(this.searchQuery)}`);
                const data = await response.json();
                this.filteredUsers = data.users || [];
            } catch (error) {
                console.error('Search error:', error);
                this.filteredUsers = this.users.filter(user => 
                    user.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    user.email.toLowerCase().includes(this.searchQuery.toLowerCase())
                );
            } finally {
                this.isLoading = false;
            }
        }
    }
}
</script>
