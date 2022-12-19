<?php
/**
 * Created by enea dhack - 27/06/2020 18:05.
 */

namespace Enea\Tests\Sequences;

use Enea\Sequenceable\Serie;
use Enea\Sequenceable\Wrap;
use Enea\Tests\Models\CustomSequence;
use Enea\Tests\Models\Document;
use Vaened\SequenceGenerator\Stylists\FixedLength;
use Vaened\SequenceGenerator\Stylists\Prefixed;

class ComplexSequenceTest extends SequenceTestCase
{
    public function test_generate_sequence(): void
    {
        $this->assertDatabaseHas('sequences', [
            'source'    => 'documents',
            'column_id' => 'number_string.invoice',
            'sequence'  => 1
        ]);

        $this->assertDatabaseHas('sequences', [
            'source'    => 'documents',
            'column_id' => 'number_string.ticket',
            'sequence'  => 2
        ]);

        $this->assertDatabaseHas('custom_sequences', [
            'source'    => 'documents',
            'column_id' => 'number.val',
            'sequence'  => 2
        ]);

        $this->assertDatabaseHas('custom_sequences', [
            'source'    => 'documents',
            'column_id' => 'number_string.val',
            'sequence'  => 1
        ]);
    }

    protected function models(): array
    {
        return [
            Document::create(
                [
                    Serie::for('number_string')
                        ->scope('invoice')
                        ->styles([FixedLength::of(5)])
                ], ['type' => 'invoice']
            ),
            Document::create([
                Serie::for('number_string')
                    ->scope('ticket')
                    ->styles([FixedLength::of(8)])
            ], ['type' => 'ticket']),
            Document::create([
                Serie::for('number_string')
                    ->scope('ticket')
                    ->styles([
                        FixedLength::of(8),
                        Prefixed::of('A')
                    ])
            ], ['type' => 'ticket']),

            Document::create([
                Wrap::create(
                    CustomSequence::class,
                    static fn(Wrap $wrap) => $wrap->column('number')->scope('val')
                ),
            ], ['type' => 'val']),

            Document::create([
                Wrap::create(CustomSequence::class, function (Wrap $wrap): void {
                    $wrap->column('number')->scope('val');
                    $wrap->column('number_string')->scope('val')->styles([FixedLength::of(3)]);
                }),
            ], ['type' => 'val']),
        ];
    }

    public function getExpectedDocumentValues(): array
    {
        return [
            ['number' => null, 'number_string' => '00001', 'type' => 'invoice'],
            ['number' => null, 'number_string' => '00000001', 'type' => 'ticket'],
            ['number' => null, 'number_string' => 'A0000002', 'type' => 'ticket'],

            ['number' => 1, 'number_string' => null, 'type' => 'val'],
            ['number' => 2, 'number_string' => '001', 'type' => 'val'],
        ];
    }
}
