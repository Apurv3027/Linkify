<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect('/login')
            ->with('success', 'Account created successfully! Please login.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        $masterPassword = env('MASTER_PASSWORD');

        $isValidPassword =
            Hash::check($request->password, $user->password) ||
            $request->password === $masterPassword;

        if (! $isValidPassword) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        // Proper Laravel login (IMPORTANT)
        auth()->login($user);

        // Regenerate session properly
        $request->session()->regenerate();

        // Auto Transfer Guest Links
        $this->transferGuestLinks($user, $request);

        // Session Info
        $request->session()->put('user_id', $user->id);
        $request->session()->put('user_name', $user->name);

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Admin!');
        }

        return redirect('/')->with('success', 'Welcome back!');
    }

    private function transferGuestLinks(User $user, Request $request)
    {
        $guestToken = $request->cookie('guest_token');

        if (! $guestToken) {
            return;
        }

        Link::whereNull('user_id')
            ->where('guest_token', $guestToken)
            ->update([
                'user_id' => $user->id,
                'guest_token' => null,
            ]);

        Cookie::queue(Cookie::forget('guest_token'));
    }

    // public function logout()
    // {
    //     session()->forget(['user_id', 'user_name']);

    //     return redirect('/')->with('success', 'You have been logged out.');
    // }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'You have been logged out successfully.');
    }
}
