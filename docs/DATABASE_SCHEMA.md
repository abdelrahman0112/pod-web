# Database Schema Design - People Of Data Platform

## Core User Management

### users
```sql
id (bigint, primary key, auto_increment)
name (varchar 255)
email (varchar 255, unique)
email_verified_at (timestamp, nullable)
password (varchar 255)
phone (varchar 20, nullable)
gender (enum: male, female, other)
birthday (date, nullable)
location_city (varchar 100, nullable)
location_country (varchar 100, nullable)
bio (text, nullable)
avatar (varchar 255, nullable)
role (enum: superadmin, admin, client, user) [default: user]
status (enum: active, inactive, suspended) [default: active]
profile_completed (boolean) [default: false]
profile_completion_percentage (tinyint) [default: 0]
last_active_at (timestamp, nullable)
created_at (timestamp)
updated_at (timestamp)
deleted_at (timestamp, nullable) -- soft deletes
```

### user_profiles
```sql
id (bigint, primary key, auto_increment)
user_id (bigint, foreign key -> users.id)
experience_level (enum: entry, junior, mid, senior, expert)
education (json) -- [{institution, degree, field, year}]
skills (json) -- [skill_tags]
interests (json) -- [interest_tags]
portfolio_links (json) -- {github, website, other}
social_links (json) -- {linkedin, twitter, github, website}
created_at (timestamp)
updated_at (timestamp)
```

### oauth_providers
```sql
id (bigint, primary key, auto_increment)
user_id (bigint, foreign key -> users.id)
provider (enum: google, linkedin)
provider_id (varchar 255)
provider_email (varchar 255)
created_at (timestamp)
updated_at (timestamp)
```

## Job Management

### job_categories
```sql
id (bigint, primary key, auto_increment)
name (varchar 100)
description (text, nullable)
slug (varchar 100, unique)
is_active (boolean) [default: true]
created_at (timestamp)
updated_at (timestamp)
```

### jobs
```sql
id (bigint, primary key, auto_increment)
user_id (bigint, foreign key -> users.id) -- job creator
category_id (bigint, foreign key -> job_categories.id)
title (varchar 255)
description (text)
company_name (varchar 255)
location (varchar 255)
type (enum: full_time, part_time, contract, remote, hybrid)
experience_required (varchar 100, nullable)
salary_min (decimal 10,2, nullable)
salary_max (decimal 10,2, nullable)
required_skills (json)
application_deadline (date, nullable)
status (enum: active, archived, closed) [default: active]
views_count (int) [default: 0]
applications_count (int) [default: 0]
created_at (timestamp)
updated_at (timestamp)
```

### job_applications
```sql
id (bigint, primary key, auto_increment)
job_id (bigint, foreign key -> jobs.id)
user_id (bigint, foreign key -> users.id)
status (enum: pending, reviewed, interview_scheduled, interviewed, accepted, rejected, withdrawn) [default: pending]
cover_letter (text, nullable)
expected_salary (decimal 10,2, nullable)
availability_date (date, nullable)
additional_info (text, nullable)
admin_notes (text, nullable)
applied_at (timestamp) [default: current_timestamp]
updated_at (timestamp)
```

## Event Management

### events
```sql
id (bigint, primary key, auto_increment)
user_id (bigint, foreign key -> users.id) -- event creator
title (varchar 255)
description (text)
type (enum: online, onground, hybrid)
location (varchar 255, nullable)
online_link (varchar 255, nullable)
start_date (datetime)
end_date (datetime)
max_attendees (int, nullable) -- null = unlimited
allow_waitlist (boolean) [default: false]
registration_deadline (datetime, nullable)
agenda (json, nullable)
banner_image (varchar 255, nullable)
status (enum: draft, published, cancelled, completed) [default: draft]
attendees_count (int) [default: 0]
waitlist_count (int) [default: 0]
chat_room_id (bigint, nullable)
chat_created_hours_before (int) [default: 24]
created_at (timestamp)
updated_at (timestamp)
```

### event_attendees
```sql
id (bigint, primary key, auto_increment)
event_id (bigint, foreign key -> events.id)
user_id (bigint, foreign key -> users.id)
status (enum: registered, confirmed, waitlisted, cancelled, attended) [default: registered]
ticket_code (varchar 20, unique)
qr_code (varchar 255, nullable)
registered_at (timestamp) [default: current_timestamp]
attended_at (timestamp, nullable)
```

## Hackathon Management

