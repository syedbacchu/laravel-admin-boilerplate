<?php

namespace App\Providers;

use App\Http\Services\Audit\AuditRepository;
use App\Http\Services\Audit\AuditRepositoryInterface;
use App\Http\Services\Audit\AuditService;
use App\Http\Services\Audit\AuditServiceInterface;
use App\Http\Services\Slider\SliderRepository;
use App\Http\Services\Slider\SliderRepositoryInterface;
use App\Http\Services\Slider\SliderService;
use App\Http\Services\Slider\SliderServiceInterface;
use Illuminate\Support\ServiceProvider;

class ServiceLayerProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->bind(SliderRepositoryInterface::class, SliderRepository::class);
        $this->app->bind(SliderServiceInterface::class, SliderService::class);

        $this->app->bind(AuditRepositoryInterface::class, AuditRepository::class);
        $this->app->bind(AuditServiceInterface::class, AuditService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
