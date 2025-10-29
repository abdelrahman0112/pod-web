# Database Design Standards - People Of Data Platform

## 1. Database Schema Design Principles

### 1.1 Naming Conventions

**Table Names**
- Use snake_case for all table names
- Use plural nouns (e.g., `users`, `jobs`, `events`)
- Avoid abbreviations unless commonly understood
- Use descriptive names that clearly indicate the table's purpose

**Column Names**
- Use snake_case for all column names
- Use descriptive names that clearly indicate the column's purpose
- Avoid reserved keywords
- Use consistent naming patterns across tables

**Index Names**
- Format: `idx_{table}_{columns}` for regular indexes
- Format: `unq_{table}_{columns}` for unique indexes
- Format: `fk_{table}_{referenced_table}` for foreign key indexes

**Examples**
```sql
-- Good table and column names
CREATE TABLE job_applications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    job_id BIGINT UNSIGNED NOT NULL,
    cover_letter TEXT,
    expected_salary DECIMAL(10,2),
    application_status ENUM('pending', 'reviewed', 'shortlisted', 'rejected', 'hired') DEFAULT 'pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Good index names
CREATE INDEX idx_job_applications_user_id ON job_applications(user_id);
CREATE INDEX idx_job_applications_job_id ON job_applications(job_id);
CREATE UNIQUE INDEX unq_job_applications_user_job ON job_applications(user_id, job_id);
CREATE INDEX idx_job_applications_status ON job_applications(application_status);
```

### 1.2 Data Types and Constraints

**Primary Keys**
- Use `BIGINT UNSIGNED AUTO_INCREMENT` for primary keys
- Always name the primary key column `id`
- Use UUIDs only when necessary for distributed systems

**Foreign Keys**
- Use `BIGINT UNSIGNED` for foreign key columns
- Name foreign key columns with `_id` suffix (e.g., `user_id`, `job_id`)
- Always add foreign key constraints with appropriate cascade actions

**String Fields**
- Use `VARCHAR` with appropriate length limits
- Use `TEXT` for long content that may exceed 255 characters
- Use `ENUM` for predefined string values with limited options

**Numeric Fields**
- Use `INT` or `BIGINT` for whole numbers
- Use `DECIMAL` for monetary values and precise calculations
- Use `FLOAT` or `DOUBLE` only when precision is not critical

**Date and Time Fields**
- Use `TIMESTAMP` for created_at and updated_at fields
- Use `DATE` for date-only fields
- Use `DATETIME` when timezone information is not needed
- Always include timezone information in application logic

**Boolean Fields**
- Use `BOOLEAN` or `TINYINT(1)` for boolean values
- Provide clear default values
- Use descriptive names (e.g., `is_active`, `has_verified_email`)

### 1.3 Standard Table Structure

**Required Fields for All Tables**
```sql
CREATE TABLE example_table (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Table-specific fields here
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**Soft Delete Support**
```sql
-- Add to tables that require soft delete
deleted_at TIMESTAMP NULL DEFAULT NULL,

-- Index for soft delete queries
CREATE INDEX idx_example_table_deleted_at ON example_table(deleted_at);
```

## 2. Core Database Schema

### 2.1 User Management Tables

**Users Table**
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'client', 'admin', 'super_admin') DEFAULT 'user',
    status ENUM('active', 'inactive', 'suspended', 'banned') DEFAULT 'active',
    provider VARCHAR(50) NULL COMMENT 'OAuth provider (google, linkedin)',
    provider_id VARCHAR(255) NULL COMMENT 'OAuth provider user ID',
    two_factor_secret TEXT NULL,
    two_factor_recovery_codes TEXT NULL,
    remember_token VARCHAR(100) NULL,
    profile_completed_at TIMESTAMP NULL,
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_users_email (email),
    INDEX idx_users_role (role),
    INDEX idx_users_status (status),
    INDEX idx_users_provider (provider, provider_id),
    INDEX idx_users_last_login (last_login_at)
);
```

