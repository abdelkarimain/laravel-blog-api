<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        // $lastMonthUsers = User::whereMonth('created_at', date('m'))->get();
        $lastMonthUsers = User::whereMonth('created_at', now()->subMonth()->month)
            ->count();

        return response()->json(
            [
                'users' => $users,
                'lastMonthUsers' => $lastMonthUsers
            ],
            200
        );
    }




    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted.']);
    }
}
