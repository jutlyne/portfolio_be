<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\BlogController;

Route::prefix('admin')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('reset_password');
    Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('refresh_token');

    Route::middleware(['auth:api'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('me', [AuthController::class, 'profile'])->name('profile');

        // blogs
        Route::group(['prefix' => 'blogs', 'as' => 'blog.'], function () {
            Route::get('', [BlogController::class, 'index'])->name('index');
            Route::get('/{id}', [BlogController::class, 'show'])->name('show');
            Route::post('', [BlogController::class, 'store'])->name('store');
        });
    });
});