### hackathons
```sql
id (bigint, primary key, auto_increment)
user_id (bigint, foreign key -> users.id) -- hackathon creator
title (varchar 255)
description (text)
registration_deadline (datetime)
submission_deadline (datetime)
min_team_members (int) [default: 2]
max_team_members (int) [default: 6]
prizes (json, nullable)
judging_criteria (text, nullable)
rules (text, nullable)
banner_image (varchar 255, nullable)
status (enum: draft, registration_open, registration_closed, submission_open, submission_closed, judging, completed) [default: draft]
teams_count (int) [default: 0]
submissions_count (int) [default: 0]
created_at (timestamp)
updated_at (timestamp)
```

### hackathon_teams
```sql
id (bigint, primary key, auto_increment)
hackathon_id (bigint, foreign key -> hackathons.id)
leader_id (bigint, foreign key -> users.id)
name (varchar 255)
description (text, nullable)
status (enum: forming, ready, submitted, disqualified) [default: forming]
members_count (int) [default: 1]
submission_file (varchar 255, nullable)
submission_links (json, nullable)
submission_description (text, nullable)
submitted_at (timestamp, nullable)
created_at (timestamp)
updated_at (timestamp)
```

### hackathon_team_members
```sql
id (bigint, primary key, auto_increment)
team_id (bigint, foreign key -> hackathon_teams.id)
user_id (bigint, foreign key -> users.id)
status (enum: invited, accepted, declined, removed) [default: accepted]
role (varchar 100, nullable)
joined_at (timestamp) [default: current_timestamp]
```

### hackathon_invitations
```sql
id (bigint, primary key, auto_increment)
team_id (bigint, foreign key -> hackathon_teams.id)
inviter_id (bigint, foreign key -> users.id)
invitee_id (bigint, foreign key -> users.id)
status (enum: pending, accepted, declined, expired) [default: pending]
message (text, nullable)
sent_at (timestamp) [default: current_timestamp]
responded_at (timestamp, nullable)
```

## Posts & Social Features

### posts
```sql
id (bigint, primary key, auto_increment)
user_id (bigint, foreign key -> users.id)
type (enum: text, image, url, poll)
content (text, nullable)
url (varchar 255, nullable)
url_title (varchar 255, nullable)
url_description (text, nullable)
url_image (varchar 255, nullable)
images (json, nullable) -- array of image paths
hashtags (json, nullable) -- array of hashtags
status (enum: active, hidden, deleted) [default: active]
views_count (int) [default: 0]
likes_count (int) [default: 0]
comments_count (int) [default: 0]
shares_count (int) [default: 0]
created_at (timestamp)
updated_at (timestamp)
```

### polls
```sql
id (bigint, primary key, auto_increment)
post_id (bigint, foreign key -> posts.id)
question (varchar 500)
options (json) -- array of poll options
expires_at (datetime)
total_votes (int) [default: 0]
created_at (timestamp)
updated_at (timestamp)
```

### poll_votes
```sql
id (bigint, primary key, auto_increment)
poll_id (bigint, foreign key -> polls.id)
user_id (bigint, foreign key -> users.id)
option_index (int)
voted_at (timestamp) [default: current_timestamp]
```

### post_likes
```sql
id (bigint, primary key, auto_increment)
post_id (bigint, foreign key -> posts.id)
user_id (bigint, foreign key -> users.id)
created_at (timestamp)
-- unique constraint on (post_id, user_id)
```

### comments
```sql
id (bigint, primary key, auto_increment)
post_id (bigint, foreign key -> posts.id)
user_id (bigint, foreign key -> users.id)
parent_id (bigint, foreign key -> comments.id, nullable)
content (text)
likes_count (int) [default: 0]
replies_count (int) [default: 0]
status (enum: active, hidden, deleted) [default: active]
created_at (timestamp)
updated_at (timestamp)
```

### comment_likes
```sql
id (bigint, primary key, auto_increment)
comment_id (bigint, foreign key -> comments.id)
user_id (bigint, foreign key -> users.id)
created_at (timestamp)
-- unique constraint on (comment_id, user_id)
```

### hashtags
```sql
id (bigint, primary key, auto_increment)
name (varchar 100, unique)
slug (varchar 100, unique)
posts_count (int) [default: 0]
created_at (timestamp)
updated_at (timestamp)
```

### post_hashtags
```sql
id (bigint, primary key, auto_increment)
post_id (bigint, foreign key -> posts.id)
hashtag_id (bigint, foreign key -> hashtags.id)
created_at (timestamp)
-- unique constraint on (post_id, hashtag_id)
```

## Internship Management

### internship_applications
```sql
id (bigint, primary key, auto_increment)
user_id (bigint, foreign key -> users.id)
full_name (varchar 255)
email (varchar 255)
phone (varchar 20)
university (varchar 255, nullable)
major (varchar 255, nullable)
graduation_year (year, nullable)
gpa (decimal 3,2, nullable)
experience (text, nullable)
skills (json)
interests (json)
availability_start (date)
availability_end (date)
motivation (text)
portfolio_links (json, nullable)
status (enum: pending, reviewing, accepted, rejected) [default: pending]
admin_response (text, nullable)
admin_notes (text, nullable)
admin_id (bigint, foreign key -> users.id, nullable)
applied_at (timestamp) [default: current_timestamp]
reviewed_at (timestamp, nullable)
```

