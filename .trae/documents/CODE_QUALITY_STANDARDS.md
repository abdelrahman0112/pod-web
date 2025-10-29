# Code Quality Standards - People Of Data Platform

## 1. Code Quality Overview

### 1.1 Quality Principles

**Core Principles**

* **Readability**: Code should be self-documenting and easy to understand

* **Maintainability**: Code should be easy to modify and extend

* **Reliability**: Code should be robust and handle edge cases gracefully

* **Performance**: Code should be efficient and scalable

* **Security**: Code should follow security best practices

* **Testability**: Code should be designed for easy testing

**Quality Metrics**

* Code coverage: Minimum 80% for critical features

* Cyclomatic complexity: Maximum 10 per method

* Code duplication: Maximum 3% across the codebase

* Technical debt ratio: Maximum 5%

* Security vulnerabilities: Zero high/critical severity issues

### 1.2 Quality Gates

**Pre-commit Checks**

```bash
#!/bin/bash
# .git/hooks/pre-commit

set -e

echo "Running pre-commit checks..."

# PHP CS Fixer
echo "Checking PHP code style..."
./vendor/bin/php-cs-fixer fix --dry-run --diff --verbose

# PHPStan
echo "Running static analysis..."
./vendor/bin/phpstan analyse --memory-limit=2G

# PHPUnit
echo "Running tests..."
./vendor/bin/phpunit --coverage-text --coverage-clover=coverage.xml

# ESLint
echo "Checking JavaScript code style..."
npm run lint

# Prettier
echo "Checking code formatting..."
npm run format:check

echo "All pre-commit checks passed!"
```

**CI/CD Quality Checks**

```yaml
# .github/workflows/quality.yml
name: Code Quality

on:
  pull_request:
    branches: [ main, develop ]
  push:
    branches: [ main, develop ]

jobs:
  quality:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        extensions: mbstring, xml, ctype, iconv, intl, pdo_mysql, dom, filter, gd, redis
        tools: composer, phpstan, php-cs-fixer
    
    - name: Install Dependencies
      run: composer install --no-progress --prefer-dist --optimize-autoloader
    
    - name: Code Style Check
      run: ./vendor/bin/php-cs-fixer fix --dry-run --diff --verbose
    
    - name: Static Analysis
      run: ./vendor/bin/phpstan analyse --memory-limit=2G --error-format=github
    
    - name: Security Check
      run: composer audit
    
    - name: Run Tests with Coverage
      run: ./vendor/bin/phpunit --coverage-clover=coverage.xml
    
    - name: Upload Coverage to Codecov
      uses: codecov/codecov-action@v3
      with:
        file: ./coverage.xml
        flags: unittests
        name: codecov-umbrella
    
    - name: SonarCloud Scan
      uses: SonarSource/sonarcloud-github-action@master
      env:
        GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}
        SONAR_TOKEN: ${{ secrets.SONAR_TOKEN }}
```

## 2. PHP Code Standards

### 2.1 PSR-12 Compliance

**PHP CS Fixer Configuration**

```php
// .php-cs-fixer.php
<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        '@PHP81Migration' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => [
            'default' => 'single_space',
            'operators' => ['=>' => null]
        ],
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => [
            'statements' => ['return']
        ],
        'cast_spaces' => true,
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
                'property' => 'one',
            ]
        ],
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => true,
        'function_typehint_space' => true,
        'include' => true,
        'increment_style' => ['style' => 'post'],
        'lowercase_cast' => true,
        'magic_constant_casing' => true,
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline'
        ],
        'native_function_casing' => true,
        'new_with_braces' => true,
        'no_blank_lines_after_class_opening' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'square_brace_block',
                'throw',
                'use'
            ]
        ],
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_mixed_echo_print' => ['use' => 'echo'],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_short_bool_cast' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_spaces_around_offset' => true,
        'no_trailing_comma_in_list_call' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'no_unneeded_control_parentheses' => true,
        'no_unused_imports' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'normalize_index_brace' => true,
        'object_operator_without_whitespace' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'phpdoc_indent' => true,
        'phpdoc_inline_tag_normalizer' => true,
        'phpdoc_no_access' => true,
        'phpdoc_no_package' => true,
        'phpdoc_no_useless_inheritdoc' => true,
        'phpdoc_scalar' => true,
        'phpdoc_single_line_var_spacing' => true,
        'phpdoc_summary' => true,
        'phpdoc_to_comment' => true,
        'phpdoc_trim' => true,
        'phpdoc_types' => true,
        'phpdoc_var_without_name' => true,
        'return_type_declaration' => true,
        'semicolon_after_instruction' => true,
        'short_scalar_cast' => true,
        'single_blank_line_before_namespace' => true,
        'single_class_element_per_statement' => true,
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'single_quote' => true,
        'space_after_semicolon' => ['remove_in_empty_for_expressions' => true],
        'standardize_not_equals' => true,
        'ternary_operator_spaces' => true,
        'trailing_comma_in_multiline' => true,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'whitespace_after_comma_in_array' => true,
    ])
    ->setFinder($finder);
```

### 2.2 Static Analysis Configuration

**PHPStan Configuration**

```neon
# phpstan.neon
parameters:
    level: 8
    paths:
        - app
        - config
        - database
        - routes
        - tests
    
    excludePaths:
        - app/Console/Kernel.php
        - app/Exceptions/Handler.php
        - app/Http/Kernel.php
        - bootstrap
        - storage
        - vendor
    
    ignoreErrors:
        - '#Call to an undefined method Illuminate\\Database\\Eloquent\\Builder#'
        - '#Call to an undefined method Illuminate\\Database\\Query\\Builder#'
    
    checkMissingIterableValueType: false
    checkGenericClassInNonGenericObjectType: false
    
    symfony:
        container_xml_path: bootstrap/cache/container.xml
    
    laravel:
        providers:
            - NunoMaduro\Larastan\LarastanServiceProvider
    
    rules:
        - NunoMaduro\Larastan\Rules\NoModelMakeRule
        - NunoMaduro\Larastan\Rules\NoUnnecessaryCollectionCallRule
        - NunoMaduro\Larastan\Rules\ModelPropertyRule
        - NunoMaduro\Larastan\Rules\NoUselessValueFunctionCallRule

includes:
    - vendor/nunomaduro/larastan/extension.neon
    - vendor/phpstan/phpstan-deprecation-rules/rules.neon
    - vendor/phpstan/phpstan-strict-rules/rules.neon
```

### 2.3 Documentation Standards

**PHPDoc Standards**

````php
<?php

namespace App\Services;

use App\Models\User;
use App\Models\Job;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Job recommendation service for matching users with relevant job opportunities.
 * 
 * This service uses machine learning algorithms and user preferences to provide
 * personalized job recommendations based on skills, experience, and location.
 * 
 * @package App\Services
 * @author People Of Data Team
 * @version 1.0.0
 * @since 2024-01-01
 */
class JobRecommendationService
{
    /**
     * The maximum number of recommendations to return per request.
     */
    private const MAX_RECOMMENDATIONS = 50;
    
    /**
     * Default recommendation algorithm weights.
     */
    private const DEFAULT_WEIGHTS = [
        'skills_match' => 0.4,
        'experience_level' => 0.3,
        'location_preference' => 0.2,
        'salary_range' => 0.1,
    ];
    
    /**
     * Create a new job recommendation service instance.
     * 
     * @param UserPreferenceService $preferenceService Service for managing user preferences
     * @param SkillMatchingService $skillService Service for skill matching algorithms
     */
    public function __construct(
        private UserPreferenceService $preferenceService,
        private SkillMatchingService $skillService
    ) {
    }
    
