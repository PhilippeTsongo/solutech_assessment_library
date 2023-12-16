<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        try {
            // Check if the user is already following before attaching
            if (!Auth::user()->following()->where('user_id', $user->id)->exists()) {
                Auth::user()->following()->attach($user->id);
                return response()->json(['message' => 'User followed successfully'], 200);
            } else {
                return response()->json(['message' => 'This user is already followed'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to follow user. ' . $e->getMessage()], 500);
        }
    }

    public function unfollow(User $user)
    {
        try {
            // Check if the user is following before detaching
            if (Auth::user()->following()->where('user_id', $user->id)->exists()) {
                Auth::user()->following()->detach($user->id);
                return response()->json(['message' => 'User unfollowed successfully'], 200);
            } else {
                return response()->json(['message' => 'You are not currently following this user'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to unfollow user. ' . $e->getMessage()], 500);
        }
    }
}
