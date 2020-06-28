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
            'column_key' => 'number',
            'description' => 'documents.number',
            'sequence' => 1
        ]);
    }

    protected function models(): array
    {
        return [
            Document::create([Serie::lineal('number')]),
        ];
    }
}
