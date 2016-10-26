<?php

namespace Mbarwick83\Mailchimp;

use Illuminate\Support\ServiceProvider;

class MailChimpServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/mailchimp.php' => config_path('mailchimp.php'),
        ], 'config');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Mbarwick83\Mailchimp\Mailchimp', function($app) {
            return new Mailchimp($app);
        });
    }
}