    /**
     * Get personalized job recommendations for a user.
     * 
     * This method analyzes the user's profile, skills, and preferences to generate
     * a ranked list of job recommendations using machine learning algorithms.
     * 
     * @param User $user The user to generate recommendations for
     * @param int $limit Maximum number of recommendations to return (default: 20)
     * @param array<string, float> $weights Custom algorithm weights (optional)
     * 
     * @return Collection<int, Job> Collection of recommended jobs ordered by relevance score
     * 
     * @throws \InvalidArgumentException When limit is less than 1 or greater than MAX_RECOMMENDATIONS
     * @throws \RuntimeException When recommendation algorithm fails
     * 
     * @example
     * ```php
     * $recommendations = $service->getRecommendations($user, 10);
     * foreach ($recommendations as $job) {
     *     echo $job->title . ' - Score: ' . $job->recommendation_score;
     * }
     * ```
     */
    public function getRecommendations(
        User $user, 
        int $limit = 20, 
        array $weights = []
    ): Collection {
        if ($limit < 1 || $limit > self::MAX_RECOMMENDATIONS) {
            throw new \InvalidArgumentException(
                sprintf('Limit must be between 1 and %d', self::MAX_RECOMMENDATIONS)
            );
        }
        
        try {
            $weights = array_merge(self::DEFAULT_WEIGHTS, $weights);
            
            // Implementation details...
            
            return $recommendations;
        } catch (\Exception $e) {
            throw new \RuntimeException(
                'Failed to generate job recommendations: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }
    
    /**
     * Calculate the relevance score for a job-user pair.
     * 
     * @param Job $job The job to score
     * @param User $user The user to score against
     * @param array<string, float> $weights Algorithm weights
     * 
     * @return float Relevance score between 0.0 and 1.0
     */
    private function calculateRelevanceScore(Job $job, User $user, array $weights): float
    {
        // Implementation details...
        
        return $score;
    }
}
````

## 3. Laravel Best Practices

### 3.1 Model Standards

**Eloquent Model Best Practices**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

/**
 * Job model representing job postings in the system.
 * 
 * @property int $id
 * @property int $user_id
 * @property int $company_id
 * @property int $category_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property array $requirements
 * @property array $responsibilities
 * @property array $benefits
 * @property array $skills_required
 * @property string $experience_level
 * @property string $employment_type
 * @property string $location_type
 * @property string|null $location
 * @property float|null $salary_min
 * @property float|null $salary_max
 * @property string $salary_currency
 * @property bool $is_salary_negotiable
 * @property Carbon $deadline
 * @property string $status
 * @property bool $is_featured
 * @property Carbon|null $featured_until
 * @property int $views_count
 * @property int $applications_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * 
 * @property-read User $user
 * @property-read Company $company
 * @property-read JobCategory $category
 * @property-read Collection<JobApplication> $applications
 * 
 * @method static Builder active()
 * @method static Builder featured()
 * @method static Builder byCategory(int $categoryId)
 * @method static Builder byLocation(string $location)
 * @method static Builder bySalaryRange(float $min, float $max)
 */
class Job extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     * 
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'category_id',
        'title',
        'slug',
        'description',
        'requirements',
        'responsibilities',
        'benefits',
        'skills_required',
        'experience_level',
        'employment_type',
        'location_type',
        'location',
        'salary_min',
        'salary_max',
        'salary_currency',
        'is_salary_negotiable',
        'deadline',
        'status',
        'is_featured',
        'featured_until',
    ];
    
    /**
     * The attributes that should be cast.
     * 
     * @var array<string, string>
     */
    protected $casts = [
        'requirements' => 'array',
        'responsibilities' => 'array',
        'benefits' => 'array',
        'skills_required' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'is_salary_negotiable' => 'boolean',
        'deadline' => 'datetime',
        'is_featured' => 'boolean',
        'featured_until' => 'datetime',
        'views_count' => 'integer',
        'applications_count' => 'integer',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     * 
     * @var array<string>
     */
    protected $hidden = [
        'deleted_at',
    ];
    
    /**
     * The accessors to append to the model's array form.
     * 
     * @var array<string>
     */
    protected $appends = [
        'is_active',
        'is_expired',
        'salary_range_formatted',
        'time_remaining',
    ];
    
    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();
        
        static::creating(function (Job $job) {
            if (empty($job->slug)) {
                $job->slug = str($job->title)->slug();
            }
        });
        
        static::updating(function (Job $job) {
            if ($job->isDirty('title') && empty($job->slug)) {
                $job->slug = str($job->title)->slug();
            }
        });
    }
    
    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    
    /**
     * Determine if the job is currently active.
     */
    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === 'active' && $this->deadline->isFuture()
        );
    }
    
    /**
     * Determine if the job has expired.
     */
    protected function isExpired(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->deadline->isPast()
        );
    }
    
    /**
     * Get the formatted salary range.
     */
    protected function salaryRangeFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->salary_min && !$this->salary_max) {
                    return 'Salary not specified';
                }
                
                if ($this->is_salary_negotiable) {
                    return 'Negotiable';
                }
                
                $min = $this->salary_min ? number_format($this->salary_min) : null;
                $max = $this->salary_max ? number_format($this->salary_max) : null;
                
                if ($min && $max) {
                    return "{$min} - {$max} {$this->salary_currency}";
                }
                
                return ($min ?: $max) . " {$this->salary_currency}";
            }
        );
    }
    
    /**
     * Get the time remaining until deadline.
     */
    protected function timeRemaining(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->deadline->diffForHumans()
        );
    }
    
    /**
     * Get the user who posted this job.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the company this job belongs to.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    
    /**
     * Get the category this job belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }
    
    /**
     * Get the applications for this job.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
    
    /**
     * Scope a query to only include active jobs.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', 'active')
              ->where('deadline', '>', now());
    }
    
    /**
     * Scope a query to only include featured jobs.
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true)
              ->where(function ($q) {
                  $q->whereNull('featured_until')
                    ->orWhere('featured_until', '>', now());
              });
    }
    
    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory(Builder $query, int $categoryId): void
    {
        $query->where('category_id', $categoryId);
    }
    
    /**
     * Scope a query to filter by location.
     */
    public function scopeByLocation(Builder $query, string $location): void
    {
        $query->where('location', 'like', "%{$location}%")
              ->orWhere('location_type', 'remote');
    }
    
    /**
     * Scope a query to filter by salary range.
     */
    public function scopeBySalaryRange(Builder $query, float $min, float $max): void
    {
        $query->where(function ($q) use ($min, $max) {
            $q->whereBetween('salary_min', [$min, $max])
              ->orWhereBetween('salary_max', [$min, $max])
              ->orWhere(function ($subQ) use ($min, $max) {
                  $subQ->where('salary_min', '<=', $min)
                       ->where('salary_max', '>=', $max);
              });
        });
    }
    
    /**
     * Increment the views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
    
    /**
     * Increment the applications count.
     */
    public function incrementApplications(): void
    {
        $this->increment('applications_count');
    }
}
```

### 3.2 Service Layer Standards

**Service Class Best Practices**

```php
<?php

namespace App\Services;

use App\Models\Job;
use App\Models\User;
use App\Models\JobApplication;
use App\Events\JobApplicationSubmitted;
use App\Exceptions\JobApplicationException;
use App\Mail\JobApplicationConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

/**
 * Service for handling job application operations.
 * 
 * This service encapsulates all business logic related to job applications,
 * including validation, file handling, notifications, and data persistence.
 */