**User Profiles Table**
```sql
CREATE TABLE user_profiles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    phone VARCHAR(20) NULL,
    gender ENUM('male', 'female', 'other', 'prefer_not_to_say') NULL,
    date_of_birth DATE NULL,
    bio TEXT NULL,
    location VARCHAR(255) NULL,
    website_url VARCHAR(500) NULL,
    linkedin_url VARCHAR(500) NULL,
    github_url VARCHAR(500) NULL,
    twitter_url VARCHAR(500) NULL,
    avatar_path VARCHAR(500) NULL,
    experience_level ENUM('entry', 'junior', 'mid', 'senior', 'lead') NULL,
    current_position VARCHAR(255) NULL,
    current_company VARCHAR(255) NULL,
    is_available_for_hire BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE INDEX unq_user_profiles_user_id (user_id),
    INDEX idx_user_profiles_location (location),
    INDEX idx_user_profiles_experience_level (experience_level),
    INDEX idx_user_profiles_available_for_hire (is_available_for_hire)
);
```

**User Skills Table**
```sql
CREATE TABLE skills (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_skills_category (category),
    INDEX idx_skills_active (is_active)
);

CREATE TABLE user_skills (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    skill_id BIGINT UNSIGNED NOT NULL,
    proficiency_level ENUM('beginner', 'intermediate', 'advanced', 'expert') DEFAULT 'intermediate',
    years_of_experience TINYINT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE,
    UNIQUE INDEX unq_user_skills_user_skill (user_id, skill_id),
    INDEX idx_user_skills_proficiency (proficiency_level),
    INDEX idx_user_skills_experience (years_of_experience)
);
```

### 2.2 Job Management Tables

**Job Categories Table**
```sql
CREATE TABLE job_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT NULL,
    icon VARCHAR(100) NULL,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_job_categories_slug (slug),
    INDEX idx_job_categories_active (is_active),
    INDEX idx_job_categories_sort (sort_order)
);
```

**Companies Table**
```sql
CREATE TABLE companies (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL COMMENT 'Company owner/representative',
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT NULL,
    website_url VARCHAR(500) NULL,
    logo_path VARCHAR(500) NULL,
    industry VARCHAR(100) NULL,
    company_size ENUM('1-10', '11-50', '51-200', '201-500', '501-1000', '1000+') NULL,
    location VARCHAR(255) NULL,
    founded_year YEAR NULL,
    is_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_companies_slug (slug),
    INDEX idx_companies_industry (industry),
    INDEX idx_companies_size (company_size),
    INDEX idx_companies_verified (is_verified)
);
```

**Jobs Table**
```sql
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL COMMENT 'Job poster',
    company_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    requirements JSON NULL COMMENT 'Array of job requirements',
    responsibilities JSON NULL COMMENT 'Array of job responsibilities',
    benefits JSON NULL COMMENT 'Array of job benefits',
    skills_required JSON NULL COMMENT 'Array of required skill IDs',
    experience_level ENUM('entry', 'junior', 'mid', 'senior', 'lead') NOT NULL,
    employment_type ENUM('full_time', 'part_time', 'contract', 'internship', 'freelance') NOT NULL,
    location_type ENUM('remote', 'onsite', 'hybrid') NOT NULL,
    location VARCHAR(255) NULL,
    salary_min DECIMAL(10,2) NULL,
    salary_max DECIMAL(10,2) NULL,
    salary_currency VARCHAR(3) DEFAULT 'EGP',
    is_salary_negotiable BOOLEAN DEFAULT FALSE,
    deadline DATE NOT NULL,
    status ENUM('draft', 'active', 'paused', 'closed', 'expired') DEFAULT 'draft',
    is_featured BOOLEAN DEFAULT FALSE,
    featured_until TIMESTAMP NULL,
    views_count INT UNSIGNED DEFAULT 0,
    applications_count INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES job_categories(id) ON DELETE RESTRICT,
    
    INDEX idx_jobs_slug (slug),
    INDEX idx_jobs_status_deadline (status, deadline),
    INDEX idx_jobs_category_status (category_id, status),
    INDEX idx_jobs_location_type (location_type),
    INDEX idx_jobs_experience_level (experience_level),
    INDEX idx_jobs_employment_type (employment_type),
    INDEX idx_jobs_salary_range (salary_min, salary_max),
    INDEX idx_jobs_featured (is_featured, featured_until),
    INDEX idx_jobs_created_at (created_at),
    FULLTEXT INDEX ft_jobs_search (title, description)
);
```

