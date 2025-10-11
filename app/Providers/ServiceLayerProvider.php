<?php

namespace App\Providers;

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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
