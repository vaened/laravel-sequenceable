<?php
/**
 * Created by enea dhack - 27/06/2020 17:55.
 */

namespace Enea\Tests\Sequences;

use Enea\Sequenceable\Wrap;
use Enea\Tests\Models\CustomSequence;
use Enea\Tests\Models\Document;

class CustomSequenceModelTest extends SequenceTestCase
{
    public function test_generate_sequence(): void
    {
        $this->assertDatabaseCount('sequences', 0);
        $this->assertDatabaseHas('custom_sequences', [
            'source'    => 'documents',
            'column_id' => 'number.hai',
            'sequence'  => 2
        ]);
    }

    protected function models(): array
    {
        return [
            Document::create([
                Wrap::create(
                    CustomSequence::class,
                    static fn(Wrap $wrap) => $wrap->column('number')->scope('hai')
                ),
            ], ['type' => 'hai']),

            Document::create([
                Wrap::create(
                    CustomSequence::class,
                    static fn(Wrap $wrap) => $wrap->column('number')->scope('hai')
                ),
            ], ['type' => 'hai']),
        ];
    }

    public function getExpectedDocumentValues(): array
    {
        return [
            ['number' => 1, 'number_string' => null, 'type' => 'hai'],
            ['number' => 2, 'number_string' => null, 'type' => 'hai'],
        ];
    }
}