**Job Applications Table**
```sql
CREATE TABLE job_applications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    job_id BIGINT UNSIGNED NOT NULL,
    cover_letter TEXT NULL,
    resume_path VARCHAR(500) NULL,
    expected_salary DECIMAL(10,2) NULL,
    available_from DATE NULL,
    additional_info TEXT NULL,
    status ENUM('pending', 'reviewed', 'shortlisted', 'interviewed', 'rejected', 'hired') DEFAULT 'pending',
    reviewed_at TIMESTAMP NULL,
    reviewed_by BIGINT UNSIGNED NULL,
    notes TEXT NULL COMMENT 'Internal notes by recruiter',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL,
    
    UNIQUE INDEX unq_job_applications_user_job (user_id, job_id),
    INDEX idx_job_applications_job_status (job_id, status),
    INDEX idx_job_applications_user_created (user_id, created_at),
    INDEX idx_job_applications_status (status),
    INDEX idx_job_applications_reviewed (reviewed_at)
);
```

### 2.3 Events Management Tables

**Event Categories Table**
```sql
CREATE TABLE event_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT NULL,
    color VARCHAR(7) DEFAULT '#3B82F6' COMMENT 'Hex color code',
    icon VARCHAR(100) NULL,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_event_categories_slug (slug),
    INDEX idx_event_categories_active (is_active),
    INDEX idx_event_categories_sort (sort_order)
);
```

**Events Table**
```sql
CREATE TABLE events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL COMMENT 'Event organizer',
    category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    agenda JSON NULL COMMENT 'Event agenda/schedule',
    event_type ENUM('workshop', 'seminar', 'conference', 'meetup', 'webinar', 'networking') NOT NULL,
    format ENUM('online', 'offline', 'hybrid') NOT NULL,
    event_date DATETIME NOT NULL,
    end_date DATETIME NULL,
    timezone VARCHAR(50) DEFAULT 'Africa/Cairo',
    location VARCHAR(500) NULL,
    online_link VARCHAR(500) NULL,
    capacity INT UNSIGNED NULL COMMENT 'Maximum attendees',
    price DECIMAL(8,2) DEFAULT 0.00,
    currency VARCHAR(3) DEFAULT 'EGP',
    registration_deadline DATETIME NULL,
    banner_path VARCHAR(500) NULL,
    status ENUM('draft', 'published', 'cancelled', 'completed') DEFAULT 'draft',
    is_featured BOOLEAN DEFAULT FALSE,
    featured_until TIMESTAMP NULL,
    views_count INT UNSIGNED DEFAULT 0,
    registrations_count INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES event_categories(id) ON DELETE RESTRICT,
    
    INDEX idx_events_slug (slug),
    INDEX idx_events_status_date (status, event_date),
    INDEX idx_events_category_status (category_id, status),
    INDEX idx_events_type_format (event_type, format),
    INDEX idx_events_date_range (event_date, end_date),
    INDEX idx_events_featured (is_featured, featured_until),
    INDEX idx_events_registration_deadline (registration_deadline),
    FULLTEXT INDEX ft_events_search (title, description)
);
```

**Event Registrations Table**
```sql
CREATE TABLE event_registrations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    event_id BIGINT UNSIGNED NOT NULL,
    registration_type ENUM('free', 'paid') NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    payment_amount DECIMAL(8,2) DEFAULT 0.00,
    payment_reference VARCHAR(255) NULL,
    ticket_code VARCHAR(50) UNIQUE NULL,
    additional_info JSON NULL COMMENT 'Custom registration fields',
    attendance_status ENUM('registered', 'attended', 'no_show') DEFAULT 'registered',
    checked_in_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    
    UNIQUE INDEX unq_event_registrations_user_event (user_id, event_id),
    INDEX idx_event_registrations_event_status (event_id, attendance_status),
    INDEX idx_event_registrations_payment (payment_status),
    INDEX idx_event_registrations_ticket (ticket_code)
);
```

