@extends('layouts.app')

@section('title', $team->name . ' - My Teams')

@section('content')
<div class="w-full">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('home.hackathons.teams') }}" class="inline-flex items-center text-slate-600 hover:text-indigo-600">
            <i class="ri-arrow-left-line mr-2"></i>
            Back to My Teams
        </a>
    </div>

    <!-- Page Header -->
    <x-page-header 
        title="{{ $team->name }}"
        description="Team for {{ $team->hackathon->title }}"
        actionUrl="{{ route('hackathons.show', $team->hackathon) }}"
        actionText="View Hackathon"
        icon="ri-trophy-line"
    />

    <!-- Pending Invitations Banner (for invited users) -->
    @if($userPendingInvitations->count() > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-blue-800 flex items-center">
                    <i class="ri-mail-line mr-2"></i>
                    You have {{ $userPendingInvitations->count() }} pending invitation(s)
                </h3>
            </div>
            <div class="space-y-3">
                @foreach($userPendingInvitations as $invitation)
                    <div class="bg-white rounded-lg p-4 border border-blue-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <x-avatar 
                                    :src="$invitation->inviter->avatar ?? null"
                                    :name="$invitation->inviter->name ?? 'User'"
                                    size="md"
                                    :color="$invitation->inviter->avatar_color ?? null"
                                />
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $invitation->inviter->name }} invited you to join</p>
                                    <p class="text-sm text-slate-600">{{ $invitation->team->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $invitation->team->hackathon->title }}</p>
                                    @if($invitation->message)
                                        <p class="text-sm text-slate-600 mt-2 italic">"{{ $invitation->message }}"</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <form method="POST" action="{{ route('home.hackathons.invitations.accept') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="invitation_id" value="{{ $invitation->id }}">
                                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                                        Accept
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('home.hackathons.invitations.reject') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="invitation_id" value="{{ $invitation->id }}">
                                    <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 text-sm">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row lg:gap-8 gap-6">
        <!-- Main Content -->
        <div class="flex-1 w-full lg:max-w-4xl min-w-0">
            <!-- Team Information -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-slate-800">Team Information</h2>
                </div>
                
                @if($team->description)
                    <p class="text-slate-600 mb-6">{{ $team->description }}</p>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <span class="text-sm text-slate-500">Hackathon</span>
                        <a href="{{ route('hackathons.show', $team->hackathon) }}" class="block text-slate-800 font-medium hover:text-indigo-600">
                            {{ $team->hackathon->title }}
                        </a>
                    </div>
                    <div>
                        <span class="text-sm text-slate-500">Team Leader</span>
                        <div class="flex items-center mt-1">
                            <x-avatar 
                                :src="$team->leader->avatar ?? null"
                                :name="$team->leader->name ?? 'User'"
                                size="sm"
                                :color="$team->leader->avatar_color ?? null"
                            />
                            <span class="ml-2 text-slate-800 font-medium">{{ $team->leader->name }}</span>
                            @if($isLeader)
                                <span class="ml-2 text-xs bg-indigo-100 text-indigo-600 px-2 py-1 rounded-full">You</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-sm text-slate-500">Team Size</span>
                        <p class="text-slate-800 font-medium">{{ $team->member_count }}/{{ $team->hackathon->max_team_size }} members</p>
                    </div>
                    <div>
                        <span class="text-sm text-slate-500">Status</span>
                        <p class="text-slate-800 font-medium">
                            @if($team->is_public)
                                <span class="inline-flex items-center text-green-600">
                                    <i class="ri-user-add-line mr-1"></i>
                                    Looking for members
                                </span>
                            @else
                                <span class="inline-flex items-center text-slate-600">
                                    <i class="ri-check-line mr-1"></i>
                                    Full team
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
                
            </div>

            <!-- Project Submission Section -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-800">Project Submission</h2>
                        <p class="text-sm text-slate-500 mt-1">
                            @if($team->hackathon->hasStarted())
                                Create and share your hackathon project
                            @elseif($team->hackathon->hasEnded())
                                Hackathon has ended
                            @else
                                Waiting for hackathon to start
                            @endif
                        </p>
                    </div>
                    @if($isMember && !$team->project && $isLeader && $team->hackathon->hasStarted() && !$team->hackathon->hasEnded())
                        <button onclick="openCreateProjectModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="ri-add-line mr-2"></i>
                            Create Project
                        </button>
                    @endif
                </div>

                @if($team->project)
                    <!-- Project Details -->
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-slate-700">Project Title</label>
                            <p class="text-slate-800 font-medium mt-1">{{ $team->project->title }}</p>
                        </div>

                        @if($team->project->description)
                            <div>
                                <label class="text-sm font-medium text-slate-700">Description</label>
                                <p class="text-slate-600 mt-1 whitespace-pre-wrap">{{ $team->project->description }}</p>
                            </div>
                        @endif

                        @if($team->project->url)
                            <div>
                                <label class="text-sm font-medium text-slate-700">Project URL</label>
                                <a href="{{ $team->project->url }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 break-all inline-flex items-center mt-1">
                                    {{ $team->project->url }}
                                    <i class="ri-external-link-line ml-1"></i>
                                </a>
                            </div>
                        @endif

                        @if($isLeader && $team->hackathon->hasStarted() && !$team->hackathon->hasEnded())
                            <button onclick="openEditProjectModal()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                                <i class="ri-edit-line mr-2"></i>
                                Edit Details
                            </button>
                        @endif
                    </div>

                    <!-- Project Files -->
                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800">Files</h3>
                                <p class="text-sm text-slate-500 mt-1">{{ $team->project->file_count }}/5 files uploaded</p>
                            </div>
                            @if($isMember && $team->project->canAcceptFiles() && $team->hackathon->hasStarted() && !$team->hackathon->hasEnded())
                                <button onclick="openUploadFilesModal()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="ri-upload-line mr-2"></i>
                                    Upload Files
                                </button>
                            @endif
                        </div>

                        @if($team->project->files->count() > 0)
                            <div class="space-y-2">
                                @foreach($team->project->files as $file)
                                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-200">
                                        <div class="flex items-center space-x-3 flex-1">
                                            <i class="ri-file-line text-2xl text-slate-400"></i>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-800 truncate">{{ $file->original_filename }}</p>
                                                <div class="flex items-center space-x-3 mt-1 text-xs text-slate-500">
                                                    <span>{{ $file->formatted_size }}</span>
                                                    <span>•</span>
                                                    <span>Uploaded by {{ $file->uploader->name }}</span>
                                                    <span>•</span>
                                                    <span>{{ $file->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('home.hackathons.projects.download-file', $file) }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 text-sm font-medium">
                                                <i class="ri-download-line mr-1"></i>
                                                Download
                                            </a>
                                            @if($isMember && $team->hackathon->hasStarted() && !$team->hackathon->hasEnded())
                                                <form method="POST" action="{{ route('home.hackathons.projects.delete-file', $file) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 text-sm font-medium">
                                                        <i class="ri-delete-bin-line mr-1"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-slate-500">
                                <i class="ri-file-line text-4xl mb-2"></i>
                                <p class="text-sm">No files uploaded yet</p>
                            </div>
                        @endif

                        @if(!$team->project->canAcceptFiles())
                            <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-sm text-amber-700">
                                <i class="ri-information-line mr-2"></i>
                                Maximum 5 files reached
                            </div>
                        @endif
                    </div>
                @else
                    <!-- No Project Yet -->
                    <div class="text-center py-12 text-slate-500">
                        @if($team->hackathon->hasStarted() && !$team->hackathon->hasEnded())
                            <i class="ri-code-s-slash-line text-5xl mb-3 text-slate-300"></i>
                            <p class="text-sm font-medium mb-2">No project submitted yet</p>
                            @if($isLeader)
                                <p class="text-xs text-slate-400 mb-4">As the team leader, you can create and manage the project</p>
                            @else
                                <p class="text-xs text-slate-400">Only the team leader can create a project</p>
                            @endif
                        @elseif($team->hackathon->hasEnded())
                            <i class="ri-time-line text-5xl mb-3 text-slate-300"></i>
                            <p class="text-sm font-medium mb-2">Hackathon has ended</p>
                            <p class="text-xs text-slate-400">No project was submitted</p>
                        @else
                            <i class="ri-calendar-line text-5xl mb-3 text-slate-300"></i>
                            <p class="text-sm font-medium mb-2">Waiting for hackathon to start</p>
                            <p class="text-xs text-slate-400">Projects can be created once the hackathon begins on {{ $team->hackathon->start_date->format('M d, Y') }}</p>
                            @if($isLeader)
                                <p class="text-xs text-slate-400 mt-2">You will be able to create the project once the hackathon starts</p>
                            @endif
                        @endif
                    </div>
                @endif
            </div>

            <!-- Team Members -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-slate-800">Team Members ({{ $team->member_count }})</h2>
                </div>
                
                <div class="space-y-4">
                    <!-- Leader -->
                    <div class="flex items-center justify-between p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                        <div class="flex items-center space-x-4">
                            <x-avatar 
                                :src="$team->leader->avatar ?? null"
                                :name="$team->leader->name ?? 'User'"
                                size="md"
                                :color="$team->leader->avatar_color ?? null"
                            />
                            <div>
                                <p class="font-semibold text-slate-800">{{ $team->leader->name }}</p>
                                <p class="text-sm text-indigo-600">Team Leader</p>
                                @if($team->leader->email)
                                    <p class="text-xs text-slate-500">{{ $team->leader->email }}</p>
                                @endif
                            </div>
                        </div>
                        @if($isLeader)
                            <span class="text-xs bg-indigo-600 text-white px-2 py-1 rounded-full">You</span>
                        @endif
                    </div>
                    
                    <!-- Members -->
                    @foreach($team->members as $member)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg border border-slate-100">
                            <div class="flex items-center space-x-4">
                                <x-avatar 
                                    :src="$member->user->avatar ?? null"
                                    :name="$member->user->name ?? 'User'"
                                    size="md"
                                    :color="$member->user->avatar_color ?? null"
                                />
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $member->user->name }}</p>
                                    <p class="text-sm text-slate-600">Member</p>
                                    @if($member->user->email)
                                        <p class="text-xs text-slate-500">{{ $member->user->email }}</p>
                                    @endif
                                    @if($member->skills)
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach($member->skills as $skill)
                                                <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full">{{ $skill }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if($isLeader && $member->user->id !== auth()->id())
                                <button onclick="removeMember({{ $member->id }})" class="text-red-500 hover:text-red-700">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                <!-- Pending Invitations -->
                @if($isLeader && $pendingInvitations->count() > 0)
                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <div class="flex items-center mb-4">
                            <h3 class="text-lg font-semibold text-slate-800">Pending Invitations</h3>
                            <span class="ml-2 text-sm text-slate-500">({{ $pendingInvitations->count() }})</span>
                        </div>
                        <div class="space-y-4">
                            @foreach($pendingInvitations as $invitation)
                                <div class="group flex items-center justify-between p-3 border border-slate-200 rounded-lg">
                                    <div class="flex items-center space-x-3 flex-1">
                                        <x-avatar 
                                            :src="$invitation->invitee->avatar ?? null"
                                            :name="$invitation->invitee->name ?? 'User'"
                                            size="sm"
                                            :color="$invitation->invitee->avatar_color ?? null"
                                        />
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm font-medium text-slate-800 truncate">{{ $invitation->invitee->name }}</p>
                                                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">Pending</span>
                                            </div>
                                            @if($invitation->message)
                                                <p class="text-xs text-slate-600 mt-1.5 italic line-clamp-2">
                                                    "{{ $invitation->message }}" <span class="text-slate-400">- {{ $invitation->created_at->diffForHumans() }}</span>
                                                </p>
                                            @else
                                                <p class="text-xs text-slate-500 mt-0.5">
                                                    {{ $invitation->created_at->diffForHumans() }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <button 
                                        type="button"
                                        onclick="openCancelInvitationModal({{ $invitation->id }})"
                                        class="flex items-center gap-1 text-xs text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors font-medium">
                                        <i class="ri-delete-bin-line"></i>
                                        Cancel
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                <!-- Join Requests -->
                @if($isLeader && count($joinRequests) > 0)
                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <div class="flex items-center mb-4">
                            <i class="ri-user-add-line text-blue-500 text-xl mr-2"></i>
                            <h3 class="text-lg font-semibold text-slate-800">Join Requests</h3>
                            <span class="ml-2 text-sm text-slate-500">({{ count($joinRequests) }})</span>
                        </div>
                        <div class="space-y-2">
                            @foreach($joinRequests as $request)
                                <div class="group flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-transparent border border-blue-200 rounded-lg">
                                    <div class="flex items-center space-x-3 flex-1">
                                        <x-avatar 
                                            :src="$request->user->avatar ?? null"
                                            :name="$request->user->name ?? 'User'"
                                            size="sm"
                                            :color="$request->user->avatar_color ?? null"
                                        />
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-slate-800 truncate">{{ $request->user->name }}</p>
                                            <p class="text-xs text-slate-500 mt-0.5">
                                                <i class="ri-time-line mr-1"></i>
                                                Requested {{ $request->created_at->diffForHumans() }}
                                            </p>
                                            @if($request->message)
                                                <p class="text-xs text-slate-600 mt-1.5 italic line-clamp-2">"{{ $request->message }}"</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-3 flex items-center space-x-2 flex-shrink-0">
                                        <form method="POST" action="{{ route('home.hackathons.join-requests.accept') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="request_id" value="{{ $request->id }}">
                                            <button type="submit" class="text-xs bg-green-600 text-white px-4 py-1.5 rounded-lg hover:bg-green-700 transition-colors font-medium">
                                                <i class="ri-check-line mr-1"></i>
                                                Accept
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('home.hackathons.join-requests.reject') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="request_id" value="{{ $request->id }}">
                                            <button type="submit" class="text-xs bg-slate-100 text-slate-600 px-4 py-1.5 rounded-lg hover:bg-red-50 hover:text-red-600 transition-colors font-medium">
                                                <i class="ri-close-line mr-1"></i>
                                                Decline
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>

        <!-- Sidebar -->
        <div class="w-full lg:w-80 lg:flex-shrink-0">
            <div class="space-y-6 sticky top-8">
                <!-- Quick Actions -->
                @if($isLeader)
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                        <h3 class="font-semibold text-slate-800 mb-4">Team Admin</h3>
                        <div class="space-y-3">
                            <button onclick="editTeam()" class="w-full flex items-center justify-center p-3 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                <i class="ri-edit-line text-indigo-600 mr-2"></i>
                                <span class="text-sm font-medium text-indigo-700">Edit Team</span>
                            </button>
                            
                            @if($team->hasAvailableSpots() && $team->hackathon->isRegistrationOpen())
                                <button onclick="openInviteModal()" class="w-full flex items-center justify-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                    <i class="ri-user-add-line text-green-600 mr-2"></i>
                                    <span class="text-sm font-medium text-green-700">Invite Members</span>
                                </button>
                            @endif
                            
                            <button type="button" onclick="openDisbandTeamModal()" class="w-full flex items-center justify-center p-3 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                                <i class="ri-team-line mr-2"></i>
                                Disband Team
                            </button>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                        <h3 class="font-semibold text-slate-800 mb-4">Actions</h3>
                        <button type="button" onclick="openLeaveTeamModal()" class="w-full flex items-center justify-center p-3 bg-slate-50 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors">
                            <i class="ri-logout-circle-line mr-2"></i>
                            Leave Team
                        </button>
                    </div>
                @endif

                <!-- Hackathon Details -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Hackathon Details</h3>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 rounded-lg p-3 border border-slate-100">
                            <span class="text-xs text-slate-500 uppercase tracking-wide">Start Date</span>
                            <p class="text-sm font-semibold text-slate-800 mt-1">{{ $team->hackathon->start_date->format('M d') }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $team->hackathon->start_date->format('Y') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-3 border border-slate-100">
                            <span class="text-xs text-slate-500 uppercase tracking-wide">End Date</span>
                            <p class="text-sm font-semibold text-slate-800 mt-1">{{ $team->hackathon->end_date->format('M d') }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $team->hackathon->end_date->format('Y') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-3 border border-slate-100">
                            <span class="text-xs text-slate-500 uppercase tracking-wide">Location</span>
                            <p class="text-sm font-semibold text-slate-800 mt-1 flex items-center">
                                <i class="ri-map-pin-line mr-1 text-xs"></i>
                                {{ $team->hackathon->location ?? 'Online' }}
                            </p>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-3 border border-slate-100">
                            <span class="text-xs text-slate-500 uppercase tracking-wide">Format</span>
                            <p class="text-sm font-semibold text-slate-800 mt-1">
                                @if(is_object($team->hackathon->format) && method_exists($team->hackathon->format, 'getLabel'))
                                    {{ $team->hackathon->format->getLabel() }}
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $team->hackathon->format ?? 'online')) }}
                                @endif
                            </p>
                        </div>
                        @if($team->hackathon->prize_pool)
                            <div class="bg-slate-50 rounded-lg p-3 border border-slate-100">
                                <span class="text-xs text-slate-500 uppercase tracking-wide">Prize Pool</span>
                                <p class="text-sm font-semibold text-slate-800 mt-1 flex items-center">
                                    {{ $team->hackathon->formatted_prize_pool }}
                                </p>
                            </div>
                        @endif
                        <div class="bg-slate-50 rounded-lg p-3 border border-slate-100">
                            <span class="text-xs text-slate-500 uppercase tracking-wide">Organizer</span>
                            <p class="text-sm font-semibold text-slate-800 mt-1 flex items-center">
                                <i class="ri-user-line mr-1 text-xs"></i>
                                {{ $team->hackathon->creator->name ?? 'Unknown' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <h4 class="font-semibold text-slate-800 mb-2 text-sm">About</h4>
                        <p class="text-slate-600 text-sm line-clamp-3">{{ Str::limit($team->hackathon->description, 100) }}</p>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('hackathons.show', $team->hackathon) }}" class="block text-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors text-sm font-medium">
                            <i class="ri-arrow-right-line mr-2"></i>
                            View Full Details
                        </a>
                    </div>
                </div>

                <!-- Team Stats -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                    <h3 class="font-semibold text-slate-800 mb-4">Team Statistics</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Team Members</span>
                            <span class="text-sm font-semibold text-slate-800">{{ $team->member_count }} / {{ $team->hackathon->max_team_size }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Available Spots</span>
                            <span class="text-sm font-semibold text-slate-800">{{ max(0, $team->hackathon->max_team_size - $team->member_count) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Team Created</span>
                            <span class="text-sm font-semibold text-slate-800">{{ $team->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Team Modal -->
<div id="editTeamModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-800">Edit Team</h3>
                <button onclick="closeEditTeamModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            
            <form id="editTeamForm" method="POST" action="{{ route('home.hackathons.teams.update') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="team_id" value="{{ $team->id }}">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Team Name</label>
                        <input type="text" name="name" value="{{ $team->name }}" 
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ $team->description }}</textarea>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" name="is_public" {{ $team->is_public ?? true ? 'checked' : '' }}
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
                        <label class="ml-2 text-sm text-slate-700">
                            Public team - Accept join requests from others
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditTeamModal()" 
                            class="px-4 py-2 text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Update Team
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Invite Members Modal -->
<div id="inviteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-800">Invite Members to Team</h3>
                <button onclick="closeInviteModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            
            <!-- Pending Invitations -->
            @if($pendingInvitations->count() > 0)
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-slate-700 mb-3">Pending Invitations</h4>
                    <div class="space-y-2">
                        @foreach($pendingInvitations as $invitation)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <x-avatar 
                                        :src="$invitation->invitee->avatar ?? null"
                                        :name="$invitation->invitee->name ?? 'User'"
                                        size="sm"
                                        :color="$invitation->invitee->avatar_color ?? null"
                                    />
                                    <div>
                                        <p class="text-sm font-medium text-slate-800">{{ $invitation->invitee->name }}</p>
                                        <p class="text-xs text-slate-500">Invited {{ $invitation->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <form method="POST" action="{{ route('home.hackathons.teams.invite') }}">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->id }}">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Search & Invite Users</label>
                        
                        <!-- Selected Users Container -->
                        <div id="selectedUsersContainer" class="mb-3 space-y-2"></div>
                        
                        <input type="text" 
                               id="userSearchInput"
                               oninput="searchUsersLive()"
                               placeholder="Search users by name or email..." 
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <div id="userSearchResults" class="mt-2 hidden max-h-60 overflow-y-auto border border-slate-200 rounded-lg"></div>
                        <div class="text-xs text-slate-500 mt-1">
                            Max team size: {{ $team->hackathon->max_team_size }} members
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Personal Message (Optional)</label>
                        <textarea name="message" rows="2" 
                                  placeholder="Add a personal message to the invitation..."
                                  class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                </div>
                
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeInviteModal()"
                                class="px-4 py-2 text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button type="submit" id="sendInvitationBtn"
                                class="px-4 py-2 bg-slate-300 text-slate-500 rounded-lg cursor-not-allowed transition-colors"
                                disabled>
                            Send Invitation
                        </button>
                    </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Team management functions
(function() {
    function editTeam() {
        document.getElementById('editTeamModal').classList.remove('hidden');
    }

    function closeEditTeamModal() {
        document.getElementById('editTeamModal').classList.add('hidden');
    }

    function removeMember(memberId) {
        openRemoveMemberModal(memberId);
    }

    function openInviteModal() {
        document.getElementById('inviteModal').classList.remove('hidden');
    }

    function closeInviteModal() {
        document.getElementById('inviteModal').classList.add('hidden');
    }

    function searchUsersLive() {
        const searchQuery = document.getElementById('userSearchInput').value;
        const resultsDiv = document.getElementById('userSearchResults');
        
        if (searchQuery.length < 2) {
            resultsDiv.classList.add('hidden');
            return;
        }
        
        fetch(`/api/users/search?q=${encodeURIComponent(searchQuery)}&team_id={{ $team->id }}`)
            .then(response => response.json())
            .then(data => {
                const users = data.users || [];
                resultsDiv.classList.remove('hidden');
                
                if (users.length === 0) {
                    resultsDiv.innerHTML = '<p class="text-sm text-slate-500 p-3 text-center">No users found</p>';
                    return;
                }
                
                resultsDiv.innerHTML = '<div class="space-y-1 p-2">' +
                    users.map(user => {
                        const userId = user.id;
                        const userName = user.name || user.email || 'User';
                        const userInitial = userName.substring(0, 1).toUpperCase();
                        return `
                        <div class="user-search-result flex items-center space-x-3 p-3 border border-slate-200 rounded-lg hover:bg-indigo-50 cursor-pointer"
                             data-user-id="${userId}" 
                             data-user-name="${userName.replace(/"/g, '&quot;')}">
                            <div class="w-8 h-8 rounded-full bg-slate-300 flex items-center justify-center text-xs text-slate-700 font-medium">
                                ${userInitial}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-800">${userName}</p>
                                ${user.email ? `<p class="text-xs text-slate-500">${user.email}</p>` : ''}
                            </div>
                        </div>`;
                    }).join('') + '</div>';
                
                // Add click event listeners
                resultsDiv.querySelectorAll('.user-search-result').forEach(element => {
                    element.addEventListener('click', function() {
                        selectUser(this.dataset.userId, this.dataset.userName);
                    });
                });
            })
            .catch(error => {
                console.error('Error searching users:', error);
                resultsDiv.innerHTML = '<p class="text-sm text-red-500 p-3 text-center">Error searching users</p>';
            });
    }

    let selectedUsers = [];
    const maxTeamSize = {{ $team->hackathon->max_team_size }};
    
    function selectUser(userId, userName) {
        const isCurrentMember = selectedUsers.some(u => u.id === userId);
        const currentMembers = {{ $team->members->count() }};
        const totalSelected = selectedUsers.length + currentMembers;
        
        // Check if user is already selected
        if (isCurrentMember) {
            return;
        }
        
        // Check if adding this user would exceed team size
        if (totalSelected >= maxTeamSize) {
            return;
        }
        
        // Add user to selected list
        selectedUsers.push({ id: userId, name: userName });
        
        // Clear search
        document.getElementById('userSearchInput').value = '';
        document.getElementById('userSearchResults').classList.add('hidden');
        
        // Update UI
        renderSelectedUsers();
    }
    
    function renderSelectedUsers() {
        const container = document.getElementById('selectedUsersContainer');
        const sendBtn = document.getElementById('sendInvitationBtn');
        
        if (selectedUsers.length === 0) {
            container.innerHTML = '';
            // Disable button
            sendBtn.disabled = true;
            sendBtn.className = 'px-4 py-2 bg-slate-300 text-slate-500 rounded-lg cursor-not-allowed transition-colors';
            return;
        }
        
        // Enable button
        sendBtn.disabled = false;
        sendBtn.className = 'px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors';
        
        const maxSpots = maxTeamSize - {{ $team->members->count() }};
        const canAddMore = selectedUsers.length < maxSpots;
        
        container.innerHTML = selectedUsers.map((user, index) => `
            <div class="flex items-center justify-between p-2 bg-indigo-50 border border-indigo-200 rounded-lg">
                <div class="flex items-center space-x-2">
                    <div class="w-6 h-6 rounded-full bg-indigo-600 flex items-center justify-center text-xs text-white font-medium">
                        ${user.name.substring(0, 1).toUpperCase()}
                    </div>
                    <span class="text-sm font-medium text-slate-800">${user.name}</span>
                </div>
                <button type="button" onclick="removeSelectedUser(${index})" 
                        class="text-red-600 hover:text-red-700">
                    <i class="ri-close-line"></i>
                </button>
                <input type="hidden" name="user_ids[]" value="${user.id}">
            </div>
        `).join('') + `
            <div class="text-xs text-slate-500">
                ${selectedUsers.length} selected. ${maxSpots - selectedUsers.length} more spot(s) available.
            </div>
        `;
    }
    
    function removeSelectedUser(index) {
        selectedUsers.splice(index, 1);
        renderSelectedUsers();
    }
    
    // Expose functions globally
    window.editTeam = editTeam;
    window.closeEditTeamModal = closeEditTeamModal;
    window.removeMember = removeMember;
    window.openInviteModal = openInviteModal;
    window.closeInviteModal = closeInviteModal;
    window.searchUsersLive = searchUsersLive;
    window.selectUser = selectUser;
    window.removeSelectedUser = removeSelectedUser;
})();
</script>

<!-- Cancellation Confirmation Modal -->
<x-confirm-modal
    id="cancelInvitationModal"
    title="Cancel Invitation"
    message="Are you sure you want to cancel this invitation?"
    confirmText="Cancel Invitation"
    cancelText="Keep Invitation"
    confirmAction="{{ route('home.hackathons.invitations.delete') }}"
    confirmMethod="DELETE"
    danger="true"
>
    <input type="hidden" name="invitation_id" id="cancelInvitationId">
</x-confirm-modal>

<!-- Disband Team Modal -->
<x-confirm-modal
    id="disbandTeamModal"
    title="Disband Team"
    message="{{ $team->members->count() > 0 ? 'Are you sure you want to disband this team? All ' . $team->members->count() . ' member(s) will be removed permanently.' : 'Are you sure you want to disband this team?' }}"
    confirmText="Yes, Disband Team"
    cancelText="Cancel"
    confirmAction="{{ route('home.hackathons.teams.disband') }}"
    confirmMethod="DELETE"
    danger="true"
>
    <input type="hidden" name="team_id" value="{{ $team->id }}">
</x-confirm-modal>

<!-- Leave Team Modal -->
<x-confirm-modal
    id="leaveTeamModal"
    title="Leave Team"
    message="Are you sure you want to leave this team?"
    confirmText="Leave Team"
    cancelText="Cancel"
    confirmAction="{{ route('home.hackathons.teams.leave') }}"
    confirmMethod="POST"
    danger="true"
>
    <input type="hidden" name="team_id" value="{{ $team->id }}">
</x-confirm-modal>

<!-- Remove Member Modal -->
<x-confirm-modal
    id="removeMemberModal"
    title="Remove Member"
    message="Are you sure you want to remove this member from the team?"
    confirmText="Remove Member"
    cancelText="Cancel"
    confirmAction=""
    confirmMethod="DELETE"
    danger="true"
>
    <input type="hidden" name="member_id" id="removeMemberId">
</x-confirm-modal>

<script>
function openCancelInvitationModal(invitationId) {
    document.getElementById('cancelInvitationId').value = invitationId;
    const modal = document.getElementById('cancelInvitationModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.classList.add('overflow-hidden');
        
        // Wait for Alpine.js to initialize if needed
        if (modal.__x && modal.__x.$data) {
            modal.__x.$data.show = true;
        } else {
            // If Alpine isn't ready, use a different approach
            setTimeout(() => {
                if (modal.__x && modal.__x.$data) {
                    modal.__x.$data.show = true;
                } else {
                    // Fallback: just show the modal directly
                    modal.style.display = 'block';
                }
            }, 10);
        }
    }
}

window.openCancelInvitationModal = openCancelInvitationModal;

function openDisbandTeamModal() {
    const modal = document.getElementById('disbandTeamModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.classList.add('overflow-hidden');
        if (modal.__x && modal.__x.$data) {
            modal.__x.$data.show = true;
        } else {
            setTimeout(() => {
                if (modal.__x && modal.__x.$data) {
                    modal.__x.$data.show = true;
                }
            }, 10);
        }
    }
}

function openLeaveTeamModal() {
    const modal = document.getElementById('leaveTeamModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.classList.add('overflow-hidden');
        if (modal.__x && modal.__x.$data) {
            modal.__x.$data.show = true;
        } else {
            setTimeout(() => {
                if (modal.__x && modal.__x.$data) {
                    modal.__x.$data.show = true;
                }
            }, 10);
        }
    }
}

function openRemoveMemberModal(memberId) {
    document.getElementById('removeMemberId').value = memberId;
    const modal = document.getElementById('removeMemberModal');
    if (modal) {
        // TODO: Set the proper action URL for member removal
        const form = modal.querySelector('form');
        if (form) {
            form.action = `/dashboard/hackathons/teams/members/${memberId}`;
        }
        
        modal.style.display = 'block';
        document.body.classList.add('overflow-hidden');
        if (modal.__x && modal.__x.$data) {
            modal.__x.$data.show = true;
        } else {
            setTimeout(() => {
                if (modal.__x && modal.__x.$data) {
                    modal.__x.$data.show = true;
                }
            }, 10);
        }
    }
}

window.openDisbandTeamModal = openDisbandTeamModal;
window.openLeaveTeamModal = openLeaveTeamModal;
window.openRemoveMemberModal = openRemoveMemberModal;

// Project management functions
function openCreateProjectModal() {
    document.getElementById('createProjectModal').classList.remove('hidden');
}

function closeCreateProjectModal() {
    document.getElementById('createProjectModal').classList.add('hidden');
}

function openEditProjectModal() {
    document.getElementById('editProjectModal').classList.remove('hidden');
}

function closeEditProjectModal() {
    document.getElementById('editProjectModal').classList.add('hidden');
}

function openUploadFilesModal() {
    document.getElementById('uploadFilesModal').classList.remove('hidden');
}

function closeUploadFilesModal() {
    document.getElementById('uploadFilesModal').classList.add('hidden');
}

window.openCreateProjectModal = openCreateProjectModal;
window.closeCreateProjectModal = closeCreateProjectModal;
window.openEditProjectModal = openEditProjectModal;
window.closeEditProjectModal = closeEditProjectModal;
window.openUploadFilesModal = openUploadFilesModal;
window.closeUploadFilesModal = closeUploadFilesModal;
</script>

<!-- Create Project Modal -->
<div id="createProjectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-800">Create Project</h3>
                <button onclick="closeCreateProjectModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            
            <form method="POST" action="{{ route('home.hackathons.projects.store', $team) }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Project Title *</label>
                        <input type="text" name="title" required 
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                        <textarea name="description" rows="4"
                                  class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Project URL</label>
                        <input type="url" name="url" 
                               placeholder="https://yourproject.com"
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeCreateProjectModal()" 
                            class="px-4 py-2 text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Create Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
@if($team->project)
<div id="editProjectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-800">Edit Project</h3>
                <button onclick="closeEditProjectModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            
            <form method="POST" action="{{ route('home.hackathons.projects.update', $team->project) }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Project Title *</label>
                        <input type="text" name="title" value="{{ $team->project->title }}" required 
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                        <textarea name="description" rows="4"
                                  class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ $team->project->description }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Project URL</label>
                        <input type="url" name="url" value="{{ $team->project->url }}"
                               placeholder="https://yourproject.com"
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditProjectModal()" 
                            class="px-4 py-2 text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Update Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Files Modal -->
@if($team->project && $isMember)
<div id="uploadFilesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-800">Upload Files</h3>
                <button onclick="closeUploadFilesModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>
            
            <form method="POST" action="{{ route('home.hackathons.projects.upload-files', $team->project) }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Select Files</label>
                        <input type="file" name="files[]" multiple 
                               accept=".pdf,.doc,.docx,.zip,.rar,.txt,.md,.py,.js,.java,.cpp,.c,.html,.css,.xml,.json,.csv,.sql,.xls,.xlsx,.ppt,.pptx,.png,.jpg,.jpeg,.gif,.svg,.psd,.ai,.fig,.sketch"
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <p class="text-xs text-slate-500 mt-2">
                            Maximum 5 files, 24MB each. Remaining slots: {{ 5 - $team->project->file_count }}
                        </p>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <p class="text-xs text-blue-700">
                            <i class="ri-information-line mr-1"></i>
                            Allowed formats: PDF, DOC, ZIP, TXT, Code files, Images, etc.
                        </p>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeUploadFilesModal()" 
                            class="px-4 py-2 text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="ri-upload-line mr-2"></i>
                        Upload Files
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endif
@endpush
@endsection
