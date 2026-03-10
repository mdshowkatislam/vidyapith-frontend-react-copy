<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('sso-auth', \App\Container\Auth::class);
        $this->app->singleton('role', \App\Container\Role::class);

        //services
        $this->app->bind(
            'App\Services\ClassRoomService\ClassRoomServiceInterface',
            'App\Services\ClassRoomService\ClassRoomService',
        );
        $this->app->bind(
            'App\Services\SubjectTeacherService\SubjectTeacherServiceInterface',
            'App\Services\SubjectTeacherService\SubjectTeacherService',
        );

        //repositories
        $this->app->bind(
            'App\Repositories\Interfaces\ClassRoomRepositoryInterface',
            'App\Repositories\ClassRoomRepository',
        );
        $this->app->bind(
            'App\Repositories\Interfaces\SubjectTeacherRepositoryInterface',
            'App\Repositories\SubjectTeacherRepository',
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void
    {
        Schema::defaultStringLength(191);
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        mobileBrowserTurnOff($request->userAgent());
    }
}
