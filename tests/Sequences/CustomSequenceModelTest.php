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
        $this->assertDatabaseHas('custom_sequences', [
            'source' => 'documents',
            'column_key' => 'number.ccn',
            'key' => 'ccn',
            'sequence' => 1
        ]);
    }

    protected function models(): array
    {
        return [
            Document::create([
                Wrap::create(CustomSequence::class, fn(Wrap $wrap) => $wrap->column('number')->alias('ccn')),
            ]),
        ];
    }
}
