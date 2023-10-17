<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Saloon\Http\Senders\GuzzleSender;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // Implicitly grant "Super-Admin" role all permission checks using can()
        Gate::before(function ($user, $ability) {
            if ($user->hasRole(env('APP_SUPER_ADMIN', 'super-admin'))) {
                return true;
            }
        });

        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
    }

    public function register(): void
    {
        $this->app->singleton(GuzzleSender::class, fn() => new GuzzleSender());
    }

    /**
     * Bootstrap any application services.
     */
}
