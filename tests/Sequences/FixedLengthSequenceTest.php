<?php
/**
 * Created by enea dhack - 27/06/2020 17:52.
 */

namespace Enea\Tests\Sequences;

use Enea\Sequenceable\Serie;
use Enea\Tests\Models\Document;

class FixedLengthSequenceTest extends SequenceTestCase
{
    public function test_generate_sequence(): void
    {
        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_id' => 'number_string',
            'sequence' => 3
        ]);
    }

    protected function models(): array
    {
        return [
            Document::create([Serie::lineal('number_string')->length(10)]),
            Document::create([Serie::lineal('number_string')->length(10)]),
            Document::create([Serie::lineal('number_string')->length(10)]),
        ];
    }

    public function getExpectedDocumentValues(): array
    {
        return [
            ['number' => null, 'number_string' => '0000000001', 'type' => null],
            ['number' => null, 'number_string' => '0000000002', 'type' => null],
            ['number' => null, 'number_string' => '0000000003', 'type' => null],
        ];
    }
}
