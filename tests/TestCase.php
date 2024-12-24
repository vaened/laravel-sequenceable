<?php
/**
 * Created by enea dhack - 30/05/2017 04:42 PM.
 */

namespace Vaened\Sequenceable\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Vaened\Sequenceable\Contracts\SequenceContract;
use Vaened\Sequenceable\Model\Sequence;
use Vaened\Sequenceable\SequenceableServiceProvider;

use function config;

class TestCase extends BaseTestCase
{
    public function getDefaultSequenceModel(): SequenceContract
    {
        $model = config('sequenceable.model') ?: Sequence::class;
        return new $model();
    }

    protected function getEnvironmentSetUp($app)
    {
        $config = $app->make('config');
        $config->set('sequenceable.model', Sequence::class);
        $config->set('sequenceable.hash', 'sha256');
    }

    protected function getPackageProviders($app): array
    {
        return [
            SequenceableServiceProvider::class,
        ];
    }
}
