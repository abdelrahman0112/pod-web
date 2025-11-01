# API Analysis & Structure Documentation

## Executive Summary

This document provides a comprehensive analysis of the current API structure and outlines the requirements for building a mobile application. The application currently has minimal API infrastructure - most endpoints return HTML views instead of JSON responses.

## Current State Analysis

### 1. Existing API Endpoints

#### Authentication
- **Status**: ❌ No API endpoints exist
- **Current Implementation**: Web-based session authentication only
- **Needs**: Full RESTful API with Sanctum token authentication

#### Existing JSON-Endpoint Routes
1. `/api/users/search` - UserSearchController (✅ Returns JSON)
2. `/api/profile/progress` - ProfileController (⚠️ Returns JSON but web route)
3. `/posts/load-more` - DashboardController (✅ Returns JSON)
4. `/notifications/get` - NotificationController (✅ Returns JSON)
5. `/notifications/unread-count` - NotificationController (✅ Returns JSON)
6. `/notifications/{notification}/mark-as-read` - NotificationController (✅ Returns JSON)
7. `/notifications/mark-all-as-read` - NotificationController (✅ Returns JSON)
8. `/notifications/{notification}` - NotificationController (⚠️ Returns JSON if expectsJson())
9. `/notifications/preferences` - NotificationController (✅ Returns JSON)
10. `/posts/{post}/comments` - CommentController (⚠️ Returns JSON if expectsJson())
11. `/search` - SearchController (⚠️ Returns JSON if expectsJson())
12. Chatify API routes in `/routes/chatify/api.php` (✅ Returns JSON)

### 2. Issues Identified

#### Critical Issues
1. **No Dedicated API Routes File**: All routes are in `routes/web.php` - need `routes/api.php`
2. **No API Authentication**: Sanctum is installed but not configured for API token auth
3. **No API Resources**: No Eloquent API Resources for consistent JSON structure
4. **Inconsistent Responses**: Mix of JSON and HTML responses based on `expectsJson()` checks
5. **No API Versioning**: No version structure (`/api/v1/`)
6. **No Request Validation**: Controllers use basic validation, need Form Requests for API
7. **No API Documentation**: No structured documentation for mobile developers

#### Medium Priority Issues
1. **Mixed Response Formats**: Some endpoints return different structures
2. **No Pagination Standards**: Inconsistent pagination implementations
3. **No Error Handling Standards**: Different error response formats
4. **Missing Endpoints**: Many features don't have API endpoints at all

### 3. Required API Endpoints for Mobile App

#### Authentication & Authorization
```
POST   /api/v1/auth/register           - Register new user
POST   /api/v1/auth/login              - Login and get token
POST   /api/v1/auth/logout             - Logout (revoke token)
POST   /api/v1/auth/refresh            - Refresh token
GET    /api/v1/auth/me                 - Get current user
POST   /api/v1/auth/forgot-password    - Request password reset
POST   /api/v1/auth/reset-password     - Reset password
GET    /api/v1/auth/verify-email       - Verify email
POST   /api/v1/auth/resend-verification - Resend verification email
```

#### User Management
```
GET    /api/v1/users                   - List users (search/filter)
GET    /api/v1/users/{id}              - Get user profile
PUT    /api/v1/users/{id}              - Update user profile
DELETE /api/v1/users/{id}              - Delete account
GET    /api/v1/users/{id}/posts        - Get user's posts
GET    /api/v1/users/search            - Search users
GET    /api/v1/profile                 - Get current user profile
PUT    /api/v1/profile                 - Update current user profile
GET    /api/v1/profile/progress        - Get profile completion progress
POST   /api/v1/profile/complete        - Complete profile
POST   /api/v1/profile/avatar          - Upload avatar
```

#### Posts & Social
```
GET    /api/v1/posts                   - List posts (feed)
POST   /api/v1/posts                   - Create post
GET    /api/v1/posts/{id}              - Get post details
PUT    /api/v1/posts/{id}              - Update post
DELETE /api/v1/posts/{id}              - Delete post
POST   /api/v1/posts/{id}/like         - Like/unlike post
POST   /api/v1/posts/{id}/share        - Share post
POST   /api/v1/posts/{id}/vote         - Vote on poll
GET    /api/v1/posts/{id}/comments     - Get post comments
POST   /api/v1/posts/{id}/comments     - Add comment
PUT    /api/v1/comments/{id}           - Update comment
DELETE /api/v1/comments/{id}           - Delete comment
```

#### Events
```
GET    /api/v1/events                  - List events
POST   /api/v1/events                  - Create event (admin/client)
GET    /api/v1/events/{id}             - Get event details
PUT    /api/v1/events/{id}             - Update event
DELETE /api/v1/events/{id}             - Delete event
POST   /api/v1/events/{id}/register    - Register for event
DELETE /api/v1/events/{id}/register    - Cancel registration
POST   /api/v1/events/{id}/check-in    - Check in to event
GET    /api/v1/events/{id}/registrations - Get registrations (admin)
```

#### Job Listings
```
GET    /api/v1/jobs                    - List job listings
POST   /api/v1/jobs                    - Create job listing (client)
GET    /api/v1/jobs/{id}               - Get job details
PUT    /api/v1/jobs/{id}               - Update job listing
DELETE /api/v1/jobs/{id}               - Delete job listing
POST   /api/v1/jobs/{id}/apply         - Apply for job
GET    /api/v1/jobs/my-applications    - Get my applications
GET    /api/v1/jobs/{id}/applications  - Get applications (client/admin)
PATCH  /api/v1/jobs/applications/{id}/accept - Accept application
PATCH  /api/v1/jobs/applications/{id}/reject - Reject application
```

