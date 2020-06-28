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
    public function test_create_document_with_proper_sequence(): void
    {
        $this->assertDatabaseHas('documents', [
            'number' => 1,
            'number_string' => null,
        ]);
        $this->assertDatabaseHas('documents', [
            'number' => null,
            'number_string' => '00001',
        ]);
        $this->assertDatabaseHas('documents', [
            'number' => 2,
            'number_string' => '00000001',
        ]);
    }

    public function test_generate_sequence(): void
    {
        $this->assertDatabaseHas('custom_sequences', [
            'source' => 'documents',
            'column_key' => 'number.num',
            'key' => 'num',
            'sequence' => 2
        ]);

        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_key' => 'number_string.ticket',
            'description' => 'documents.number_string.ticket',
            'sequence' => 1
        ]);

        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_key' => 'number_string.invoice',
            'description' => 'documents.number_string.invoice',
            'sequence' => 1
        ]);
    }

    protected function models(): array
    {
        return [
            Document::create([
                Wrap::create(CustomSequence::class, fn (Wrap $wrap) => $wrap->column('number')->alias('num')),
            ]),
            Document::create([
                Serie::lineal('number_string')->alias('invoice')->length(5),
            ]),
            Document::create([
                Serie::lineal('number_string')->alias('ticket')->length(8),
                Wrap::create(CustomSequence::class, fn (Wrap $wrap) => $wrap->column('number')->alias('num')),
            ]),
        ];
    }
}
