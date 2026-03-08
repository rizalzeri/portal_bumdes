<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showSuperAdminLogin()
    {
        return view('auth.superadmin_login');
    }

    public function superAdminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials) && Auth::user()->role === 'superadmin') {
            $request->session()->regenerate();
            return redirect()->intended('superadmin/dashboard');
        }

        Auth::logout();
        return back()->withErrors(['email' => 'The provided credentials do not match our superadmin records.']);
    }

    public function showAdminKabupatenLogin()
    {
        return view('auth.admin_kabupaten_login');
    }

    public function adminKabupatenLogin(Request $request)
    {
        $request->validate([
            'email' => ['required'], // using 'email' field name but storing username
            'password' => ['required'],
        ]);

        $loginType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginType => $request->email, 'password' => $request->password]) && Auth::user()->role === 'admin_kabupaten') {
            $request->session()->regenerate();
            return redirect()->intended('admin-kabupaten/dashboard');
        }

        Auth::logout();
        return back()->withErrors(['email' => 'Username/Email atau password salah.']);
    }

    public function showUserLogin()
    {
        return view('auth.user_login');
    }

    public function userLogin(Request $request)
    {
        $request->validate([
            'email' => ['required'], // user enters 'domain' (username) here
            'password' => ['required'],
        ]);

        $loginType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginType => $request->email, 'password' => $request->password]) && Auth::user()->role === 'user') {
            
            // Check if gratis account is inactive
            if (Auth::user()->subscription_status === 'inactive') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda sedang menunggu konfirmasi/aktivasi dari Admin Kabupaten.']);
            }

            $request->session()->regenerate();
            
            // Redirect to dynamic slug dashboard
            return redirect()->intended(Auth::user()->username . '/dashboard');
        }

        Auth::logout();
        return back()->withErrors(['email' => 'Username/Domain atau password salah.']);
    }

    public function logout(Request $request)
    {
        $role = Auth::check() ? Auth::user()->role : 'user';
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($role === 'superadmin') return redirect('/superadmin/login');
        if ($role === 'admin_kabupaten') return redirect('/admin-kabupaten/login');
        return redirect('/user/login');
    }
}
