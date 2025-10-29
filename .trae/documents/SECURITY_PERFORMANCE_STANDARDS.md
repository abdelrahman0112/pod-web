# Security and Performance Standards - People Of Data Platform

## 1. Security Standards

### 1.1 Authentication Security

**Multi-Factor Authentication (MFA)**
- Implement TOTP-based 2FA for all user accounts
- Require MFA for admin and client users
- Support backup codes for account recovery
- SMS fallback for users without authenticator apps

**Password Security**
```php
// Password requirements
'password' => [
    'required',
    'string',
    'min:8',
    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
    'confirmed'
]

// Password hashing
class User extends Authenticatable
{
    protected $casts = [
        'password' => 'hashed',
    ];
    
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
```

**Session Security**
```php
// config/session.php
'lifetime' => 120, // 2 hours
'expire_on_close' => true,
'encrypt' => true,
'http_only' => true,
'same_site' => 'strict',
'secure' => env('SESSION_SECURE_COOKIE', true),
```

**OAuth Security**
```php
// Secure OAuth implementation
class OAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)
            ->stateless()
            ->with(['state' => Str::random(40)])
            ->redirect();
    }
    
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            
            // Validate OAuth response
            if (!$socialUser->getEmail()) {
                throw new Exception('Email not provided by OAuth provider');
            }
            
            // Create or update user
            $user = User::updateOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'name' => $socialUser->getName(),
                    'email_verified_at' => now(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]
            );
            
            Auth::login($user);
            
            return redirect()->intended('/dashboard');
        } catch (Exception $e) {
            Log::error('OAuth authentication failed', [
                'provider' => $provider,
                'error' => $e->getMessage()
            ]);
            
            return redirect('/login')->with('error', 'Authentication failed');
        }
    }
}
```

### 1.2 Authorization & Access Control

**Role-Based Access Control (RBAC)**
```php
// User roles and permissions
class User extends Authenticatable
{
    public function hasRole($role)
    {
        return $this->role === $role;
    }
    
    public function hasPermission($permission)
    {
        $permissions = [
            'super_admin' => ['*'],
            'admin' => [
                'users.view', 'users.edit', 'content.moderate',
                'jobs.view', 'events.view', 'analytics.view'
            ],
            'client' => [
                'jobs.create', 'jobs.edit', 'jobs.delete',
                'events.create', 'events.edit', 'events.delete',
                'hackathons.create', 'hackathons.edit'
            ],
            'user' => [
                'jobs.apply', 'events.register', 'posts.create',
                'chat.send', 'profile.edit'
            ]
        ];
        
        $userPermissions = $permissions[$this->role] ?? [];
        
        return in_array('*', $userPermissions) || in_array($permission, $userPermissions);
    }
}

// Middleware for permission checking
class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {
        if (!auth()->user()?->hasPermission($permission)) {
            abort(403, 'Insufficient permissions');
        }
        
        return $next($request);
    }
}
```

**Resource-Level Authorization**
```php
// Policy-based authorization
class JobPolicy
{
    public function view(User $user, Job $job)
    {
        return $job->status === 'active' || 
               $user->id === $job->user_id || 
               $user->hasRole('admin');
    }
    
    public function update(User $user, Job $job)
    {
        return $user->id === $job->user_id || $user->hasRole('admin');
    }
    
    public function delete(User $user, Job $job)
    {
        return $user->id === $job->user_id || $user->hasRole('admin');
    }
    
    public function apply(User $user, Job $job)
    {
        return $user->email_verified_at !== null &&
               $job->status === 'active' &&
               $job->deadline > now() &&
               !$job->applications()->where('user_id', $user->id)->exists();
    }
}
```

### 1.3 Input Validation & Sanitization

