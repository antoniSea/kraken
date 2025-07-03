<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Inertia::render('AgeGatePage');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::post('/register', function (Request $request) {
    app(CreateNewUser::class)->create($request->all());
    return response()->json(['success' => true]);
});

Route::get('/privacy-policy', function () {
    $policy = File::get(resource_path('markdown/policy.md'));
    return Inertia::render('PrivacyPolicy', [
        'policy' => $policy,
    ]);
});

// Age consent middleware dla GET /register
Route::get('/register', function (\Illuminate\Http\Request $request) {
    if (!$request->cookie('ageGateAccepted')) {
        return redirect('/');
    }
    return Inertia::render('Auth/Register');
})->name('register');

// Admin panel placeholder
Route::get('/admin', function () {
    $user = request()->user();
    if (!$user || !$user->is_admin) {
        return redirect('/dashboard');
    }
    $users = \App\Models\User::all();
    return Inertia::render('Admin/Index', [
        'users' => $users,
    ]);
})->middleware(['auth', config('jetstream.auth_session'), 'verified']);

// Dodawanie użytkownika
Route::post('/admin/users', function (\Illuminate\Http\Request $request) {
    $user = request()->user();
    if (!$user || !$user->is_admin) {
        abort(403);
    }
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'password' => 'required|string|min:6',
        'is_admin' => 'boolean',
    ]);
    $data['password'] = bcrypt($data['password']);
    $u = \App\Models\User::create($data);
    return response()->json($u);
})->middleware(['auth', config('jetstream.auth_session'), 'verified']);

// Edycja użytkownika
Route::put('/admin/users/{id}', function (\Illuminate\Http\Request $request, $id) {
    $user = request()->user();
    if (!$user || !$user->is_admin) {
        abort(403);
    }
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'is_admin' => 'boolean',
    ]);
    $u = \App\Models\User::findOrFail($id);
    $u->update($data);
    return response()->json($u);
})->middleware(['auth', config('jetstream.auth_session'), 'verified']);

// Usuwanie użytkownika
Route::delete('/admin/users/{id}', function ($id) {
    $user = request()->user();
    if (!$user || !$user->is_admin) {
        abort(403);
    }
    $u = \App\Models\User::findOrFail($id);
    $u->delete();
    return response()->json(['success' => true]);
})->middleware(['auth', config('jetstream.auth_session'), 'verified']);

// Age consent API - ustawia cookie przez backend
Route::post('/age-consent', function () {
    return response()->json(['success' => true])->cookie('ageGateAccepted', '1', 60*24*365, '/');
});

Route::post('/login', function (Request $request) {
    $login = $request->input('login');
    $password = $request->input('password');
    $remember = $request->boolean('remember');

    $user = \App\Models\User::where('email', $login)
        ->orWhere('nickname', $login)
        ->first();

    if ($user && \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
        Auth::login($user, $remember);
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'login' => 'Nieprawidłowy login lub hasło.',
    ]);
});
