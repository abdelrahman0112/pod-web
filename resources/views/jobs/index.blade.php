@extends('layouts.app')

@section('title', 'Jobs - People Of Data')

@section('content')
<x-page-header 
    title="Jobs"
    description="Find your next career opportunity in data science and analytics"
    actionUrl="{{ auth()->check() && auth()->user()->canPostJobs() ? route('jobs.create') : '' }}"
    actionText="{{ auth()->check() && auth()->user()->canPostJobs() ? 'Post Job' : '' }}"
    icon="ri-add-line"
/>

<!-- Filters -->
<x-filters-section alpine-data="jobFilters()" all-label="All Jobs">
    <!-- Experience Level Filter -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center space-x-2 px-4 py-2 border border-slate-200 rounded-button text-sm hover:bg-slate-50 !rounded-button whitespace-nowrap">
                    <div class="w-4 h-4 flex items-center justify-center">
                        <i class="ri-user-line"></i>
                    </div>
                    <span x-text="selectedExperienceLevel || 'Experience Level'"></span>
                    <div class="w-4 h-4 flex items-center justify-center">
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                </button>
                <div x-show="open" @click.away="open = false" 
                     class="absolute top-full left-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-50">
                    <div class="p-2">
                        <button @click="filterByExperienceLevel('all'); open = false" 
                                class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                            All Levels
                        </button>
                        @foreach($experienceLevels as $value => $label)
                            <button @click="filterByExperienceLevel('{{ $value }}'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Location Type Filter -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center space-x-2 px-4 py-2 border border-slate-200 rounded-button text-sm hover:bg-slate-50 !rounded-button whitespace-nowrap">
                    <div class="w-4 h-4 flex items-center justify-center">
                        <i class="ri-map-pin-line"></i>
                    </div>
                    <span x-text="selectedLocationType || 'Location Type'"></span>
                    <div class="w-4 h-4 flex items-center justify-center">
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                </button>
                <div x-show="open" @click.away="open = false" 
                     class="absolute top-full left-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-50">
                    <div class="p-2">
                        <button @click="filterByLocationType('all'); open = false" 
                                class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                            All Types
                        </button>
                        @foreach($locationTypes as $value => $label)
                            <button @click="filterByLocationType('{{ $value }}'); open = false" 
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Salary Range Filter -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center space-x-2 px-4 py-2 border border-slate-200 rounded-button text-sm hover:bg-slate-50 !rounded-button whitespace-nowrap">
                    <div class="w-4 h-4 flex items-center justify-center">
                        <i class="ri-money-dollar-circle-line"></i>
                    </div>
                    <span x-text="selectedSalaryRange || 'Salary Range'"></span>
                    <div class="w-4 h-4 flex items-center justify-center">
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                </button>
                <div x-show="open" @click.away="open = false" 
                     class="absolute top-full left-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-50">
                    <div class="p-2">
                        <button @click="filterBySalaryRange('all'); open = false" 
                                class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                            All Ranges
                        </button>
                        <button @click="filterBySalaryRange('under_50k'); open = false" 
                                class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                            Under EGP 50k
                        </button>
                        <button @click="filterBySalaryRange('50k_100k'); open = false" 
                                class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                            EGP 50k - EGP 100k
                        </button>
                        <button @click="filterBySalaryRange('100k_150k'); open = false" 
                                class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                            EGP 100k - EGP 150k
                        </button>
                        <button @click="filterBySalaryRange('over_150k'); open = false" 
                                class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                            Over EGP 150k
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Date Posted Filter -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center space-x-2 px-4 py-2 border border-slate-200 rounded-button text-sm hover:bg-slate-50 !rounded-button whitespace-nowrap">
                    <div class="w-4 h-4 flex items-center justify-center">
                        <i class="ri-calendar-line"></i>
                    </div>
                    <span x-text="selectedDatePosted || 'Date Posted'"></span>
                    <div class="w-4 h-4 flex items-center justify-center">
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                </button>
                <div x-show="open" @click.away="open = false" 
                     class="absolute top-full left-0 mt-2 w-48 bg-white border border-slate-200 rounded-lg shadow-lg z-50">
                    <div class="p-2">
                        <button @click="filterByDatePosted('all'); open = false" 
                                class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                            Any Time
                        </button>
                        <button @click="filterByDatePosted('today'); open = false" 
                                class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                            Today
                        </button>
                        <button @click="filterByDatePosted('this_week'); open = false" 
                                class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                            This Week
                        </button>
                        <button @click="filterByDatePosted('this_month'); open = false" 
                                class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                            This Month
                        </button>
                        <button @click="filterByDatePosted('last_month'); open = false" 
                                class="w-full text-left px-3 py-2 text-sm hover:bg-slate-50 rounded">
                            Last Month
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Search -->
            <div class="relative flex-1 max-w-sm">
                <input type="text" 
                       x-model="searchQuery" 
                       @input.debounce.500ms="searchJobs()"
                       placeholder="Search jobs, companies, skills..." 
                       class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-button text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                <div class="absolute left-3 top-2.5 text-slate-400 w-4 h-4 flex items-center justify-center">
                    <i class="ri-search-line"></i>
                </div>
            </div>
