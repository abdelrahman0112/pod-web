# Development Guidelines - People Of Data Platform

## 1. Code Standards & Best Practices

### 1.1 PHP/Laravel Standards

**PSR-12 Compliance**
- Follow PSR-12 coding standard for all PHP code
- Use PHP CS Fixer for automatic code formatting
- Configure IDE to use PSR-12 formatting rules
- Run `composer run-script format` before committing

**Laravel Best Practices**
```php
// Good: Use Eloquent relationships
class User extends Model
{
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}

// Good: Use Form Requests for validation
class CreateJobRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100',
        ];
    }
}

// Good: Use Resource Controllers
class JobController extends Controller
{
    public function index()
    {
        return JobResource::collection(
            Job::with('company', 'category')->paginate(15)
        );
    }
}
```

**Naming Conventions**
- Controllers: PascalCase with "Controller" suffix (e.g., `JobController`)
- Models: PascalCase singular (e.g., `Job`, `User`)
- Database tables: snake_case plural (e.g., `jobs`, `user_profiles`)
- Variables: camelCase (e.g., `$jobTitle`, `$userEmail`)
- Constants: UPPER_SNAKE_CASE (e.g., `MAX_FILE_SIZE`)
- Methods: camelCase (e.g., `createJob()`, `getUserProfile()`)

### 1.2 Frontend Standards

**JavaScript/Alpine.js**
```javascript
// Good: Use Alpine.js for simple interactions
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open" x-transition>Content</div>
</div>

// Good: Use Livewire for complex interactions
class JobSearch extends Component
{
    public $search = '';
    public $filters = [];
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
}
```

**CSS/Tailwind Standards**
```html
<!-- Good: Use Tailwind utility classes -->
<div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
    <h3 class="text-lg font-semibold text-gray-900 mb-2">Job Title</h3>
    <p class="text-gray-600 text-sm">Company Name</p>
</div>

<!-- Good: Use component classes for repeated patterns -->
<button class="btn btn-primary">
    Apply Now
</button>
```

### 1.3 Database Standards

**Migration Best Practices**
```php
// Good: Descriptive migration names
// 2024_01_15_create_job_applications_table.php
Schema::create('job_applications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('job_id')->constrained()->onDelete('cascade');
    $table->text('cover_letter');
    $table->string('status')->default('pending');
    $table->timestamps();
    
    $table->index(['user_id', 'job_id']);
    $table->index('status');
});
```

**Model Relationships**
```php
// Good: Define clear relationships
class Job extends Model
{
    protected $fillable = [
        'title', 'description', 'company_id', 'category_id'
    ];
    
    protected $casts = [
        'requirements' => 'array',
        'benefits' => 'array',
        'deadline' => 'datetime',
    ];
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('deadline', '>', now());
    }
}
```

## 2. Git Workflow & Version Control

### 2.1 GitFlow Branching Strategy

**Branch Types**
- `main`: Production-ready code
- `develop`: Integration branch for features
- `feature/*`: New features (e.g., `feature/job-application-system`)
- `hotfix/*`: Critical bug fixes (e.g., `hotfix/login-security-fix`)
- `release/*`: Release preparation (e.g., `release/v1.2.0`)

**Branch Naming Conventions**
```bash
# Feature branches
feature/user-authentication
feature/job-posting-system
feature/chat-integration

# Bug fix branches
bugfix/profile-image-upload
bugfix/notification-delivery

# Hotfix branches
hotfix/security-vulnerability
hotfix/payment-processing
```

### 2.2 Commit Message Standards

**Conventional Commits Format**
```
type(scope): description

[optional body]

[optional footer]
```

**Examples**
```bash
feat(auth): add OAuth login with Google and LinkedIn
fix(jobs): resolve pagination issue in job listings
docs(api): update authentication endpoint documentation
refactor(user): optimize profile completion wizard
test(events): add unit tests for event registration
```

**Commit Types**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

### 2.3 Pull Request Guidelines

**PR Template**
```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Unit tests pass
- [ ] Integration tests pass
- [ ] Manual testing completed

## Checklist
- [ ] Code follows style guidelines
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] No merge conflicts
```

