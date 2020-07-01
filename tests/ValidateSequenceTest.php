<?php
/**
 * Created by enea dhack - 30/06/2020 18:21.
 */

namespace Enea\Tests;

use Enea\Sequenceable\Serie;
use Enea\Tests\Models\Document;
use LogicException;

class ValidateSequenceTest extends DatabaseTestCase
{
    public function test_add_the_same_column_multiple_times_throw_an_exception(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Column 'number' should only have one sequence");

        Document::create([
            Serie::lineal('number'),
            Serie::lineal('number')->alias('invoice'),
        ])->save();
    }

    public function test_use_the_default_sequence(): void
    {
        $this->app->make('config')->set('sequenceable.model', null);
        Document::create([Serie::lineal('number')])->save();
        $this->assertDatabaseCount('sequences', 1);
    }
}