**Comprehensive Input Validation**
```php
class CreateJobRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s\-\.\(\)]+$/' // Alphanumeric with basic punctuation
            ],
            'description' => [
                'required',
                'string',
                'min:100',
                'max:5000'
            ],
            'salary_min' => [
                'nullable',
                'integer',
                'min:0',
                'max:1000000'
            ],
            'salary_max' => [
                'nullable',
                'integer',
                'gt:salary_min',
                'max:1000000'
            ],
            'requirements' => [
                'required',
                'array',
                'max:20'
            ],
            'requirements.*' => [
                'string',
                'max:100'
            ],
            'deadline' => [
                'required',
                'date',
                'after:today',
                'before:' . now()->addYear()->toDateString()
            ],
            'location_type' => [
                'required',
                'in:remote,onsite,hybrid'
            ],
            'experience_level' => [
                'required',
                'in:entry,junior,mid,senior,lead'
            ]
        ];
    }
    
    public function prepareForValidation()
    {
        $this->merge([
            'title' => strip_tags($this->title),
            'description' => strip_tags($this->description, '<p><br><ul><ol><li><strong><em>'),
        ]);
    }
}
```

**File Upload Security**
```php
class FileUploadService
{
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
    
    public function validateFile(UploadedFile $file)
    {
        // Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new ValidationException('File size exceeds maximum allowed size');
        }
        
        // Check MIME type
        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES)) {
            throw new ValidationException('File type not allowed');
        }
        
        // Check file extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
        if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions)) {
            throw new ValidationException('File extension not allowed');
        }
        
        // Scan for malware (if antivirus service is available)
        if (config('services.antivirus.enabled')) {
            $this->scanForMalware($file);
        }
        
        return true;
    }
    
    public function storeSecurely(UploadedFile $file, string $directory)
    {
        $this->validateFile($file);
        
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, 'private');
        
        // Log file upload
        Log::info('File uploaded', [
            'user_id' => auth()->id(),
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType()
        ]);
        
        return $path;
    }
}
```

### 1.4 Data Protection & Encryption

**Data Encryption**
```php
class EncryptionService
{
    public function encryptSensitiveData(array $data)
    {
        $sensitiveFields = ['phone', 'address', 'national_id'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = encrypt($data[$field]);
            }
        }
        
        return $data;
    }
    
    public function decryptSensitiveData(Model $model)
    {
        $sensitiveFields = ['phone', 'address', 'national_id'];
        
        foreach ($sensitiveFields as $field) {
            if ($model->$field) {
                try {
                    $model->$field = decrypt($model->$field);
                } catch (DecryptException $e) {
                    Log::warning('Failed to decrypt field', [
                        'model' => get_class($model),
                        'field' => $field,
                        'id' => $model->id
                    ]);
                    $model->$field = null;
                }
            }
        }
        
        return $model;
    }
}
```

**Database Security**
```php
// Secure database configuration
// config/database.php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'strict' => true,
    'engine' => null,
    'options' => [
        PDO::ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        PDO::ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ],
],
```

### 1.5 API Security

**Rate Limiting**
```php
// Rate limiting configuration
class RateLimitingService
{
    public function configureRateLimits()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
        
        RateLimiter::for('uploads', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
        
        RateLimiter::for('chat', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()->id);
        });
    }
}
```

**API Token Security**
```php
class ApiTokenService
{
    public function createSecureToken(User $user, array $abilities = ['*'])
    {
        $token = $user->createToken(
            name: 'api-token-' . now()->timestamp,
            abilities: $abilities,
            expiresAt: now()->addDays(30)
        );
        
        // Log token creation
        Log::info('API token created', [
            'user_id' => $user->id,
            'token_id' => $token->accessToken->id,
            'abilities' => $abilities,
            'expires_at' => $token->accessToken->expires_at
        ]);
        
        return $token;
    }
    
    public function revokeExpiredTokens()
    {
        $expiredTokens = PersonalAccessToken::where('expires_at', '<', now())->get();
        
        foreach ($expiredTokens as $token) {
            $token->delete();
        }
        
        Log::info('Expired tokens revoked', ['count' => $expiredTokens->count()]);
    }
}
```

## 2. Performance Standards

### 2.1 Response Time Requirements

**Performance Benchmarks**
- Page load time: < 3 seconds (95th percentile)
- API response time: < 500ms (95th percentile)
- Database query time: < 100ms (average)
- File upload time: < 30 seconds for 5MB files
- Search response time: < 1 second

**Performance Monitoring**
```php
class PerformanceMiddleware
{
    public function handle($request, Closure $next)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        $memoryUsage = $endMemory - $startMemory;
        
        // Log slow requests
        if ($executionTime > 1000) { // > 1 second
            Log::warning('Slow request detected', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'execution_time' => $executionTime,
                'memory_usage' => $memoryUsage,
                'user_id' => auth()->id()
            ]);
        }
        
        // Add performance headers
        $response->headers->set('X-Execution-Time', $executionTime);
        $response->headers->set('X-Memory-Usage', $memoryUsage);
        
        return $response;
    }
}
```

