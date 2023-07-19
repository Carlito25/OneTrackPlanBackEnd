<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{

    public function index()
    {
        $user = User::get();

        return response()->json($user);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);


        return response()->json(['message' => 'Registration successful'], 201);
    }

    // User login
    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = auth()->attempt($credentials);

            return response()->json(['token' => $token, 'user_id' => $user->id, 'name' => $user->name], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
        
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Logout successful'], 200);
    }
}
