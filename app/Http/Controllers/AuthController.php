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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials) && Auth::user()->role === 'admin_kabupaten') {
            $request->session()->regenerate();
            return redirect()->intended('admin-kabupaten/dashboard');
        }

        Auth::logout();
        return back()->withErrors(['email' => 'The provided credentials do not match our admin kabupaten records.']);
    }

    public function showUserLogin()
    {
        return view('auth.user_login');
    }

    public function userLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials) && Auth::user()->role === 'user') {
            $request->session()->regenerate();
            return redirect()->intended('user/dashboard');
        }

        Auth::logout();
        return back()->withErrors(['email' => 'The provided credentials do not match our BUMDes user records.']);
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
