<?php 
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // --- REGISTRATION ---

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:users',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // This redirects them straight to login after signing up!
    return redirect()->route('login')->with('success', 'Account created successfully! Please log in.');
}

    // --- LOGIN ---

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'name' => 'required|string',
        'password' => 'required|string',
    ]);

    if (Auth::attempt($credentials, $request->remember)) {
        $request->session()->regenerate();
        
        // Redirect back to the login page with a success status
        return redirect()->route('login')->with('status', 'You are logged in!');
    }

    return back()->withErrors([
        'name' => 'The provided credentials do not match our records.',
    ])->onlyInput('name');
}
    // --- LOGOUT ---

    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate the session and regenerate the CSRF token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('posts.index')->with('success', 'You have been successfully logged out.');
    }
}