</x-filters-section>

<div class="flex flex-col lg:flex-row lg:gap-8 gap-6 w-full justify-center">
    <!-- Jobs List -->
    <div class="flex-1 w-full lg:max-w-3xl min-w-0 lg:flex-shrink-0">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="jobs-container">
        @if($jobs->count() > 0)
                @foreach($jobs as $job)
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 hover:shadow-lg hover:shadow-indigo-100/50 transition-all group">
                        <div class="p-6">
                            <!-- Job Poster -->
                            <div class="mb-2">
                                <a href="{{ route('profile.show.other', $job->posted_by) }}" class="inline-flex items-center space-x-2 px-2 py-1.5 -mx-2 -my-1.5 rounded-lg hover:bg-slate-50 transition-colors group/poster">
                                    <x-avatar 
                                        :src="$job->poster->avatar ?? null"
                                        :name="$job->poster->name ?? 'User'"
                                        size="sm"
                                        :color="$job->poster->avatar_color ?? null" />
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-800 group-hover/poster:text-indigo-600 transition-colors flex items-center">
                                            <span class="truncate">{{ $job->poster->name }}</span><x-business-badge :user="$job->poster" />
                                        </p>
                                        <p class="text-xs text-slate-500">{{ $job->company_name }}</p>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="mb-4">
                                <h3 class="text-xl font-semibold text-slate-800">
                                    <a href="{{ route('jobs.show', $job) }}" class="hover:text-indigo-600 transition-colors">
                                        {{ $job->title }}
                                    </a>
                                </h3>
                                @if($job->formatted_salary)
                                    <p class="text-lg font-bold text-green-600 mt-1">
                                        EGP {{ number_format($job->salary_min ?? 0) }}@if($job->salary_max) - {{ number_format($job->salary_max) }}@endif
                                    </p>
                                @endif
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-slate-600 mb-4">
                                <div class="flex items-center space-x-1">
                                    <div class="w-4 h-4 flex items-center justify-center">
                                        <i class="ri-map-pin-line"></i>
                                    </div>
                                    <span>
                                        @if($job->location_type === \App\LocationType::REMOTE)
                                            Remote
                                        @else
                                            {{ $job->location }}
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <div class="w-4 h-4 flex items-center justify-center">
                                        <i class="ri-time-line"></i>
                                    </div>
                                    <span>{{ $job->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            
                            <!-- Job Metadata -->
                            <div class="flex flex-wrap gap-2 mb-3">
                                <div class="flex items-center space-x-1.5 text-xs text-slate-600">
                                    <div class="flex-shrink-0 w-6 h-6 bg-indigo-50 rounded-md flex items-center justify-center">
                                        <i class="ri-user-line text-indigo-600 text-xs"></i>
                                    </div>
                                    <span class="font-medium">{{ $job->experience_level->getLabel() }}</span>
                                </div>
                                
                                <div class="flex items-center space-x-1.5 text-xs text-slate-600">
                                    <div class="flex-shrink-0 w-6 h-6 bg-purple-50 rounded-md flex items-center justify-center">
                                        <i class="ri-map-pin-line text-purple-600 text-xs"></i>
                                    </div>
                                    <span class="font-medium">{{ $job->location_type->getLabel() }}</span>
                                </div>
                                
                                @if($job->category)
                                <div class="flex items-center space-x-1.5 text-xs text-slate-600">
                                    <div class="flex-shrink-0 w-6 h-6 rounded-md flex items-center justify-center" style="background-color: {{ $job->category->color }}20;">
                                        <i class="ri-folder-line" style="color: {{ $job->category->color }};"></i>
                                    </div>
                                    <span class="font-medium">{{ $job->category->name }}</span>
                                </div>
                                @endif
                            </div>
                            
                            <p class="text-slate-600 mb-4">
                                {{ Str::limit($job->description, 150) }}
                            </p>
                            @php
                                $skills = is_array($job->required_skills) 
                                    ? $job->required_skills 
                                    : (is_string($job->required_skills) ? json_decode($job->required_skills, true) : []);
                            @endphp
                            @if($skills && is_array($skills) && count($skills) > 0)
                                <div class="mb-4">
                                    <h4 class="font-medium text-slate-800 mb-2 text-sm">Key Requirements:</h4>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach(array_slice($skills, 0, 3) as $skill)
                                            <span class="inline-flex items-center px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">
                                                {{ $skill }}
                                            </span>
                                        @endforeach
                                        @if(count($skills) > 3)
                                            <span class="inline-flex items-center px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-medium">
                                                +{{ count($skills) - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-1 text-slate-600">
                                    <div class="w-4 h-4 flex items-center justify-center">
                                        <i class="ri-user-line"></i>
                                    </div>
                                    <span class="text-sm">{{ $job->applications_count }} {{ Str::plural('applicant', $job->applications_count) }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($job->isAcceptingApplications())
                                        <a href="{{ route('jobs.show', $job) }}" 
                                           class="bg-indigo-600 text-white px-6 py-2 rounded-button text-sm hover:bg-indigo-700 transition-colors !rounded-button whitespace-nowrap">
                                            Apply Now
                                        </a>
                                    @else
                                        <span class="bg-gray-100 text-gray-600 px-6 py-2 rounded-button text-sm !rounded-button whitespace-nowrap">
                                            {{ $job->display_status }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
        @else
            <!-- No Jobs Found -->
            <x-empty-search-state
                icon="ri-briefcase-line"
                iconBg="from-indigo-100 to-purple-100"
                iconColor="text-indigo-400"
                :filterKeys="['search', 'category', 'experience_level', 'location_type', 'skills']"
                clearFiltersFunction="clearAllFilters"
                title="No Jobs Found"
                titleFiltered="No Jobs Match Your Filters"
                description="There are no job listings available at the moment. Check back later for new opportunities!"
                descriptionFiltered="No jobs match your current filters. Try adjusting your search criteria or clearing the filters to see more job opportunities."
            />
        @endif
        </div>
        
        <!-- Load More -->
        @if($jobs->hasPages())
        <div id="load-more-container" class="mt-8 flex justify-center">
            <button id="load-more-btn" class="bg-white border border-slate-200 text-slate-600 px-6 py-3 rounded-button hover:bg-slate-50 transition-colors !rounded-button whitespace-nowrap" data-next-page="{{ $jobs->nextPageUrl() }}">
                <span id="load-more-text">Load More Jobs</span>
                <div id="load-more-spinner" class="hidden">
                    <div class="inline-block w-4 h-4 border-2 border-slate-300 border-t-slate-600 rounded-full animate-spin"></div>
                </div>
            </button>
        </div>
        @endif
    </div>

    <!-- Right Sidebar -->
    <div class="w-full lg:w-80 lg:flex-shrink-0 min-w-0">
        <div class="space-y-6">
            @auth
                <!-- My Job Applications Widget -->
                <x-widgets.my-job-applications :applications="$userApplications" />
            @endauth
            
            <!-- Job Search Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Job Statistics</h3>
                @php
                    $totalJobs = \App\Models\JobListing::where('status', 'active')->count();
                    $totalCompanies = \App\Models\JobListing::where('status', 'active')->distinct('company_name')->count('company_name');
                    $remoteJobs = \App\Models\JobListing::where('status', 'active')->where('location_type', 'remote')->count();
                @endphp
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Total Active Jobs</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $totalJobs }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Companies Hiring</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $totalCompanies }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Remote Positions</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $remoteJobs }}</span>
                    </div>
                </div>
            </div>

            <!-- Categories Insights -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Job Categories</h3>
                @php
                    $categoryCounts = \App\Models\Category::where('is_active', true)
                        ->withCount(['jobListings' => function($query) {
                            $query->where('status', 'active');
                        }])
                        ->orderBy('job_listings_count', 'desc')
                        ->orderBy('name')
                        ->get();
                @endphp
                <div class="space-y-3">
                    @foreach($categoryCounts as $category)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 rounded-full" style="background-color: {{ $category->color }}"></div>
                                <span class="text-sm text-slate-700">{{ $category->name }}</span>
                            </div>
                            <span class="text-sm font-semibold text-slate-800">{{ $category->job_listings_count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function jobFilters() {
        return {
            selectedCategory: null,
            selectedExperienceLevel: null,
            selectedLocationType: null,
            selectedSalaryRange: null,
            selectedDatePosted: null,
            searchQuery: '',
            loading: false,
            
            // Category management
            categories: @json($categories->map(function($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'color' => $category->color
                ];
            })),
            visibleCategories: [],
            hiddenCategories: [],
            hasMoreCategories: false,
            
            init() {
                // Initialize from URL parameters
                const urlParams = new URLSearchParams(window.location.search);
                this.selectedCategory = urlParams.get('category') ? parseInt(urlParams.get('category')) : null;
                this.selectedExperienceLevel = urlParams.get('experience_level') || null;
                this.selectedLocationType = urlParams.get('location_type') || null;
                this.selectedSalaryRange = urlParams.get('salary_range') || null;
                this.selectedDatePosted = urlParams.get('date_posted') || null;
                this.searchQuery = urlParams.get('search') || '';
                
                // Initialize all categories as visible first
                this.visibleCategories = [...this.categories];
                this.hiddenCategories = [];
                this.hasMoreCategories = false;
                
                // Wait for DOM to render before calculating
                this.$nextTick(() => {
                    setTimeout(() => {
                        this.calculateVisibleCategories();
                    }, 50);
                });
                
                // Listen for window resize
                window.addEventListener('resize', () => {
                    this.handleResize();
                });
            },
            
            calculateVisibleCategories() {
                const container = this.$refs.categoryContainer;
                if (!container) {
                    return;
                }
                
                // Reset to show all categories temporarily for measurement
                this.visibleCategories = [...this.categories];
                this.hiddenCategories = [];
                this.hasMoreCategories = false;
                
                // Wait for DOM update
                this.$nextTick(() => {
                    const containerWidth = container.offsetWidth;
                    const horizontalPadding = 48; // p-6 = 24px each side = 48px total
                    const moreButtonWidth = 100; // Reserve space for "More" button
                    const spacingBuffer = 20; // Extra buffer for spacing
                    let availableWidth = containerWidth - horizontalPadding - moreButtonWidth - spacingBuffer;
                    
                    // Get all category buttons (exclude "All Jobs" button)
                    const buttons = Array.from(container.querySelectorAll('button')).slice(1);
                    let totalWidth = 0;
                    let visibleCount = 0;
                    
                    // Calculate cumulative width
                    for (let i = 0; i < buttons.length; i++) {
                        const buttonWidth = buttons[i].offsetWidth + 8; // 8px for space-x-2
                        
                        if (totalWidth + buttonWidth <= availableWidth) {
                            totalWidth += buttonWidth;
                            visibleCount++;
                        } else {
                            break;
                        }
                    }
                    
                    // If we can't fit all, ensure we have room for "More" button
                    if (visibleCount < this.categories.length) {
                        // Keep at least 2 visible
                        visibleCount = Math.max(2, visibleCount);
                        this.visibleCategories = this.categories.slice(0, visibleCount);
                        this.hiddenCategories = this.categories.slice(visibleCount);
                        this.hasMoreCategories = true;
                    } else {
                        this.visibleCategories = [...this.categories];
                        this.hiddenCategories = [];
                        this.hasMoreCategories = false;
                    }
                });
            },
            
            handleResize() {
                // Debounce resize events
                clearTimeout(this.resizeTimeout);
                this.resizeTimeout = setTimeout(() => {
                    this.calculateVisibleCategories();
                }, 100);
            },
            
            filterByCategory(categoryId) {
                console.log('🏷️ Category filter applied:', categoryId);
                this.selectedCategory = categoryId;
                this.applyFilters();
            },
            
            filterByExperienceLevel(level) {
                console.log('📊 Experience level filter applied:', level);
                this.selectedExperienceLevel = level === 'all' ? null : level;
                this.applyFilters();
            },
            
            filterByLocationType(type) {
                console.log('📍 Location type filter applied:', type);
                this.selectedLocationType = type === 'all' ? null : type;
                this.applyFilters();
            },
            
            filterBySalaryRange(range) {
                console.log('💰 Salary range filter applied:', range);
                this.selectedSalaryRange = range === 'all' ? null : range;
                this.applyFilters();
            },
            
            filterByDatePosted(date) {
                console.log('📅 Date posted filter applied:', date);
                this.selectedDatePosted = date === 'all' ? null : date;
                this.applyFilters();
            },
            
            searchJobs() {
                console.log('🔍 Search filter applied:', this.searchQuery);
                this.applyFilters();
            },
            
            applyFilters() {
                this.loading = true;
                
                const params = new URLSearchParams();
                if (this.selectedCategory) params.append('category', this.selectedCategory);
                if (this.selectedExperienceLevel) params.append('experience_level', this.selectedExperienceLevel);
                if (this.selectedLocationType) params.append('location_type', this.selectedLocationType);
                if (this.selectedSalaryRange) params.append('salary_range', this.selectedSalaryRange);
                if (this.selectedDatePosted) params.append('date_posted', this.selectedDatePosted);
                if (this.searchQuery) params.append('search', this.searchQuery);
                
                const url = `{{ route('jobs.index') }}?${params.toString()}`;
                
                console.log('🔄 Applying filters - Re-fetching data from database:', {
                    selectedCategory: this.selectedCategory,
                    selectedExperienceLevel: this.selectedExperienceLevel,
                    selectedLocationType: this.selectedLocationType,
                    selectedSalaryRange: this.selectedSalaryRange,
                    selectedDatePosted: this.selectedDatePosted,
                    searchQuery: this.searchQuery,
                    url: url
                });
                
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        // Update jobs container
                        const newJobsContainer = doc.querySelector('#jobs-container');
                        if (newJobsContainer) {
                            const jobCount = newJobsContainer.children.length;
                            console.log(`✅ Database returned ${jobCount} jobs matching filters`);
                            document.getElementById('jobs-container').innerHTML = newJobsContainer.innerHTML;
                        }
                        
                        // Update load more button
                        const newLoadMoreContainer = doc.querySelector('#load-more-container');
                        const currentLoadMoreContainer = document.querySelector('#load-more-container');
                        
                        if (newLoadMoreContainer && currentLoadMoreContainer) {
                            // Replace the entire load more container
                            currentLoadMoreContainer.outerHTML = newLoadMoreContainer.outerHTML;
                            
                            // Ensure the new button is properly enabled after DOM update
                            setTimeout(() => {
                                const newLoadMoreBtn = document.querySelector('#load-more-btn');
                                if (newLoadMoreBtn) {
                                    newLoadMoreBtn.disabled = false;
                                }
                            }, 10);
                        } else if (!newLoadMoreContainer && currentLoadMoreContainer) {
                            // Hide load more container if no more pages
                            currentLoadMoreContainer.style.display = 'none';
                        }
                        
                        // Update URL in browser
                        window.history.pushState({}, '', url);
                    })
                    .catch(error => {
                        console.error('Error filtering jobs:', error);
                        alert('Error filtering jobs. Please try again.');
                    })
                    .finally(() => {
                        this.loading = false;
                        console.log('🎯 Filter application completed - All jobs re-fetched from database');
                    });
            }
        }
    }

    // Load more functionality
    document.addEventListener("DOMContentLoaded", function () {
        // Use event delegation to handle dynamically added load more buttons
        document.addEventListener('click', function(e) {
            // Check if clicked element or its parent is the load more button
            let loadMoreBtn = e.target;
            if (loadMoreBtn.id !== 'load-more-btn') {
                loadMoreBtn = e.target.closest('#load-more-btn');
            }
            
            if (loadMoreBtn && loadMoreBtn.id === 'load-more-btn') {
                console.log('Load more button clicked');
                
                // Prevent multiple clicks
                if (loadMoreBtn.disabled) {
                    console.log('Button already disabled, ignoring click');
                    return;
                }
                
                const nextPageUrl = loadMoreBtn.getAttribute("data-next-page");
                console.log('Next page URL:', nextPageUrl);
                
                if (!nextPageUrl) {
                    console.log('No next page URL found');
                    return;
                }

                // Show loading state immediately
                loadMoreBtn.disabled = true;
                const loadMoreText = loadMoreBtn.querySelector("#load-more-text");
                const loadMoreSpinner = loadMoreBtn.querySelector("#load-more-spinner");
                if (loadMoreText) loadMoreText.classList.add("hidden");
                if (loadMoreSpinner) loadMoreSpinner.classList.remove("hidden");
                
                console.log('Loading state activated');

                // Fetch next page with current filters
                const currentParams = new URLSearchParams(window.location.search);
                const nextPageUrlWithFilters = nextPageUrl + (nextPageUrl.includes('?') ? '&' : '?') + currentParams.toString();
                
                console.log('Fetching URL:', nextPageUrlWithFilters);
                
                fetch(nextPageUrlWithFilters)
                    .then(response => {
                        console.log('Load more response status:', response.status);
                        return response.text();
                    })
                    .then(html => {
                        console.log('Load more HTML length:', html.length);
                        
                        // Parse the response to extract jobs
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newJobs = doc.querySelectorAll('#jobs-container > div');
                        
                        console.log('Found', newJobs.length, 'new jobs to append');
                        
                        // Append new jobs to container
                        const container = document.getElementById("jobs-container");
                        newJobs.forEach(job => {
                            container.appendChild(job);
                        });

                        // Update next page URL
                        const newNextPage = doc.querySelector('#load-more-btn')?.getAttribute("data-next-page");
                        console.log('New next page URL:', newNextPage);
                        
                        if (newNextPage) {
                            loadMoreBtn.setAttribute("data-next-page", newNextPage);
                            console.log('Updated next page URL');
                        } else {
                            // No more pages, hide the button
                            loadMoreBtn.parentElement.style.display = 'none';
                            console.log('No more pages, hiding load more button');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading more jobs:', error);
                        alert('Error loading more jobs. Please try again.');
                    })
                    .finally(() => {
                        // Hide loading state
                        loadMoreBtn.disabled = false;
                        if (loadMoreText) loadMoreText.classList.remove("hidden");
                        if (loadMoreSpinner) loadMoreSpinner.classList.add("hidden");
                        console.log('Loading state deactivated');
                    });
            }
        });
    });

    function clearAllFilters() {
        console.log('🗑️ Clearing all filters');
        // Get the Alpine.js component instance
        const jobFiltersComponent = Alpine.$data(document.querySelector('[x-data="jobFilters()"]'));
        if (jobFiltersComponent) {
            jobFiltersComponent.selectedCategory = null;
            jobFiltersComponent.selectedExperienceLevel = null;
            jobFiltersComponent.selectedLocationType = null;
            jobFiltersComponent.selectedSalaryRange = null;
            jobFiltersComponent.selectedDatePosted = null;
            jobFiltersComponent.searchQuery = '';
            
            // Clear search input
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.value = '';
            }
            
            jobFiltersComponent.applyFilters();
        }
    }

</script>
@endpush