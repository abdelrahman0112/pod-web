# People Of Data Platform - Project Rules & Guidelines

## 1. Project Overview & Mission

### Core Mission
Connect data science and AI professionals in Egypt and MENA region, provide career opportunities, and foster community growth through events, hackathons, job postings, and educational content.

### Target Audience
- Data Scientists, AI Engineers, ML Engineers
- Data Analysts, Business Intelligence professionals
- Students and fresh graduates in data/AI fields
- Business owners and companies seeking data talent
- Tech recruiters and HR professionals

### Platform Objectives
1. **Career Development**: Job opportunities, internships, skill sharing
2. **Community Building**: Events, networking, knowledge exchange
3. **Innovation**: Hackathons, project collaborations
4. **Knowledge Sharing**: Posts, discussions, mentorship
5. **Business Connections**: Client-freelancer matching

## 2. Development Standards & Guidelines

### Code Quality Standards
- **PHP Standards**: PSR-12 coding standards compliance
- **Testing**: PHPUnit for backend testing with minimum 80% coverage
- **Documentation**: Inline documentation for all methods and classes
- **Linting**: PHP CS Fixer for automated code formatting
- **Version Control**: GitFlow workflow with feature branches

### File Structure Rules
```
app/
├── Http/Controllers/     # Business logic controllers
├── Models/              # Eloquent models
├── Services/            # Business logic services
├── Repositories/        # Data access layer
├── Notifications/       # Email and app notifications
└── Providers/           # Service providers

resources/
├── views/
│   ├── layouts/         # Base layouts
│   ├── components/      # Reusable components
│   └── [feature]/       # Feature-specific views
└── js/                  # Frontend JavaScript
```

### Naming Conventions
- **Controllers**: PascalCase with "Controller" suffix (e.g., `JobListingController`)
- **Models**: PascalCase singular (e.g., `JobListing`)
- **Database Tables**: snake_case plural (e.g., `job_listings`)
- **Routes**: kebab-case (e.g., `/job-listings`)
- **Variables**: camelCase (e.g., `$jobListing`)
- **Constants**: UPPER_SNAKE_CASE (e.g., `MAX_FILE_SIZE`)

## 3. Technical Architecture Rules

### Technology Stack Requirements
- **Backend**: Laravel (Latest LTS) with PHP 8.1+
- **Frontend**: Livewire 3.x + Alpine.js 3.x + Tailwind CSS
- **Database**: MySQL 8.0+ with proper indexing
- **Authentication**: Laravel Sanctum + OAuth (Google, LinkedIn)
- **Chat**: Chatify integration with API support
- **AI**: OpenAI GPT-4 integration
- **File Storage**: Laravel File Storage (local/cloud)
- **Queue System**: Laravel Queues with Database driver

### Database Design Standards

#### Table Design Rules
1. **Primary Keys**: Always use `bigint` auto-increment `id`
2. **Timestamps**: Include `created_at` and `updated_at` on all tables
3. **Soft Deletes**: Use `deleted_at` for user-related data
4. **Foreign Keys**: Use descriptive names (e.g., `user_id`, `job_id`)
5. **Enums**: Use for status fields with predefined values
6. **JSON Fields**: Use for flexible data structures (skills, links, etc.)

#### Indexing Requirements
```sql
-- Performance indexes required
INDEX idx_users_email (email)
INDEX idx_jobs_category_status (category_id, status)
INDEX idx_posts_user_created (user_id, created_at)
INDEX idx_notifications_user_read (user_id, read_at)
```

#### Data Validation Rules
- **Email**: Valid email format, unique across users
- **Phone**: International format with country code
- **Passwords**: Minimum 8 characters, mixed case, numbers
- **File Uploads**: Size limits, type validation, virus scanning
- **User Input**: XSS protection, SQL injection prevention

## 4. User Roles & Permission System

### Role Hierarchy
1. **Super Administrator**: Full system control
2. **Administrator**: Limited administrative control
3. **Client User**: Business account with enhanced capabilities
4. **Regular User**: Standard community member

