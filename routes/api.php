<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AuthController;

Route::prefix('admin')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('refresh_token');

    Route::middleware(['auth:api'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('me', [AuthController::class, 'profile'])->name('profile');
    });
});
