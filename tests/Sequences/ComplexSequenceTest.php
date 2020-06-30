<?php
/**
 * Created by enea dhack - 27/06/2020 18:05.
 */

namespace Enea\Tests\Sequences;

use Enea\Sequenceable\Serie;
use Enea\Sequenceable\Wrap;
use Enea\Tests\Models\CustomSequence;
use Enea\Tests\Models\Document;

class ComplexSequenceTest extends SequenceTestCase
{
    public function test_generate_sequence(): void
    {
        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_id' => 'number_string.invoice',
            'sequence' => 1
        ]);

        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_id' => 'number_string.ticket',
            'sequence' => 2
        ]);

        $this->assertDatabaseHas('custom_sequences', [
            'source' => 'documents',
            'column_id' => 'number.val',
            'sequence' => 2
        ]);

        $this->assertDatabaseHas('custom_sequences', [
            'source' => 'documents',
            'column_id' => 'number_string.val',
            'sequence' => 1
        ]);
    }

    protected function models(): array
    {
        return [
            Document::create([Serie::lineal('number_string')->alias('invoice')->length(5)], ['type' => 'invoice']),
            Document::create([Serie::lineal('number_string')->alias('ticket')->length(8)], ['type' => 'ticket']),
            Document::create([Serie::lineal('number_string')->alias('ticket')->length(8)], ['type' => 'ticket']),

            Document::create([
                Wrap::create(CustomSequence::class, fn(Wrap $wrap) => $wrap->column('number')->alias('val')),
            ], ['type' => 'val']),

            Document::create([
                Wrap::create(CustomSequence::class, function (Wrap $wrap): void {
                    $wrap->column('number')->alias('val');
                    $wrap->column('number_string')->alias('val')->length(3);
                }),
            ], ['type' => 'val']),
        ];
    }

    public function getExpectedDocumentValues(): array
    {
        return [
            ['number' => null, 'number_string' => '00001', 'type' => 'invoice'],
            ['number' => null, 'number_string' => '00000001', 'type' => 'ticket'],
            ['number' => null, 'number_string' => '00000002', 'type' => 'ticket'],

            ['number' => 1, 'number_string' => null, 'type' => 'val'],
            ['number' => 2, 'number_string' => '001', 'type' => 'val'],
        ];
    }
}
