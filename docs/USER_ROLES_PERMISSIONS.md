# User Roles & Permissions - People Of Data Platform

## User Role Hierarchy

### 1. Super Administrator
**Full System Control**
- Complete access to all platform features
- System configuration and settings
- User role management
- Platform analytics and reporting
- Data backup and system maintenance

#### Permissions:
- ✅ All user management (create, edit, delete, role changes)
- ✅ All content moderation and management
- ✅ System settings and configuration
- ✅ Analytics and reporting access
- ✅ All admin permissions
- ✅ Database and system maintenance

### 2. Administrator
**Limited Administrative Control**
- Moderate specific platform features
- Handle user requests and applications
- Content moderation within assigned areas

#### Permissions:
- ✅ Event subscription management
- ✅ Hackathon application review
- ✅ Job application oversight
- ✅ Internship application processing
- ✅ Client conversion request handling
- ✅ Content moderation (posts, comments)
- ✅ User profile verification
- ❌ User role changes
- ❌ System configuration
- ❌ Super admin functions

### 3. Client User
**Business/Organization Account**
- Enhanced capabilities for business users
- Content creation and event management
- Access to specialized tools

#### Permissions:
- ✅ All regular user permissions
- ✅ Create and manage events
- ✅ Post job opportunities
- ✅ Create hackathons
- ✅ Access to AI business tools
- ✅ Manage job applications for posted jobs
- ✅ Event attendee management
- ✅ Enhanced profile features
- ✅ Priority support
- ❌ Administrative functions

#### Conversion Process:
1. User submits client conversion request
2. Provides company information:
   - Company name (required)
   - Business field/industry (required)
   - Company website URL (optional)
   - Business registration info (if available)
   - LinkedIn company page (optional)
3. Admin reviews and approves/rejects request
4. User receives notification of decision

### 4. Regular User
**Standard Community Member**
- Access to all basic platform features
- Community interaction and networking
- Job applications and event participation

#### Permissions:
- ✅ Profile creation and management
- ✅ Apply for jobs
- ✅ Join events (with verification)
- ✅ Participate in hackathons
- ✅ Apply for internships
- ✅ Create and engage with posts
- ✅ Comment and vote on content
- ✅ 1-on-1 chat with other users
- ✅ Use AI assistant
- ✅ Search platform content
- ❌ Create events
- ❌ Post jobs
- ❌ Create hackathons

## Email Verification Requirements

### Verified Email Benefits:
- ✅ Apply for events
- ✅ Apply for jobs
- ✅ Join hackathons
- ✅ Apply for internships
- ✅ Receive important notifications

### Unverified Email Limitations:
- ❌ Cannot apply for events, jobs, hackathons, internships
- ❌ Limited notification preferences
- ✅ Can still browse, post, chat, use AI assistant

## Profile Completion Requirements

### Mandatory Fields (All Users):
**Personal Information:**
- Full name
- Email address
- Phone number
- Location (city, country)
- Gender
- Bio/About section
- Avatar image

**Professional Information:**
- Skills and expertise
- Experience level
- Education background
- Portfolio/work links

**Social Links:**
- LinkedIn profile
- GitHub profile
- Twitter handle
- Personal website

### Profile Completion Enforcement:
- Users guided through onboarding process
- Step-by-step profile completion wizard
- Progressive disclosure to avoid overwhelming users
- Critical information required before accessing key features
- Gentle reminders for incomplete profiles

## Permission Matrix

| Feature | Super Admin | Admin | Client | User |
|---------|-------------|-------|--------|------|
| User Management | ✅ | ✅* | ❌ | ❌ |
| System Settings | ✅ | ❌ | ❌ | ❌ |
| Create Events | ✅ | ✅ | ✅ | ❌ |
| Manage Events | ✅ | ✅ | ✅** | ❌ |
| Post Jobs | ✅ | ✅ | ✅ | ❌ |
| Manage Job Apps | ✅ | ✅ | ✅** | ❌ |
| Create Hackathons | ✅ | ✅ | ✅ | ❌ |
| Apply for Jobs | ✅ | ✅ | ✅ | ✅ |
| Join Events | ✅ | ✅ | ✅ | ✅ |
| Post Content | ✅ | ✅ | ✅ | ✅ |
| Moderate Content | ✅ | ✅ | ❌ | ❌ |
| Chat System | ✅ | ✅ | ✅ | ✅ |
| AI Assistant | ✅ | ✅ | ✅ | ✅ |

*Limited to specific areas
**Only for own created content

## Role Transition Rules

### User → Client:
- Must submit conversion request
- Admin approval required
- Cannot be reversed by user
- Maintains all previous permissions + new capabilities

### User/Client → Admin:
- Super admin assignment only
- Specific permission scope defined
- Can be revoked by super admin

### Admin → Super Admin:
- Manual system-level assignment
- Cannot be self-requested

Last Updated: January 2025
