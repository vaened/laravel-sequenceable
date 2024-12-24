<?php
/**
 * Created by enea dhack - 27/06/2020 17:52.
 */

namespace Vaened\Sequenceable\Tests\Sequences;

use Vaened\Sequenceable\Serie;
use Vaened\Sequenceable\Tests\Models\Document;
use Vaened\SequenceGenerator\Stylists\FixedLength;

class FixedLengthSequenceTest extends SequenceTestCase
{
    public function test_generate_sequence(): void
    {
        $this->assertDatabaseHas('sequences', [
            'source'    => 'documents',
            'column_id' => 'number_string',
            'sequence'  => 3
        ]);
    }

    public function getExpectedDocumentValues(): array
    {
        return [
            ['number' => null, 'number_string' => '0000000001', 'type' => null],
            ['number' => null, 'number_string' => '0000000002', 'type' => null],
            ['number' => null, 'number_string' => '0000000003', 'type' => null],
        ];
    }

    protected function models(): array
    {
        return [
            Document::create([Serie::lineal('number_string')->styles([FixedLength::of(10)])]),
            Document::create([Serie::lineal('number_string')->styles([FixedLength::of(10)])]),
            Document::create([Serie::lineal('number_string')->styles([FixedLength::of(10)])]),
        ];
    }
}
