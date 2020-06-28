<?php
/**
 * Created by enea dhack - 27/06/2020 17:52.
 */

namespace Enea\Tests\Sequences;

use Enea\Sequenceable\Serie;
use Enea\Tests\Models\Document;

class FixedLengthSequenceTest extends SequenceTestCase
{
    public function test_create_document_with_proper_sequence(): void
    {
        $this->assertDatabaseHas('documents', [
            'number_string' => '0000000001',
        ]);
    }

    public function test_generate_sequence(): void
    {
        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_key' => 'number_string',
            'description' => 'documents.number_string',
            'sequence' => 1
        ]);
    }

    protected function models(): array
    {
        return [
            Document::create([Serie::lineal('number_string')->length(10)])
        ];
    }
}
