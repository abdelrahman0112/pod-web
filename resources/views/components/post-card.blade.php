@props(['post'])

@php
    use Illuminate\Support\Facades\Storage;
@endphp

<article class="bg-white rounded-xl shadow-[0_8px_24px_rgba(0,0,0,0.04)] p-6 relative">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-3 flex-1">
            <x-avatar 
                :src="$post->user->avatar ?? null"
                :name="$post->user->name ?? 'User'"
                size="sm"
                :color="$post->user->avatar_color ?? null" />
            <div>
                <h4 class="font-semibold text-slate-800">{{ $post->user->name ?? 'Anonymous' }}</h4>
            <p class="text-sm text-slate-500">{{ $post->user->job_title ?? 'Member' }} • 
                <a href="{{ route('posts.show', $post) }}" class="hover:text-indigo-600 transition-colors">{{ $post->created_at->diffForHumans() }}</a>
            </p>
            </div>
        </div>
        
        @auth
            @if($post->user_id === auth()->id())
                <div class="relative">
                    <button onclick="togglePostOptions({{ $post->id }})" class="text-slate-400 hover:text-slate-600 transition-colors p-1">
                        <i class="ri-more-2-fill text-lg"></i>
                    </button>
                    
                    <div id="post-options-{{ $post->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 z-10">
                        <div class="py-1">
                            <a href="{{ route('posts.edit', $post) }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                <i class="ri-pencil-line mr-2"></i>
                                Edit
                            </a>
                            <button onclick="deletePost({{ $post->id }})" class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <i class="ri-delete-bin-line mr-2"></i>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        @endauth
    </div>
    
    <p class="text-slate-700 mb-4">{{ $post->content }}</p>
    
    @if($post->images && count($post->images) > 0)
        <div class="mb-4" data-post-images='@json($post->images)'>
            <div class="grid {{ count($post->images) === 1 ? 'grid-cols-1' : 'grid-cols-2' }} gap-2">
                @foreach(array_slice($post->images, 0, 4) as $index => $image)
                    <div class="relative w-full aspect-square cursor-pointer group" onclick="openLightbox({{ $post->id }}, {{ $index }})" data-post-id="{{ $post->id }}" data-image-index="{{ $index }}">
                        <img src="{{ Storage::url($image) }}" 
                             alt="Post image" 
                             class="w-full h-full object-cover group-hover:opacity-90 transition-opacity rounded-lg">
                        @if($index === 3 && count($post->images) > 4)
                            <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center rounded-lg">
                                <div class="text-center text-white">
                                    <div class="text-4xl font-bold mb-1">+{{ count($post->images) - 4 }}</div>
                                    <div class="text-xl">more photos</div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    
    @if($post->hashtags && count($post->hashtags) > 0)
        <div class="flex flex-wrap gap-2 mb-4">
            @foreach($post->hashtags as $hashtag)
                <span class="bg-indigo-100 text-indigo-600 px-2 py-1 rounded-full text-xs">{{ $hashtag }}</span>
            @endforeach
        </div>
    @endif
    
    <div class="-mx-6 -mb-6 border-t border-slate-100">
        <div class="grid grid-cols-3 divide-x divide-slate-100">
            <button class="like-btn flex items-center justify-center space-x-2 py-4 px-6 transition-colors {{ $post->is_liked ? 'text-red-500' : 'text-slate-500' }}" data-post-id="{{ $post->id }}" data-liked="{{ $post->is_liked ? 'true' : 'false' }}">
                <div class="w-5 h-5 flex items-center justify-center">
                    <i class="{{ $post->is_liked ? 'ri-heart-fill' : 'ri-heart-line' }}"></i>
                </div>
                <span class="text-sm">Like</span>
                <span class="text-sm likes-count">{{ $post->likes_count ?? 0 }}</span>
            </button>
            <button class="comment-btn flex items-center justify-center space-x-2 hover:text-indigo-600 transition-colors text-slate-500 py-4 px-6" data-post-id="{{ $post->id }}">
                <div class="w-5 h-5 flex items-center justify-center">
                    <i class="ri-chat-3-line"></i>
                </div>
                <span class="text-sm">Comment</span>
                <span class="text-sm comments-count">{{ $post->comments_count ?? 0 }}</span>
            </button>
            <button class="flex items-center justify-center space-x-2 hover:text-indigo-600 transition-colors text-slate-500 py-4 px-6">
                <div class="w-5 h-5 flex items-center justify-center">
                    <i class="ri-share-line"></i>
                </div>
                <span class="text-sm">Share</span>
            </button>
        </div>
    </div>
</article>
