<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{

    public function index()
    {
        $user = User::get();

        return response()->json($user);
    }
    // User registration
    public function store(Request $request)
    {
        // Validate registration data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Perform any additional actions, such as sending a verification email

        // Return a response indicating successful registration
        return response()->json(['message' => 'Registration successful'], 201);
    }

    // User login
    public function login(Request $request)
    {
        // Validate login data
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Authentication successful
            $user = Auth::user();

            // Generate an API token for the user if needed

            // Return a response with user data or the token
            return response()->json(['user' => $user], 200);
        } else {
            // Authentication failed
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    // User logout
    public function logout()
    {
        // Perform any necessary actions for logging the user out

        // Logout the authenticated user
        Auth::logout();

        // Return a response indicating successful logout
        return response()->json(['message' => 'Logout successful'], 200);
    }
}
