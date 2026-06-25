<?php

use App\Http\Controllers\Admin\App\AppSliderController;
use App\Http\Controllers\Admin\Attribute\AttributeController;
use App\Http\Controllers\Admin\Attribute\AttributeValueController;
use App\Http\Controllers\Admin\Audit\AuditSettingController;
use App\Http\Controllers\Admin\Comparism\ComparismController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Faq\FaqCategoryController;
use App\Http\Controllers\Admin\Faq\FaqController;
use App\Http\Controllers\Admin\FileManager\FileManagerController;
use App\Http\Controllers\Admin\Role\RoleController;
use App\Http\Controllers\Admin\Settings\CustomFieldController;
use App\Http\Controllers\Admin\Settings\SettingsController;
use App\Http\Controllers\Admin\Settings\SettingFieldController;
use App\Http\Controllers\Admin\Post\PostCategoryController;
use App\Http\Controllers\Admin\Post\PostCommentController;
use App\Http\Controllers\Admin\Post\PostController;
use App\Http\Controllers\Admin\Post\TagController;
use App\Http\Controllers\Admin\Service\ServiceCategoryController;
use App\Http\Controllers\Admin\Service\ServiceController;
use App\Http\Controllers\Admin\Feature\FeatureCategoryController;
use App\Http\Controllers\Admin\Feature\FeatureController;
use App\Http\Controllers\Admin\Products\ProductCategoryController;
use App\Http\Controllers\Admin\Products\ProductFeatureController;
use App\Http\Controllers\Admin\Project\ProjectCategoryController;
use App\Http\Controllers\Admin\Project\ProjectController;
use App\Http\Controllers\Admin\AboutCompany\AboutCompanyController;
use App\Http\Controllers\Admin\BatteryWater\BatteryWaterLeadController;
use App\Http\Controllers\Admin\Comparism\ComparismAreaController;
use App\Http\Controllers\Admin\Contact\ContactController;
use App\Http\Controllers\Admin\Lead\CollectLeadController;
use App\Http\Controllers\Admin\Subscriber\SubscriberController;
use App\Http\Controllers\Admin\Products\ProductController;
use App\Http\Controllers\Admin\Stat\StatController;
use App\Http\Controllers\Admin\Team\TeamController;
use App\Http\Controllers\Admin\Testimonial\TestimonialController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('log', [Sdtech\LogViewerLaravel\Controllers\LogViewerLaravelController::class, 'index'])->name('errorLog');

