<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;   // 👈 agrega esta línea
use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\ReportRepository;
use App\Repositories\Contracts\ReportRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191); // 👈 ahora sí funciona

        Gate::define('viewSwagger', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('super-admin');
        });

        Gate::define('viewCatalogs', function ($user) {
            return !is_null($user);
        });
    }
}
