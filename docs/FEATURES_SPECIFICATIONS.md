# Features Specifications - People Of Data Platform

## 1. Authentication System

### Registration Methods
- **Default Registration**: Email/password with form validation
- **Google OAuth**: Integration with Google OAuth 2.0
- **LinkedIn OAuth**: Integration with LinkedIn OAuth 2.0

### Email Verification
- **Not Required**: Users can login without verification
- **Encouraged**: Persistent reminder messages for unverified emails
- **Feature Restrictions**: Cannot apply for events/jobs/hackathons/internships without verification
- **Verification Process**: Email link with token-based verification

### Password Requirements
- Minimum 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number
- Password confirmation required

### Account Management
- **Soft Delete**: Account deactivation instead of permanent deletion
- **Data Retention**: User data maintained for potential reactivation
- **Profile Privacy**: All information public except birthday

## 2. User Profiles System

### Mandatory Profile Fields

#### Personal Information
- Full name (first name, last name)
- Email address (primary contact)
- Phone number (with country code)
- Location (city, country/region)
- Gender (dropdown selection)
- Bio/About section (500 character limit)
- Avatar image (upload with compression)
- Birthday (private field)

#### Professional Information
- Skills and expertise (tags/categories)
- Experience level (entry, junior, mid, senior, expert)
- Education background (institution, degree, field of study)
- Portfolio links (GitHub, personal website, etc.)

#### Social Media Links
- LinkedIn profile URL
- GitHub profile URL
- Twitter/X handle
- Personal website URL
- Other relevant social platforms

### Profile Completion System
- **Onboarding Wizard**: Multi-step guided setup after registration
- **Progress Tracking**: Visual progress indicator for profile completion
- **Gentle Enforcement**: Feature access gradually unlocked with completion
- **No Overwhelm**: Reasonable information requests without complexity

## 3. Events Management System

### Event Types
- **On-ground Events**: Physical location-based events
- **Online Events**: Virtual events with meeting links
- **Hybrid Events**: Combined physical and virtual attendance

### Event Creation (Admin/Client only)
- Event title and description
- Date and time (single or multi-day support)
- Location (physical address or online link)
- Maximum attendees (or unlimited)
- Event agenda/schedule
- Event banner image
- Registration deadline

### Registration System
- **Reservation Process**: Simple one-click event registration
- **Capacity Management**: Automatic blocking when full
- **Waitlist Option**: Configurable per event
- **Auto-promotion**: Waitlisted users auto-promoted when spots available
- **Cancellation**: Users can cancel attendance

### Ticket System
- **Digital Tickets**: Generated for confirmed attendees
- **QR Code**: Unique QR code per ticket
- **Text Code**: Alternative text-based verification
- **Check-in System**: Admin tool for event check-in verification

### Event Chat Rooms
- **Auto-creation**: Group chat created before event (configurable timing)
- **Opt-in Participation**: Attendees choose to join chat
- **Notification Control**: Users control chat notifications
- **Pre-event Period**: Chat availability defined in admin settings

## 4. Jobs Management System

### Job Categories
- **Dynamic Categories**: Admin-configurable job categories
- **Examples**: Data Scientist, ML Engineer, Data Analyst, BI Developer
- **Category Management**: Full CRUD operations in admin dashboard

### Job Posting (Admin/Client)
- Job title and description
- Company information
- Location (remote/on-site/hybrid)
- Salary range (optional)
- Required skills and experience
- Application deadline
- Job category assignment

### Application Process
- **Manual Information**: Applicants enter information directly
- **No File Uploads**: All information through form fields
- **Required Fields**: Configurable per job posting
- **Application Tracking**: Status updates for applicants

### Application Status Enum
```php
enum JobApplicationStatus: string {
    case PENDING = 'pending';
    case REVIEWED = 'reviewed';
    case INTERVIEW_SCHEDULED = 'interview_scheduled';
    case INTERVIEWED = 'interviewed';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case WITHDRAWN = 'withdrawn';
}
```

### Job Visibility
- **Active Jobs**: Displayed prominently in search results
- **Past Deadline**: Lower priority in results, no new applications
- **Archived**: Hidden from public view, accessible to job creator
- **No Deletion**: Jobs never permanently deleted

## 5. Hackathons System

### Hackathon Structure
- Event title and description
- Registration deadline
- Project submission deadline
- Minimum team members (e.g., 2)
- Maximum team members (e.g., 6)
- Prizes and recognition
- Judging criteria

