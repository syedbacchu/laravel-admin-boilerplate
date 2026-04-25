<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\Post\BlogCommentController;
use App\Http\Controllers\Api\Post\BlogController;
use App\Http\Controllers\Api\Service\ServiceCategoryController;
use App\Http\Controllers\Api\Service\ServiceController;
use App\Http\Controllers\Api\Feature\FeatureCategoryController;
use App\Http\Controllers\Api\Feature\FeatureController;
use App\Http\Controllers\Api\User\ProfileController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth', 'as' => 'apiAuth.'], function () {
    Route::get('test-connection', [AuthController::class, 'test'])->name('test');
    Route::post('login', [AuthController::class, 'apiLogin'])->name('login');
});

Route::group(['prefix' => 'blogs', 'as' => 'apiBlog.'], function () {
    Route::get('/', [BlogController::class, 'index'])->name('list');
    Route::get('{identifier}/comments', [BlogCommentController::class, 'index'])->name('comments');
    Route::post('{identifier}/comments', [BlogCommentController::class, 'store'])->name('commentStore');
    Route::get('{identifier}', [BlogController::class, 'show'])->name('details');
});

Route::group(['prefix' => 'services', 'as' => 'apiService.'], function () {
    Route::get('/', [ServiceController::class, 'index'])->name('list');
    Route::get('{identifier}', [ServiceController::class, 'show'])->name('details');
});

Route::group(['prefix' => 'service-categories', 'as' => 'apiServiceCategory.'], function () {
    Route::get('/', [ServiceCategoryController::class, 'index'])->name('list');
    Route::get('{identifier}', [ServiceCategoryController::class, 'show'])->name('details');
});

Route::group(['prefix' => 'features', 'as' => 'apiFeature.'], function () {
    Route::get('/', [FeatureController::class, 'index'])->name('list');
    Route::get('{identifier}', [FeatureController::class, 'show'])->name('details');
});

Route::group(['prefix' => 'feature-categories', 'as' => 'apiFeatureCategory.'], function () {
    Route::get('/', [FeatureCategoryController::class, 'index'])->name('list');
    Route::get('{identifier}', [FeatureCategoryController::class, 'show'])->name('details');
});

Route::group(['middleware' => ['auth:api'],'prefix' => 'user', 'as' => 'apiUser.', ], function () {
    Route::get('me', [ProfileController::class, 'me'])->name('me');
    Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
});
