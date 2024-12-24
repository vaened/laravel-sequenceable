<?php

namespace Vaened\Sequenceable;

use Illuminate\Support\ServiceProvider;
use Vaened\Sequenceable\Model\Sequence;
use Vaened\SequenceGenerator\Contracts\SequenceRepository;

use function base_path;
use function config;
use function database_path;

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
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations'     => database_path('migrations'),
                __DIR__ . '/../config/sequenceable.php' => base_path('config/sequenceable.php'),
            ], 'sequenceable');
        }
    }
}

