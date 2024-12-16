<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);    
    }

    public function refresh()
    {
        try {
            $newToken = auth()->refresh();

            return response()->json([
                'token' => $newToken,
                'expires_in' => auth()->factory()->getTTL() * 60
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to refresh token.',
            ], 401);
        }
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out.']);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