### 2.2 Database Optimization

**Query Optimization**
```php
class OptimizedJobService
{
    public function getJobsWithFilters(array $filters)
    {
        return Job::query()
            ->select(['id', 'title', 'company_id', 'category_id', 'location', 'salary_min', 'salary_max', 'created_at'])
            ->with([
                'company:id,name,logo',
                'category:id,name'
            ])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($filters['category'] ?? null, function ($query, $category) {
                $query->where('category_id', $category);
            })
            ->when($filters['location_type'] ?? null, function ($query, $locationType) {
                $query->where('location_type', $locationType);
            })
            ->when($filters['experience_level'] ?? null, function ($query, $level) {
                $query->where('experience_level', $level);
            })
            ->when($filters['salary_min'] ?? null, function ($query, $salaryMin) {
                $query->where('salary_max', '>=', $salaryMin);
            })
            ->when($filters['salary_max'] ?? null, function ($query, $salaryMax) {
                $query->where('salary_min', '<=', $salaryMax);
            })
            ->where('status', 'active')
            ->where('deadline', '>', now())
            ->orderBy('featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }
}
```

**Database Indexing Strategy**
```sql
-- Jobs table indexes
CREATE INDEX idx_jobs_status_deadline ON jobs(status, deadline);
CREATE INDEX idx_jobs_category_status ON jobs(category_id, status);
CREATE INDEX idx_jobs_location_type ON jobs(location_type);
CREATE INDEX idx_jobs_experience_level ON jobs(experience_level);
CREATE INDEX idx_jobs_salary_range ON jobs(salary_min, salary_max);
CREATE INDEX idx_jobs_featured_created ON jobs(featured, created_at);
CREATE FULLTEXT INDEX idx_jobs_search ON jobs(title, description);

-- Events table indexes
CREATE INDEX idx_events_status_date ON events(status, event_date);
CREATE INDEX idx_events_type_status ON events(event_type, status);
CREATE INDEX idx_events_location ON events(location);

-- Posts table indexes
CREATE INDEX idx_posts_user_created ON posts(user_id, created_at);
CREATE INDEX idx_posts_type_status ON posts(post_type, status);
CREATE FULLTEXT INDEX idx_posts_content ON posts(content);

-- Applications table indexes
CREATE INDEX idx_applications_job_status ON job_applications(job_id, status);
CREATE INDEX idx_applications_user_created ON job_applications(user_id, created_at);
CREATE UNIQUE INDEX idx_applications_unique ON job_applications(user_id, job_id);
```

### 2.3 Caching Strategy

**Multi-Level Caching**
```php
class CacheService
{
    public function getFeaturedJobs()
    {
        return Cache::tags(['jobs', 'featured'])
            ->remember('featured_jobs', 3600, function () {
                return Job::with('company:id,name,logo')
                         ->where('featured', true)
                         ->where('status', 'active')
                         ->limit(6)
                         ->get();
            });
    }
    
    public function getJobCategories()
    {
        return Cache::remember('job_categories', 86400, function () {
            return JobCategory::select('id', 'name', 'slug')
                             ->withCount('jobs')
                             ->orderBy('name')
                             ->get();
        });
    }
    
    public function getUserProfile($userId)
    {
        return Cache::tags(['users', "user_{$userId}"])
            ->remember("user_profile_{$userId}", 1800, function () use ($userId) {
                return User::with([
                    'profile',
                    'skills',
                    'experiences'
                ])->find($userId);
            });
    }
    
    public function invalidateUserCache($userId)
    {
        Cache::tags(["user_{$userId}"])->flush();
    }
    
    public function invalidateJobsCache()
    {
        Cache::tags(['jobs'])->flush();
    }
}
```

**Redis Configuration**
```php
// config/cache.php
'redis' => [
    'driver' => 'redis',
    'connection' => 'cache',
    'lock_connection' => 'default',
],

// config/database.php
'redis' => [
    'client' => env('REDIS_CLIENT', 'phpredis'),
    
    'options' => [
        'cluster' => env('REDIS_CLUSTER', 'redis'),
        'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
    ],
    
    'default' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_DB', '0'),
    ],
    
    'cache' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', '6379'),
        'database' => env('REDIS_CACHE_DB', '1'),
    ],
],
```

