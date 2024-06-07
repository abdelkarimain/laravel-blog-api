<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{


    public function index()
    {
        try {
            $comments = Comment::orderBy('created_at', 'desc')->paginate(7);
            $lastMonthComments = Comment::whereMonth('created_at', now()->subMonth()->month)
                ->count();

            return response()->json(
                [
                    'comments' => $comments,
                    'lastMonthComments' => $lastMonthComments
                ],
                200
            );

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function storeComment(Request $request)
    {

        try {
            $request->validate([
                'content' => 'required',
                'post_id' => 'required',
            ]);
            $userId = Auth::id();

            $comment = new Comment();
            $comment->content = $request->input('content');
            $comment->user_id = $userId;
            $comment->post_id = $request->input('post_id');
            $comment->save();

            return response()->json([
                'message' => 'Comment created successfully',
                'comment' => $comment
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPostComments($postId)
    {

        try {
            $comments = Comment::where('post_id', $postId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'comments' => $comments
            ], 200);


        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get user comments
    public function getUser($userId)
    {

        try {
            $user = User::find($userId);
            return response()->json([
                'user' => $user
            ], 200);


        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // editComment
    public function editComment(Request $request, $commentId)
    {
        try {
            $comment = Comment::findOrFail($commentId);
            $comment->content = $request->input('content');
            $comment->save();
            return response()->json([
                'message' => 'Comment updated successfully',
                'comment' => $comment
            ], 200);


        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // delete comment
    public function deleteComment($commentId)
    {
        try {
            $comment = Comment::find($commentId);
            $comment->delete();
            return response()->json([
                'message' => 'Comment deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
