<?php

namespace Rigorbb\FastAuthLinks;

use Illuminate\Support\ServiceProvider;

class FastAuthLinkProvider extends ServiceProvider {

    /**
     *
     */
    public function register()
    {
        $this->app->singleton('FastAuthLink', function ($app) {
            return new FastAuthLink(config('app.key'));
        });
    }
}