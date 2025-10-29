# Deployment Guide - People Of Data Platform

## Hostinger Deployment Setup

### Prerequisites
- Hostinger hosting account with PHP 8.1+ support
- MySQL database access
- Domain name configured
- SSL certificate (Let's Encrypt)

### Server Requirements
- **PHP Version**: 8.1 or higher
- **PHP Extensions**: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, cURL, GD
- **MySQL**: 8.0 or higher
- **Storage**: Minimum 1GB for application files
- **Memory**: 256MB PHP memory limit (recommended)

## Environment Configuration

### .env File Setup
```env
# Application
APP_NAME="People Of Data"
APP_ENV=production
APP_KEY=base64:GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=your_email@your-domain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@your-domain.com
MAIL_FROM_NAME="People Of Data"

# OAuth Configuration
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URL=https://your-domain.com/auth/google/callback

LINKEDIN_CLIENT_ID=your_linkedin_client_id
LINKEDIN_CLIENT_SECRET=your_linkedin_client_secret
LINKEDIN_REDIRECT_URL=https://your-domain.com/auth/linkedin/callback

# OpenAI Configuration
OPENAI_API_KEY=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

# File Storage
FILESYSTEM_DISK=local

# Session & Cache
SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=database

# Analytics
GOOGLE_ANALYTICS_ID=your_ga_tracking_id
HOTJAR_ID=your_hotjar_id
```

## Deployment Steps

### 1. Initial Setup
```bash
# Upload project files to Hostinger public_html
# Extract Laravel project to public_html/pod-web/

# Set correct permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 644 .env

# Generate application key
php artisan key:generate
```

### 2. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed initial data
php artisan db:seed

# Create storage link
php artisan storage:link
```

### 3. OAuth Provider Setup

#### Google OAuth Setup
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create new project or select existing
3. Enable Google+ API
4. Create OAuth 2.0 credentials
5. Add authorized redirect URI: `https://your-domain.com/auth/google/callback`
6. Copy Client ID and Secret to .env file

#### LinkedIn OAuth Setup
1. Go to [LinkedIn Developer Portal](https://developer.linkedin.com/)
2. Create new application
3. Add redirect URL: `https://your-domain.com/auth/linkedin/callback`
4. Request access to Sign In with LinkedIn
5. Copy Client ID and Secret to .env file

### 4. File Permissions & Security
```bash
# Set secure permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/

# Secure sensitive files
chmod 600 .env
chmod 600 config/database.php
```

### 5. Hostinger Specific Configuration

#### htaccess Configuration
```apache
# public/.htaccess
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Prevent access to sensitive files
<Files .env>
    Require all denied
</Files>

<Files composer.json>
    Require all denied
</Files>

<Files composer.lock>
    Require all denied
</Files>

<Files package.json>
    Require all denied
</Files>
```

#### PHP Configuration
```ini
# php.ini or .htaccess modifications
php_value memory_limit 256M
php_value max_execution_time 300
php_value max_input_vars 3000
php_value upload_max_filesize 10M
php_value post_max_size 10M
```

## SSL Certificate Setup

### Let's Encrypt (Free)
1. Access Hostinger control panel
2. Navigate to SSL section
3. Enable Let's Encrypt SSL certificate
4. Force HTTPS redirect

### Custom SSL Certificate
1. Purchase SSL certificate
2. Upload certificate files via control panel
3. Configure SSL in Hostinger dashboard

## Database Optimization

### MySQL Configuration
```sql
# Recommended MySQL settings for shared hosting
set global innodb_buffer_pool_size = 128M;
set global query_cache_size = 32M;
set global max_connections = 100;
```

### Database Maintenance
```bash
# Regular maintenance commands
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear caches when needed
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Performance Optimization

### Application Optimization
```bash
# Production optimizations
composer install --no-dev --optimize-autoloader
npm run production

# Laravel optimizations
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Caching Strategy
- **Config Cache**: Cache all configuration files
- **Route Cache**: Cache all route definitions
- **View Cache**: Cache compiled Blade templates
- **Query Cache**: Use Redis or file cache for database queries

### Image Optimization
- Automatic image compression on upload
- WebP format conversion when supported
- CDN integration for static assets (future enhancement)

## Monitoring & Maintenance

### Log Management
```bash
# Monitor Laravel logs
tail -f storage/logs/laravel.log

# Log rotation
php artisan log:clear
```

### Backup Strategy
```bash
# Database backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# File backup
tar -czf files_backup_$(date +%Y%m%d).tar.gz storage/ public/uploads/
```

### Health Checks
- Monitor application response times
- Check database connection status
- Verify SSL certificate validity
- Monitor disk space usage
- Check error rates in logs

## Security Measures

### Application Security
- Regular Laravel framework updates
- Dependency vulnerability scanning
- Input validation and sanitization
- SQL injection prevention (Eloquent ORM)
- XSS protection (Blade templating)
- CSRF protection for forms

### Server Security
- Regular PHP updates
- Secure file permissions
- Disabled dangerous PHP functions
- Web application firewall (if available)
- Regular security audits

## Troubleshooting

### Common Issues

#### 500 Internal Server Error
1. Check file permissions (755 for directories, 644 for files)
2. Verify .env file configuration
3. Clear all caches
4. Check error logs

#### Database Connection Issues
1. Verify database credentials in .env
2. Check database server status
3. Confirm database exists and user has permissions
4. Test connection manually

#### OAuth Authentication Issues
1. Verify redirect URLs match exactly
2. Check API credentials in .env
3. Ensure OAuth applications are approved
4. Test with browser developer tools

#### File Upload Issues
1. Check upload directory permissions
2. Verify PHP upload limits
3. Confirm disk space availability
4. Check file size restrictions

### Performance Issues
1. Enable Laravel caching
2. Optimize database queries
3. Compress images and assets
4. Monitor server resources
5. Consider CDN for static files

## Scaling Considerations

### Traffic Growth
- Monitor server resources
- Consider VPS upgrade path
- Implement caching strategies
- Optimize database queries
- CDN for static assets

### Feature Expansion
- Modular architecture for new features
- API-first approach for mobile apps
- Microservices consideration for high load
- Database optimization and indexing

Last Updated: January 2025
