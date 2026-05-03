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
use App\Http\Controllers\Api\Project\ProjectCategoryController;
use App\Http\Controllers\Api\Project\ProjectController;
use App\Http\Controllers\Api\Slider\SliderController;
use App\Http\Controllers\Api\Stat\StatsController;
use App\Http\Controllers\Api\Team\TeamsController;
use App\Http\Controllers\Api\Testimonials\TestimonialsController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\Home\HomeController;
use App\Http\Controllers\Api\AboutCompany\AboutCompanyController;
use App\Http\Controllers\Api\Faq\FaqController;
use App\Http\Controllers\Api\Faq\FaqCategoryController;
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
    Route::get('summary', [BlogController::class, 'summary'])->name('summary');
    Route::get('{identifier}/comments', [BlogCommentController::class, 'index'])->name('comments');
    Route::post('{identifier}/comments', [BlogCommentController::class, 'store'])->name('commentStore');
    Route::get('{identifier}', [BlogController::class, 'show'])->name('details');
});

Route::group(['prefix' => 'home', 'as' => 'apiHome.'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});

Route::group(['prefix' => 'about-company', 'as' => 'apiAboutCompany.'], function () {
    Route::get('/', [AboutCompanyController::class, 'index'])->name('index');
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

Route::group(['prefix' => 'projects', 'as' => 'apiProject.'], function () {
    Route::get('/', [ProjectController::class, 'index'])->name('list');
    Route::get('{identifier}', [ProjectController::class, 'show'])->name('details');
});

Route::group(['prefix' => 'project-categories', 'as' => 'apiProjectCategory.'], function () {
    Route::get('/', [ProjectCategoryController::class, 'index'])->name('list');
    Route::get('{identifier}', [ProjectCategoryController::class, 'show'])->name('details');
});

Route::group(['middleware' => ['auth:api'],'prefix' => 'user', 'as' => 'apiUser.', ], function () {
    Route::get('me', [ProfileController::class, 'me'])->name('me');
    Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
});

Route::group(['prefix' => 'testimonials', 'as' => 'apiTestimonial.'], function () {
    Route::get('/', [TestimonialsController::class, 'index'])->name('list');
    Route::get('{identifier}', [TestimonialsController::class, 'show'])->name('details');
});

Route::group(['prefix' => 'sliders', 'as' => 'slider.'], function () {
    Route::get('/', [SliderController::class, 'index'])->name('list');
    Route::get('{identifier}', [SliderController::class, 'show'])->name('details');
});

Route::group(['prefix' => 'stat', 'as' => 'apiStat.'], function () {
    Route::get('/', [StatsController::class, 'index'])->name('list');
    Route::get('{identifier}', [StatsController::class, 'show'])->name('details');
});

Route::group(['prefix' => 'team', 'as' => 'apiTeam.'], function () {
    Route::get('/', [TeamsController::class, 'index'])->name('list');
    Route::get('{identifier}', [TeamsController::class, 'show'])->name('details');
});

Route::group(['prefix' => 'faq-category', 'as' => 'apiFaqCategory.'], function () {
    Route::get('/', [FaqCategoryController::class, 'index'])->name('list');
    Route::get('{identifier}', [FaqCategoryController::class, 'show'])->name('details');
});

Route::group(['prefix' => 'faq', 'as' => 'apiFaq.'], function () {
    Route::get('/', [FaqController::class, 'index'])->name('list');
    Route::get('{identifier}', [FaqController::class, 'show'])->name('details');
});
