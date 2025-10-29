<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Hackathon;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = Auth::user();

            // Check if user has admin role or is marked as admin
            // For now, we'll check if user has 'role' field or if they're the first user
            if (! $this->userCanAccessAdmin($user)) {
                abort(403, 'Access denied. Admin privileges required.');
            }

            return $next($request);
        });
    }

    /**
     * Check if user can access admin panel.
     */
    private function userCanAccessAdmin($user): bool
    {
        // Check if user has admin role (if role system is implemented)
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole(['admin', 'super_admin']);
        }

        // Check if user has a role attribute
        if (isset($user->role) && in_array($user->role, ['admin', 'super_admin'])) {
            return true;
        }

        // Fallback: Allow first user or users with specific email domains
        if ($user->id === 1) {
            return true;
        }

        // You could also check email domain for admin access
        $adminDomains = ['admin@peopleofdata.com', 'super@peopleofdata.com'];
        if (in_array($user->email, $adminDomains)) {
            return true;
        }

        return false;
    }

    /**
     * Show the admin dashboard.
     */
    public function dashboard()
    {
        $stats = $this->getDashboardStats();
        $recentActivity = $this->getRecentActivity();
        $chartData = $this->getChartData();

        return view('admin.dashboard', compact('stats', 'recentActivity', 'chartData'));
    }

    /**
     * Get dashboard statistics.
     */
    private function getDashboardStats(): array
    {
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $totalPosts = Post::count();
        $publishedPosts = Post::where('is_published', true)->count();

        $totalJobs = JobListing::count();
        $activeJobs = JobListing::where('status', 'active')->count();

        $totalEvents = Event::count();
        $upcomingEvents = Event::where('start_date', '>', now())->count();

        $totalHackathons = Hackathon::count();
        $activeHackathons = Hackathon::where('is_active', true)->count();

        $pendingApplications = JobApplication::where('status', 'pending')->count();
        $eventRegistrations = EventRegistration::where('status', 'confirmed')->count();

        return [
            'users' => [
                'total' => $totalUsers,
                'new_this_month' => $newUsersThisMonth,
                'growth_rate' => $totalUsers > 0 ? round(($newUsersThisMonth / $totalUsers) * 100, 1) : 0,
            ],
            'posts' => [
                'total' => $totalPosts,
                'published' => $publishedPosts,
                'publish_rate' => $totalPosts > 0 ? round(($publishedPosts / $totalPosts) * 100, 1) : 0,
            ],
            'jobs' => [
                'total' => $totalJobs,
                'active' => $activeJobs,
                'pending_applications' => $pendingApplications,
            ],
            'events' => [
                'total' => $totalEvents,
                'upcoming' => $upcomingEvents,
                'registrations' => $eventRegistrations,
            ],
            'hackathons' => [
                'total' => $totalHackathons,
                'active' => $activeHackathons,
            ],
        ];
    }

    /**
     * Get recent platform activity.
     */
    private function getRecentActivity(): array
    {
        $recentUsers = User::latest()->limit(5)->get(['id', 'name', 'email', 'created_at']);
        $recentPosts = Post::with('user')->latest()->limit(5)->get(['id', 'content', 'user_id', 'created_at']);
        $recentJobs = JobListing::with('poster')->latest()->limit(5)->get(['id', 'title', 'company_name', 'posted_by', 'created_at']);
        $recentEvents = Event::with('creator')->latest()->limit(5)->get(['id', 'title', 'start_date', 'created_by', 'created_at']);

        return [
            'users' => $recentUsers,
            'posts' => $recentPosts,
            'jobs' => $recentJobs,
            'events' => $recentEvents,
        ];
    }

    /**
     * Get chart data for dashboard.
     */
    private function getChartData(): array
    {
        // User registration over last 30 days
        $userRegistrations = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = User::whereDate('created_at', $date->toDateString())->count();
            $userRegistrations[] = [
                'date' => $date->format('M j'),
                'count' => $count,
            ];
        }

        // Posts creation over last 7 days
        $postsData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Post::whereDate('created_at', $date->toDateString())->count();
            $postsData[] = [
                'date' => $date->format('M j'),
                'count' => $count,
            ];
        }

        // Job applications over last 14 days
        $jobApplications = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = JobApplication::whereDate('created_at', $date->toDateString())->count();
            $jobApplications[] = [
                'date' => $date->format('M j'),
                'count' => $count,
            ];
        }

        return [
            'user_registrations' => $userRegistrations,
            'posts' => $postsData,
            'job_applications' => $jobApplications,
        ];
    }

    // Users management moved to Filament admin panel

    /**
     * Show content management page.
     */
    public function content(Request $request)
    {
        $posts = Post::with('user')
            ->when($request->filled('status'), function ($query) use ($request) {
                if ($request->status === 'published') {
                    $query->where('is_published', true);
                } elseif ($request->status === 'draft') {
                    $query->where('is_published', false);
                } elseif ($request->status === 'featured') {
                    $query->where('is_featured', true);
                }
            })
            ->latest()
            ->paginate(15);

        return view('admin.content', compact('posts'));
    }

    /**
     * Show analytics page.
     */
    public function analytics()
    {
        $stats = $this->getDashboardStats();
        $chartData = $this->getChartData();

        // Additional analytics data
        $topCategories = JobListing::join('categories', 'job_listings.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, COUNT(*) as count')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        $userGrowth = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.analytics', compact('stats', 'chartData', 'topCategories', 'userGrowth'));
    }

    /**
     * Show settings page.
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Update platform settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'allow_registrations' => 'boolean',
            'require_email_verification' => 'boolean',
            'auto_approve_posts' => 'boolean',
            'max_file_upload_size' => 'required|integer|min:1|max:100',
        ]);

        // In a real implementation, you'd store these in a settings table or config
        // For now, this is just a placeholder
        session()->flash('success', 'Settings updated successfully!');

        return redirect()->route('admin.settings');
    }
}