### 2.4 Social Features Tables

**Posts Table**
```sql
CREATE TABLE posts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    post_type ENUM('text', 'image', 'url', 'poll') NOT NULL,
    content TEXT NOT NULL,
    url VARCHAR(500) NULL COMMENT 'For URL posts',
    url_title VARCHAR(255) NULL COMMENT 'Extracted URL title',
    url_description TEXT NULL COMMENT 'Extracted URL description',
    url_image VARCHAR(500) NULL COMMENT 'Extracted URL image',
    images JSON NULL COMMENT 'Array of image paths for image posts',
    poll_options JSON NULL COMMENT 'Poll options with vote counts',
    poll_expires_at TIMESTAMP NULL,
    hashtags JSON NULL COMMENT 'Array of hashtags',
    status ENUM('draft', 'published', 'hidden', 'deleted') DEFAULT 'published',
    is_pinned BOOLEAN DEFAULT FALSE,
    likes_count INT UNSIGNED DEFAULT 0,
    comments_count INT UNSIGNED DEFAULT 0,
    shares_count INT UNSIGNED DEFAULT 0,
    views_count INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX idx_posts_user_created (user_id, created_at),
    INDEX idx_posts_type_status (post_type, status),
    INDEX idx_posts_status_created (status, created_at),
    INDEX idx_posts_pinned (is_pinned),
    FULLTEXT INDEX ft_posts_content (content)
);
```

**Post Interactions Table**
```sql
CREATE TABLE post_interactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    post_id BIGINT UNSIGNED NOT NULL,
    interaction_type ENUM('like', 'share', 'bookmark') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    
    UNIQUE INDEX unq_post_interactions_user_post_type (user_id, post_id, interaction_type),
    INDEX idx_post_interactions_post_type (post_id, interaction_type),
    INDEX idx_post_interactions_user_type (user_id, interaction_type)
);
```

**Comments Table**
```sql
CREATE TABLE comments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    post_id BIGINT UNSIGNED NOT NULL,
    parent_id BIGINT UNSIGNED NULL COMMENT 'For threaded comments',
    content TEXT NOT NULL,
    status ENUM('published', 'hidden', 'deleted') DEFAULT 'published',
    likes_count INT UNSIGNED DEFAULT 0,
    replies_count INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE,
    
    INDEX idx_comments_post_created (post_id, created_at),
    INDEX idx_comments_user_created (user_id, created_at),
    INDEX idx_comments_parent (parent_id),
    INDEX idx_comments_status (status)
);
```

## 3. Performance Optimization

### 3.1 Indexing Strategy

**Primary Indexes**
```sql
-- Composite indexes for common query patterns
CREATE INDEX idx_jobs_category_status_deadline ON jobs(category_id, status, deadline);
CREATE INDEX idx_events_category_status_date ON events(category_id, status, event_date);
CREATE INDEX idx_posts_user_status_created ON posts(user_id, status, created_at);

-- Covering indexes for frequently accessed columns
CREATE INDEX idx_jobs_listing_cover ON jobs(status, deadline, category_id, location_type, experience_level) 
    INCLUDE (id, title, company_id, salary_min, salary_max, created_at);
```

**Full-Text Search Indexes**
```sql
-- Full-text search for jobs
CREATE FULLTEXT INDEX ft_jobs_search ON jobs(title, description);

-- Full-text search for events
CREATE FULLTEXT INDEX ft_events_search ON events(title, description);

-- Full-text search for posts
CREATE FULLTEXT INDEX ft_posts_search ON posts(content);

-- Full-text search for users
CREATE FULLTEXT INDEX ft_users_search ON users(name, email);
```

### 3.2 Partitioning Strategy

