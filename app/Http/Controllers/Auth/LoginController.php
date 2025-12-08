<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if user exists and is using OAuth
        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->oauth_provider === 'google' && !$user->password) {
            return back()->withErrors([
                'email' => 'This account uses Google login. Please sign in with Google.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect based on user role
            return $this->redirectToDashboard($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    protected function redirectToDashboard($user)
    {
        switch ($user->role) {
            case 'owner':
                return redirect()->route('owner.dashboard');
            case 'rider':
                return redirect()->route('rider.dashboard');
            case 'customer':
            default:
                return redirect()->route('customer.dashboard');
        }
    }

    // In your LoginController
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'owner') {
            return redirect()->route('owner.dashboard');
        } elseif ($user->role === 'rider') {
            return redirect()->route('rider.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with([
            'success' => 'You have been logged out successfully.',
            'logout' => true
        ]);
    }
}
