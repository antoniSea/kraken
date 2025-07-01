<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cookie;

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
    return Inertia::render('Admin/Index');
});

// Age consent API - ustawia cookie przez backend
Route::post('/age-consent', function () {
    return response()->json(['success' => true])->cookie('ageGateAccepted', '1', 60*24*365, '/');
});
