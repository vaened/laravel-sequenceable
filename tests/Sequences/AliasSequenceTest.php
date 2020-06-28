<?php
/**
 * Created by enea dhack - 27/06/2020 17:47.
 */

namespace Enea\Tests\Sequences;

use Enea\Sequenceable\Serie;
use Enea\Tests\Models\Document;

class AliasSequenceTest extends SequenceTestCase
{
    public function test_generate_sequence(): void
    {
        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_key' => 'number.invoice',
            'description' => 'documents.number.invoice',
            'sequence' => 1
        ]);
    }

    protected function models(): array
    {
        return [
            Document::create([Serie::lineal('number')->alias('invoice')])
        ];
    }

    protected function expectedDocument(): array
    {
        return [
            'number' => 1,
        ];
    }
}
