@extends('layouts.app')

@section('title', 'Community Posts')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Community Posts
        </h2>
        <a href="{{ route('posts.create') }}" class="btn-primary">
            Create Post
        </a>
    </div>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Design content will be pasted here -->
        
        <!-- Create Post Card -->
        <div class="card mb-6">
            <div class="flex items-start space-x-4">
                <x-avatar 
                    :src="auth()->user()->avatar ?? null"
                    :name="auth()->user()->name ?? 'User'"
                    size="sm"
                    :color="auth()->user()->avatar_color ?? null" />
                <div class="flex-1">
                    <button class="w-full text-left p-3 border border-gray-300 rounded-lg text-gray-500 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500"
                            onclick="window.location.href='{{ route('posts.create') }}'">
                        What's on your mind, {{ auth()->user()->name }}?
                    </button>
                </div>
            </div>
        </div>

        <!-- Posts Feed -->
        <div class="space-y-6">
            <!-- Sample Post -->
            <div class="card">
                <div class="space-y-4">
                    <!-- Post Header -->
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-3">
                            <x-avatar 
                                name="Sarah Johnson"
                                size="sm" />
                            <div>
                                <h4 class="font-medium text-gray-900">Sarah Johnson</h4>
                                <p class="text-sm text-gray-500">Senior Data Scientist • 2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Post Content -->
                    <div>
                        <p class="text-gray-800">
                            Just finished an amazing project using transformer models for sentiment analysis! 🚀 
                            The results were incredible - achieved 95% accuracy on our custom dataset. 
                            Here are some key insights I learned along the way...
                        </p>
                        
                        <!-- Hashtags -->
                        <div class="mt-3 flex flex-wrap gap-2">
                            <a href="#" class="text-primary-600 hover:text-primary-800 text-sm">#MachineLearning</a>
                            <a href="#" class="text-primary-600 hover:text-primary-800 text-sm">#NLP</a>
                            <a href="#" class="text-primary-600 hover:text-primary-800 text-sm">#Transformers</a>
                            <a href="#" class="text-primary-600 hover:text-primary-800 text-sm">#DataScience</a>
                        </div>
                    </div>

                    <!-- Post Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex items-center space-x-6">
                            <button class="flex items-center space-x-2 text-gray-500 hover:text-primary-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V18m-7-8a2 2 0 01-2-2V6a2 2 0 012-2h2.343M11 7L9 5l2-2m0 0l2 2m-2-2v6"/>
                                </svg>
                                <span class="text-sm">24</span>
                            </button>
                            <button class="flex items-center space-x-2 text-gray-500 hover:text-primary-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span class="text-sm">12</span>
                            </button>
                            <button class="flex items-center space-x-2 text-gray-500 hover:text-primary-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                </svg>
                                <span class="text-sm">Share</span>
                            </button>
                        </div>
                        <button class="text-gray-500 hover:text-gray-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Post with Image -->
            <div class="card">
                <div class="space-y-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-3">
                            <x-avatar 
                                name="Ahmed Hassan"
                                size="sm" />
                            <div>
                                <h4 class="font-medium text-gray-900">Ahmed Hassan</h4>
                                <p class="text-sm text-gray-500">ML Engineer • 4 hours ago</p>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"/>
                            </svg>
                        </button>
                    </div>

                    <div>
                        <p class="text-gray-800">
                            Just deployed my first computer vision model to production! 🎉 
                            It's detecting objects in real-time with amazing accuracy. 
                            Here's a snapshot of the dashboard showing the results.
                        </p>
                        
                        <!-- Image -->
                        <div class="mt-3">
                            <img src="https://via.placeholder.com/600x300/3B82F6/FFFFFF?text=Computer+Vision+Dashboard" 
                                 alt="Computer Vision Dashboard" 
                                 class="w-full rounded-lg">
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <a href="#" class="text-primary-600 hover:text-primary-800 text-sm">#ComputerVision</a>
                            <a href="#" class="text-primary-600 hover:text-primary-800 text-sm">#ObjectDetection</a>
                            <a href="#" class="text-primary-600 hover:text-primary-800 text-sm">#Production</a>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex items-center space-x-6">
                            <button class="flex items-center space-x-2 text-gray-500 hover:text-primary-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V18m-7-8a2 2 0 01-2-2V6a2 2 0 012-2h2.343M11 7L9 5l2-2m0 0l2 2m-2-2v6"/>
                                </svg>
                                <span class="text-sm">18</span>
                            </button>
                            <button class="flex items-center space-x-2 text-gray-500 hover:text-primary-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span class="text-sm">7</span>
                            </button>
                            <button class="flex items-center space-x-2 text-gray-500 hover:text-primary-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                </svg>
                                <span class="text-sm">Share</span>
                            </button>
                        </div>
                        <button class="text-gray-500 hover:text-gray-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Poll Post -->
            <div class="card">
                <div class="space-y-4">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-3">
                            <x-avatar 
                                name="Fatima Ali"
                                size="sm" />
                            <div>
                                <h4 class="font-medium text-gray-900">Fatima Ali</h4>
                                <p class="text-sm text-gray-500">Data Analyst • 6 hours ago</p>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"/>
                            </svg>
                        </button>
                    </div>

                    <div>
                        <p class="text-gray-800">
                            What's your preferred tool for data visualization? Curious to see what the community is using most!
                        </p>
                        
                        <!-- Poll -->
                        <div class="mt-4 space-y-2">
                            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 cursor-pointer">
                                <div class="flex justify-between items-center">
                                    <span>Tableau</span>
                                    <span class="text-sm text-gray-500">35%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-primary-600 h-2 rounded-full" style="width: 35%"></div>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 cursor-pointer">
                                <div class="flex justify-between items-center">
                                    <span>Power BI</span>
                                    <span class="text-sm text-gray-500">28%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-primary-600 h-2 rounded-full" style="width: 28%"></div>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 cursor-pointer">
                                <div class="flex justify-between items-center">
                                    <span>Python (Matplotlib/Plotly)</span>
                                    <span class="text-sm text-gray-500">25%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-primary-600 h-2 rounded-full" style="width: 25%"></div>
                                </div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 cursor-pointer">
                                <div class="flex justify-between items-center">
                                    <span>R (ggplot2)</span>
                                    <span class="text-sm text-gray-500">12%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-primary-600 h-2 rounded-full" style="width: 12%"></div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">42 votes • 2 days left</p>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <a href="#" class="text-primary-600 hover:text-primary-800 text-sm">#DataVisualization</a>
                            <a href="#" class="text-primary-600 hover:text-primary-800 text-sm">#Tools</a>
                            <a href="#" class="text-primary-600 hover:text-primary-800 text-sm">#Poll</a>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex items-center space-x-6">
                            <button class="flex items-center space-x-2 text-gray-500 hover:text-primary-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V18m-7-8a2 2 0 01-2-2V6a2 2 0 012-2h2.343M11 7L9 5l2-2m0 0l2 2m-2-2v6"/>
                                </svg>
                                <span class="text-sm">42</span>
                            </button>
                            <button class="flex items-center space-x-2 text-gray-500 hover:text-primary-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span class="text-sm">15</span>
                            </button>
                            <button class="flex items-center space-x-2 text-gray-500 hover:text-primary-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                </svg>
                                <span class="text-sm">Share</span>
                            </button>
                        </div>
                        <button class="text-gray-500 hover:text-gray-700">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load More -->
        <div class="text-center mt-8">
            <button class="btn-secondary">Load More Posts</button>
        </div>
    </div>
</div>
@endsection