## Client Conversion Requests

### client_requests
```sql
id (bigint, primary key, auto_increment)
user_id (bigint, foreign key -> users.id)
company_name (varchar 255)
company_field (varchar 255)
company_url (varchar 255, nullable)
business_registration (varchar 255, nullable)
linkedin_company (varchar 255, nullable)
additional_info (text, nullable)
status (enum: pending, approved, rejected) [default: pending]
admin_response (text, nullable)
admin_id (bigint, foreign key -> users.id, nullable)
requested_at (timestamp) [default: current_timestamp]
reviewed_at (timestamp, nullable)
```

## Notifications System

### notifications
```sql
id (bigint, primary key, auto_increment)
user_id (bigint, foreign key -> users.id)
type (varchar 100) -- job_application_status, event_accepted, etc.
title (varchar 255)
message (text)
data (json, nullable) -- additional data
read_at (timestamp, nullable)
email_sent (boolean) [default: false]
email_sent_at (timestamp, nullable)
created_at (timestamp)
```

### notification_preferences
```sql
id (bigint, primary key, auto_increment)
user_id (bigint, foreign key -> users.id)
type (varchar 100)
email_enabled (boolean) [default: true]
app_enabled (boolean) [default: true]
created_at (timestamp)
updated_at (timestamp)
-- unique constraint on (user_id, type)
```

## AI Assistant

### ai_conversations
```sql
id (bigint, primary key, auto_increment)
user_id (bigint, foreign key -> users.id)
title (varchar 255, nullable)
messages_count (int) [default: 0]
total_tokens (int) [default: 0]
created_at (timestamp)
updated_at (timestamp)
```

### ai_messages
```sql
id (bigint, primary key, auto_increment)
conversation_id (bigint, foreign key -> ai_conversations.id)
role (enum: user, assistant, system)
content (text)
tokens (int, nullable)
model (varchar 100, nullable)
created_at (timestamp)
```

## Analytics & Tracking

### user_analytics
```sql
id (bigint, primary key, auto_increment)
user_id (bigint, foreign key -> users.id, nullable)
session_id (varchar 255, nullable)
event_type (varchar 100) -- page_view, click, form_submit, etc.
event_name (varchar 255)
page_url (varchar 500)
referrer (varchar 500, nullable)
user_agent (varchar 500, nullable)
ip_address (varchar 45, nullable)
metadata (json, nullable)
created_at (timestamp)
```

### system_analytics
```sql
id (bigint, primary key, auto_increment)
metric_name (varchar 100)
metric_value (decimal 15,2)
metric_type (enum: counter, gauge, histogram)
tags (json, nullable)
recorded_at (timestamp) [default: current_timestamp]
```

## System Settings

### settings
```sql
id (bigint, primary key, auto_increment)
key (varchar 100, unique)
value (text)
type (enum: string, integer, boolean, json) [default: string]
description (text, nullable)
created_at (timestamp)
updated_at (timestamp)
```

## Indexes and Constraints

### Performance Indexes
```sql
-- Users
INDEX idx_users_email (email)
INDEX idx_users_role (role)
INDEX idx_users_status (status)
INDEX idx_users_deleted_at (deleted_at)

-- Jobs
INDEX idx_jobs_category_status (category_id, status)
INDEX idx_jobs_user_id (user_id)
INDEX idx_jobs_deadline (application_deadline)

-- Events
INDEX idx_events_user_id (user_id)
INDEX idx_events_dates (start_date, end_date)
INDEX idx_events_status (status)

-- Posts
INDEX idx_posts_user_id (user_id)
INDEX idx_posts_created_at (created_at)
INDEX idx_posts_type_status (type, status)

-- Notifications
INDEX idx_notifications_user_read (user_id, read_at)
INDEX idx_notifications_created_at (created_at)

-- Analytics
INDEX idx_user_analytics_user_event (user_id, event_type)
INDEX idx_user_analytics_created_at (created_at)
```

### Foreign Key Constraints
- All foreign keys include `ON DELETE CASCADE` or `ON DELETE SET NULL` as appropriate
- Soft delete tables use `ON DELETE SET NULL` for user references
- Junction tables use `ON DELETE CASCADE`

### Unique Constraints
- Email addresses across users and oauth_providers
- Event ticket codes
- Team names within hackathons
- Hashtag names and slugs

Last Updated: January 2025