class JobApplicationService
{
    /**
     * Submit a job application.
     * 
     * @param User $user The user submitting the application
     * @param Job $job The job being applied to
     * @param array $data Application data
     * @param UploadedFile|null $resume Resume file (optional)
     * 
     * @return JobApplication The created application
     * 
     * @throws JobApplicationException When application cannot be submitted
     */
    public function submitApplication(
        User $user,
        Job $job,
        array $data,
        ?UploadedFile $resume = null
    ): JobApplication {
        // Validate application eligibility
        $this->validateApplicationEligibility($user, $job);
        
        DB::beginTransaction();
        
        try {
            // Handle resume upload
            $resumePath = null;
            if ($resume) {
                $resumePath = $this->handleResumeUpload($resume, $user->id);
            }
            
            // Create application
            $application = JobApplication::create([
                'user_id' => $user->id,
                'job_id' => $job->id,
                'cover_letter' => $data['cover_letter'] ?? null,
                'resume_path' => $resumePath,
                'expected_salary' => $data['expected_salary'] ?? null,
                'available_from' => $data['available_from'] ?? null,
                'additional_info' => $data['additional_info'] ?? null,
                'status' => 'pending',
            ]);
            
            // Update job applications count
            $job->incrementApplications();
            
            // Send confirmation email
            Mail::to($user)->queue(new JobApplicationConfirmation($application));
            
            // Dispatch event
            event(new JobApplicationSubmitted($application));
            
            DB::commit();
            
            return $application;
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Clean up uploaded file if transaction failed
            if ($resumePath) {
                Storage::disk('private')->delete($resumePath);
            }
            
            throw new JobApplicationException(
                'Failed to submit job application: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }
    
    /**
     * Validate if user can apply for the job.
     * 
     * @param User $user
     * @param Job $job
     * 
     * @throws JobApplicationException
     */
    private function validateApplicationEligibility(User $user, Job $job): void
    {
        // Check if job is active
        if (!$job->is_active) {
            throw new JobApplicationException('This job is no longer accepting applications.');
        }
        
        // Check if user already applied
        if ($job->applications()->where('user_id', $user->id)->exists()) {
            throw new JobApplicationException('You have already applied for this job.');
        }
        
        // Check if user is the job poster
        if ($job->user_id === $user->id) {
            throw new JobApplicationException('You cannot apply for your own job posting.');
        }
        
        // Check if user profile is complete
        if (!$user->profile_completed_at) {
            throw new JobApplicationException('Please complete your profile before applying for jobs.');
        }
    }
    
    /**
     * Handle resume file upload.
     * 
     * @param UploadedFile $file
     * @param int $userId
     * 
     * @return string The stored file path
     * 
     * @throws JobApplicationException
     */
    private function handleResumeUpload(UploadedFile $file, int $userId): string
    {
        // Validate file
        if (!$file->isValid()) {
            throw new JobApplicationException('Invalid resume file.');
        }
        
        $allowedMimes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new JobApplicationException('Resume must be a PDF or Word document.');
        }
        
        if ($file->getSize() > 5 * 1024 * 1024) { // 5MB
            throw new JobApplicationException('Resume file size must be less than 5MB.');
        }
        
        // Generate unique filename
        $filename = 'resume_' . $userId . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Store file
        $path = $file->storeAs('resumes', $filename, 'private');
        
        if (!$path) {
            throw new JobApplicationException('Failed to upload resume file.');
        }
        
        return $path;
    }
    
    /**
     * Update application status.
     * 
     * @param JobApplication $application
     * @param string $status
     * @param User $reviewer
     * @param string|null $notes
     * 
     * @return JobApplication
     */
    public function updateApplicationStatus(
        JobApplication $application,
        string $status,
        User $reviewer,
        ?string $notes = null
    ): JobApplication {
        $application->update([
            'status' => $status,
            'reviewed_at' => now(),
            'reviewed_by' => $reviewer->id,
            'notes' => $notes,
        ]);
        
        // Send status update notification
        // Implementation depends on notification preferences
        
        return $application;
    }
    
    /**
     * Get applications for a job with filtering and pagination.
     * 
     * @param Job $job
     * @param array $filters
     * @param int $perPage
     * 
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getJobApplications(Job $job, array $filters = [], int $perPage = 20)
    {
        $query = $job->applications()
            ->with(['user.profile', 'reviewedBy'])
            ->latest();
        
        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['reviewed'])) {
            if ($filters['reviewed']) {
                $query->whereNotNull('reviewed_at');
            } else {
                $query->whereNull('reviewed_at');
            }
        }
        
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
        
        return $query->paginate($perPage);
    }
}
```

### 3.3 Controller Standards

**API Controller Best Practices**

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobApplicationRequest;
use App\Http\Resources\JobApplicationResource;
use App\Models\Job;
use App\Services\JobApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Job application API controller.
 * 
 * Handles HTTP requests for job application operations including
 * submission, status updates, and retrieval of applications.
 * 
 * @group Job Applications
 */
class JobApplicationController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private JobApplicationService $applicationService
    ) {
        $this->middleware('auth:sanctum');
        $this->middleware('verified');
    }
    
    /**
     * Submit a job application.
     * 
     * Submit an application for a specific job posting. The user must be
     * authenticated and have a complete profile to apply.
     * 
     * @param JobApplicationRequest $request
     * @param Job $job
     * 
     * @return JsonResponse
     * 
     * @response 201 {
     *   "data": {
     *     "id": 1,
     *     "job_id": 123,
     *     "status": "pending",
     *     "cover_letter": "I am interested in this position...",
     *     "expected_salary": 50000,
     *     "submitted_at": "2024-01-15T10:30:00Z"
     *   },
     *   "message": "Application submitted successfully"
     * }
     * 
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "cover_letter": ["The cover letter field is required."]
     *   }
     * }
     * 
     * @response 409 {
     *   "message": "You have already applied for this job."
     * }
     */
    public function store(JobApplicationRequest $request, Job $job): JsonResponse
    {
        try {
            $application = $this->applicationService->submitApplication(
                $request->user(),
                $job,
                $request->validated(),
                $request->file('resume')
            );
            
            return response()->json([
                'data' => new JobApplicationResource($application),
                'message' => 'Application submitted successfully',
            ], Response::HTTP_CREATED);
        } catch (\App\Exceptions\JobApplicationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_CONFLICT);
        }
    }
    
    /**
     * Get user's job applications.
     * 
     * Retrieve a paginated list of the authenticated user's job applications
     * with optional filtering by status and date range.
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     * 
     * @queryParam status string Filter by application status. Example: pending
     * @queryParam date_from string Filter applications from this date. Example: 2024-01-01
     * @queryParam date_to string Filter applications to this date. Example: 2024-01-31
     * @queryParam per_page integer Number of items per page (max 100). Example: 20
     * 
     * @response {
     *   "data": [
     *     {
     *       "id": 1,
     *       "job": {
     *         "id": 123,
     *         "title": "Senior Data Scientist",
     *         "company": "Tech Corp"
     *       },
     *       "status": "pending",
     *       "submitted_at": "2024-01-15T10:30:00Z"
     *     }
     *   ],
     *   "links": {
     *     "first": "http://api.example.com/applications?page=1",
     *     "last": "http://api.example.com/applications?page=5",
     *     "prev": null,
     *     "next": "http://api.example.com/applications?page=2"
     *   },
     *   "meta": {
     *     "current_page": 1,
     *     "total": 95
     *   }
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'status' => 'sometimes|string|in:pending,reviewed,shortlisted,interviewed,rejected,hired',
            'date_from' => 'sometimes|date',
            'date_to' => 'sometimes|date|after_or_equal:date_from',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);
        
        $applications = $request->user()
            ->jobApplications()
            ->with(['job.company'])
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->when($request->date_from, fn($q, $date) => $q->whereDate('created_at', '>=', $date))
            ->when($request->date_to, fn($q, $date) => $q->whereDate('created_at', '<=', $date))
            ->latest()
            ->paginate($request->get('per_page', 20));
        
        return response()->json([
            'data' => JobApplicationResource::collection($applications->items()),
            'links' => [
                'first' => $applications->url(1),
                'last' => $applications->url($applications->lastPage()),
                'prev' => $applications->previousPageUrl(),
                'next' => $applications->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $applications->currentPage(),
                'last_page' => $applications->lastPage(),
                'per_page' => $applications->perPage(),
                'total' => $applications->total(),
            ],
        ]);
    }
    
    /**
     * Get a specific job application.
     * 
     * @param int $id
     * 
     * @return JsonResponse
     * 
     * @response {
     *   "data": {
     *     "id": 1,
     *     "job": {
     *       "id": 123,
     *       "title": "Senior Data Scientist",
     *       "company": "Tech Corp"
     *     },
     *     "status": "pending",
     *     "cover_letter": "I am interested in this position...",
     *     "expected_salary": 50000,
     *     "submitted_at": "2024-01-15T10:30:00Z",
     *     "reviewed_at": null,
     *     "notes": null
     *   }
     * }
     * 
     * @response 404 {
     *   "message": "Application not found"
     * }
     */
    public function show(int $id): JsonResponse
    {
        $application = auth()->user()
            ->jobApplications()
            ->with(['job.company'])
            ->findOrFail($id);
        
        return response()->json([
            'data' => new JobApplicationResource($application),
        ]);
    }
    
