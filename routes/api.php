<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\BlogController;
use App\Http\Controllers\Api\Admin\TagController;
use App\Http\Controllers\Api\User\BlogController as UserBlogController;
use App\Http\Controllers\Api\User\TagController as UserTagController;

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
            Route::put('/{id}', [BlogController::class, 'update'])->name('update');
            Route::delete('/{id}', [BlogController::class, 'delete'])->name('delete');
        });

        Route::group(['prefix' => 'tags', 'as' => 'tag.'], function () {
            Route::get('', [TagController::class, 'index'])->name('index');
            Route::post('', [TagController::class, 'store'])->name('store');
            Route::patch('/{id}', [TagController::class, 'updatePatch'])->name('patch');
            Route::delete('/{id}', [TagController::class, 'delete'])->name('delete');
        });
    });
});

Route::group(['prefix' => 'blogs', 'as' => 'user.blog.'], function () {
    Route::get('', [UserBlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [UserBlogController::class, 'show'])->name('show');

    Route::post('upload-file', [UserBlogController::class, 'uploadFile'])->name('upload_file');
});

Route::group(['prefix' => 'tags', 'as' => 'user.tag.'], function () {
    Route::get('', [UserTagController::class, 'index'])->name('index');
});