**Time-Based Partitioning for Large Tables**
```sql
-- Partition posts table by month
ALTER TABLE posts PARTITION BY RANGE (YEAR(created_at) * 100 + MONTH(created_at)) (
    PARTITION p202401 VALUES LESS THAN (202402),
    PARTITION p202402 VALUES LESS THAN (202403),
    PARTITION p202403 VALUES LESS THAN (202404),
    -- Add more partitions as needed
    PARTITION p_future VALUES LESS THAN MAXVALUE
);

-- Partition analytics tables by date
CREATE TABLE analytics_events (
    id BIGINT UNSIGNED AUTO_INCREMENT,
    user_id BIGINT UNSIGNED,
    event_type VARCHAR(50),
    event_data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (id, created_at),
    INDEX idx_analytics_user_type (user_id, event_type),
    INDEX idx_analytics_type_created (event_type, created_at)
) PARTITION BY RANGE (TO_DAYS(created_at)) (
    PARTITION p_2024_01 VALUES LESS THAN (TO_DAYS('2024-02-01')),
    PARTITION p_2024_02 VALUES LESS THAN (TO_DAYS('2024-03-01')),
    -- Add monthly partitions
);
```

### 3.3 Query Optimization Guidelines

**Efficient Query Patterns**
```sql
-- Good: Use covering indexes
SELECT id, title, company_id, salary_min, salary_max, created_at 
FROM jobs 
WHERE status = 'active' AND deadline > NOW() 
ORDER BY created_at DESC 
LIMIT 20;

-- Good: Use proper JOIN conditions
SELECT j.id, j.title, c.name as company_name, jc.name as category_name
FROM jobs j
INNER JOIN companies c ON j.company_id = c.id
INNER JOIN job_categories jc ON j.category_id = jc.id
WHERE j.status = 'active' AND j.deadline > NOW();

-- Good: Use EXISTS for better performance
SELECT u.id, u.name, u.email
FROM users u
WHERE EXISTS (
    SELECT 1 FROM job_applications ja 
    WHERE ja.user_id = u.id AND ja.status = 'pending'
);

-- Avoid: N+1 queries (use eager loading in application)
-- Avoid: SELECT * (specify needed columns)
-- Avoid: Functions in WHERE clauses on indexed columns
```

## 4. Data Integrity and Constraints

### 4.1 Foreign Key Constraints

**Cascade Rules**
```sql
-- User deletion cascades to related data
ALTER TABLE user_profiles ADD CONSTRAINT fk_user_profiles_user_id 
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

ALTER TABLE job_applications ADD CONSTRAINT fk_job_applications_user_id 
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- Job deletion cascades to applications
ALTER TABLE job_applications ADD CONSTRAINT fk_job_applications_job_id 
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE;

-- Category deletion restricted if jobs exist
ALTER TABLE jobs ADD CONSTRAINT fk_jobs_category_id 
    FOREIGN KEY (category_id) REFERENCES job_categories(id) ON DELETE RESTRICT;
```

### 4.2 Check Constraints

**Data Validation at Database Level**
```sql
-- Salary validation
ALTER TABLE jobs ADD CONSTRAINT chk_jobs_salary_range 
    CHECK (salary_max IS NULL OR salary_min IS NULL OR salary_max >= salary_min);

-- Date validation
ALTER TABLE jobs ADD CONSTRAINT chk_jobs_deadline 
    CHECK (deadline >= DATE(created_at));

ALTER TABLE events ADD CONSTRAINT chk_events_date_range 
    CHECK (end_date IS NULL OR end_date >= event_date);

-- Capacity validation
ALTER TABLE events ADD CONSTRAINT chk_events_capacity 
    CHECK (capacity IS NULL OR capacity > 0);

-- Price validation
ALTER TABLE events ADD CONSTRAINT chk_events_price 
    CHECK (price >= 0);
```

### 4.3 Unique Constraints

**Business Logic Constraints**
```sql
-- Prevent duplicate applications
ALTER TABLE job_applications ADD CONSTRAINT unq_job_applications_user_job 
    UNIQUE (user_id, job_id);

-- Prevent duplicate event registrations
ALTER TABLE event_registrations ADD CONSTRAINT unq_event_registrations_user_event 
    UNIQUE (user_id, event_id);

-- Prevent duplicate skill assignments
ALTER TABLE user_skills ADD CONSTRAINT unq_user_skills_user_skill 
    UNIQUE (user_id, skill_id);

-- Ensure unique slugs
ALTER TABLE jobs ADD CONSTRAINT unq_jobs_slug UNIQUE (slug);
ALTER TABLE events ADD CONSTRAINT unq_events_slug UNIQUE (slug);
```

