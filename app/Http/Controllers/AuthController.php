<?php 

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Redirect authenticated users to their appropriate dashboard.
     */
    protected function redirectHome(): string
    {
        if (Auth::check() && Auth::user()->isEmployee()) {
            return route('employee.dashboard');
        }

        return route('admin.dashboard');
    }

    // Registration is disabled — only admin can add employees.
    // --- LOGIN ---

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            return redirect()->intended($this->redirectHome());
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // --- LOGOUT ---

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 4. Send them back to the login page after logging out
        return redirect()->route('login')->with('success', 'You have been successfully logged out.');
    }

    // --- GOOGLE LOGIN ---

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Throwable $e) {
            return redirect()
                ->route('login')
                ->withErrors(['name' => 'Google sign-in failed. Please try again.']);
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Existing user logging in via Google is assigned admin role.
            $user->update([
                'google_id' => $googleUser->getId(),
                'name' => $user->name ?: $googleUser->getName(),
                'role' => User::ROLE_ADMIN,
            ]);
        } else {
            // New Google sign-up creates an admin account (exception case).
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => null,
                'role' => User::ROLE_ADMIN,
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended($this->redirectHome());
    }
}