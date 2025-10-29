# Technical Specifications - People Of Data Platform

## Technology Stack

### Backend
- **Framework**: Laravel (Latest LTS)
- **API**: Laravel API Resources for mobile app integration
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum + OAuth (Google, LinkedIn)
- **File Storage**: Laravel File Storage
- **Queue System**: Laravel Queues with Database driver

### Frontend
- **Framework**: Livewire 3.x
- **JavaScript**: Alpine.js 3.x
- **AJAX**: Alpine.js with Livewire
- **CSS Framework**: Tailwind CSS
- **Icons**: Heroicons
- **Image Processing**: Intervention Image

### Third-Party Integrations
- **Chat System**: Chatify (with API support)
- **AI Assistant**: OpenAI GPT-4
- **OAuth Providers**: Google OAuth 2.0, LinkedIn OAuth 2.0
- **Email**: Laravel Mail with SMTP
- **Analytics**: Google Analytics + Custom tracking
- **UX Tools**: Hotjar or similar

### Hosting & Deployment
- **Host**: Hostinger Shared/VPS Hosting
- **Database**: MySQL on Hostinger
- **File Storage**: Local storage (Hostinger disk space)
- **Domain**: Custom domain through Hostinger
- **SSL**: Let's Encrypt via Hostinger

## Architecture

### Application Architecture
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │    Backend      │    │    Database     │
│   (Livewire +  │◄──►│   (Laravel +    │◄──►│     (MySQL)     │
│   Alpine.js)    │    │     API)        │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│  Third-Party    │    │   File Storage  │    │    Queue Jobs   │
│  Services       │    │   (Local/Cloud) │    │   (Background)  │
│  (OAuth, AI,    │    │                 │    │                 │
│  Chat, Email)   │    │                 │    │                 │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### Database Architecture
- **Primary Database**: MySQL for all application data
- **Sessions**: Database sessions for Livewire
- **Cache**: File-based cache for performance
- **Queues**: Database queue driver for background jobs

### Security Requirements
- **Authentication**: Multi-factor authentication support
- **Authorization**: Role-based access control (RBAC)
- **Data Protection**: Encrypted sensitive data
- **Input Validation**: Server-side validation for all inputs
- **CSRF Protection**: Laravel CSRF tokens
- **SQL Injection**: Eloquent ORM protection
- **XSS Protection**: Blade template escaping

### Performance Optimization
- **Caching**: Redis/File cache for frequently accessed data
- **Database**: Proper indexing and query optimization
- **Images**: Automatic compression and optimization
- **CDN**: Consider CDN for static assets
- **Lazy Loading**: Implement for large datasets

### Mobile Responsiveness
- **Design**: Mobile-first responsive design
- **Performance**: Optimized for mobile networks
- **Touch**: Touch-friendly interface elements
- **PWA**: Progressive Web App capabilities

### API Design
- **REST API**: RESTful endpoints for mobile app
- **Authentication**: API token-based authentication
- **Rate Limiting**: API rate limiting for security
- **Documentation**: Auto-generated API documentation
- **Versioning**: API versioning strategy

## Development Environment

### Local Development
- **PHP**: 8.1+ with required extensions
- **Node.js**: 18+ for asset compilation
- **MySQL**: 8.0+ local instance
- **Composer**: PHP dependency management
- **NPM**: Frontend dependency management

### Code Quality
- **Standards**: PSR-12 coding standards
- **Testing**: PHPUnit for backend testing
- **Linting**: PHP CS Fixer for code formatting
- **Documentation**: Inline documentation for all methods

### Version Control
- **Git**: Version control with feature branches
- **Workflow**: GitFlow workflow
- **CI/CD**: Automated testing and deployment

Last Updated: January 2025
