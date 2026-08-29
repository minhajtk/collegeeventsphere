<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $fieldType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$fieldType => $credentials['login'], 'password' => $credentials['password']], $request->has('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->status === 'suspended') {
                Auth::logout();
                return back()->withErrors(['login' => 'Your account has been suspended. Please contact the administrator.']);
            }

            return $this->redirectBasedOnRole($user)->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|alpha_dash|max:50|unique:users',
            'phone' => 'nullable|string|max:20',
            'role' => 'nullable|string|in:student,organizer',
            'department' => 'required|string|max:255',
            'enrolment_number' => 'required|string|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $role = $validated['role'] ?? 'student';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => strtolower($validated['username']),
            'phone' => $validated['phone'],
            'role' => $role,
            'department' => $validated['department'],
            'enrolment_number' => strtoupper($validated['enrolment_number']),
            'password' => Hash::make($validated['password']),
            'status' => 'active',
        ]);

        Auth::login($user);

        if ($user->isOrganizer()) {
            return redirect()->route('organizer.dashboard')->with('success', 'Organizer account registered! Welcome to the EventSphere Organizer Portal.');
        }

        return redirect()->route('student.dashboard')->with('success', 'Registration successful! Welcome to EventSphere.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'You have logged out successfully.');
    }

    private function redirectBasedOnRole(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isOrganizer()) {
            return redirect()->route('organizer.dashboard');
        }
        return redirect()->route('student.dashboard');
    }
}
