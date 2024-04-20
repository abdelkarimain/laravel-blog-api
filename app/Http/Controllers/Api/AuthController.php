<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /* Register API */
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


    /* Login API */
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


    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }


    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
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
}
