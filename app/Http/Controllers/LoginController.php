<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('welcome');
    }

    /**
     * Handle the login request.
     * Validates name + password and attempts authentication.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        // Attempt to authenticate using 'name' as the identifier
        if (Auth::attempt(['name' => $credentials['name'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect('mainpage')->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        // Authentication failed
        return back()
            ->withInput($request->only('name'))
            ->with('error', 'These credentials do not match our records.');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out.');
    }
}
