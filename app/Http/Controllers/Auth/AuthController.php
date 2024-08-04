<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->has('rememberme'))) {
            $request->session()->regenerate();
 
            return redirect()->intended();
        }

        return back()->withErrors([
            'email' => 'البيانات المدخلة لا تتوافق مع البيانات المسجلة لدينا',
        ])->onlyInput('email');
    }

    /**
     * Log out of the application
     */
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