### Permission Matrix
| Feature | Super Admin | Admin | Client | User |
|---------|-------------|-------|--------|---------|
| User Management | ✅ | ✅* | ❌ | ❌ |
| System Settings | ✅ | ❌ | ❌ | ❌ |
| Create Events | ✅ | ✅ | ✅ | ❌ |
| Post Jobs | ✅ | ✅ | ✅ | ❌ |
| Create Hackathons | ✅ | ✅ | ✅ | ❌ |
| Apply for Jobs | ✅ | ✅ | ✅ | ✅ |
| Join Events | ✅ | ✅ | ✅ | ✅ |
| Post Content | ✅ | ✅ | ✅ | ✅ |
| Moderate Content | ✅ | ✅ | ❌ | ❌ |
| Chat System | ✅ | ✅ | ✅ | ✅ |
| AI Assistant | ✅ | ✅ | ✅ | ✅ |

*Limited to specific areas

### Email Verification Requirements
- **Required for**: Job applications, event registration, hackathon participation, internship applications
- **Not required for**: Browsing, posting, chatting, AI assistant usage
- **Verification process**: Token-based email verification with persistent reminders

## 5. Feature Implementation Rules

### Authentication System
- **Registration Methods**: Email/password, Google OAuth, LinkedIn OAuth
- **Password Requirements**: 8+ characters, uppercase, lowercase, number
- **Account Management**: Soft delete with data retention
- **Profile Privacy**: All information public except birthday

### Events Management
- **Event Types**: On-ground, Online, Hybrid
- **Registration**: One-click with capacity management
- **Tickets**: Digital with QR codes and text codes
- **Chat Rooms**: Auto-created before events (configurable timing)
- **Check-in**: Admin verification system

### Jobs Management
- **Categories**: Admin-configurable dynamic categories
- **Application Process**: Manual information entry (no file uploads)
- **Status Tracking**: Enum-based status system
- **Visibility**: Active, past deadline, archived (never deleted)

### Hackathons System
- **Team Structure**: 2-6 members, leader-based management
- **Participation**: One team per hackathon per user
- **Submissions**: File uploads (50MB limit) + project links
- **Winner Selection**: Manual admin selection with notifications

### Posts & Social Features
- **Post Types**: Text, Image, URL, Poll
- **Image Handling**: 5MB limit, automatic compression, up to 10 images
- **Polls**: Maximum 20 options, up to 3 days duration
- **Hashtags**: Auto-completion, related suggestions, dedicated pages
- **Moderation**: Instant publishing with admin review capability

### Chat System (Chatify)
- **Features**: 1-on-1 chat, real-time messaging, file sharing
- **File Limits**: 10MB per file with type validation
- **Mobile Support**: Full API support for mobile apps

### AI Assistant
- **Integration**: OpenAI GPT-4 with full platform context
- **Usage**: Unlimited for all users
- **Features**: General chat, platform help, career advice

## 6. Security & Performance Standards

### Security Requirements
- **Authentication**: Multi-factor authentication support
- **Authorization**: Role-based access control (RBAC)
- **Data Protection**: Encryption for sensitive data
- **Input Validation**: Server-side validation for all inputs
- **CSRF Protection**: Laravel CSRF tokens on all forms
- **SQL Injection**: Eloquent ORM protection
- **XSS Protection**: Blade template escaping

### Performance Optimization
- **Caching**: Redis/File cache for frequently accessed data
- **Database**: Proper indexing and query optimization
- **Images**: Automatic compression and WebP conversion
- **Lazy Loading**: Implementation for large datasets
- **CDN**: Consider for static assets

### Mobile Responsiveness
- **Design**: Mobile-first responsive design
- **Performance**: Optimized for mobile networks
- **Touch**: Touch-friendly interface elements
- **PWA**: Progressive Web App capabilities

## 7. API Design Rules

### REST API Standards
- **Authentication**: API token-based authentication
- **Rate Limiting**: Implement for security and performance
- **Documentation**: Auto-generated API documentation
- **Versioning**: API versioning strategy (v1, v2, etc.)
- **Response Format**: Consistent JSON response structure

### API Response Structure
```json
{
  "success": true,
  "data": {},
  "message": "Success message",
  "errors": [],
  "meta": {
    "pagination": {},
    "timestamp": "2025-01-01T00:00:00Z"
  }
}
```

## 8. Notification System Rules

### Email Notifications (Important Only)
- Account security alerts
- Job application status updates
- Event acceptance/rejection
- Hackathon results
- System announcements

### In-App Notifications (All Events)
- New messages and chat activity
- Post interactions (likes, comments)
- Event reminders
- Application updates
- Platform activity

### Notification Preferences
- **Granular Control**: Per-notification-type preferences
- **Frequency Options**: Instant, daily digest, weekly summary
- **Channel Selection**: Email and/or in-app notifications

