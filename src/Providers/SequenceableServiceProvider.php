<?php

namespace Enea\Sequenceable\Providers;

use Illuminate\Support\ServiceProvider;

class SequenceableServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes( [ __DIR__. '/../DataBase/Migrations' => database_path( 'migrations' ) ], 'migrations');
        $this->publishes( [ __DIR__ . '/../config/sequenceable.php' => base_path('config/sequenceable.php') ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
