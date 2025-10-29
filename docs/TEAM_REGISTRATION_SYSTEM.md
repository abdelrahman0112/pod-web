# Team Registration System - Full Analysis

## Overview
This document explains the complete team registration and management system for hackathons.

## Key Principles

### 1. Team-Hackathon Relationship
**Teams are ALWAYS tied to a specific hackathon at creation time.**
- A team cannot exist without a hackathon
- A team can only participate in one hackathon
- Teams are created by selecting a hackathon first
- This ensures teams follow the hackathon's rules from day one

### 2. Team Size Management

#### Hackathon Rules
Each hackathon defines:
- `min_team_size` - Minimum team members required (e.g., 2)
- `max_team_size` - Maximum team members allowed (e.g., 6)

#### Team States
Teams can be in different states based on member count:

1. **Incomplete** (Below minimum)
   - Member count < `min_team_size`
   - Team exists but is not ready to participate
   - Leader can continue recruiting
   - Team should show: "Need {X} more members"

2. **Ready** (Within range)
   - Member count >= `min_team_size` AND <= `max_team_size`
   - Team is fully functional and can participate
   - Additional members can still join up to max

3. **Full** (At maximum)
   - Member count == `max_team_size`
   - No more members can join
   - Team is locked

4. **Over capacity** (Error state)
   - Member count > `max_team_size`
   - Should never happen due to validation

### 3. Team Visibility & Recruitment

#### Single Setting: `is_public`
We consolidated two confusing fields into one simple concept:

- **`is_public = true`**: Team accepts join requests from others
  - Shows up in "Find Teams" search
  - Other users can request to join
  - Team actively recruiting
  
- **`is_public = false`**: Team is private/closed
  - Only invited members can join
  - Not shown in public searches
  - Leader must manually invite people

This replaces the old confusing combination of:
- ❌ `is_looking_for_members` + `accepts_join_requests` (redundant)
- ✅ `is_public` (clear and simple)

### 4. Registration Flow

#### Creating a Team
1. User clicks "Create Team"
2. Select hackathon from dropdown (only shows upcoming, active hackathons)
3. Enter team name (unique per hackathon)
4. Optionally add description
5. Set if team is public or private
6. Team created with only leader (count = 1)

#### Adding Members to Team

**Scenario A: Public Team (is_public = true)**
- Team appears in "Find Teams" search
- Other users can click "Request to Join"
- Leader gets notification of join request
- Leader can approve/reject

**Scenario B: Private Team (is_public = false)**
- Team not shown in search
- Leader must manually invite specific users
- Invited users can accept/decline invitation
- No random join requests

#### Team Validation

When attempting to participate in hackathon:
```php
// Check if team meets minimum requirement
if (!$team->meetsMinimumSize()) {
    return error("Team needs at least {$hackathon->min_team_size} members");
}

// Check if team is over capacity
if ($team->member_count > $hackathon->max_team_size) {
    return error("Team exceeds maximum of {$hackathon->max_team_size} members");
}

// Allow participation
return success();
```

### 5. Member Count Calculation
```php
public function getMemberCountAttribute(): int
{
    return $this->members()->count() + 1; // Leader + members
}
```

**Important**: Leader is always counted as 1, then add regular members.

### 6. Complete Registration States

#### Team States
```
🟡 Forming    - Below minimum size
🟢 Ready      - Meets minimum, not at max
🔵 Complete   - At or near preferred size
⚫ Locked     - At maximum capacity
```

#### Registration Flow
```
1. User creates/joins team (count = 1)
   └─ State: Forming
   
2. Additional members join (count = 2-5)
   └─ State: Ready (if meets min)
   
3. Team reaches max capacity (count = 6)
   └─ State: Locked
```

### 7. UI Indicators

**For each team, show:**
- Current member count: "3/6 members"
- Status: "Ready to participate" or "Need 1 more member"
- Visibility: "Open to join" or "Private team"

**Example display:**
```
Team Alpha
👥 3/6 members
✅ Ready to participate (meets minimum of 2)
🌐 Open to join requests
```

### 8. Enforcement Points

#### When Creating Team
- ✅ User can participate in hackathon
- ✅ Hackathon registration is open
- ✅ Team name is unique for this hackathon
- ⚠️ Team created below minimum (must recruit before participating)

#### When Adding Member
- ✅ Team has available spots
- ✅ User not already in team
- ✅ User can participate in hackathon
- ✅ If public team: accepts join requests automatically
- ✅ If private team: must be invited

#### When Participating in Hackathon
- ✅ Team meets minimum size requirement
- ✅ Team not over maximum size
- ✅ All members registered for hackathon
- ✅ Registration deadline not passed

## Summary

1. **One team = One hackathon** (teams are tied at creation)
2. **Min/max enforced** at participation time
3. **One visibility setting** (`is_public`) replaces confusing dual settings
4. **Clear states** for team readiness
5. **Validation at every step** ensures data integrity

This system ensures:
- ✅ Teams always follow hackathon rules
- ✅ No orphaned or floating teams
- ✅ Clear recruitment and visibility model
- ✅ Proper size validation at critical points
