<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'boolean',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Аккаунт деактивирован.']);
            }

            return redirect()->intended(route('home'))
                ->with('success', 'Добро пожаловать, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:100|unique:users,nickname',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'city' => 'nullable|string|max:100',
            'agree_terms' => 'required|accepted',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'nickname' => $validated['nickname'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => User::ROLE_USER,
            'avatar' => null,
            'is_verified' => false,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Регистрация успешна! Добро пожаловать в FurHouse!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Вы вышли из аккаунта.');
    }

    public function showProfile(Request $request)
    {
        $user = auth()->user();
        $activeTab = $request->get('tab', 'all');

        // Получаем избранное
        $favoritesQuery = Favorite::where('user_id', $user->id)->with('favoritable.user');

        if ($activeTab === 'products') {
            $favoritesQuery->where('favoritable_type', 'App\\Models\\Product');
        } elseif ($activeTab === 'services') {
            $favoritesQuery->where('favoritable_type', 'App\\Models\\Service');
        }

        $favorites = $favoritesQuery->get();

        // Счётчики
        $productsCount = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', 'App\\Models\\Product')
            ->count();
        $servicesCount = Favorite::where('user_id', $user->id)
            ->where('favoritable_type', 'App\\Models\\Service')
            ->count();
        $favoritesCount = $productsCount + $servicesCount;

        return view('auth.profile', compact(
            'user', 'favorites', 'activeTab',
            'productsCount', 'servicesCount', 'favoritesCount'
        ));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:100|unique:users,nickname,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'city' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Профиль обновлён!');
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Пароль изменён!');
    }

    public function removeFavorite(Favorite $favorite)
    {
        if ($favorite->user_id !== auth()->id()) {
            abort(403);
        }

        $favorite->delete();

        return response()->json(['success' => true]);
    }

    public function showPublicProfile(User $user)
    {
        return view('auth.public-profile', compact('user'));
    }
}