## 5. Data Migration and Versioning

### 5.1 Migration Best Practices

**Safe Migration Patterns**
```php
// Good: Add columns with default values
Schema::table('users', function (Blueprint $table) {
    $table->timestamp('last_login_at')->nullable()->after('remember_token');
    $table->index('last_login_at');
});

// Good: Create new tables with all constraints
Schema::create('user_preferences', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->json('notification_settings')->nullable();
    $table->json('privacy_settings')->nullable();
    $table->timestamps();
    
    $table->unique('user_id');
});

// Good: Rename columns safely
Schema::table('jobs', function (Blueprint $table) {
    $table->renameColumn('job_type', 'employment_type');
});

// Good: Drop constraints before dropping columns
Schema::table('jobs', function (Blueprint $table) {
    $table->dropForeign(['old_category_id']);
    $table->dropColumn('old_category_id');
});
```

**Data Migration Scripts**
```php
// Migrate existing data when changing structure
class MigrateJobSalaryFormat extends Migration
{
    public function up()
    {
        // Add new columns
        Schema::table('jobs', function (Blueprint $table) {
            $table->decimal('salary_min', 10, 2)->nullable()->after('location');
            $table->decimal('salary_max', 10, 2)->nullable()->after('salary_min');
            $table->string('salary_currency', 3)->default('EGP')->after('salary_max');
        });
        
        // Migrate existing salary data
        DB::table('jobs')->whereNotNull('salary_range')->chunk(100, function ($jobs) {
            foreach ($jobs as $job) {
                $salaryRange = explode('-', $job->salary_range);
                if (count($salaryRange) === 2) {
                    DB::table('jobs')->where('id', $job->id)->update([
                        'salary_min' => trim($salaryRange[0]),
                        'salary_max' => trim($salaryRange[1]),
                    ]);
                }
            }
        });
        
        // Drop old column
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn('salary_range');
        });
    }
    
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('salary_range')->nullable();
        });
        
        // Reverse migration logic here
        
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['salary_min', 'salary_max', 'salary_currency']);
        });
    }
}
```

## 6. Backup and Recovery

### 6.1 Backup Strategy

**Automated Backup Script**
```bash
#!/bin/bash
# Database backup script

DB_NAME="people_of_data"
DB_USER="backup_user"
DB_PASS="secure_password"
BACKUP_DIR="/var/backups/mysql"
DATE=$(date +"%Y%m%d_%H%M%S")

# Create backup directory if it doesn't exist
mkdir -p $BACKUP_DIR

# Full backup
mysqldump --user=$DB_USER --password=$DB_PASS \
    --single-transaction --routines --triggers \
    $DB_NAME > $BACKUP_DIR/full_backup_$DATE.sql

# Compress backup
gzip $BACKUP_DIR/full_backup_$DATE.sql

# Remove backups older than 30 days
find $BACKUP_DIR -name "full_backup_*.sql.gz" -mtime +30 -delete

# Log backup completion
echo "$(date): Backup completed - full_backup_$DATE.sql.gz" >> /var/log/mysql_backup.log
```

**Point-in-Time Recovery Setup**
```sql
-- Enable binary logging for point-in-time recovery
-- Add to my.cnf
[mysqld]
log-bin=mysql-bin
binlog-format=ROW
expire_logs_days=7
max_binlog_size=100M
sync_binlog=1
```

### 6.2 Recovery Procedures

**Recovery Commands**
```bash
# Restore from full backup
mysql -u root -p people_of_data < full_backup_20240115_120000.sql

# Point-in-time recovery
mysqlbinlog --start-datetime="2024-01-15 12:00:00" \
    --stop-datetime="2024-01-15 14:30:00" \
    mysql-bin.000001 | mysql -u root -p people_of_data

# Verify data integrity after recovery
mysqlcheck --user=root --password --check --all-databases
```

---

**Last Updated**: January 2025
**Version**: 1.0
**Maintained By**: Database Team