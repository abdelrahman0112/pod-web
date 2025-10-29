@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Admin Dashboard
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Design content will be pasted here -->
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Total Users</h3>
                        <p class="text-2xl font-bold text-blue-600">1,248</p>
                        <p class="text-sm text-gray-500">+15% this month</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H8a2 2 0 00-2-2V6m8 0H8m0 0v-.5a.5.5 0 01.5-.5h7a.5.5 0 01.5.5V6"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Active Jobs</h3>
                        <p class="text-2xl font-bold text-green-600">156</p>
                        <p class="text-sm text-gray-500">+8 this week</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Events</h3>
                        <p class="text-2xl font-bold text-purple-600">24</p>
                        <p class="text-sm text-gray-500">3 this month</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-500 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Hackathons</h3>
                        <p class="text-2xl font-bold text-orange-600">8</p>
                        <p class="text-sm text-gray-500">2 active</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Client Conversion Requests -->
            <div class="card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Client Conversion Requests</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        5 Pending
                    </span>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Ahmed Hassan</h4>
                            <p class="text-sm text-gray-600">TechCorp • tech@techcorp.com</p>
                            <p class="text-xs text-gray-500">Requested 2 days ago</p>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                Approve
                            </button>
                            <button class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                Reject
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Sarah Johnson</h4>
                            <p class="text-sm text-gray-600">DataFlow Inc • info@dataflow.com</p>
                            <p class="text-xs text-gray-500">Requested 1 day ago</p>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                Approve
                            </button>
                            <button class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                Reject
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="#" class="btn-secondary w-full text-center">View All Requests</a>
                </div>
            </div>

            <!-- Internship Applications -->
            <div class="card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Internship Applications</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        12 New
                    </span>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Fatima Ali</h4>
                            <p class="text-sm text-gray-600">Cairo University • Computer Science</p>
                            <p class="text-xs text-gray-500">Applied 1 hour ago</p>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                Review
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-900">Mohamed Ibrahim</h4>
                            <p class="text-sm text-gray-600">AUC • Data Science</p>
                            <p class="text-xs text-gray-500">Applied 3 hours ago</p>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                                Review
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="#" class="btn-secondary w-full text-center">View All Applications</a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Content Management -->
            <div class="lg:col-span-2">
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Content Activity</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3 p-3 border border-gray-200 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H8a2 2 0 00-2-2V6m8 0H8m0 0v-.5a.5.5 0 01.5-.5h7a.5.5 0 01.5.5V6"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">
                                    New job posted: <strong>Senior Data Scientist at TechCorp</strong>
                                </p>
                                <p class="text-xs text-gray-500">Posted by Ahmed Hassan • 2 hours ago</p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 text-xs">View</button>
                                <button class="text-red-600 hover:text-red-800 text-xs">Hide</button>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3 border border-gray-200 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">
                                    New event created: <strong>AI in Healthcare Workshop</strong>
                                </p>
                                <p class="text-xs text-gray-500">Created by Admin • 1 day ago</p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 text-xs">View</button>
                                <button class="text-red-600 hover:text-red-800 text-xs">Edit</button>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3 p-3 border border-gray-200 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center">
                                    <svg class="h-4 w-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">
                                    Flagged post: <strong>Inappropriate content reported</strong>
                                </p>
                                <p class="text-xs text-gray-500">Reported by community • 3 hours ago</p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 text-xs">Review</button>
                                <button class="text-red-600 hover:text-red-800 text-xs">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="space-y-6">
                <!-- Admin Tools -->
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin Tools</h3>
                    <div class="space-y-2">
                        <a href="{{ route('filament.admin.resources.event-categories.index') }}" class="block w-full btn-primary text-center">
                            Event Categories
                        </a>
                        <a href="#" class="block w-full btn-secondary text-center">
                            User Management
                        </a>
                        <a href="#" class="block w-full btn-secondary text-center">
                            Content Moderation
                        </a>
                        <a href="#" class="block w-full btn-secondary text-center">
                            Analytics Dashboard
                        </a>
                        <a href="#" class="block w-full btn-secondary text-center">
                            System Settings
                        </a>
                    </div>
                </div>

                <!-- System Status -->
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">System Status</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Database</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Healthy
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">API Services</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Online
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Chat System</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Maintenance
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">AI Assistant</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
