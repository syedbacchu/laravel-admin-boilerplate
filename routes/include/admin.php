<?php

use App\Http\Controllers\Admin\App\AppSliderController;
use App\Http\Controllers\Admin\Audit\AuditSettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Faq\FaqCategoryController;
use App\Http\Controllers\Admin\Faq\FaqController;
use App\Http\Controllers\Admin\FileManager\FileManagerController;
use App\Http\Controllers\Admin\Role\RoleController;
use App\Http\Controllers\Admin\Settings\CustomFieldController;
use App\Http\Controllers\Admin\Settings\SettingsController;
use App\Http\Controllers\Admin\Settings\SettingFieldController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('log', [Sdtech\LogViewerLaravel\Controllers\LogViewerLaravelController::class, 'index'])->name('errorLog');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    // user management
    Route::resource('users', UserController::class)->names([
        'index'   => 'user.list',
        'create'   => 'user.create',
        'edit'   => 'user.edit',
        'store'   => 'user.store',
        'update'  => 'user.update',
        'destroy' => 'user.delete',
        'show' => 'user.show',
    ]);

    Route::group(['prefix' => 'users', 'as' => 'user.'], function () {
        Route::post('user-status', [UserController::class, 'status'])->name('status');
    });
    // General Setting
    Route::resource('fields', SettingFieldController::class)->names([
        'index'   => 'settings.fields.index',
        'create'   => 'settings.fields.create',
        'edit'   => 'settings.fields.edit',
        'store'   => 'settings.fields.store',
        'update'  => 'settings.fields.update',
        'destroy' => 'settings.fields.delete',
        'show' => 'settings.fields.show',
    ]);
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', [SettingsController::class, 'index'])->name('generalSetting');
        Route::post('/settings/{group}', [SettingsController::class, 'update'])->name('update');
    });


    // App Slider
    Route::resource('app-slider', AppSliderController::class)->names([
        'index'   => 'appSlider.list',
        'create'   => 'appSlider.create',
        'edit'   => 'appSlider.edit',
        'store'   => 'appSlider.store',
        'update'  => 'appSlider.update',
        'destroy' => 'appSlider.delete',
        'show' => 'appSlider.show',
    ]);
    Route::group(['prefix' => 'app-slider', 'as' => 'appSlider.'], function () {
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
        Route::get('delete/{id}', [FileManagerController::class, 'destroy'])->name('delete');
    });

    // custom fields
    Route::group(['prefix' => 'custom-fields', 'as' => 'customField.'], function () {
        Route::get('/', [CustomFieldController::class, 'index'])->name('index');
        Route::get('list', [CustomFieldController::class, 'listByModule'])->name('list')->middleware('skip.permission');
        Route::post('store', [CustomFieldController::class, 'store'])->name('store');
        Route::post('update', [CustomFieldController::class, 'update'])->name('update');
    });

    Route::resource('role', RoleController::class)->names([
        'index'   => 'role.index',
        'create'   => 'role.create',
        'store'   => 'role.store',
        'update'  => 'role.update',
        'destroy' => 'role.destroy',
        'show' => 'role.show',
    ]);
    Route::group([ 'as' => 'role.'], function () {
        Route::post('role-publish', [RoleController::class, 'roleStatus'])->name('status');
        Route::get('role-sync-permission', [RoleController::class, 'syncPermission'])->name('syncPermission');
        Route::get('web-permission', [RoleController::class, 'webPermission'])->name('webPermission');
        Route::get('api-permission', [RoleController::class, 'apiPermission'])->name('apiPermission');
        Route::get('api-role', [RoleController::class, 'apiRole'])->name('apiRole');
        Route::get('delete-permission/{id}', [RoleController::class, 'deletePermission'])->name('deletePermission');
        Route::post('permission-publish', [RoleController::class, 'permissionPublish'])->name('permissionStatus');
    });

    // Faq Categories
    Route::resource('faq-categories', FaqCategoryController::class)->names([
        'index'   => 'faqCategory.list',
        'create'   => 'faqCategory.create',
        'edit'   => 'faqCategory.edit',
        'store'   => 'faqCategory.store',
        'update'  => 'faqCategory.update',
        'destroy' => 'faqCategory.delete',
        'show' => 'faqCategory.show',
    ]);
    Route::group(['prefix' => 'faq-categories', 'as' => 'faqCategory.'], function () {
        Route::post('publish', [FaqCategoryController::class, 'faqCategoryStatus'])->name('publish');
    });

    // Faq
    Route::resource('faq', FaqController::class)->names([
        'index'   => 'faq.list',
        'create'   => 'faq.create',
        'edit'   => 'faq.edit',
        'store'   => 'faq.store',
        'update'  => 'faq.update',
        'destroy' => 'faq.delete',
        'show' => 'faq.show',
    ]);
    Route::group(['prefix' => 'faq', 'as' => 'faq.'], function () {
        Route::post('publish', [FaqController::class, 'faqStatus'])->name('publish');
    });
