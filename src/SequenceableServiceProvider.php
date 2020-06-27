<?php

namespace Enea\Sequenceable;

use Illuminate\Support\ServiceProvider;

class SequenceableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'migrations');
        $this->publishes([__DIR__ . '/../config/sequenceable.php' => base_path('config/sequenceable.php')]);
    }
}