**Review Requirements**
- At least 2 reviewers for main branch merges
- All CI/CD checks must pass
- No merge conflicts
- Documentation updated if needed

## 3. Testing Standards

### 3.1 Unit Testing

**PHPUnit Best Practices**
```php
class JobServiceTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_can_create_job_with_valid_data()
    {
        // Arrange
        $user = User::factory()->create();
        $jobData = [
            'title' => 'Senior Data Scientist',
            'description' => 'We are looking for...',
            'company_id' => Company::factory()->create()->id,
        ];
        
        // Act
        $job = app(JobService::class)->create($jobData, $user);
        
        // Assert
        $this->assertInstanceOf(Job::class, $job);
        $this->assertEquals($jobData['title'], $job->title);
        $this->assertDatabaseHas('jobs', ['title' => $jobData['title']]);
    }
    
    public function test_cannot_create_job_without_required_fields()
    {
        $this->expectException(ValidationException::class);
        
        app(JobService::class)->create([], User::factory()->create());
    }
}
```

**Test Coverage Requirements**
- Minimum 80% code coverage
- All critical business logic must be tested
- Test both happy path and edge cases
- Use factories for test data generation

### 3.2 Feature Testing

**Livewire Component Testing**
```php
class JobSearchTest extends TestCase
{
    public function test_can_search_jobs_by_title()
    {
        Job::factory()->create(['title' => 'Data Scientist']);
        Job::factory()->create(['title' => 'Web Developer']);
        
        Livewire::test(JobSearch::class)
            ->set('search', 'Data')
            ->assertSee('Data Scientist')
            ->assertDontSee('Web Developer');
    }
}
```

### 3.3 Browser Testing

**Laravel Dusk for E2E Testing**
```php
class JobApplicationTest extends DuskTestCase
{
    public function test_user_can_apply_for_job()
    {
        $user = User::factory()->create();
        $job = Job::factory()->create();
        
        $this->browse(function (Browser $browser) use ($user, $job) {
            $browser->loginAs($user)
                   ->visit("/jobs/{$job->id}")
                   ->click('@apply-button')
                   ->type('cover_letter', 'I am interested in this position...')
                   ->click('@submit-application')
                   ->assertSee('Application submitted successfully');
        });
    }
}
```

## 4. Security Guidelines

### 4.1 Authentication & Authorization

**Laravel Sanctum Implementation**
```php
// Good: Use middleware for route protection
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('jobs', JobController::class);
});

// Good: Use policies for authorization
class JobPolicy
{
    public function update(User $user, Job $job)
    {
        return $user->id === $job->user_id || $user->hasRole('admin');
    }
}
```

**Input Validation**
```php
// Good: Always validate user input
class CreateJobRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:100|max:5000',
            'salary_min' => 'nullable|integer|min:0',
            'salary_max' => 'nullable|integer|gt:salary_min',
            'deadline' => 'required|date|after:today',
        ];
    }
    
    public function messages()
    {
        return [
            'salary_max.gt' => 'Maximum salary must be greater than minimum salary.',
        ];
    }
}
```

### 4.2 Data Protection

**Sensitive Data Handling**
```php
// Good: Use encryption for sensitive data
class User extends Model
{
    protected $hidden = ['password', 'remember_token'];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = encrypt($value);
    }
    
    public function getPhoneAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }
}
```

**File Upload Security**
```php
// Good: Validate file uploads
class ProfileImageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'avatar' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048', // 2MB
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'
            ]
        ];
    }
}
```

## 5. Performance Optimization

### 5.1 Database Optimization

**Query Optimization**
```php
// Good: Use eager loading to prevent N+1 queries
class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::with(['company', 'category', 'applications'])
                  ->where('status', 'active')
                  ->orderBy('created_at', 'desc')
                  ->paginate(15);
                  
        return view('jobs.index', compact('jobs'));
    }
}

// Good: Use database indexes
Schema::table('jobs', function (Blueprint $table) {
    $table->index(['status', 'created_at']);
    $table->index('company_id');
    $table->index('category_id');
});
```

