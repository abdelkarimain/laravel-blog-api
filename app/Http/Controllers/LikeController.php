<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{

    function get_reaction_count($postId)
    {
        $likeCount = Like::where('post_id', $postId)
            ->where('like', true)
            ->count();

        $dislikeCount = Like::where('post_id', $postId)
            ->where('dislike', true)
            ->count();

        return [
            'like_count' => $likeCount,
            'dislike_count' => $dislikeCount
        ];
    }


    function save_like(Request $request)
    {
        $user = Auth::user();
        $existing_like = Like::where('user_id', $user->id)
            ->where('post_id', $request->post_id)
            ->first();

        if (!$existing_like) {
            // If no existing like record, create a new one
            $like = new Like();
            $like->user_id = $user->id;
            $like->post_id = $request->post_id;
        } else {
            // If an existing like record is found, use it
            $like = $existing_like;
        }

        // Initialize variables to track changes
        $likeChanged = false;
        $dislikeChanged = false;

        if ($request->type === 'like') {
            if ($like->like) {
                // If the user already liked the post, remove the like
                $like->like = false;
                $likeChanged = true;
            } else {
                // Toggle like and reset dislike
                $like->like = true;
                $like->dislike = false;
                $likeChanged = true;
                $dislikeChanged = $like->dislike; // Track if dislike was changed
            }
        } else {
            if ($like->dislike) {
                // If the user already disliked the post, remove the dislike
                $like->dislike = false;
                $dislikeChanged = true;
            } else {
                // Toggle dislike and reset like
                $like->dislike = true;
                $like->like = false;
                $dislikeChanged = true;
                $likeChanged = $like->like; // Track if like was changed
            }
        }

        // Check if both like and dislike are false, then delete the like record
        if (!$like->like && !$like->dislike) {
            $like->delete();
        } else {
            // Save changes if any
            if ($likeChanged || $dislikeChanged) {
                $like->save();
            }
        }

        // Prepare response message
        $responseMessage = 'Reaction saved successfully';
        if (!$like->like && !$like->dislike) {
            $responseMessage = 'Reaction removed successfully';
        } elseif ($likeChanged) {
            $responseMessage = 'Like ' . ($like->like ? 'added' : 'removed') . ' successfully';
        } elseif ($dislikeChanged) {
            $responseMessage = 'Dislike ' . ($like->dislike ? 'added' : 'removed') . ' successfully';
        }

        return response()->json([
            'status' => 200,
            'message' => $responseMessage
        ]);
    }

    function get_like_status(Request $request)
    {
        $user = Auth::user();
        $postId = $request->post_id;

        $like = Like::where('user_id', $user->id)
            ->where('post_id', $postId)
            ->first();

        if (!$like) {
            return 'none';
        } elseif ($like->like) {
            return 'like';
        } elseif ($like->dislike) {
            return 'dislike';
        }

        return 'none';
    }
}