### Team Management
- **Team Creation**: Any user can create a team
- **Team Leadership**: Creator becomes team leader
- **Invitation System**: Leaders invite platform users
- **Multi-team Participation**: Users can join multiple teams
- **Hackathon Restriction**: One team per hackathon per user

### Project Submission
- **File Upload**: Support for project files
- **Recommended Formats**: ZIP, PDF, source code
- **File Size Limit**: 50MB per submission
- **Project Links**: GitHub, demo links, presentation
- **Submission Details**: Project description, technology stack

### Winner Selection
- **Admin Selection**: Manual winner selection by administrators
- **Notification System**: Winners notified via email and in-app
- **Public Announcement**: Winner announcement on platform

## 6. Internship Applications

### Application Process
- **Dedicated Page**: Separate internship application page
- **Comprehensive Form**: All essential information required
- **Admin Review**: Manual review and response by administrators
- **Response Types**: Acceptance with details or rejection with advice

### Required Information
- Personal and contact details
- Educational background
- Relevant experience
- Skills and interests
- Availability and duration
- Portfolio or project examples

## 7. Posts & Social Features

### Post Types
- **Text Posts**: Rich text content with formatting
- **Image Posts**: Single or multiple image uploads
- **URL Posts**: Link sharing with preview
- **Poll Posts**: Multiple choice polls (up to 20 options)

### Image Handling
- **Size Limit**: 5MB per image
- **Compression**: Automatic lossless compression
- **Formats**: JPEG, PNG, WebP support
- **Multiple Images**: Up to 10 images per post

### Poll System
- **Option Limit**: Maximum 20 poll options
- **Duration**: User-configurable up to 3 days
- **Voting**: One vote per user
- **Results**: Real-time result display

### Hashtag System
- **Auto-completion**: Hashtag suggestions while typing
- **Related Tags**: Suggestion of similar/related hashtags
- **Tag Pages**: Dedicated pages for each hashtag
- **Search Integration**: Hashtag-based content discovery

### Engagement Features
- **Comments**: Nested commenting system
- **Voting**: Upvote/downvote for posts and comments
- **Sharing**: Internal sharing within platform
- **Bookmarking**: Save posts for later viewing

### Content Moderation
- **Instant Publishing**: All content published immediately
- **Admin Review**: Manual moderation through admin dashboard
- **Reporting System**: User reporting for inappropriate content
- **Moderation Actions**: Hide, edit, or remove content

## 8. Chat System (Chatify Integration)

### Chat Features
- **1-on-1 Chat**: Direct messaging between users
- **Real-time Messaging**: Live message delivery
- **Message History**: Complete conversation history
- **Online Status**: User availability indicators

### File Sharing
- **Supported Types**: Images, documents, PDFs
- **Size Limit**: 10MB per file
- **Preview**: In-chat file preview when possible
- **Security**: File type validation and scanning

### Mobile API Support
- **REST API**: Full API support for mobile applications
- **Push Notifications**: Mobile notification support
- **Synchronization**: Cross-device message sync

## 9. AI Assistant

### Integration
- **OpenAI API**: GPT-4 integration with provided API key
- **Context Access**: Full user profile and platform data access
- **No Usage Limits**: Unlimited usage for all users

### Features
- **General Chat**: Open-ended conversation capability
- **Platform Help**: Assistance with platform features
- **Career Advice**: Data science and AI career guidance
- **Technical Support**: Help with technical questions

### Future Enhancements
- **Custom Training**: Platform-specific model training
- **Specialized Tools**: Role-based AI tool access
- **Integration**: Deep platform feature integration

## 10. Search System

### Search Scope
- **Global Search**: Across all platform content
- **User Search**: Find users by name, skills, location
- **Job Search**: Job opportunities with filters
- **Event Search**: Upcoming and past events
- **Post Search**: Content and hashtag search
- **Hackathon Search**: Current and past hackathons

### Search Features
- **Auto-complete**: Search suggestions
- **Filters**: Category, date, location filters
- **Sorting**: Relevance, date, popularity
- **Advanced Search**: Multi-criteria search options

## 11. Notification System

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

## 12. Analytics & Tracking

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

### Admin Analytics
- **Dashboard Metrics**: Key performance indicators
- **User Growth**: Registration and retention metrics
- **Content Performance**: Popular posts, events, jobs
- **Platform Health**: System performance and errors

Last Updated: January 2025
