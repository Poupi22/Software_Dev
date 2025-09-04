<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\Post;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    public function show(Forum $forum, Request $request)
    {
        $forumThreads = $forum->threads()
            ->withCount('posts')
            ->with(['user', 'posts.user'])
            ->latest()
            ->get();

        $thread = null;
        $posts = collect();

        // Handle both thread parameter from request and from route
        $threadId = $request->thread ?? $request->route('thread');

        if ($threadId) {
            $thread = Thread::where('forum_id', $forum->id)
                ->with(['posts.user'])
                ->find($threadId);

            if ($thread) {
                $posts = $thread->posts()->oldest()->get();
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'forum' => $forum,
                'thread' => $thread,
                'posts' => $posts,
                'html' => [
                    'messagesContainer' => view('etudiant.partials.messages', [
                        'posts' => $posts,
                        'thread' => $thread
                    ])->render(),
                    'inputContainer' => view('etudiant.partials.reply-form', [
                        'thread' => $thread
                    ])->render()
                ]
            ]);
        }

        return view('etudiant.message', [
            'forum' => $forum,
            'forumThreads' => $forumThreads,
            'thread' => $thread,
            'posts' => $posts
        ]);
    }

    public function storePost(Request $request, Thread $thread)
    {
        try {
            $validated = $request->validate([
                'body' => 'required|string|max:1000'
            ]);

            $post = Post::create([
                'body' => $validated['body'],
                'thread_id' => $thread->id,
                'user_id' => auth()->id(),
            ])->load('user');

            // Check if it's an AJAX/JSON request
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'post' => $post,
                    'message' => 'Message posted successfully!',
                ]);
            }

            // ✅ FIXED: Redirect back to the forum page with the thread parameter
            return redirect()->route('etudiant.message', [
                'forum' => $thread->forum_id,
                'thread' => $thread->id
            ])->with('success', 'Message posted successfully!');

        } catch (\Exception $e) {
            // Handle both JSON and regular requests for errors
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Server error: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Error posting message: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'forum_id' => 'required|exists:forums,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:2000'
        ]);

        $thread = Thread::create([
            'title' => $validated['title'],
            'forum_id' => $validated['forum_id'],
            'user_id' => Auth::id()
        ]);

        Post::create([
            'body' => $validated['content'],
            'thread_id' => $thread->id,
            'user_id' => Auth::id()
        ]);

        // ✅ FIXED: Redirect to the forum page with the new thread parameter
        return redirect()->route('etudiant.message', [
            'forum' => $thread->forum_id,
            'thread' => $thread->id
        ])->with('success', 'Thread created successfully!');
    }
}
