<?php
/**
 * Created by enea dhack - 30/06/2020 18:21.
 */

namespace Enea\Tests;

use Enea\Sequenceable\Serie;
use Enea\Tests\Models\Document;
use Vaened\SequenceGenerator\Exceptions\SequenceError;

class ValidateSequenceTest extends DatabaseTestCase
{
    public function test_add_the_same_column_multiple_times_throw_an_exception(): void
    {
        $this->expectException(SequenceError::class);
        $this->expectExceptionMessage("the name 'number' is already registered");

        Document::create([
            Serie::lineal('number'),
            Serie::lineal('number')->scope('invoice'),
        ])->save();
    }

    public function test_use_the_default_sequence(): void
    {
        $this->app->make('config')->set('sequenceable.model', null);
        Document::create([Serie::lineal('number')])->save();
        $this->assertDatabaseCount('sequences', 1);
    }
}