Route::group(['middleware' => ['skip.permission','no.permission.sync']], function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::get('edit-profile', [UserController::class, 'editProfile'])->name('editProfile');
    Route::post('update-profile', [UserController::class, 'updateProfile'])->name('updateProfile');
    Route::post('update-password', [UserController::class, 'updatePassword'])->name('updatePassword');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});


    // user management
    Route::resource('users', UserController::class)
        ->except(['destroy'])
        ->names([
        'index'   => 'user.list',
        'create'   => 'user.create',
        'edit'   => 'user.edit',
        'store'   => 'user.store',
        'update'  => 'user.update',
        'show' => 'user.show',
    ]);
    Route::group(['prefix' => 'users', 'as' => 'user.'], function () {
        Route::get('user-delete/{id}', [UserController::class, 'destroy'])->name('delete');
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
        Route::post('/test-mail', [SettingsController::class, 'testMail'])->name('testMail');
    });

    // About Company
    Route::group(['prefix' => 'about-company', 'as' => 'about-company.'], function () {
        Route::get('/', [AboutCompanyController::class, 'index'])->name('edit');
        Route::post('/', [AboutCompanyController::class, 'update'])->name('update');
    });

    // Contact Management
    Route::group(['prefix' => 'contact', 'as' => 'contact.'], function () {
        Route::get('/', [ContactController::class, 'index'])->name('index');
        Route::get('show', [ContactController::class, 'show'])->name('show');
        Route::post('reply/{id}', [ContactController::class, 'reply'])->name('reply');
    });

    // Subscriber Management
    Route::group(['prefix' => 'subscriber', 'as' => 'subscriber.'], function () {
        Route::get('/', [SubscriberController::class, 'index'])->name('index');
        Route::post('toggle-status', [SubscriberController::class, 'toggleStatus'])->name('toggleStatus');
        Route::delete('delete', [SubscriberController::class, 'destroy'])->name('delete');
    });

    // App Slider
    Route::resource('app-slider', AppSliderController::class)
        ->except(['destroy'])
        ->names([
        'index'   => 'appSlider.list',
        'create'   => 'appSlider.create',
        'edit'   => 'appSlider.edit',
        'store'   => 'appSlider.store',
        'update'  => 'appSlider.update',
        'show' => 'appSlider.show',
    ]);
    Route::group(['prefix' => 'app-slider', 'as' => 'appSlider.'], function () {
        Route::get('app-slider-delete/{id}', [AppSliderController::class, 'destroy'])->name('delete');
        Route::post('publish', [AppSliderController::class, 'publish'])->name('publish');
    });

    Route::group(['prefix' => 'slider', 'as' => 'slider.'], function () {
        Route::get('/', [AppSliderController::class, 'sliderList'])->name('list');
        Route::get('create', [AppSliderController::class, 'sliderCreate'])->name('create');
        Route::post('store', [AppSliderController::class, 'sliderStore'])->name('store');
        Route::get('edit/{id}', [AppSliderController::class, 'sliderEdit'])->name('edit');

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
        Route::get('list', [FileManagerController::class, 'list'])->name('all')->middleware(['skip.permission','no.permission.sync']);
        Route::get('/', [FileManagerController::class, 'index'])->name('list')->middleware(['skip.permission','no.permission.sync']);
        Route::get('list-partial', [FileManagerController::class, 'listPartial'])->name('partial')->middleware(['skip.permission','no.permission.sync']);
        Route::get('create', [FileManagerController::class, 'create'])->name('create');
        Route::post('store-file', [FileManagerController::class, 'storeFile'])->name('storeFile')->middleware(['skip.permission','no.permission.sync']);
        Route::post('store', [FileManagerController::class, 'store'])->name('store');
        Route::get('delete/{id}', [FileManagerController::class, 'destroy'])->name('delete');
    });

    // custom fields
    Route::group(['prefix' => 'custom-fields', 'as' => 'customField.'], function () {
        Route::get('/', [CustomFieldController::class, 'index'])->name('index');
        Route::get('list', [CustomFieldController::class, 'listByModule'])->name('list')->middleware('skip.permission');
        Route::post('store', [CustomFieldController::class, 'store'])->name('store');
        Route::post('update', [CustomFieldController::class, 'update'])->name('update');
        Route::get('delete/{id}', [CustomFieldController::class, 'destroy'])->name('delete');
    });

    Route::resource('role', RoleController::class)
        ->except(['destroy'])
        ->names([
        'index'   => 'role.index',
        'create'   => 'role.create',
        'edit'   => 'role.edit',
        'store'   => 'role.store',
        'update'  => 'role.update',
        'show' => 'role.show',
    ]);

    Route::group([ 'as' => 'role.'], function () {
        Route::get('role-delete/{id}', [RoleController::class, 'delete'])->name('destroy');
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

    // Post Categories
    Route::resource('post-categories', PostCategoryController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'postCategory.list',
        'create'   => 'postCategory.create',
        'edit'   => 'postCategory.edit',
        'store'   => 'postCategory.store',
        'update'  => 'postCategory.update',
        'show' => 'postCategory.show',
    ]);
    Route::group(['prefix' => 'post-categories', 'as' => 'postCategory.'], function () {
        Route::get('post-category-delete/{id}', [PostCategoryController::class, 'destroy'])->name('delete');
        Route::post('publish', [PostCategoryController::class, 'postCategoryStatus'])->name('publish');
    });

    // Tags
    Route::resource('tags', TagController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'tag.list',
        'create'   => 'tag.create',
        'edit'   => 'tag.edit',
        'store'   => 'tag.store',
        'update'  => 'tag.update',
        'show' => 'tag.show',
    ]);
    Route::group(['prefix' => 'tags', 'as' => 'tag.'], function () {
        Route::get('tag-delete/{id}', [TagController::class, 'destroy'])->name('delete');
    });

    // Posts
    Route::resource('posts', PostController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'post.list',
        'create'   => 'post.create',
        'edit'   => 'post.edit',
        'store'   => 'post.store',
        'update'  => 'post.update',
        'show' => 'post.show',
    ]);
    Route::group(['prefix' => 'posts', 'as' => 'post.'], function () {
        Route::get('post-delete/{id}', [PostController::class, 'destroy'])->name('delete');
        Route::post('publish', [PostController::class, 'postStatus'])->name('publish');
    });

    // Post Comments
    Route::group(['prefix' => 'post-comments', 'as' => 'postComment.'], function () {
        Route::get('/', [PostCommentController::class, 'index'])->name('list');
        Route::get('reply/{id}', [PostCommentController::class, 'reply'])->name('reply');
        Route::post('reply/{id}', [PostCommentController::class, 'storeReply'])->name('replyStore');
        Route::get('approve/{id}', [PostCommentController::class, 'approve'])->name('approve');
        Route::get('decline/{id}', [PostCommentController::class, 'decline'])->name('decline');
        Route::get('delete/{id}', [PostCommentController::class, 'destroy'])->name('delete');
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

    // Service Categories
    Route::resource('service-categories', ServiceCategoryController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'serviceCategory.list',
        'create'   => 'serviceCategory.create',
        'edit'   => 'serviceCategory.edit',
        'store'   => 'serviceCategory.store',
        'update'  => 'serviceCategory.update',
        'show' => 'serviceCategory.show',
    ]);
    Route::group(['prefix' => 'service-categories', 'as' => 'serviceCategory.'], function () {
        Route::get('service-category-delete/{id}', [ServiceCategoryController::class, 'destroy'])->name('delete');
        Route::post('publish', [ServiceCategoryController::class, 'serviceCategoryStatus'])->name('publish');
    });

    // Services
    Route::resource('services', ServiceController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'service.list',
        'create'   => 'service.create',
        'edit'   => 'service.edit',
        'store'   => 'service.store',
        'update'  => 'service.update',
        'show' => 'service.show',
    ]);
    Route::group(['prefix' => 'services', 'as' => 'service.'], function () {
        Route::get('service-delete/{id}', [ServiceController::class, 'destroy'])->name('delete');
        Route::post('publish', [ServiceController::class, 'serviceStatus'])->name('publish');
    });

    // Feature Categories
    Route::resource('feature-categories', FeatureCategoryController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'featureCategory.list',
        'create'   => 'featureCategory.create',
        'edit'   => 'featureCategory.edit',
        'store'   => 'featureCategory.store',
        'update'  => 'featureCategory.update',
        'show' => 'featureCategory.show',
    ]);
    Route::group(['prefix' => 'feature-categories', 'as' => 'featureCategory.'], function () {
        Route::get('feature-category-delete/{id}', [FeatureCategoryController::class, 'destroy'])->name('delete');
        Route::post('publish', [FeatureCategoryController::class, 'featureCategoryStatus'])->name('publish');
    });

    // Features
    Route::resource('features', FeatureController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'feature.list',
        'create'   => 'feature.create',
        'edit'   => 'feature.edit',
        'store'   => 'feature.store',
        'update'  => 'feature.update',
        'show' => 'feature.show',
    ]);
    Route::group(['prefix' => 'features', 'as' => 'feature.'], function () {
        Route::get('feature-delete/{id}', [FeatureController::class, 'destroy'])->name('delete');
        Route::post('publish', [FeatureController::class, 'featureStatus'])->name('publish');
    });

    // Project Categories
    Route::resource('project-categories', \App\Http\Controllers\Admin\Project\ProjectCategoryController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'projectCategory.list',
        'create'   => 'projectCategory.create',
        'edit'   => 'projectCategory.edit',
        'store'   => 'projectCategory.store',
        'update'  => 'projectCategory.update',
        'show' => 'projectCategory.show',
    ]);
    Route::group(['prefix' => 'project-categories', 'as' => 'projectCategory.'], function () {
        Route::get('project-category-delete/{id}', [\App\Http\Controllers\Admin\Project\ProjectCategoryController::class, 'destroy'])->name('delete');
        Route::post('publish', [\App\Http\Controllers\Admin\Project\ProjectCategoryController::class, 'projectCategoryStatus'])->name('publish');
    });

    // Projects
    Route::resource('projects', \App\Http\Controllers\Admin\Project\ProjectController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'project.list',
        'create'   => 'project.create',
        'edit'   => 'project.edit',
        'store'   => 'project.store',
        'update'  => 'project.update',
        'show' => 'project.show',
    ]);
    Route::group(['prefix' => 'projects', 'as' => 'project.'], function () {
        Route::get('project-delete/{id}', [\App\Http\Controllers\Admin\Project\ProjectController::class, 'destroy'])->name('delete');
        Route::post('publish', [\App\Http\Controllers\Admin\Project\ProjectController::class, 'projectStatus'])->name('publish');
    });


     // Testimonial
    Route::resource('testimonials', TestimonialController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'testimonial.list',
        'create'   => 'testimonial.create',
        'edit'   => 'testimonial.edit',
        'store'   => 'testimonial.store',
        'update'  => 'testimonial.update',
        'show' => 'testimonial.show',
    ]);
    Route::group(['prefix' => 'testimonials', 'as' => 'testimonial.'], function () {
        Route::get('testimonial-delete/{id}', [TestimonialController::class, 'destroy'])->name('delete');
        Route::post('publish', [TestimonialController::class, 'testimonialStatus'])->name('publish');
    });

      // Stat
    Route::resource('stats', StatController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'stat.list',
        'create'   => 'stat.create',
        'edit'   => 'stat.edit',
        'store'   => 'stat.store',
        'update'  => 'stat.update',
        'show' => 'stat.show',
    ]);
    Route::group(['prefix' => 'stats', 'as' => 'stat.'], function () {
        Route::get('stat-delete/{id}', [StatController::class, 'destroy'])->name('delete');
        Route::post('publish', [StatController::class, 'statStatus'])->name('publish');
    });


      // Team
    Route::resource('teams', TeamController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'team.list',
        'create'   => 'team.create',
        'edit'   => 'team.edit',
        'store'   => 'team.store',
        'update'  => 'team.update',
        'show' => 'team.show',
    ]);
    Route::group(['prefix' => 'teams', 'as' => 'team.'], function () {
        Route::get('team-delete/{id}', [TeamController::class, 'destroy'])->name('delete');
        Route::post('publish', [TeamController::class, 'teamStatus'])->name('publish');
    });

    // Attribute type
    Route::resource('attributes-type', AttributeController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'attribute.list',
        'create'   => 'attribute.create',
        'edit'   => 'attribute.edit',
        'store'   => 'attribute.store',
        'update'  => 'attribute.update',
        'show' => 'attribute.show',
    ]);
    Route::group(['prefix' => 'attributes', 'as' => 'attribute.'], function () {
        Route::get('attribute-delete/{id}', [AttributeController::class, 'destroy'])->name('delete');
        Route::post('publish', [AttributeController::class, 'attributeStatus'])->name('publish');
    });

    // Attribute Value
    Route::resource('attributes-value', AttributeValueController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'attribute.value.list',
        'create'   => 'attribute.value.create',
        'edit'   => 'attribute.value.edit',
        'store'   => 'attribute.value.store',
        'update'  => 'attribute.value.update',
        'show' => 'attribute.value.show',
    ]);
    Route::group(['prefix' => 'attributes-value', 'as' => 'attribute.value.'], function () {
        Route::get('attribute-value-delete/{id}', [AttributeValueController::class, 'destroy'])->name('delete');
        Route::post('publish', [AttributeValueController::class, 'attributeValueStatus'])->name('publish');
    });

    // Products Categories
    Route::resource('product-categories', ProductCategoryController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'product.category.list',
        'create'   => 'product.category.create',
        'edit'   => 'product.category.edit',
        'store'   => 'product.category.store',
        'update'  => 'product.category.update',
        'show' => 'product.category.show',
    ]);
    Route::group(['prefix' => 'product-categories', 'as' => 'product.category.'], function () {
        Route::get('product-category-delete/{id}', [ProductCategoryController::class, 'destroy'])->name('delete');
        Route::post('publish', [ProductCategoryController::class, 'productCategoryStatus'])->name('publish');
    });

    // Product Features
    Route::resource('product-features', ProductFeatureController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'product.feature.list',
        'create'  => 'product.feature.create',
        'edit'    => 'product.feature.edit',
        'store'   => 'product.feature.store',
        'update'  => 'product.feature.update',
        'show'    => 'product.feature.show',
    ]);
    Route::group(['prefix' => 'product-features', 'as' => 'product.feature.'], function () {
        Route::get('delete/{id}', [ProductFeatureController::class, 'destroy'])->name('delete');
        Route::post('publish', [ProductFeatureController::class, 'status'])->name('publish');
    });

    // Products
    Route::resource('products', ProductController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'product.list',
        'create'   => 'product.create',
        'edit'   => 'product.edit',
        'store'   => 'product.store',
        'update'  => 'product.update',
        'show' => 'product.show',
    ]);
    Route::group(['prefix' => 'products', 'as' => 'product.'], function () {
        Route::get('product-delete/{id}', [ProductController::class, 'destroy'])->name('delete');
        Route::post('publish', [ProductController::class, 'productStatus'])->name('publish');
        Route::post('featured', [ProductController::class, 'productFeatured'])->name('featured');
    });


    // Collect Leads
    Route::resource('collect-leads', CollectLeadController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'collect.lead.list',
        'show' => 'collect.lead.show',
    ]);
    Route::group(['prefix' => 'collect-leads', 'as' => 'collect.lead.'], function () {
        Route::get('collect-lead-delete/{id}', [CollectLeadController::class, 'destroy'])->name('delete');
        Route::post('publish', [CollectLeadController::class, 'collectLeadStatus'])->name('publish');
    });

    // Battery Water Leads
    Route::resource('battery-water-leads', BatteryWaterLeadController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'battery.water.lead.list',
        'show' => 'battery.water.lead.show',
    ]);
    Route::group(['prefix' => 'battery-water-leads', 'as' => 'battery.water.lead.'], function () {
        Route::get('battery-water-lead-delete/{id}', [BatteryWaterLeadController::class, 'destroy'])->name('delete');
        Route::post('publish', [BatteryWaterLeadController::class, 'batteryWaterLeadStatus'])->name('publish');
    });

    Route::resource('comparism', ComparismController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'comparism.list',
        'create'   => 'comparism.create',
        'edit'   => 'comparism.edit',
        'store'   => 'comparism.store',
        'update'  => 'comparism.update',
        'show' => 'comparism.show',
    ]);
    Route::group(['prefix' => 'comparism', 'as' => 'comparism.'], function () {
        Route::get('comparism-delete/{id}', [ComparismController::class, 'destroy'])->name('delete');
        Route::post('publish', [ComparismController::class, 'comparismStatus'])->name('publish');
    });

    Route::resource('comparism-area', ComparismAreaController::class)
    ->except(['destroy'])
    ->names([
        'index'   => 'comparismArea.list',
        'create'   => 'comparismArea.create',
        'edit'   => 'comparismArea.edit',
        'store'   => 'comparismArea.store',
        'update'  => 'comparismArea.update',
        'show' => 'comparismArea.show',
    ]);
    Route::group(['prefix' => 'comparism-area', 'as' => 'comparismArea.'], function () {
        Route::get('comparism-area-delete/{id}', [ComparismAreaController::class, 'destroy'])->name('delete');
        Route::post('publish', [ComparismAreaController::class, 'comparismAreaStatus'])->name('publish');
    });