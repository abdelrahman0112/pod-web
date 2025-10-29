<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a newly created comment.
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:3000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $validated['post_id'] = $post->id;
        $validated['user_id'] = Auth::id();

        // If replying to a comment, ensure it belongs to the same post
        if ($validated['parent_id']) {
            $parentComment = Comment::find($validated['parent_id']);
            if ($parentComment->post_id !== $post->id) {
                return response()->json(['error' => 'Invalid parent comment'], 400);
            }
        }

        $comment = Comment::create($validated);
        $comment->load('user');

        // Increment post comments count
        $post->increment('comments_count');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
                'comments_count' => $post->fresh()->comments_count,
            ]);
        }

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment)
    {
        if (! $comment->canUserEdit(Auth::user())) {
            abort(403, 'Unauthorized to edit this comment.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:3000',
        ]);

        $comment->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
            ]);
        }

        return redirect()->back()->with('success', 'Comment updated successfully!');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        if (! $comment->canUserEdit(Auth::user())) {
            abort(403, 'Unauthorized to delete this comment.');
        }

        $post = $comment->post;
        $comment->delete();

        // Decrement post comments count
        $post->decrement('comments_count');

        return response()->json([
            'success' => true,
            'comments_count' => $post->fresh()->comments_count,
        ]);
    }

    /**
     * Get comments for a post (AJAX).
     */
    public function index(Post $post)
    {
        $comments = $post->comments()
            ->with(['user', 'replies' => function ($query) {
                $query->with(['user', 'replies.user', 'replies.replies.user'])
                    ->approved()
                    ->oldest();
            }])
            ->approved()
            ->topLevel()
            ->latest()
            ->paginate(20);

        // Transform comments to include reply count and user permissions
        $commentsData = $comments->getCollection()->map(function ($comment) {
            $commentArray = $comment->toArray();
            $commentArray['can_edit'] = $comment->canUserEdit(auth()->user());
            $commentArray['replies_count'] = $comment->replies->count();

            // Process nested replies recursively
            if ($comment->replies) {
                $commentArray['replies'] = $comment->replies->map(function ($reply) {
                    $replyArray = $reply->toArray();
                    $replyArray['can_edit'] = $reply->canUserEdit(auth()->user());

                    // Process nested replies for the reply
                    if ($reply->replies) {
                        $replyArray['replies'] = $reply->replies->map(function ($nestedReply) {
                            $nestedReplyArray = $nestedReply->toArray();
                            $nestedReplyArray['can_edit'] = $nestedReply->canUserEdit(auth()->user());

                            return $nestedReplyArray;
                        });
                    }

                    return $replyArray;
                });
            }

            return $commentArray;
        });

        return response()->json([
            'comments' => [
                'data' => $commentsData,
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total(),
            ],
        ]);
    }

    /**
     * Approve a comment (admin only).
     */
    public function approve(Comment $comment)
    {
        $this->authorize('admin'); // Assuming you have admin authorization

        $comment->update(['is_approved' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * Reject a comment (admin only).
     */
    public function reject(Comment $comment)
    {
        $this->authorize('admin'); // Assuming you have admin authorization

        $comment->update(['is_approved' => false]);

        return response()->json(['success' => true]);
    }
}
