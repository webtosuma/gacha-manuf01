<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Socialite\Facades\Socialite;
use App\Socialite\YahooJpProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
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

        /* Yahooログイン サービスプロバイダー */
        Socialite::extend('yahoo', function ($app) {
        $config = $app['config']['services.yahoo'];
        dd($config);
        return new YahooJpProvider(
            $app['request'],
            $config['client_id'],
            $config['client_secret'],
            $config['redirect']
        );
    });    }
}
