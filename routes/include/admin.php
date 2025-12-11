<?php

use App\Http\Controllers\Admin\App\AppSliderController;
use App\Http\Controllers\Admin\Audit\AuditSettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\FileManager\FileManagerController;
use Illuminate\Support\Facades\Route;


    Route::get('log', [Sdtech\LogViewerLaravel\Controllers\LogViewerLaravelController::class, 'index'])->name('errorLog');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');



     // General Setting
    Route::group(['prefix' => 'general-setting'], function () {
        Route::get('/', [SettingsController::class, 'generalSetting'])->name('generalSettingList');
    });


    // App Slider
    Route::group(['prefix' => 'app-slider', 'as' => 'appSlider.'], function () {
        Route::get('/', [AppSliderController::class, 'index'])->name('list');
        Route::get('create', [AppSliderController::class, 'create'])->name('create');
        Route::post('store', [AppSliderController::class, 'store'])->name('store');
        Route::get('edit/{id}', [AppSliderController::class, 'edit'])->name('edit');
        Route::post('update', [AppSliderController::class, 'update'])->name('update');
        Route::get('delete/{id}', [AppSliderController::class, 'destroy'])->name('delete');
        Route::post('publish', [AppSliderController::class, 'publish'])->name('publish');
    });


    // audit
    Route::group(['prefix' => 'audit', 'as' => 'audit.'], function () {
        Route::get('logs', [AuditSettingController::class, 'index'])->name('logs');
        Route::get('log/{id}', [AuditSettingController::class, 'show'])->name('log.show');
        Route::get('delete/{id}', [AuditSettingController::class, 'destroy'])->name('log.delete');
        Route::get('settings', [AuditSettingController::class, 'settings'])->name('settings');
        Route::post('update-model', [AuditSettingController::class, 'updateModel'])->name('updateModel');
        Route::get('reset-audit-model', [AuditSettingController::class, 'resetModel'])->name('resetModel');
    });

    // File Manager
    Route::group(['prefix' => 'file-manager', 'as' => 'fileManager.'], function () {
        Route::get('list', [FileManagerController::class, 'list'])->name('all');
        Route::get('/', [FileManagerController::class, 'index'])->name('list');
        Route::get('list-partial', [FileManagerController::class, 'listPartial'])->name('partial');
        Route::get('create', [FileManagerController::class, 'create'])->name('create');
        Route::post('store-file', [FileManagerController::class, 'storeFile'])->name('storeFile');
        Route::post('store', [FileManagerController::class, 'store'])->name('store');
        Route::get('edit/{id}', [FileManagerController::class, 'edit'])->name('edit');
        Route::post('update', [FileManagerController::class, 'update'])->name('update');
        Route::get('delete/{id}', [FileManagerController::class, 'destroy'])->name('delete');
    });

