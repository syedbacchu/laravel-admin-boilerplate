<?php
use App\Http\Controllers\Auth\AuthController;


Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::group([ 'as' => 'auth.'], function () {
        Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password');
        Route::post('forgot-password', [AuthController::class, 'forgotPasswordProcess'])->name('forgot.password.process');
        Route::get('reset-password', [AuthController::class, 'resetPassword'])->name('forgot.password.reset');
    });
});
