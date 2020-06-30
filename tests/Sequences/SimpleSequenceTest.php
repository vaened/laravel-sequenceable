<?php
/**
 * Created by enea dhack - 27/06/2020 17:25.
 */

namespace Enea\Tests\Sequences;

use Enea\Sequenceable\Serie;
use Enea\Tests\Models\Document;

class SimpleSequenceTest extends SequenceTestCase
{
    public function test_generate_sequence(): void
    {
        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_id' => 'number',
            'sequence' => 4
        ]);
        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_id' => 'number_string',
            'sequence' => 2
        ]);
    }

    protected function models(): array
    {
        return [
            Document::create([Serie::lineal('number')]),
            Document::create([
                Serie::lineal('number'),
                Serie::lineal('number_string'),
            ]),
            Document::create([Serie::lineal('number')]),
            Document::create([
                Serie::lineal('number'),
                Serie::lineal('number_string'),
            ]),
        ];
    }

    public function getExpectedDocumentValues(): array
    {
        return [
            ['number' => 1, 'number_string' => null, 'type' => null],
            ['number' => 2, 'number_string' => '1', 'type' => null],
            ['number' => 3, 'number_string' => null, 'type' => null],
            ['number' => 4, 'number_string' => '2', 'type' => null],
        ];
    }
}
