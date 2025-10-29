@extends('layouts.app')

@section('title', 'Hackathons - People Of Data')

@section('content')
    
<x-page-header
    title="Hackathons"
    description="Discover coding competitions and programming challenges to showcase your skills"
    actionUrl="{{ auth()->check() && auth()->user()->canCreateHackathons() ? route('hackathons.create') : '' }}"
    actionText="{{ auth()->check() && auth()->user()->canCreateHackathons() ? 'Host Hackathon' : '' }}"
    icon="ri-add-line"
/>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-8" x-data="hackathonFilters()" x-init="init()">
        <div class="space-y-4">
            <!-- Category Filters -->
            <div class="flex items-center space-x-2 flex-wrap w-full" x-ref="categoryContainer">
                <button @click="filterByCategory(null)" 
                        :class="selectedCategory === null ? 'bg-indigo-100 text-indigo-600' : 'text-slate-600 hover:bg-slate-100'"
                        class="px-4 py-2 rounded-button text-sm font-medium !rounded-button whitespace-nowrap">
                    All Hackathons
                </button>
                
                <!-- Visible Categories -->
                <template x-for="(category, index) in visibleCategories" :key="category.id">
                    <button @click="filterByCategory(category.id)" 
                            :class="selectedCategory === category.id ? 'text-white' : 'text-slate-600 hover:bg-slate-100'"
                            :style="selectedCategory === category.id ? 'background-color: ' + category.color : ''"
                            class="px-4 py-2 rounded-button text-sm font-medium !rounded-button whitespace-nowrap">
                        <span x-text="category.name"></span>
                    </button>
                </template>
                
                <!-- Show More Button -->
                <div x-show="hasMoreCategories" class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center space-x-1 px-4 py-2 border border-slate-200 rounded-button text-sm hover:bg-slate-50 !rounded-button whitespace-nowrap">
                        <span>More</span>
                        <div class="w-4 h-4 flex items-center justify-center">
                            <i class="ri-arrow-down-s-line" :class="open ? 'rotate-180' : ''"></i>
                        </div>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" 
                         class="absolute top-full left-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-50">
                        <div class="p-2">
                            <template x-for="category in hiddenCategories" :key="category.id">
                                <button @click="filterByCategory(category.id); open = false" 
                                        :class="selectedCategory === category.id ? 'text-white' : 'text-slate-600 hover:bg-slate-50'"
                                        :style="selectedCategory === category.id ? 'background-color: ' + category.color : ''"
                                        class="w-full text-left px-3 py-2 text-sm rounded whitespace-nowrap">
                                    <span x-text="category.name"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filter Dropdowns -->
            <div class="flex items-center space-x-4 flex-wrap">
                <!-- Status Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center space-x-2 px-4 py-2 border border-slate-200 rounded-button text-sm hover:bg-slate-50 !rounded-button whitespace-nowrap">
                        <div class="w-4 h-4 flex items-center justify-center">
                            <i class="ri-pulse-line"></i>
                        </div>
                        <span x-text="getStatusLabel()"></span>
                        <div class="w-4 h-4 flex items-center justify-center">
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </button>
                    <div x-show="open" @click.away="open = false" 
                         class="absolute top-full left-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-50">
                        <div class="p-2">
                            <button @click="filterByStatus(''); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                All Status
                            </button>
                            <button @click="filterByStatus('upcoming'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                Upcoming
                            </button>
                            <button @click="filterByStatus('ongoing'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                Ongoing
                            </button>
                            <button @click="filterByStatus('registration_open'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                Open Registration
                            </button>
                            <button @click="filterByStatus('past'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                Past
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Skill Level Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center space-x-2 px-4 py-2 border border-slate-200 rounded-button text-sm hover:bg-slate-50 !rounded-button whitespace-nowrap">
                        <div class="w-4 h-4 flex items-center justify-center">
                            <i class="ri-trophy-line"></i>
                        </div>
                        <span x-text="getSkillLevelLabel()"></span>
                        <div class="w-4 h-4 flex items-center justify-center">
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </button>
                    <div x-show="open" @click.away="open = false" 
                         class="absolute top-full left-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-50">
                        <div class="p-2">
                            <button @click="filterBySkillLevel(''); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                All Skill Levels
                            </button>
                            <button @click="filterBySkillLevel('beginner'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                Beginner
                            </button>
                            <button @click="filterBySkillLevel('intermediate'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                Intermediate
                            </button>
                            <button @click="filterBySkillLevel('advanced'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                Advanced
                            </button>
                            <button @click="filterBySkillLevel('all-levels'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                All Levels
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Prize Range Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center space-x-2 px-4 py-2 border border-slate-200 rounded-button text-sm hover:bg-slate-50 !rounded-button whitespace-nowrap">
                        <div class="w-4 h-4 flex items-center justify-center">
                            <i class="ri-gift-line"></i>
                        </div>
                        <span x-text="getPrizeRangeLabel()"></span>
                        <div class="w-4 h-4 flex items-center justify-center">
                            <i class="ri-arrow-down-s-line"></i>
                        </div>
                    </button>
                    <div x-show="open" @click.away="open = false" 
                         class="absolute top-full left-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-50">
                        <div class="p-2">
                            <button @click="filterByPrizeRange(''); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                All Prizes
                            </button>
                            <button @click="filterByPrizeRange('free'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                Free to Enter
                            </button>
                            <button @click="filterByPrizeRange('0-10'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                EGP 0 - EGP 10k
                            </button>
                            <button @click="filterByPrizeRange('10-50'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                EGP 10k - EGP 50k
                            </button>
                            <button @click="filterByPrizeRange('50-100'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                EGP 50k - EGP 100k
                            </button>
                            <button @click="filterByPrizeRange('100+'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                EGP 100k+
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Search Form -->
                <div class="relative flex-1 max-w-sm">
                    <input type="text" 
                           x-model="searchQuery" 
                           @input.debounce.500ms="searchHackathons($event.target.value)"
                           placeholder="Search hackathons..." 
                           class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                    <div class="absolute left-3 top-2.5 text-slate-400 w-4 h-4 flex items-center justify-center">
                        <i class="ri-search-line"></i>
                    </div>
                </div>

                <!-- Clear Filters -->
                @if(request()->hasAny(['category', 'status', 'search', 'skill_level', 'prize_range']))
                    <a href="{{ route('hackathons.index') }}" 
                       class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg text-sm font-medium border border-slate-300">
                        <i class="ri-close-line mr-1"></i>
                        Clear All
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Results Header -->
    @if(request()->hasAny(['category', 'status', 'search', 'skill_level', 'prize_range']))
    <div class="flex items-center justify-between mb-6 mt-6">
        <div class="flex items-center space-x-2">
            <span class="text-sm text-slate-500">Filtered by:</span>
                    @if(request('category'))
                        @php $category = \App\Models\HackathonCategory::find(request('category')); @endphp
                        @if($category)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-white" style="background-color: {{ $category->color }}">
                            {{ $category->name }}
                            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="ml-1 hover:opacity-80">
                                <i class="ri-close-line"></i>
                            </a>
                        </span>
                        @endif
                    @endif
                    @if(request('status'))
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-600">
                            {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="ml-1 hover:text-indigo-800">
                                <i class="ri-close-line"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('search'))
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-600">
                            "{{ request('search') }}"
                            <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="ml-1 hover:text-green-800">
                                <i class="ri-close-line"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('skill_level'))
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-600">
                            {{ ucfirst(request('skill_level')) }}
                            <a href="{{ request()->fullUrlWithQuery(['skill_level' => null]) }}" class="ml-1 hover:text-blue-800">
                                <i class="ri-close-line"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('prize_range'))
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-600">
                            @switch(request('prize_range'))
                                @case('free') Free to Enter @break
                                @case('0-10') EGP 0 - EGP 10k @break
                                @case('10-50') EGP 10k - EGP 50k @break
                                @case('50-100') EGP 50k - EGP 100k @break
                                @case('100+') EGP 100k+ @break
                                @default {{ request('prize_range') }} @break
                            @endswitch
                            <a href="{{ request()->fullUrlWithQuery(['prize_range' => null]) }}" class="ml-1 hover:text-purple-800">
                                <i class="ri-close-line"></i>
                            </a>
                        </span>
                    @endif
        </div>
    </div>
    @endif

    <div class="flex flex-col lg:flex-row lg:gap-8 gap-6 w-full justify-center">
        <!-- Hackathons List -->
        <div class="flex-1 w-full lg:max-w-3xl min-w-0 lg:flex-shrink-0">
            <div id="hackathons-container">
                <div class="grid grid-cols-1 gap-6">
                    @forelse($hackathons ?? [] as $hackathon)
                        <x-cards.hackathon-card :hackathon="$hackathon" />
                    @empty
                        <x-empty-search-state
                            icon="ri-trophy-line"
                            iconBg="from-indigo-100 to-purple-100"
                            iconColor="text-indigo-400"
                            :filterKeys="['category', 'status', 'search', 'skill_level', 'prize_range']"
                            clearFiltersFunction="clearAllFilters"
                            title="No Hackathons Found"
                            titleFiltered="No Hackathons Match Your Filters"
                            description="There are currently no hackathons available. Check back later for new opportunities!"
                            descriptionFiltered="No hackathons match your current filters. Try adjusting your search criteria or clearing the filters to see more hackathons."
                        />
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($hackathons->hasPages())
                    <div id="load-more-container" class="mt-8">
                        {{ $hackathons->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="w-full lg:w-80 lg:flex-shrink-0 min-w-0">
            <div class="space-y-6">
                <!-- Competition Statistics -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Competition Statistics</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Active Hackathons</span>
                            <span class="text-sm font-semibold text-slate-800">{{ \App\Models\Hackathon::where('start_date', '<=', now())->where('end_date', '>=', now())->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Total Participants</span>
                            <span class="text-sm font-semibold text-slate-800">{{ \App\Models\HackathonTeamMember::count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Prize Pool Total</span>
                            <span class="text-sm font-semibold text-slate-800">EGP {{ number_format(\App\Models\Hackathon::sum('prize_pool')) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Organizing Companies</span>
                            <span class="text-sm font-semibold text-slate-800">{{ \App\Models\Hackathon::distinct('created_by')->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Top Hackathon Organizers -->
                @php
                    $topOrganizers = \App\Models\Hackathon::select('created_by')
                        ->selectRaw('COUNT(*) as hackathon_count')
                        ->groupBy('created_by')
                        ->having('hackathon_count', '>=', 2)
                        ->orderBy('hackathon_count', 'desc')
                        ->take(5)
                        ->get()
                        ->map(function($item) {
                            return \App\Models\User::find($item->created_by);
                        })
                        ->filter();
                @endphp
                @if($topOrganizers->count() >= 2)
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Top Organizers</h3>
                    <div class="space-y-2">
                        @foreach($topOrganizers as $organizer)
                        <a href="{{ route('profile.show.other', $organizer->id) }}" class="flex items-center space-x-3 hover:bg-slate-50 rounded-lg p-2 -m-2 transition-colors group">
                            <x-avatar 
                                :src="$organizer->avatar ?? null"
                                :name="$organizer->name ?? 'User'"
                                size="sm"
                                :color="$organizer->avatar_color ?? null" />
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 group-hover:text-indigo-600 transition-colors truncate flex items-center">{{ $organizer->name }}<x-business-badge :user="$organizer" /></p>
                                <p class="text-xs text-slate-500 truncate">{{ $organizer->job_title ?? 'Organizer' }}</p>
                            </div>
                            <div class="w-2 h-2 bg-green-500 rounded-full flex-shrink-0"></div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Available Teams to Join -->
                @php
                    $availableTeams = \App\Models\HackathonTeam::where('is_public', true)
                        ->with(['leader', 'hackathon'])
                        ->inRandomOrder()
                        ->take(3)
                        ->get();
                @endphp
                @if($availableTeams->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-slate-800">Available Teams</h3>
                        <a href="{{ route('home.hackathons.teams') }}" class="text-sm text-indigo-600 hover:text-indigo-800">View All</a>
                    </div>
                    <div class="space-y-4">
                        @foreach($availableTeams as $team)
                        <div class="border border-slate-100 rounded-lg p-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-slate-800 truncate">{{ $team->name }}</h4>
                                    <p class="text-xs text-slate-500 mt-1">Looking for members</p>
                                </div>
                                <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded-full">Open</span>
                            </div>
                            <div class="flex items-center space-x-2 text-xs text-slate-600 mb-2">
                                <i class="ri-user-line"></i>
                                <span>{{ $team->members()->count() + 1 }}/{{ $team->hackathon->max_team_size ?? 'N/A' }} members</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <a href="{{ route('hackathons.show', $team->hackathon) }}" class="text-slate-600 hover:text-indigo-600">
                                    {{ $team->hackathon->title }}
                                </a>
                                <a href="{{ route('home.hackathons.teams.show', $team) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                    View Team →
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Available Teams</h3>
                    <div class="text-center py-6">
                        <i class="ri-team-line text-4xl text-slate-300 mb-3"></i>
                        <p class="text-sm text-slate-600 mb-4">No teams available at the moment</p>
                        <a href="{{ route('hackathons.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm">
                            <i class="ri-add-line mr-2"></i>
                            Join a Hackathon
                        </a>
                    </div>
                </div>
                @endif

                <!-- My Teams -->
                @auth
                @php
                    $userTeams = \App\Models\HackathonTeamMember::where('user_id', auth()->id())
                        ->with(['team.leader', 'team.hackathon', 'team.members'])
                        ->latest()
                        ->get();
                @endphp
                @if($userTeams->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-slate-800">My Teams</h3>
                        <a href="{{ route('home.hackathons.teams') }}" class="text-sm text-indigo-600 hover:text-indigo-800">View All</a>
                    </div>
                    <div class="space-y-3">
                        @foreach($userTeams->take(3) as $membership)
                        <div class="border border-slate-100 rounded-lg p-3 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-slate-800 truncate">{{ $membership->team->name }}</h4>
                                @if($membership->team->leader_id === auth()->id())
                                    <span class="text-xs bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-full">Leader</span>
                                @else
                                    <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">Member</span>
                                @endif
                            </div>
                            <div class="flex items-center space-x-2 text-xs text-slate-600 mb-2">
                                <i class="ri-calendar-line"></i>
                                <span>{{ $membership->team->hackathon->title }}</span>
                            </div>
                            <a href="{{ route('home.hackathons.teams.show', $membership->team) }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                View Details →
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @endauth

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function hackathonFilters() {
            return {
                categories: @json($categories),
                selectedCategory: {{ request('category') ?: 'null' }},
                selectedStatus: '{{ request('status') }}',
                selectedSkillLevel: '{{ request('skill_level') }}',
                selectedPrizeRange: '{{ request('prize_range') }}',
                searchQuery: '{{ request('search') }}',
                visibleCount: 5,
                
                get visibleCategories() {
                    return this.categories.slice(0, this.visibleCount);
                },
                
                get hiddenCategories() {
                    return this.categories.slice(this.visibleCount);
                },
                
                get hasMoreCategories() {
                    return this.categories.length > this.visibleCount;
                },
                
                init() {
                    // Initialize from URL parameters
                    this.selectedCategory = {{ request('category') ?: 'null' }};
                    this.selectedStatus = '{{ request('status') }}';
                    this.selectedSkillLevel = '{{ request('skill_level') }}';
                    this.selectedPrizeRange = '{{ request('prize_range') }}';
                    this.searchQuery = '{{ request('search') }}';
                },
                
                filterByCategory(categoryId) {
                    this.selectedCategory = categoryId;
                    this.applyFilters();
                },
                
                filterByStatus(status) {
                    this.selectedStatus = status;
                    this.applyFilters();
                },
                
                searchHackathons(query) {
                    this.searchQuery = query;
                    this.applyFilters();
                },
                
                filterBySkillLevel(level) {
                    this.selectedSkillLevel = level;
                    this.applyFilters();
                },
                
                filterByPrizeRange(range) {
                    this.selectedPrizeRange = range;
                    this.applyFilters();
                },
                
                getStatusLabel() {
                    if (!this.selectedStatus) return 'Status';
                    return this.selectedStatus === 'upcoming' ? 'Upcoming' :
                           this.selectedStatus === 'ongoing' ? 'Ongoing' :
                           this.selectedStatus === 'registration_open' ? 'Open Registration' :
                           this.selectedStatus === 'past' ? 'Past' :
                           'Status';
                },
                
                getSkillLevelLabel() {
                    if (!this.selectedSkillLevel) return 'Skill Level';
                    return this.selectedSkillLevel === 'beginner' ? 'Beginner' :
                           this.selectedSkillLevel === 'intermediate' ? 'Intermediate' :
                           this.selectedSkillLevel === 'advanced' ? 'Advanced' :
                           this.selectedSkillLevel === 'all-levels' ? 'All Levels' :
                           'Skill Level';
                },
                
                getPrizeRangeLabel() {
                    if (!this.selectedPrizeRange) return 'Prize Range';
                    return this.selectedPrizeRange === 'free' ? 'Free to Enter' :
                           this.selectedPrizeRange === '0-10' ? 'EGP 0 - EGP 10k' :
                           this.selectedPrizeRange === '10-50' ? 'EGP 10k - EGP 50k' :
                           this.selectedPrizeRange === '50-100' ? 'EGP 50k - EGP 100k' :
                           this.selectedPrizeRange === '100+' ? 'EGP 100k+' :
                           'Prize Range';
                },
                
                applyFilters() {
                    const params = new URLSearchParams();
                    
                    if (this.selectedCategory) params.append('category', this.selectedCategory);
                    if (this.selectedStatus) params.append('status', this.selectedStatus);
                    if (this.selectedSkillLevel) params.append('skill_level', this.selectedSkillLevel);
                    if (this.selectedPrizeRange) params.append('prize_range', this.selectedPrizeRange);
                    if (this.searchQuery) params.append('search', this.searchQuery);
                    
                    const url = `{{ route('hackathons.index') }}?${params.toString()}`;
                    
                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html',
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContainer = doc.querySelector('#hackathons-container');
                        const newCount = doc.querySelector('#hackathons-count');
                        
                        if (newContainer) {
                            document.querySelector('#hackathons-container').innerHTML = newContainer.innerHTML;
                        }
                        
                        if (newCount) {
                            document.querySelector('#hackathons-count').textContent = newCount.textContent;
                        }
                        
                        // Update URL without page reload
                        window.history.pushState({}, '', url);
                        
                        // Re-initialize countdowns after filter update
                        initHackathonCountdowns();
                    })
                    .catch(error => {
                        console.error('Error applying filters:', error);
                    });
                }
            }
        }

        // Hackathon countdown functionality
        function initHackathonCountdowns() {
            const countdownElements = document.querySelectorAll('[class^="countdown-"]');
            
            countdownElements.forEach(function(element) {
                const hackathonId = element.className.match(/countdown-(\d+)/)?.[1];
                if (!hackathonId) return;
                
                const targetTime = parseInt(element.getAttribute('data-target-time'));
                if (!targetTime) return;
                
                function updateCountdown() {
                    const now = Math.floor(Date.now() / 1000);
                    const distance = targetTime - now;
                    
                    if (distance < 0) {
                        element.textContent = 'Started';
                        return;
                    }
                    
                    const days = Math.floor(distance / (60 * 60 * 24));
                    const hours = Math.floor((distance % (60 * 60 * 24)) / (60 * 60));
                    const minutes = Math.floor((distance % (60 * 60)) / 60);
                    const seconds = Math.floor(distance % 60);
                    
                    if (days > 0) {
                        element.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                    } else if (hours > 0) {
                        element.textContent = `${hours}h ${minutes}m ${seconds}s`;
                    } else if (minutes > 0) {
                        element.textContent = `${minutes}m ${seconds}s`;
                    } else {
                        element.textContent = `${seconds}s`;
                    }
                }
                
                // Update immediately
                updateCountdown();
                
                // Update every second
                setInterval(updateCountdown, 1000);
            });
        }

        // Initialize countdowns on page load
        document.addEventListener('DOMContentLoaded', function() {
            initHackathonCountdowns();
        });

        function clearAllFilters() {
            console.log('🗑️ Clearing all filters');
            // Get the Alpine.js component instance
            const hackathonFiltersComponent = Alpine.$data(document.querySelector('[x-data="hackathonFilters()"]'));
            if (hackathonFiltersComponent) {
                hackathonFiltersComponent.selectedCategory = null;
                hackathonFiltersComponent.selectedStatus = '';
                hackathonFiltersComponent.selectedSkillLevel = '';
                hackathonFiltersComponent.selectedPrizeRange = '';
                hackathonFiltersComponent.searchQuery = '';
                
                // Clear search input
                const searchInput = document.querySelector('input[placeholder="Search hackathons..."]');
                if (searchInput) {
                    searchInput.value = '';
                }
                
                hackathonFiltersComponent.applyFilters();
            }
        }
    </script>
    @endpush
@endsection