#### Hackathons
```
GET    /api/v1/hackathons              - List hackathons
POST   /api/v1/hackathons              - Create hackathon (client)
GET    /api/v1/hackathons/{id}         - Get hackathon details
PUT    /api/v1/hackathons/{id}         - Update hackathon
DELETE /api/v1/hackathons/{id}         - Delete hackathon
POST   /api/v1/hackathons/{id}/register - Register for hackathon
GET    /api/v1/hackathons/{id}/teams   - Get hackathon teams
POST   /api/v1/hackathons/{id}/join-team - Join team
DELETE /api/v1/hackathons/{id}/leave-team - Leave team
GET    /api/v1/hackathons/teams        - Get my teams
POST   /api/v1/hackathons/teams        - Create team
PUT    /api/v1/hackathons/teams/{id}   - Update team
DELETE /api/v1/hackathons/teams/{id}   - Delete team
POST   /api/v1/hackathons/teams/{id}/invite - Invite member
POST   /api/v1/hackathons/teams/{id}/join-request - Request to join
POST   /api/v1/hackathons/invitations/{id}/accept - Accept invitation
POST   /api/v1/hackathons/invitations/{id}/reject - Reject invitation
```

#### Internships
```
GET    /api/v1/internships             - List internships
POST   /api/v1/internships             - Create internship (client)
GET    /api/v1/internships/{id}        - Get internship details
POST   /api/v1/internships/apply       - Apply for internship
GET    /api/v1/internships/my-applications - Get my applications
```

#### Notifications
```
GET    /api/v1/notifications           - List notifications
GET    /api/v1/notifications/unread-count - Get unread count
PATCH  /api/v1/notifications/{id}/read - Mark as read
PATCH  /api/v1/notifications/read-all  - Mark all as read
DELETE /api/v1/notifications/{id}      - Delete notification
DELETE /api/v1/notifications           - Clear all notifications
POST   /api/v1/notifications/preferences - Update preferences
```

#### Search
```
GET    /api/v1/search                  - Global search
GET    /api/v1/search/posts            - Search posts
GET    /api/v1/search/events           - Search events
GET    /api/v1/search/jobs             - Search jobs
GET    /api/v1/search/hackathons       - Search hackathons
GET    /api/v1/search/users            - Search users
```

#### Chat/Messaging (Chatify)
```
POST   /api/chat/auth                  - Pusher authentication (existing)
POST   /api/idInfo                     - Fetch user/group info (existing)
POST   /api/sendMessage                - Send message (existing)
POST   /api/fetchMessages              - Fetch messages (existing)
GET    /api/download/{fileName}        - Download attachment (existing)
POST   /api/makeSeen                   - Mark messages as seen (existing)
GET    /api/getContacts                - Get contacts (existing)
POST   /api/star                       - Favorite conversation (existing)
POST   /api/favorites                  - Get favorites (existing)
GET    /api/search                     - Search messages (existing)
POST   /api/shared                     - Get shared photos (existing)
POST   /api/deleteConversation         - Delete conversation (existing)
POST   /api/updateSettings             - Update settings (existing)
POST   /api/setActiveStatus            - Set active status (existing)
GET    /api/unread-count               - Get unread count (existing)
```

## Response Structure Standards

### Success Response Format
```json
{
  "success": true,
  "data": {
    // Resource data
  },
  "message": "Optional success message"
}
```

### Paginated Response Format
```json
{
  "success": true,
  "data": [
    // Array of resources
  ],
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 10,
    "per_page": 15,
    "to": 15,
    "total": 150
  },
  "links": {
    "first": "http://example.com/api/v1/resource?page=1",
    "last": "http://example.com/api/v1/resource?page=10",
    "prev": null,
    "next": "http://example.com/api/v1/resource?page=2"
  }
}
```

### Error Response Format
```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `204` - No Content (for delete operations)
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `429` - Too Many Requests
- `500` - Server Error

## Authentication

### Token-Based Authentication (Sanctum)
- **Token Type**: Bearer Token
- **Header Format**: `Authorization: Bearer {token}`
- **Token Creation**: On successful login/registration
- **Token Expiration**: Configurable (default: no expiration)
- **Token Refresh**: Via `/api/v1/auth/refresh`

### Authorization
- **Role-Based Access Control**: Use existing role middleware
- **Policy-Based Authorization**: Use existing policies
- **Token Scopes**: Not implemented (can be added if needed)

## Required Implementation Steps

1. ✅ Create `routes/api.php` file
2. ✅ Configure Sanctum for API authentication
3. ✅ Create API Resources for all models
4. ✅ Create API Controllers
5. ✅ Create Form Requests for validation
6. ✅ Implement consistent response helpers
7. ✅ Add API versioning
8. ✅ Create comprehensive API documentation
9. ✅ Add rate limiting
10. ✅ Add CORS configuration

## Rate Limiting

- **Authenticated Users**: 60 requests per minute
- **Unauthenticated Users**: 20 requests per minute
- **Strict Endpoints**: 10 requests per minute (login, register)

## CORS Configuration

- Allow requests from mobile app domains
- Configure allowed methods: GET, POST, PUT, PATCH, DELETE, OPTIONS
- Configure allowed headers: Authorization, Content-Type, Accept

## Next Steps

1. Review this analysis
2. Implement API structure following this document
3. Test all endpoints
4. Generate API documentation (Swagger/OpenAPI)
5. Provide mobile app team with API documentation

