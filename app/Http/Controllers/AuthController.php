<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('kanban.index');
        }
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // === Tambahan: Generate Sanctum Token ===
            $user = Auth::user();
            $token = $user->createToken('kanban-token')->plainTextToken;

            // Kalau request dari API (expects JSON), balikin token
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'user' => $user,
                ]);
            }

            return redirect()->intended(route('kanban.index'));
        }


        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Show register form (optional)
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration (optional)
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Generate avatar (2 huruf pertama nama)
        $nameParts = explode(' ', $validated['name']);
        $avatar = strtoupper(substr($nameParts[0], 0, 1));
        if (isset($nameParts[1])) {
            $avatar .= strtoupper(substr($nameParts[1], 0, 1));
        } else {
            $avatar .= strtoupper(substr($nameParts[0], 1, 1));
        }

        // Random color
        $colors = ['#4299e1', '#48bb78', '#ed8936', '#e53e3e', '#68d391', '#f6ad55', '#38b2ac', '#d53f8c'];
        $color = $colors[array_rand($colors)];

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'avatar' => $avatar,
            'color' => $color,
            'role' => 'user',
        ]);

        Auth::login($user);

        return redirect()->route('kanban.index');
    }

    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('kanban-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
}
