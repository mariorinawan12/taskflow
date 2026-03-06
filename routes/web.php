<?php

use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('workspace.create');
    }
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisterController::class, 'store'])
        ->name('register.store');

    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])
        ->name('login.authenticate');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');

    Route::get('/workspaces/create', [WorkspaceController::class, 'create'])
        ->name('workspace.create');
    Route::post('/workspaces', [WorkspaceController::class, 'store'])
        ->name('workspace.store');
});

Route::middleware(['auth', 'resolve.workspace'])
    ->prefix('/{workspace:slug}')
    ->group(function () {
        Route::get('/dashboard', [WorkspaceController::class, 'dashboard'])
            ->name('workspace.dashboard');
    });