## 9. Analytics & Tracking Standards

### User Analytics
- **Platform Usage**: Page views, feature usage, session duration
- **Engagement Metrics**: Post interactions, chat activity, event participation
- **Conversion Tracking**: Job applications, event registrations
- **User Journey**: Complete user flow tracking

### UX Analytics
- **Behavior Tracking**: Mouse movements, clicks, scrolls
- **Heatmaps**: Page interaction heatmaps
- **Session Recordings**: User session replay
- **A/B Testing**: Feature variation testing

## 10. Deployment & Maintenance Guidelines

### Environment Configuration
- **Production**: Hostinger hosting with SSL certificate
- **Staging**: Mirror production environment for testing
- **Development**: Local environment with Docker/Laravel Sail

### Deployment Process
1. **Code Review**: Mandatory peer review for all changes
2. **Testing**: Automated tests must pass
3. **Staging Deployment**: Deploy to staging for final testing
4. **Production Deployment**: Deploy during low-traffic periods
5. **Monitoring**: Monitor application health post-deployment

### Backup Strategy
- **Database**: Daily automated backups with 30-day retention
- **Files**: Weekly file system backups
- **Code**: Git repository with multiple remotes
- **Recovery**: Documented recovery procedures

### Monitoring Requirements
- **Application Health**: Response times, error rates
- **Database Performance**: Query performance, connection status
- **Server Resources**: CPU, memory, disk usage
- **Security**: Failed login attempts, suspicious activity

## 11. Content Moderation Rules

### Content Guidelines
- **Prohibited Content**: Spam, harassment, inappropriate material
- **Professional Standards**: Maintain professional community atmosphere
- **Language**: English and Arabic supported
- **Copyright**: Respect intellectual property rights

### Moderation Process
1. **Instant Publishing**: All content published immediately
2. **Community Reporting**: User reporting system
3. **Admin Review**: Manual moderation through dashboard
4. **Actions**: Hide, edit, or remove content as needed
5. **Appeals**: User appeal process for moderation decisions

## 12. File Upload & Storage Rules

### File Size Limits
- **Profile Images**: 2MB maximum
- **Post Images**: 5MB maximum
- **Chat Files**: 10MB maximum
- **Hackathon Submissions**: 50MB maximum

### Supported File Types
- **Images**: JPEG, PNG, WebP, GIF
- **Documents**: PDF, DOC, DOCX
- **Archives**: ZIP, RAR (for hackathon submissions)
- **Code**: Common programming file extensions

### Storage Security
- **Virus Scanning**: All uploaded files scanned
- **Type Validation**: Server-side file type verification
- **Access Control**: Proper file access permissions
- **Backup**: Regular backup of uploaded files

## 13. Error Handling & Logging

### Error Handling Standards
- **User-Friendly Messages**: Clear, actionable error messages
- **Graceful Degradation**: Fallback options when features fail
- **Validation Errors**: Specific field-level error messages
- **System Errors**: Generic messages with detailed logging

### Logging Requirements
- **Application Logs**: All errors, warnings, and important events
- **Security Logs**: Authentication attempts, permission violations
- **Performance Logs**: Slow queries, high resource usage
- **User Activity**: Important user actions for audit trail

## 14. Testing Standards

### Testing Requirements
- **Unit Tests**: All models and services
- **Feature Tests**: All controllers and routes
- **Browser Tests**: Critical user flows
- **API Tests**: All API endpoints

### Test Coverage Goals
- **Minimum Coverage**: 80% code coverage
- **Critical Features**: 95% coverage for authentication, payments
- **Continuous Integration**: Automated testing on all commits
- **Performance Testing**: Load testing for high-traffic scenarios

## 15. Documentation Standards

### Code Documentation
- **PHPDoc**: All classes, methods, and properties
- **README**: Clear setup and usage instructions
- **API Documentation**: Auto-generated from code annotations
- **Database Schema**: ER diagrams and table documentation

### User Documentation
- **User Guides**: Feature-specific help documentation
- **FAQ**: Common questions and troubleshooting
- **Video Tutorials**: For complex features
- **Release Notes**: Document all changes and new features

---

**Last Updated**: January 2025
**Version**: 1.0
**Maintained By**: Development Team

This document serves as the definitive guide for all development work on the People Of Data platform. All team members must adhere to these rules and guidelines to ensure consistency, quality, and maintainability of the platform.