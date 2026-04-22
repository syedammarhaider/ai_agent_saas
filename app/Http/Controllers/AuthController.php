<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\AdminUser;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        // Check if email matches admin email
        if ($request->email !== 'syedammar496539@gmail.com') {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Access denied. Only admin can login.']);
        }

        // Attempt authentication
        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'Invalid password.']);
        }

        // Update last login
        $admin = Auth::user();
        $admin->last_login_at = now();
        $admin->save();

        // Regenerate session
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    /**
     * Get authenticated user
     */
    public function user(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return response()->json([
            'id' => $request->user()->id,
            'email' => $request->user()->email,
            'last_login_at' => $request->user()->last_login_at,
        ]);
    }
}
