<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get the ID of the currently authenticated user
        $userId = Auth::id();

        // Retrieve posts for the current user with pagination
        $posts = Post::where('userId', $userId)->paginate(5);

        return response()->json($posts, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */



    public function store(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'content' => 'required',
                'title' => 'required',
            ]);

            // Generating the slug from the title
            $baseSlug = Str::slug($request->title);
            $slug = $baseSlug;

            // make a unique slug
            $count = 1;
            while (Post::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = Str::random(10) . "." . $image->getClientOriginalExtension();
                $image->move('uploads/posts/', $filename);

                $imageUrl = 'http://127.0.0.1:8000/uploads/posts/' . $filename;
            }


            // creating new post
            $post = Post::create([
                'userId' => Auth::id(),
                'content' => $request->content,
                'title' => $request->title,
                'image' => $imageUrl ?? 'https://placehold.co/600x400/EEE/31343C',
                'category' => $request->category ?? 'none',
                'slug' => $slug,
            ]);

            return response()->json(
                ['message' => 'Post created successfully'],
                201
            );

        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'An error occurred while storing the post.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate request
            $request->validate([
                'title' => 'string',
                'category' => 'string|nullable',
                'slug' => 'unique:posts,slug,' . $id,
            ]);

            // Find the post
            $post = Post::findOrFail($id);



            // Handle image update
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($post->image) {
                    Storage::delete($post->image);
                }

                $image = $request->file('image');
                $filename = Str::random(10) . "." . $image->getClientOriginalExtension();
                $image->move('uploads/posts/', $filename);

                $imageUrl = 'http://127.0.0.1:8000/uploads/posts/' . $filename;

                // Update post image URL
                $post->image = $imageUrl;
            }

            // Update other post attributes
            if ($request->filled('title')) {
                $post->title = $request->title;

                // Generating the slug from the title
                $baseSlug = Str::slug($request->title);
                $slug = $baseSlug;

                // make a unique slug
                $count = 1;
                while (Post::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }

                $post->slug = $slug;
            }

            if ($request->filled('category')) {
                $post->category = $request->category;
            }

            if ($request->filled('slug')) {
                $post->slug = $request->slug;
            }

            // Update the post
            $post->save();

            return response()->json(
                ['message' => 'Post updated successfully', 'post' => $post],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'An error occurred while updating the post.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            // Find the post
            $post = Post::findOrFail($id);

            // Check if the post exists
            if (!$post) {
                return response()->json(['message' => 'Post not found'], 404);
            }

            return response()->json($post, 200);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'An error occurred while retrieving the post.',
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Find the post
        $post = Post::findOrFail($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Check if the post has an image
        // if ($post->image) {
        //     // Delete the image
        //     Storage::delete($post->image);
        // }
        // Delete the post

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);

    }
}
