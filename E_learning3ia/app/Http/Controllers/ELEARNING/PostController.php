<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\ForumThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    
   
    /**
     * Remove the specified post from storage.
     */
    // app/Http/Controllers/Elearning/PostController.php
public function destroy($id)
{
    try {
        $post = Post::find($id);
        
        // Check if post exists
        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }

        // Authorization check
        if ($post->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized - You can only delete your own posts'
            ], 403);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error deleting post',
            'error' => $e->getMessage()
        ], 500);
    }
}

   
}