    /**
     * Withdraw a job application.
     * 
     * @param int $id
     * 
     * @return JsonResponse
     * 
     * @response 200 {
     *   "message": "Application withdrawn successfully"
     * }
     * 
     * @response 404 {
     *   "message": "Application not found"
     * }
     * 
     * @response 422 {
     *   "message": "Cannot withdraw application in current status"
     * }
     */
    public function destroy(int $id): JsonResponse
    {
        $application = auth()->user()
            ->jobApplications()
            ->findOrFail($id);
        
        if (in_array($application->status, ['hired', 'rejected'])) {
            return response()->json([
                'message' => 'Cannot withdraw application in current status',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $application->update(['status' => 'withdrawn']);
        
        return response()->json([
            'message' => 'Application withdrawn successfully',
        ]);
    }
}
```

## 4. Frontend Code Standards

### 4.1 JavaScript/Alpine.js Standards

**Alpine.js Component Standards**

```javascript
// resources/js/components/job-search.js

/**
 * Job search component with filtering and pagination.
 * 
 * Provides real-time search functionality with debounced input,
 * category filtering, location filtering, and infinite scroll pagination.
 */
export default function jobSearch() {
    return {
        // State
        jobs: [],
        loading: false,
        hasMore: true,
        currentPage: 1,
        
        // Filters
        searchQuery: '',
        selectedCategory: '',
        selectedLocation: '',
        salaryMin: '',
        salaryMax: '',
        experienceLevel: '',
        employmentType: '',
        
        // UI State
        showFilters: false,
        searchDebounceTimer: null,
        
        /**
         * Initialize the component.
         */
        init() {
            this.loadJobs();
            this.setupInfiniteScroll();
            
            // Watch for search query changes
            this.$watch('searchQuery', () => {
                this.debounceSearch();
            });
            
            // Watch for filter changes
            ['selectedCategory', 'selectedLocation', 'salaryMin', 'salaryMax', 'experienceLevel', 'employmentType']
                .forEach(filter => {
                    this.$watch(filter, () => {
                        this.resetAndSearch();
                    });
                });
        },
        
        /**
         * Load jobs with current filters.
         * 
         * @param {boolean} append - Whether to append to existing jobs or replace
         */
        async loadJobs(append = false) {
            if (this.loading) return;
            
            this.loading = true;
            
            try {
                const params = new URLSearchParams({
                    page: this.currentPage,
                    search: this.searchQuery,
                    category: this.selectedCategory,
                    location: this.selectedLocation,
                    salary_min: this.salaryMin,
                    salary_max: this.salaryMax,
                    experience_level: this.experienceLevel,
                    employment_type: this.employmentType,
                }).toString();
                
                const response = await fetch(`/api/jobs?${params}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${this.getAuthToken()}`,
                    },
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (append) {
                    this.jobs = [...this.jobs, ...data.data];
                } else {
                    this.jobs = data.data;
                }
                
                this.hasMore = data.meta.current_page < data.meta.last_page;
                this.currentPage = data.meta.current_page;
                
                // Update URL without page reload
                this.updateUrl();
            } catch (error) {
                console.error('Failed to load jobs:', error);
                this.showError('Failed to load jobs. Please try again.');
            } finally {
                this.loading = false;
            }
        },
        
        /**
         * Debounce search input to avoid excessive API calls.
         */
        debounceSearch() {
            clearTimeout(this.searchDebounceTimer);
            this.searchDebounceTimer = setTimeout(() => {
                this.resetAndSearch();
            }, 300);
        },
        
        /**
         * Reset pagination and search with current filters.
         */
        resetAndSearch() {
            this.currentPage = 1;
            this.hasMore = true;
            this.loadJobs(false);
        },
        
        /**
         * Load more jobs for infinite scroll.
         */
        loadMore() {
            if (this.hasMore && !this.loading) {
                this.currentPage++;
                this.loadJobs(true);
            }
        },
        
        /**
         * Setup infinite scroll functionality.
         */
        setupInfiniteScroll() {
            const observer = new IntersectionObserver(
                (entries) => {
                    if (entries[0].isIntersecting) {
                        this.loadMore();
                    }
                },
                { threshold: 0.1 }
            );
            
            // Observe the load more trigger element
            this.$nextTick(() => {
                const trigger = document.getElementById('load-more-trigger');
                if (trigger) {
                    observer.observe(trigger);
                }
            });
        },
        
        /**
         * Clear all filters.
         */
        clearFilters() {
            this.searchQuery = '';
            this.selectedCategory = '';
            this.selectedLocation = '';
            this.salaryMin = '';
            this.salaryMax = '';
            this.experienceLevel = '';
            this.employmentType = '';
            this.resetAndSearch();
        },
        
        /**
         * Toggle filters panel visibility.
         */
        toggleFilters() {
            this.showFilters = !this.showFilters;
        },
        
        /**
         * Apply for a job.
         * 
         * @param {number} jobId - The ID of the job to apply for
         */
        async applyForJob(jobId) {
            try {
                const response = await fetch(`/api/jobs/${jobId}/apply`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${this.getAuthToken()}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to apply for job');
                }
                
                // Update job in the list to show applied status
                const jobIndex = this.jobs.findIndex(job => job.id === jobId);
                if (jobIndex !== -1) {
                    this.jobs[jobIndex].user_has_applied = true;
                }
                
                this.showSuccess('Application submitted successfully!');
            } catch (error) {
                console.error('Failed to apply for job:', error);
                this.showError(error.message);
            }
        },
        
        /**
         * Save/unsave a job.
         * 
         * @param {number} jobId - The ID of the job to save/unsave
         */
        async toggleSaveJob(jobId) {
            try {
                const job = this.jobs.find(j => j.id === jobId);
                const method = job.user_has_saved ? 'DELETE' : 'POST';
                
                const response = await fetch(`/api/jobs/${jobId}/save`, {
                    method,
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${this.getAuthToken()}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                
                if (!response.ok) {
                    throw new Error('Failed to save/unsave job');
                }
                
                // Update job in the list
                const jobIndex = this.jobs.findIndex(j => j.id === jobId);
                if (jobIndex !== -1) {
                    this.jobs[jobIndex].user_has_saved = !this.jobs[jobIndex].user_has_saved;
                }
                
                const message = job.user_has_saved ? 'Job removed from saved jobs' : 'Job saved successfully';
                this.showSuccess(message);
            } catch (error) {
                console.error('Failed to save/unsave job:', error);
                this.showError('Failed to save job. Please try again.');
            }
        },
        
        /**
         * Update URL with current search parameters.
         */
        updateUrl() {
            const params = new URLSearchParams();
            
            if (this.searchQuery) params.set('search', this.searchQuery);
            if (this.selectedCategory) params.set('category', this.selectedCategory);
            if (this.selectedLocation) params.set('location', this.selectedLocation);
            if (this.salaryMin) params.set('salary_min', this.salaryMin);
            if (this.salaryMax) params.set('salary_max', this.salaryMax);
            if (this.experienceLevel) params.set('experience_level', this.experienceLevel);
            if (this.employmentType) params.set('employment_type', this.employmentType);
            
            const newUrl = `${window.location.pathname}${params.toString() ? '?' + params.toString() : ''}`;
            window.history.replaceState({}, '', newUrl);
        },
        
        /**
         * Get authentication token from meta tag or localStorage.
         * 
         * @returns {string|null} The auth token
         */
        getAuthToken() {
            return document.querySelector('meta[name="api-token"]')?.content || 
                   localStorage.getItem('auth_token');
        },
        
        /**
         * Show success message.
         * 
         * @param {string} message - The success message
         */
        showSuccess(message) {
            // Implementation depends on your notification system
            // This could dispatch an Alpine.js event or call a global notification function
            this.$dispatch('show-notification', {
                type: 'success',
                message: message,
            });
        },
        
        /**
         * Show error message.
         * 
         * @param {string} message - The error message
         */
        showError(message) {
            this.$dispatch('show-notification', {
                type: 'error',
                message: message,
            });
        },
        
        /**
         * Format salary range for display.
         * 
         * @param {Object} job - The job object
         * @returns {string} Formatted salary range
         */
        formatSalaryRange(job) {
            if (!job.salary_min && !job.salary_max) {
                return 'Salary not specified';
            }
            
            if (job.is_salary_negotiable) {
                return 'Negotiable';
            }
            
            const min = job.salary_min ? this.formatNumber(job.salary_min) : null;
            const max = job.salary_max ? this.formatNumber(job.salary_max) : null;
            
            if (min && max) {
                return `${min} - ${max} ${job.salary_currency}`;
            }
            
            return `${min || max} ${job.salary_currency}`;
        },
        
        /**
         * Format number with thousands separator.
         * 
         * @param {number} number - The number to format
         * @returns {string} Formatted number
         */
        formatNumber(number) {
            return new Intl.NumberFormat().format(number);
        },
        
        /**
         * Get relative time string.
         * 
         * @param {string} dateString - ISO date string
         * @returns {string} Relative time string
         */
        getRelativeTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);
            
            const intervals = {
                year: 31536000,
                month: 2592000,
                week: 604800,
                day: 86400,
                hour: 3600,
                minute: 60,
            };
            
            for (const [unit, seconds] of Object.entries(intervals)) {
                const interval = Math.floor(diffInSeconds / seconds);
                if (interval >= 1) {
                    return `${interval} ${unit}${interval > 1 ? 's' : ''} ago`;
                }
            }
            
            return 'Just now';
        },
    };
}
```

### 4.2 CSS/Tailwind Standards

**Tailwind CSS Component Classes**

```css
/* resources/css/components.css */

/* Button Components */
.btn {
    @apply inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-medium text-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed;
}

.btn-primary {
    @apply btn bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500;
}

.btn-secondary {
    @apply btn bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-500;
}

.btn-success {
    @apply btn bg-green-600 text-white hover:bg-green-700 focus:ring-green-500;
}

.btn-danger {
    @apply btn bg-red-600 text-white hover:bg-red-700 focus:ring-red-500;
}

.btn-outline {
    @apply btn border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:ring-blue-500;
}

.btn-sm {
    @apply px-3 py-1.5 text-xs;
}

.btn-lg {
    @apply px-6 py-3 text-base;
}

/* Form Components */
.form-input {
    @apply block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm;
}

.form-input-error {
    @apply form-input border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500;
}

.form-label {
    @apply block text-sm font-medium text-gray-700 mb-1;
}

.form-label-required::after {
    @apply text-red-500;
    content: ' *';
}

.form-error {
    @apply mt-1 text-sm text-red-600;
}

.form-help {
    @apply mt-1 text-sm text-gray-500;
}

.form-group {
    @apply mb-4;
}

/* Card Components */
.card {
    @apply bg-white overflow-hidden shadow rounded-lg;
}

.card-header {
    @apply px-4 py-5 sm:px-6 border-b border-gray-200;
}

.card-body {
    @apply px-4 py-5 sm:p-6;
}

.card-footer {
    @apply px-4 py-4 sm:px-6 border-t border-gray-200;
}

/* Badge Components */
.badge {
    @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
}

.badge-primary {
    @apply badge bg-blue-100 text-blue-800;
}

.badge-success {
    @apply badge bg-green-100 text-green-800;
}

.badge-warning {
    @apply badge bg-yellow-100 text-yellow-800;
}

.badge-danger {
    @apply badge bg-red-100 text-red-800;
}

.badge-gray {
    @apply badge bg-gray-100 text-gray-800;
}

/* Alert Components */
.alert {
    @apply p-4 rounded-md;
}

.alert-success {
    @apply alert bg-green-50 border border-green-200;
}

.alert-error {
    @apply alert bg-red-50 border border-red-200;
}

.alert-warning {
    @apply alert bg-yellow-50 border border-yellow-200;
}

.alert-info {
    @apply alert bg-blue-50 border border-blue-200;
}

.alert-title {
    @apply text-sm font-medium mb-1;
}

.alert-success .alert-title {
    @apply text-green-800;
}

.alert-error .alert-title {
    @apply text-red-800;
}

.alert-warning .alert-title {
    @apply text-yellow-800;
}

.alert-info .alert-title {
    @apply text-blue-800;
}

.alert-message {
    @apply text-sm;
}

.alert-success .alert-message {
    @apply text-green-700;
}

.alert-error .alert-message {
    @apply text-red-700;
}

.alert-warning .alert-message {
    @apply text-yellow-700;
}

.alert-info .alert-message {
    @apply text-blue-700;
}

/* Loading Components */
.spinner {
    @apply animate-spin rounded-full border-2 border-gray-300 border-t-blue-600;
}

.spinner-sm {
    @apply w-4 h-4;
}

.spinner-md {
    @apply w-6 h-6;
}

.spinner-lg {
    @apply w-8 h-8;
}

/* Table Components */
.table {
    @apply min-w-full divide-y divide-gray-200;
}

.table-header {
    @apply bg-gray-50;
}

.table-header-cell {
    @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
}

.table-body {
    @apply bg-white divide-y divide-gray-200;
}

.table-row {
    @apply hover:bg-gray-50;
}

.table-cell {
    @apply px-6 py-4 whitespace-nowrap text-sm text-gray-900;
}

/* Navigation Components */
.nav-link {
    @apply text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200;
}

.nav-link-active {
    @apply nav-link bg-gray-100 text-gray-900;
}

/* Responsive Utilities */
.container {
    @apply max-w-7xl mx-auto px-4 sm:px-6 lg:px-8;
}

.section {
    @apply py-12;
}

.section-header {
    @apply text-center mb-12;
}

.section-title {
    @apply text-3xl font-extrabold text-gray-900 sm:text-4xl;
}

.section-subtitle {
    @apply mt-4 text-xl text-gray-600;
}
```

## 5. Testing Standards

### 5.1 Unit Testing with PHPUnit

**Test Structure and Naming**
```php
<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Models\Job;
use App\Services\JobRecommendationService;
use App\Services\UserPreferenceService;
use App\Services\SkillMatchingService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

/**
 * Test suite for JobRecommendationService.
 * 
 * Tests the job recommendation algorithm including skill matching,
 * preference filtering, and scoring calculations.
 */
class JobRecommendationServiceTest extends TestCase
{
    use RefreshDatabase;
    
    private JobRecommendationService $service;
    private UserPreferenceService $preferenceService;
    private SkillMatchingService $skillService;
    
    /**
     * Set up test dependencies.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->preferenceService = Mockery::mock(UserPreferenceService::class);
        $this->skillService = Mockery::mock(SkillMatchingService::class);
        
        $this->service = new JobRecommendationService(
            $this->preferenceService,
            $this->skillService
        );
    }
    
    /**
     * Test that recommendations are returned for valid user.
     * 
     * @test
     */
    public function it_returns_recommendations_for_valid_user(): void
    {
        // Arrange
        $user = User::factory()->create([
            'skills' => ['PHP', 'Laravel', 'MySQL'],
            'experience_level' => 'senior',
            'preferred_location' => 'Remote',
        ]);
        
        $jobs = Job::factory()->count(5)->create([
            'status' => 'active',
            'deadline' => now()->addDays(30),
        ]);
        
        $this->preferenceService
            ->shouldReceive('getUserPreferences')
            ->with($user)
            ->once()
            ->andReturn([
                'preferred_salary_min' => 50000,
                'preferred_salary_max' => 80000,
                'preferred_location' => 'Remote',
            ]);
        
        $this->skillService
            ->shouldReceive('calculateSkillMatch')
            ->andReturn(0.8);
        
        // Act
        $recommendations = $this->service->getRecommendations($user, 10);
        
        // Assert
        $this->assertNotEmpty($recommendations);
        $this->assertLessThanOrEqual(10, $recommendations->count());
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $recommendations);
        
        // Verify each recommendation has a score
        $recommendations->each(function ($job) {
            $this->assertObjectHasAttribute('recommendation_score', $job);
            $this->assertIsFloat($job->recommendation_score);
            $this->assertGreaterThanOrEqual(0, $job->recommendation_score);
            $this->assertLessThanOrEqual(1, $job->recommendation_score);
        });
    }
    
    /**
     * Test that invalid limit throws exception.
     * 
     * @test
     */
    public function it_throws_exception_for_invalid_limit(): void
    {
        // Arrange
        $user = User::factory()->create();
        
        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be between 1 and 50');
        
        $this->service->getRecommendations($user, 0);
    }
    
    /**
     * Test that recommendations are ordered by score.
     * 
     * @test
     */
    public function it_orders_recommendations_by_score(): void
    {
        // Arrange
        $user = User::factory()->create();
        
        Job::factory()->count(5)->create([
            'status' => 'active',
            'deadline' => now()->addDays(30),
        ]);
        
        $this->preferenceService
            ->shouldReceive('getUserPreferences')
            ->andReturn([]);
        
        // Mock different skill match scores
        $this->skillService
            ->shouldReceive('calculateSkillMatch')
            ->andReturn(0.9, 0.7, 0.8, 0.6, 0.5);
        
        // Act
        $recommendations = $this->service->getRecommendations($user, 5);
        
        // Assert
        $scores = $recommendations->pluck('recommendation_score')->toArray();
        $sortedScores = collect($scores)->sortDesc()->values()->toArray();
        
        $this->assertEquals($sortedScores, $scores);
    }
    
    /**
     * Test custom weights affect scoring.
     * 
     * @test
     */
    public function it_applies_custom_weights_to_scoring(): void
    {
        // Arrange
        $user = User::factory()->create();
        $job = Job::factory()->create([
            'status' => 'active',
            'deadline' => now()->addDays(30),
        ]);
        
        $customWeights = [
            'skills_match' => 0.8,
            'experience_level' => 0.1,
            'location_preference' => 0.1,
            'salary_range' => 0.0,
        ];
        
        $this->preferenceService
            ->shouldReceive('getUserPreferences')
            ->andReturn([]);
        
        $this->skillService
            ->shouldReceive('calculateSkillMatch')
            ->andReturn(0.9);
        
        // Act
        $recommendations = $this->service->getRecommendations($user, 1, $customWeights);
        
        // Assert
        $this->assertNotEmpty($recommendations);
        // Score should be heavily influenced by skills match (0.8 weight)
        $this->assertGreaterThan(0.7, $recommendations->first()->recommendation_score);
    }
    
    /**
     * Test that inactive jobs are excluded.
     * 
     * @test
     */
    public function it_excludes_inactive_jobs(): void
    {
        // Arrange
        $user = User::factory()->create();
        
        // Create active and inactive jobs
        Job::factory()->count(3)->create([
            'status' => 'active',
            'deadline' => now()->addDays(30),
        ]);
        
        Job::factory()->count(2)->create([
            'status' => 'inactive',
            'deadline' => now()->addDays(30),
        ]);
        
        Job::factory()->count(2)->create([
            'status' => 'active',
            'deadline' => now()->subDays(1), // Expired
        ]);
        
        $this->preferenceService
            ->shouldReceive('getUserPreferences')
            ->andReturn([]);
        
        $this->skillService
            ->shouldReceive('calculateSkillMatch')
            ->andReturn(0.8);
        
        // Act
        $recommendations = $this->service->getRecommendations($user, 10);
        
        // Assert
        $this->assertCount(3, $recommendations);
        
        $recommendations->each(function ($job) {
            $this->assertEquals('active', $job->status);
            $this->assertTrue($job->deadline->isFuture());
        });
    }
    
    /**
     * Clean up mocks after each test.
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
```

### 5.2 Feature Testing with Livewire

**Livewire Component Testing**
```php
<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\JobSearch;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Test suite for JobSearch Livewire component.
 */
class JobSearchTest extends TestCase
{
    use RefreshDatabase;
    
    private User $user;
    
    /**
     * Set up test data.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }
    
    /**
     * Test component renders successfully.
     * 
     * @test
     */
    public function it_renders_successfully(): void
    {
        Livewire::test(JobSearch::class)
            ->assertStatus(200)
            ->assertSee('Search Jobs')
            ->assertSee('Filters');
    }
    
    /**
     * Test search functionality.
     * 
     * @test
     */
    public function it_can_search_jobs(): void
    {
        // Arrange
        $matchingJob = Job::factory()->create([
            'title' => 'Senior PHP Developer',
            'status' => 'active',
            'deadline' => now()->addDays(30),
        ]);
        
        $nonMatchingJob = Job::factory()->create([
            'title' => 'Python Data Scientist',
            'status' => 'active',
            'deadline' => now()->addDays(30),
        ]);
        
        // Act & Assert
        Livewire::test(JobSearch::class)
            ->set('search', 'PHP')
            ->assertSee($matchingJob->title)
            ->assertDontSee($nonMatchingJob->title);
    }
    
    /**
     * Test category filtering.
     * 
     * @test
     */
    public function it_can_filter_by_category(): void
    {
        // Arrange
        $category1 = JobCategory::factory()->create(['name' => 'Development']);
        $category2 = JobCategory::factory()->create(['name' => 'Design']);
        
        $devJob = Job::factory()->create([
            'category_id' => $category1->id,
            'status' => 'active',
            'deadline' => now()->addDays(30),
        ]);
        
        $designJob = Job::factory()->create([
            'category_id' => $category2->id,
            'status' => 'active',
            'deadline' => now()->addDays(30),
        ]);
        
        // Act & Assert
        Livewire::test(JobSearch::class)
            ->set('selectedCategory', $category1->id)
            ->assertSee($devJob->title)
            ->assertDontSee($designJob->title);
    }
    
    /**
     * Test pagination.
     * 
     * @test
     */
    public function it_paginates_results(): void
    {
        // Arrange
        Job::factory()->count(25)->create([
            'status' => 'active',
            'deadline' => now()->addDays(30),
        ]);
        
        // Act & Assert
        Livewire::test(JobSearch::class)
            ->assertSee('Next')
            ->call('nextPage')
            ->assertSee('Previous');
    }
    
    /**
     * Test job application from search results.
     * 
     * @test
     */
    public function it_can_apply_for_job_from_search(): void
    {
        // Arrange
        $job = Job::factory()->create([
            'status' => 'active',
            'deadline' => now()->addDays(30),
        ]);
        
        // Act & Assert
        Livewire::test(JobSearch::class)
            ->call('applyForJob', $job->id)
            ->assertEmitted('job-applied')
            ->assertSee('Application submitted successfully');
        
        $this->assertDatabaseHas('job_applications', [
            'user_id' => $this->user->id,
            'job_id' => $job->id,
            'status' => 'pending',
        ]);
    }
    
    /**
     * Test saving jobs from search results.
     * 
     * @test
     */
    public function it_can_save_job_from_search(): void
    {
        // Arrange
        $job = Job::factory()->create([
            'status' => 'active',
            'deadline' => now()->addDays(30),
        ]);
        
        // Act & Assert
        Livewire::test(JobSearch::class)
            ->call('toggleSaveJob', $job->id)
            ->assertSee('Job saved successfully');
        
        $this->assertDatabaseHas('saved_jobs', [
            'user_id' => $this->user->id,
            'job_id' => $job->id,
        ]);
    }
    
    /**
     * Test clearing filters.
     * 
     * @test
     */
    public function it_can_clear_filters(): void
    {
        // Arrange
        $category = JobCategory::factory()->create();
        
        // Act & Assert
        Livewire::test(JobSearch::class)
            ->set('search', 'PHP')
            ->set('selectedCategory', $category->id)
            ->set('location', 'Remote')
            ->call('clearFilters')
            ->assertSet('search', '')
            ->assertSet('selectedCategory', '')
            ->assertSet('location', '');
    }
}
```

## 6. Performance Standards

### 6.1 Database Query Optimization

**Query Performance Guidelines**
```php
<?php

// ❌ Bad: N+1 Query Problem
class BadJobController extends Controller
{
    public function index()
    {
        $jobs = Job::all();
        
        foreach ($jobs as $job) {
            echo $job->company->name; // N+1 queries
            echo $job->category->name; // N+1 queries
        }
    }
}

// ✅ Good: Eager Loading
class GoodJobController extends Controller
{
    public function index()
    {
        $jobs = Job::with(['company', 'category', 'user'])
            ->active()
            ->latest()
            ->paginate(20);
        
        return view('jobs.index', compact('jobs'));
    }
    
    public function search(Request $request)
    {
        $query = Job::query()
            ->with(['company:id,name,logo', 'category:id,name'])
            ->select(['id', 'title', 'company_id', 'category_id', 'location', 'salary_min', 'salary_max', 'created_at'])
            ->active();
        
        // Use database indexes for filtering
        if ($request->filled('search')) {
            $query->whereFullText(['title', 'description'], $request->search);
        }
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }
        
        if ($request->filled('salary_min')) {
            $query->where('salary_max', '>=', $request->salary_min);
        }
        
        return $query->paginate(20);
    }
}
```

### 6.2 Caching Strategies

**Redis Caching Implementation**
```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

/**
 * Caching service for optimizing data retrieval.
 */
class CacheService
{
    /**
     * Cache job categories with tags for easy invalidation.
     */
    public function getJobCategories(): Collection
    {
        return Cache::tags(['job_categories'])
            ->remember('job_categories.all', 3600, function () {
                return JobCategory::with('jobs:id,category_id')
                    ->withCount('jobs')
                    ->orderBy('name')
                    ->get();
            });
    }
    
    /**
     * Cache user's job recommendations.
     */
    public function getUserJobRecommendations(User $user, int $limit = 20): Collection
    {
        $cacheKey = "user.{$user->id}.job_recommendations.{$limit}";
        
        return Cache::remember($cacheKey, 1800, function () use ($user, $limit) {
            return app(JobRecommendationService::class)
                ->getRecommendations($user, $limit);
        });
    }
    
    /**
     * Cache job search results with complex key.
     */
    public function getJobSearchResults(array $filters, int $page = 1): LengthAwarePaginator
    {
        $cacheKey = 'job_search.' . md5(serialize($filters)) . ".page.{$page}";
        
        return Cache::remember($cacheKey, 600, function () use ($filters, $page) {
            return app(JobSearchService::class)
                ->search($filters, $page);
        });
    }
    
    /**
     * Invalidate related caches when job is created/updated.
     */
    public function invalidateJobCaches(Job $job): void
    {
        // Clear job categories cache
        Cache::tags(['job_categories'])->flush();
        
        // Clear search result caches
        $this->clearJobSearchCaches();
        
        // Clear user recommendation caches for users in same category
        $this->clearUserRecommendationCaches($job->category_id);
    }
    
    /**
     * Clear job search caches using pattern matching.
     */
    private function clearJobSearchCaches(): void
    {
        $keys = Redis::keys('laravel_cache:job_search.*');
        if (!empty($keys)) {
            Redis::del($keys);
        }
    }
    
    /**
     * Clear user recommendation caches.
     */
    private function clearUserRecommendationCaches(int $categoryId): void
    {
        // Get users interested in this category
        $userIds = UserPreference::where('preferred_categories', 'like', "%{$categoryId}%")
            ->pluck('user_id');
        
        foreach ($userIds as $userId) {
            $pattern = "user.{$userId}.job_recommendations.*";
            $keys = Redis::keys("laravel_cache:{$pattern}");
            if (!empty($keys)) {
                Redis::del($keys);
            }
        }
    }
}
```

## 7. Security Standards

### 7.1 Input Validation and Sanitization

**Form Request Validation**
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Job creation request with comprehensive validation.
 */
class CreateJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Job::class);
    }
    
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-\.\(\)]+$/', // Only allow safe characters
            ],
            'description' => [
                'required',
                'string',
                'max:10000',
                'not_regex:/<script[^>]*>.*?<\/script>/i', // Block script tags
            ],
            'company_id' => [
                'required',
                'integer',
                'exists:companies,id',
                function ($attribute, $value, $fail) {
                    if (!$this->user()->companies()->where('id', $value)->exists()) {
                        $fail('You can only post jobs for your own companies.');
                    }
                },
            ],
            'category_id' => 'required|integer|exists:job_categories,id',
            'requirements' => 'required|array|min:1|max:20',
            'requirements.*' => 'required|string|max:500',
            'responsibilities' => 'required|array|min:1|max:20',
            'responsibilities.*' => 'required|string|max:500',
            'benefits' => 'nullable|array|max:15',
            'benefits.*' => 'required|string|max:500',
            'skills_required' => 'required|array|min:1|max:30',
            'skills_required.*' => 'required|string|max:50|regex:/^[a-zA-Z0-9\s\+\#\.\-]+$/',
            'experience_level' => 'required|string|in:entry,junior,mid,senior,lead,executive',
            'employment_type' => 'required|string|in:full_time,part_time,contract,freelance,internship',
            'location_type' => 'required|string|in:onsite,remote,hybrid',
            'location' => 'required_unless:location_type,remote|nullable|string|max:255',
            'salary_min' => 'nullable|numeric|min:0|max:1000000',
            'salary_max' => 'nullable|numeric|min:0|max:1000000|gte:salary_min',
            'salary_currency' => 'required_with:salary_min,salary_max|string|in:USD,EUR,GBP,CAD,AUD',
            'is_salary_negotiable' => 'boolean',
            'deadline' => 'required|date|after:today|before:' . now()->addYear(),
            'is_featured' => 'boolean',
            'featured_until' => 'required_if:is_featured,true|nullable|date|after:deadline',
        ];
    }
    
    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.regex' => 'Job title contains invalid characters.',
            'description.not_regex' => 'Job description cannot contain script tags.',
            'skills_required.*.regex' => 'Skill names can only contain letters, numbers, and common symbols.',
            'deadline.after' => 'Application deadline must be in the future.',
            'deadline.before' => 'Application deadline cannot be more than one year from now.',
        ];
    }
    
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => strip_tags($this->title),
            'description' => $this->sanitizeHtml($this->description),
            'is_salary_negotiable' => $this->boolean('is_salary_negotiable'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }
    
    /**
     * Sanitize HTML content while preserving safe formatting.
     */
    private function sanitizeHtml(string $content): string
    {
        $allowedTags = '<p><br><strong><em><ul><ol><li><h3><h4><h5><h6>';
        return strip_tags($content, $allowedTags);
    }
    
    /**
     * Get validated data with additional processing.
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        
        // Additional sanitization
        if (isset($validated['requirements'])) {
            $validated['requirements'] = array_map('strip_tags', $validated['requirements']);
        }
        
        if (isset($validated['responsibilities'])) {
            $validated['responsibilities'] = array_map('strip_tags', $validated['responsibilities']);
        }
        
        if (isset($validated['benefits'])) {
            $validated['benefits'] = array_map('strip_tags', $validated['benefits']);
        }
        
        return $validated;
    }
}
```

### 7.2 Authentication and Authorization

**Policy-Based Authorization**
```php
<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Job policy for authorization checks.
 */
class JobPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any jobs.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view jobs
    }
    
    /**
     * Determine whether the user can view the job.
     */
    public function view(?User $user, Job $job): bool
    {
        // Public jobs can be viewed by anyone
        if ($job->status === 'active') {
            return true;
        }
        
        // Private/draft jobs can only be viewed by owner or admins
        return $user && (
            $user->id === $job->user_id ||
            $user->hasRole(['admin', 'super_admin'])
        );
    }
    
    /**
     * Determine whether the user can create jobs.
     */
    public function create(User $user): bool
    {
        // Only verified client users can create jobs
        return $user->hasVerifiedEmail() && 
               $user->hasRole(['client_user', 'admin', 'super_admin']) &&
               $user->profile_completed_at !== null;
    }
    
    /**
     * Determine whether the user can update the job.
     */
    public function update(User $user, Job $job): bool
    {
        // Job owner or admins can update
        if ($user->id === $job->user_id || $user->hasRole(['admin', 'super_admin'])) {
            return true;
        }
        
        // Company members with permission can update
        return $job->company && 
               $job->company->members()
                   ->where('user_id', $user->id)
                   ->where('can_manage_jobs', true)
                   ->exists();
    }
    
    /**
     * Determine whether the user can delete the job.
     */
    public function delete(User $user, Job $job): bool
    {
        // Cannot delete if there are applications
        if ($job->applications()->exists()) {
            return $user->hasRole(['admin', 'super_admin']);
        }
        
        return $this->update($user, $job);
    }
    
    /**
     * Determine whether the user can apply for the job.
     */
    public function apply(User $user, Job $job): bool
    {
        // Cannot apply to own job
        if ($user->id === $job->user_id) {
            return false;
        }
        
        // Job must be active
        if (!$job->is_active) {
            return false;
        }
        
        // User must have completed profile
        if (!$user->profile_completed_at) {
            return false;
        }
        
        // Cannot apply twice
        if ($job->applications()->where('user_id', $user->id)->exists()) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Determine whether the user can view job applications.
     */
    public function viewApplications(User $user, Job $job): bool
    {
        return $this->update($user, $job);
    }
    
    /**
     * Determine whether the user can manage job applications.
     */
    public function manageApplications(User $user, Job $job): bool
    {
        return $this->update($user, $job);
    }
}
```

## 8. Documentation Standards

### 8.1 API Documentation

**OpenAPI/Swagger Documentation**
```yaml
# api-docs.yaml
openapi: 3.0.0
info:
  title: People Of Data API
  description: |
    API for the People Of Data platform providing endpoints for job management,
    user authentication, events, hackathons, and social features.
    
    ## Authentication
    This API uses Bearer token authentication. Include your token in the Authorization header:
    ```
    Authorization: Bearer your-token-here
    ```
    
    ## Rate Limiting
    API requests are limited to 60 requests per minute for authenticated users
    and 20 requests per minute for unauthenticated users.
    
    ## Error Handling
    The API returns standard HTTP status codes and JSON error responses:
    ```json
    {
      "message": "Error description",
      "errors": {
        "field": ["Validation error message"]
      }
    }
    ```
  version: 1.0.0
  contact:
    name: People Of Data Team
    email: api@peopleofdata.com
  license:
    name: MIT
    url: https://opensource.org/licenses/MIT

servers:
  - url: https://api.peopleofdata.com/v1
    description: Production server
  - url: https://staging-api.peopleofdata.com/v1
    description: Staging server
  - url: http://localhost:8000/api/v1
    description: Development server

paths:
  /jobs:
    get:
      summary: Get jobs list
      description: |
        Retrieve a paginated list of job postings with optional filtering.
        Results are ordered by relevance and creation date.
      tags:
        - Jobs
      parameters:
        - name: search
          in: query
          description: Search term for job title and description
          required: false
          schema:
            type: string
            example: "PHP Developer"
        - name: category_id
          in: query
          description: Filter by job category ID
          required: false
          schema:
            type: integer
            example: 1
        - name: location
          in: query
          description: Filter by location
          required: false
          schema:
            type: string
            example: "Remote"
        - name: experience_level
          in: query
          description: Filter by experience level
          required: false
          schema:
            type: string
            enum: [entry, junior, mid, senior, lead, executive]
        - name: employment_type
          in: query
          description: Filter by employment type
          required: false
          schema:
            type: string
            enum: [full_time, part_time, contract, freelance, internship]
        - name: salary_min
          in: query
          description: Minimum salary filter
          required: false
          schema:
            type: number
            example: 50000
        - name: salary_max
          in: query
          description: Maximum salary filter
          required: false
          schema:
            type: number
            example: 100000
        - name: page
          in: query
          description: Page number for pagination
          required: false
          schema:
            type: integer
            minimum: 1
            default: 1
        - name: per_page
          in: query
          description: Number of items per page
          required: false
          schema:
            type: integer
            minimum: 1
            maximum: 100
            default: 20
      responses:
        '200':
          description: Successful response
          content:
            application/json:
              schema:
                type: object
                properties:
                  data:
                    type: array
                    items:
                      $ref: '#/components/schemas/Job'
                  links:
                    $ref: '#/components/schemas/PaginationLinks'
                  meta:
                    $ref: '#/components/schemas/PaginationMeta'
        '422':
          description: Validation error
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ValidationError'

components:
  schemas:
    Job:
      type: object
      properties:
        id:
          type: integer
          example: 1
        title:
          type: string
          example: "Senior PHP Developer"
        slug:
          type: string
          example: "senior-php-developer"
        description:
          type: string
          example: "We are looking for an experienced PHP developer..."
        company:
          $ref: '#/components/schemas/Company'
        category:
          $ref: '#/components/schemas/JobCategory'
        location:
          type: string
          nullable: true
          example: "San Francisco, CA"
        location_type:
          type: string
          enum: [onsite, remote, hybrid]
          example: "hybrid"
        employment_type:
          type: string
          enum: [full_time, part_time, contract, freelance, internship]
          example: "full_time"
        experience_level:
          type: string
          enum: [entry, junior, mid, senior, lead, executive]
          example: "senior"
        salary_min:
          type: number
          nullable: true
          example: 80000
        salary_max:
          type: number
          nullable: true
          example: 120000
        salary_currency:
          type: string
          example: "USD"
        is_salary_negotiable:
          type: boolean
          example: false
        requirements:
          type: array
          items:
            type: string
          example: ["5+ years PHP experience", "Laravel framework knowledge"]
        responsibilities:
          type: array
          items:
            type: string
          example: ["Develop web applications", "Code review"]
        benefits:
          type: array
          items:
            type: string
          example: ["Health insurance", "Remote work"]
        skills_required:
          type: array
          items:
            type: string
          example: ["PHP", "Laravel", "MySQL", "JavaScript"]
        deadline:
          type: string
          format: date-time
          example: "2024-02-15T23:59:59Z"
        is_featured:
          type: boolean
          example: false
        views_count:
          type: integer
          example: 150
        applications_count:
          type: integer
          example: 12
        created_at:
          type: string
          format: date-time
          example: "2024-01-15T10:30:00Z"
        updated_at:
          type: string
          format: date-time
          example: "2024-01-15T10:30:00Z"
        user_has_applied:
          type: boolean
          description: Whether the authenticated user has applied for this job
          example: false
        user_has_saved:
          type: boolean
          description: Whether the authenticated user has saved this job
          example: true
        time_remaining:
          type: string
          description: Human-readable time until deadline
          example: "15 days remaining"
        salary_range_formatted:
          type: string
          description: Formatted salary range
          example: "$80,000 - $120,000 USD"

  securitySchemes:
    bearerAuth:
      type: http
      scheme: bearer
      bearerFormat: JWT

security:
  - bearerAuth: []
```

## 9. Monitoring and Quality Metrics

### 9.1 Code Quality Metrics

**SonarQube Configuration**
```properties
# sonar-project.properties
sonar.projectKey=people-of-data
sonar.projectName=People Of Data Platform
sonar.projectVersion=1.0.0

# Source code
sonar.sources=app,config,database,routes
sonar.tests=tests

# Language settings
sonar.php.version=8.1
sonar.javascript.lcov.reportPaths=coverage/lcov.info

# Coverage reports
sonar.php.coverage.reportPaths=coverage.xml
sonar.php.tests.reportPath=tests/reports/phpunit.xml

# Exclusions
sonar.exclusions=**/*.blade.php,bootstrap/**,storage/**,vendor/**,node_modules/**,public/**
sonar.test.exclusions=tests/**

# Quality gates
sonar.qualitygate.wait=true

# Rules
sonar.php.file.suffixes=php
sonar.javascript.file.suffixes=.js,.jsx
sonar.css.file.suffixes=.css,.scss,.sass
```

### 9.2 Performance Monitoring

**Application Performance Monitoring**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware for monitoring application performance.
 */
class PerformanceMonitoring
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $memoryUsage = $endMemory - $startMemory;
        
        // Log performance metrics
        $this->logPerformanceMetrics($request, $response, $executionTime, $memoryUsage);
        
        // Add performance headers for debugging
        if (config('app.debug')) {
            $response->headers->set('X-Execution-Time', round($executionTime, 2) . 'ms');
            $response->headers->set('X-Memory-Usage', $this->formatBytes($memoryUsage));
            $response->headers->set('X-Peak-Memory', $this->formatBytes(memory_get_peak_usage(true)));
        }
        
        return $response;
    }
    
