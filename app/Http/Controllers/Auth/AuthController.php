<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
<<<<<<< HEAD

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $this->rememberIntendedUrl($request);

=======
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        return view('auth.login');
    }

    public function login(Request $request)
    {
<<<<<<< HEAD
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->can('admin.access')) {
                $request->session()->forget('url.intended');

                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended('/dashboard');
=======
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->can('admin.access')) {
                return redirect('/admin/dashboard');
            }
            return redirect('/dashboard');
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

<<<<<<< HEAD
    public function showRegisterForm(Request $request)
    {
        $this->rememberIntendedUrl($request);

=======
    public function showRegisterForm()
    {
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        return view('auth.register');
    }

    public function register(Request $request)
    {
<<<<<<< HEAD
        $phoneInput = $request->input('phone');

        if (blank($phoneInput) && $request->filled('phone_number')) {
            $phoneInput = (string) $request->input('phone_country_code', '+91')
                . (string) $request->input('phone_number');
        }

        $request->merge([
            'phone' => $this->normalizePhoneNumber($phoneInput),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => ['required', 'string', 'max:20', 'regex:/^\+[1-9]\d{9,14}$/', 'unique:users,phone'],
=======
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
<<<<<<< HEAD
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'phone_verified_at' => now(),
            'password' => Hash::make($validated['password']),
        ]);

        $this->assignCustomerRole($user);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }



    private function assignCustomerRole(User $user): void
    {
=======
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $role = Role::withTrashed()->firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'web',
        ]);

        if ($role->trashed()) {
            $role->restore();
        }

        $user->assignRole('customer');
<<<<<<< HEAD
=======
        Auth::login($user);

        return redirect('/');
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
<<<<<<< HEAD

    private function rememberIntendedUrl(Request $request): void
    {
        $explicitTarget = $request->query('redirect');

        if (! $explicitTarget && $request->session()->has('url.intended')) {
            return;
        }

        $target = $explicitTarget ?: $request->headers->get('referer');

        if (! is_string($target) || $target === '') {
            return;
        }

        $path = parse_url($target, PHP_URL_PATH);

        if (! is_string($path) || in_array($path, ['/login', '/register'], true)) {
            return;
        }

        $appUrl = rtrim(config('app.url'), '/');
        $isSameOrigin = Str::startsWith($target, url('/')) || Str::startsWith($target, $appUrl);
        $isRelative = Str::startsWith($target, '/') && ! Str::startsWith($target, '//');

        if ($isSameOrigin || $isRelative) {
            $request->session()->put('url.intended', $target);
        }
    }

    private function normalizePhoneNumber(?string $phone): string
    {
        $phone = trim((string) $phone);

        if ($phone === '') {
            return '';
        }

        $digits = preg_replace('/\D+/', '', $phone) ?: '';

        if ($digits === '') {
            return '';
        }

        if (Str::startsWith($phone, '+')) {
            return '+' . $digits;
        }

        if (Str::startsWith($digits, '00')) {
            return '+' . substr($digits, 2);
        }

        $defaultCountryCode = '+91';
        $defaultDigits = '91';

        if ($defaultDigits !== '' && strlen($digits) > 10 && Str::startsWith($digits, $defaultDigits)) {
            return '+' . $digits;
        }

        return $defaultCountryCode . $digits;
    }
}
=======
}

>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
