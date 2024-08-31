<?php

namespace Enea\Sequenceable;

use Enea\Sequenceable\Model\Sequence;
use Illuminate\Support\ServiceProvider;
use Vaened\SequenceGenerator\Contracts\SequenceRepository;
use function config;

class SequenceableServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SequenceRepository::class, function () {
            $model = config('sequenceable.model') ?: Sequence::class;
            return new $model();
        });
    }

    public function boot(): void
    {
        $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'migrations');
        $this->publishes([__DIR__ . '/../config/sequenceable.php' => base_path('config/sequenceable.php')]);
    }
}