    /**
     * Log performance metrics.
     */
    private function logPerformanceMetrics(
        Request $request,
        Response $response,
        float $executionTime,
        int $memoryUsage
    ): void {
        $metrics = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'route' => $request->route()?->getName(),
            'status_code' => $response->getStatusCode(),
            'execution_time_ms' => round($executionTime, 2),
            'memory_usage_bytes' => $memoryUsage,
            'memory_usage_mb' => round($memoryUsage / 1024 / 1024, 2),
            'peak_memory_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];
        
        // Log slow requests
        if ($executionTime > 1000) { // Slower than 1 second
            Log::warning('Slow request detected', $metrics);
        }
        
        // Log high memory usage
        if ($memoryUsage > 50 * 1024 * 1024) { // More than 50MB
            Log::warning('High memory usage detected', $metrics);
        }
        
        // Log to performance channel
        Log::channel('performance')->info('Request metrics', $metrics);
    }
    
    /**
     * Format bytes to human readable format.
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
```

## 10. Conclusion

This Code Quality Standards document establishes the foundation for maintaining high-quality, secure, and performant code across the People Of Data platform. All team members must adhere to these standards to ensure consistency, maintainability, and reliability of the codebase.

### Key Takeaways:

1. **Consistency**: Follow PSR-12 coding standards and established naming conventions
2. **Testing**: Maintain minimum 80% code coverage with comprehensive unit and feature tests
3. **Security**: Implement proper input validation, authorization, and security best practices
4. **Performance**: Optimize database queries, implement caching, and monitor application performance
5. **Documentation**: Maintain comprehensive PHPDoc comments and API documentation
6. **Quality Gates**: Use automated tools for code quality checks and continuous integration

Regular code reviews and adherence to these standards will ensure the platform remains scalable, secure, and maintainable as it grows.
```

