<?php
/**
 * Created by enea dhack - 27/06/2020 17:47.
 */

namespace Vaened\Sequenceable\Tests\Sequences;

use Vaened\Sequenceable\Serie;
use Vaened\Sequenceable\Tests\Models\Document;

class AliasSequenceTest extends SequenceTestCase
{
    public function test_generate_sequence(): void
    {
        $this->assertDatabaseHas('sequences', [
            'source'    => 'documents',
            'column_id' => 'number.invoice',
            'sequence'  => 2
        ]);

        $this->assertDatabaseHas('sequences', [
            'source'    => 'documents',
            'column_id' => 'number.ticket',
            'sequence'  => 1
        ]);
    }

    public function getExpectedDocumentValues(): array
    {
        return [
            ['number' => 1, 'number_string' => null, 'type' => 'invoice'],
            ['number' => 2, 'number_string' => null, 'type' => 'invoice'],
            ['number' => 1, 'number_string' => null, 'type' => 'ticket'],
        ];
    }

    protected function models(): array
    {
        return [
            Document::create([Serie::lineal('number')->scope('invoice')], ['type' => 'invoice']),
            Document::create([Serie::lineal('number')->scope('invoice')], ['type' => 'invoice']),
            Document::create([Serie::lineal('number')->scope('ticket')], ['type' => 'ticket']),
        ];
    }
}