### 2.4 Asset Optimization

**Frontend Performance**
```javascript
// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs', 'axios'],
                    utils: ['lodash']
                }
            }
        },
        chunkSizeWarningLimit: 1000,
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true
            }
        }
    },
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
        },
    },
});
```

**Image Optimization**
```php
class ImageOptimizationService
{
    public function optimizeImage(UploadedFile $file, array $sizes = [])
    {
        $image = Image::make($file);
        $optimizedImages = [];
        
        // Original size optimization
        $optimized = $image->encode('jpg', 85);
        $filename = Str::uuid() . '.jpg';
        Storage::put("images/{$filename}", $optimized);
        $optimizedImages['original'] = $filename;
        
        // Generate different sizes
        foreach ($sizes as $size => $dimensions) {
            $resized = $image->fit($dimensions['width'], $dimensions['height']);
            $resizedFilename = Str::uuid() . "_{$size}.jpg";
            Storage::put("images/{$resizedFilename}", $resized->encode('jpg', 85));
            $optimizedImages[$size] = $resizedFilename;
        }
        
        return $optimizedImages;
    }
    
    public function generateWebP($imagePath)
    {
        $image = Image::make(Storage::path($imagePath));
        $webpPath = str_replace('.jpg', '.webp', $imagePath);
        
        $webpImage = $image->encode('webp', 85);
        Storage::put($webpPath, $webpImage);
        
        return $webpPath;
    }
}
```

### 2.5 Monitoring & Analytics

**Performance Monitoring**
```php
class PerformanceMonitor
{
    public function trackPageLoad($page, $loadTime, $userId = null)
    {
        DB::table('performance_metrics')->insert([
            'metric_type' => 'page_load',
            'page' => $page,
            'value' => $loadTime,
            'user_id' => $userId,
            'created_at' => now()
        ]);
        
        // Alert if load time is too high
        if ($loadTime > 5000) { // 5 seconds
            $this->sendPerformanceAlert($page, $loadTime);
        }
    }
    
    public function trackDatabaseQuery($query, $executionTime)
    {
        if ($executionTime > 100) { // 100ms
            Log::warning('Slow database query', [
                'query' => $query,
                'execution_time' => $executionTime,
                'user_id' => auth()->id()
            ]);
        }
    }
    
    public function generatePerformanceReport()
    {
        $metrics = DB::table('performance_metrics')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('
                metric_type,
                page,
                AVG(value) as avg_value,
                MAX(value) as max_value,
                MIN(value) as min_value,
                COUNT(*) as count
            ')
            ->groupBy('metric_type', 'page')
            ->get();
            
        return $metrics;
    }
}
```

**Health Checks**
```php
class HealthCheckService
{
    public function performHealthCheck()
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'redis' => $this->checkRedis(),
            'storage' => $this->checkStorage(),
            'queue' => $this->checkQueue(),
            'external_apis' => $this->checkExternalAPIs()
        ];
        
        $overallHealth = collect($checks)->every(fn($check) => $check['status'] === 'healthy');
        
        return [
            'status' => $overallHealth ? 'healthy' : 'unhealthy',
            'checks' => $checks,
            'timestamp' => now()->toISOString()
        ];
    }
    
    private function checkDatabase()
    {
        try {
            DB::select('SELECT 1');
            return ['status' => 'healthy', 'message' => 'Database connection successful'];
        } catch (Exception $e) {
            return ['status' => 'unhealthy', 'message' => 'Database connection failed: ' . $e->getMessage()];
        }
    }
    
    private function checkRedis()
    {
        try {
            Cache::put('health_check', 'test', 10);
            $value = Cache::get('health_check');
            return $value === 'test' 
                ? ['status' => 'healthy', 'message' => 'Redis connection successful']
                : ['status' => 'unhealthy', 'message' => 'Redis read/write failed'];
        } catch (Exception $e) {
            return ['status' => 'unhealthy', 'message' => 'Redis connection failed: ' . $e->getMessage()];
        }
    }
}
```

---

**Last Updated**: January 2025
**Version**: 1.0
**Maintained By**: Security & DevOps Team