**Caching Strategy**
```php
// Good: Cache expensive queries
class JobService
{
    public function getFeaturedJobs()
    {
        return Cache::remember('featured_jobs', 3600, function () {
            return Job::with('company')
                     ->where('featured', true)
                     ->where('status', 'active')
                     ->limit(6)
                     ->get();
        });
    }
}
```

### 5.2 Frontend Optimization

**Asset Optimization**
```javascript
// vite.config.js
export default defineConfig({
    plugins: [laravel(['resources/css/app.css', 'resources/js/app.js'])],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs'],
                    utils: ['axios']
                }
            }
        }
    }
});
```

**Image Optimization**
```php
// Good: Optimize images on upload
class ImageService
{
    public function processProfileImage($file)
    {
        $image = Image::make($file)
                     ->fit(300, 300)
                     ->encode('jpg', 85);
                     
        $filename = Str::uuid() . '.jpg';
        Storage::put("avatars/{$filename}", $image);
        
        return $filename;
    }
}
```

## 6. API Development

### 6.1 RESTful API Design

**Resource Controllers**
```php
// Good: Follow REST conventions
Route::apiResource('jobs', JobApiController::class);

class JobApiController extends Controller
{
    public function index(Request $request)
    {
        $jobs = Job::query()
                  ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
                  ->when($request->category, fn($q) => $q->where('category_id', $request->category))
                  ->paginate(15);
                  
        return JobResource::collection($jobs);
    }
    
    public function store(CreateJobRequest $request)
    {
        $job = Job::create($request->validated());
        
        return new JobResource($job);
    }
}
```

**API Resources**
```php
class JobResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'applications_count' => $this->when(
                $request->user()?->can('view', $this->resource),
                $this->applications_count
            ),
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
```

### 6.2 API Authentication

**Token-based Authentication**
```php
// Good: Use Sanctum for API authentication
class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        
        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;
        
        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
        ]);
    }
}
```

## 7. Error Handling & Logging

### 7.1 Exception Handling

**Custom Exception Classes**
```php
class JobNotFoundException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Job not found',
                'error_code' => 'JOB_NOT_FOUND'
            ], 404);
        }
        
        return response()->view('errors.404', [], 404);
    }
}
```

**Global Exception Handler**
```php
class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException && $request->expectsJson()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $exception->errors()
            ], 422);
        }
        
        return parent::render($request, $exception);
    }
}
```

### 7.2 Logging Standards

**Structured Logging**
```php
class JobService
{
    public function createJob(array $data, User $user)
    {
        Log::info('Job creation started', [
            'user_id' => $user->id,
            'job_title' => $data['title']
        ]);
        
        try {
            $job = Job::create($data);
            
            Log::info('Job created successfully', [
                'job_id' => $job->id,
                'user_id' => $user->id
            ]);
            
            return $job;
        } catch (Exception $e) {
            Log::error('Job creation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            
            throw $e;
        }
    }
}
```

## 8. Documentation Standards

### 8.1 Code Documentation

**PHPDoc Standards**
```php
/**
 * Create a new job posting
 * 
 * @param array $data Job data including title, description, requirements
 * @param User $user The user creating the job
 * @return Job The created job instance
 * @throws ValidationException When job data is invalid
 * @throws AuthorizationException When user cannot create jobs
 */
public function createJob(array $data, User $user): Job
{
    // Implementation
}
```

### 8.2 API Documentation

**OpenAPI/Swagger Documentation**
```php
/**
 * @OA\Post(
 *     path="/api/jobs",
 *     summary="Create a new job",
 *     tags={"Jobs"},
 *     security={{"sanctum": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "description"},
 *             @OA\Property(property="title", type="string", maxLength=255),
 *             @OA\Property(property="description", type="string", minLength=100)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Job created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Job")
 *     )
 * )
 */
public function store(CreateJobRequest $request)
{
    // Implementation
}
```

---

**Last Updated**: January 2025
**Version**: 1.0
**Maintained By**: Development Team