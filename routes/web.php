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
    Route::get('/kodeks', function () {
        return Inertia::render('Kodeks');
    })->name('kodeks');
    Route::get('/regulamin', function () {
        return Inertia::render('Regulamin');
    })->name('regulamin');
    Route::post('/profile/files', [\App\Http\Controllers\ProfileFileUploadController::class, 'store']);
    Route::get('/profile', function () {
        return Inertia::render('Profile/Show');
    })->name('profile');
    Route::get('/profile/konkursy', [\App\Http\Controllers\ProfileFileUploadController::class, 'konkursy']);
    Route::get('/profile/points', [\App\Http\Controllers\ProfileFileUploadController::class, 'points']);
});

// Trasy admina
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin',
])->group(function () {
    Route::get('/admin/users-files', [\App\Http\Controllers\AdminUserFilesController::class, 'index']);
    Route::get('/admin/users', [\App\Http\Controllers\AdminUserController::class, 'index']);
    Route::post('/admin/users', [\App\Http\Controllers\AdminUserController::class, 'store']);
    Route::put('/admin/users/{user}', [\App\Http\Controllers\AdminUserController::class, 'update']);
    Route::delete('/admin/users/{user}', [\App\Http\Controllers\AdminUserController::class, 'destroy']);
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
    return Inertia::render('Auth/Register');
})->name('register');

