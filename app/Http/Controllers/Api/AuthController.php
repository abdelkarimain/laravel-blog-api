<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'password_confirmation' => 'required|same:password'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $username = strtolower(str_replace(' ', '_', $request->name)) . rand(10000, 99999);


        $existingUser = User::where('username', $username)->first();

        if ($existingUser) {
            $suffix = 1;
            do {
                $newUsername = $username . '_' . $suffix;
                $existingUser = User::where('username', $newUsername)->first();
                $suffix++;
            } while ($existingUser);

            $username = $newUsername;
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // $token = Auth::login($user);
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'User Registered Successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer'
            ]
        ]);





    }


    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|string|email|exists:users,email',
                'password' => 'required|string',
            ]
        );


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $credentials = request(['email', 'password']);
        
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 404);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('access-token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'user' => $user,
            'message' => 'Successfully logged in',
            'accessToken' => $token,
            'token_type' => 'Bearer',
        ]);
    }


    public function google(Request $request)
    {
        // Check if the user exists by email
        $user = User::where('email', $request->email)->first();

        // If the user does not exist, create a new user
        if (!$user) {
            $username = strtolower(str_replace(' ', '_', $request->name)) . rand(10000, 99999);

            // Ensure username is unique
            while (User::where('username', $username)->exists()) {
                $username .= rand(0, 9);
            }

            $password = rand(10000000, 99999999);

            $user = new User();
            $user->email = $request->email;
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($password);
            $user->username = $username;
            $user->image = $request->input('googlePhotoUrl');
            $user->save();
        }

        $tokenResult = $user->createToken('access-token');
        $token = $tokenResult->plainTextToken;

        return response()->json([
            'user' => $user,
            'message' => 'Successfully logged in',
            'accessToken' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong'
            ], 500);
        }

    }







    public function update(Request $request)
    {
        // Get current user
        $userId = Auth::id();
        $user = User::findOrFail($userId);

        // Validate the data submitted by user
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $userId,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update user data
        $user->name = $request->input('name');
        $user->username = $request->input('username');

        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::random(10) . "." . $image->getClientOriginalExtension();
            $image->move('uploads/', $filename);
            // Delete the old image if it exists
            if ($user->image) {
                Storage::delete('uploads/' . $user->image);
            }
            $user->image = 'http://127.0.0.1:8000/uploads/' . $filename;
        }

        // Save the user
        $user->save();

        return response()->json([
            'user' => $user,
            'message' => 'Successfully updated'
        ]);
    }


    public function destroy(Request $request)
    {
        $user = Auth::user();
        $user->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ]);
    }

















}
