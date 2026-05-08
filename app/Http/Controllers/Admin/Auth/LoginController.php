<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->can('admin.access')) {
<<<<<<< HEAD
                $request->session()->forget('url.intended');

                return redirect()->route('admin.dashboard');
=======
                return redirect()->intended('/admin/dashboard');
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            }

            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'You do not have admin access.',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

<<<<<<< HEAD
        return redirect()->route('admin.login');
=======
        return redirect('/admin/login');
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }
}

