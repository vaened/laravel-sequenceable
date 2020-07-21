<?php
/**
 * Created by enea dhack - 27/06/2020 17:18.
 */

namespace Enea\Tests\Sequences;

use Enea\Tests\DatabaseTestCase;
use Enea\Tests\Models\Document;

abstract class SequenceTestCase extends DatabaseTestCase
{
    abstract public function test_generate_sequence(): void;

    abstract protected function models(): array;

    abstract public function getExpectedDocumentValues(): array;

    public function setUp(): void
    {
        parent::setUp();
        $this->generate();
    }

    public function test_create_document_with_proper_sequence(): void
    {
        $documents = $this->getExpectedDocumentValues();

        $this->assertDatabaseCount('documents', count($documents));
        foreach ($documents as $document) {
            $this->assertDatabaseHas('documents', [
                'number' => $document['number'],
                'number_string' => $document['number_string'],
                'type' => $document['type'],
            ]);
        }
    }

    protected function generate(): void
    {
        array_map(fn(Document $document) => $document->save(), $this->models());
    }
}
