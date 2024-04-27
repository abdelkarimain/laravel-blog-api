<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
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



}
