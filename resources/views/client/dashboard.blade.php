@extends('layouts.app')

@section('title', 'Client Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Client Dashboard - {{ auth()->user()->company_name ?? 'Your Company' }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 00-2 2H8a2 2 0 00-2-2V6m8 0H8m0 0v-.5a.5.5 0 01.5-.5h7a.5.5 0 01.5.5V6z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Active Jobs</h3>
                        <p class="text-2xl font-bold text-blue-600">12</p>
                        <p class="text-sm text-gray-500">Currently posted</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Applications</h3>
                        <p class="text-2xl font-bold text-green-600">89</p>
                        <p class="text-sm text-gray-500">This month</p>
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
                        <p class="text-2xl font-bold text-purple-600">3</p>
                        <p class="text-sm text-gray-500">Upcoming</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-500 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Views</h3>
                        <p class="text-2xl font-bold text-orange-600">1.2K</p>
                        <p class="text-sm text-gray-500">Profile views</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent Applications -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Applications</h3>
                        <a href="{{ route('jobs.index') }}" class="text-primary-600 hover:text-primary-800 text-sm">View All</a>
                    </div>
                    <div class="space-y-4">
                        <!-- Application items will be loaded dynamically -->
                        <div class="border-b border-gray-200 pb-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">Ahmed Mohamed</h4>
                                    <p class="text-sm text-gray-600">Applied for: Senior Data Scientist</p>
                                    <p class="text-sm text-gray-500">5 years experience • Cairo University</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="btn-secondary text-sm">View</button>
                                    <button class="btn-primary text-sm">Contact</button>
                                </div>
                            </div>
                        </div>
                        <div class="border-b border-gray-200 pb-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">Sara Ibrahim</h4>
                                    <p class="text-sm text-gray-600">Applied for: ML Engineer</p>
                                    <p class="text-sm text-gray-500">3 years experience • AUC</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="btn-secondary text-sm">View</button>
                                    <button class="btn-primary text-sm">Contact</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Analytics -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('jobs.create') }}" class="block w-full btn-primary text-center">
                            Post New Job
                        </a>
                        <a href="{{ route('events.create') }}" class="block w-full btn-secondary text-center">
                            Create Event
                        </a>
                        <a href="{{ route('profile.edit') }}" class="block w-full btn-secondary text-center">
                            Update Company Profile
                        </a>
                    </div>
                </div>

                <!-- Performance Summary -->
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">This Month</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Job Views</span>
                            <span class="font-medium">2,340</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Applications</span>
                            <span class="font-medium">89</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Interview Requests</span>
                            <span class="font-medium">23</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Profile Views</span>
                            <span class="font-medium">156</span>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="card">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Upcoming Events</h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 bg-blue-100 rounded-lg p-2">
                                <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">Tech Talk: AI in Finance</h4>
                                <p class="text-xs text-gray-600">Tomorrow, 6:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
