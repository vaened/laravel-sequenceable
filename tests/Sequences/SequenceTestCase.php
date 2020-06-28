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

    public function setUp(): void
    {
        parent::setUp();
        $this->generate();
    }

    protected function generate(): void
    {
        array_map(fn(Document $document) => $document->save(), $this->models());
    }

    public function test_create_document_with_proper_sequence(): void
    {
        $this->assertDatabaseHas('documents', [
            'number' => 1,
        ]);
    }
}
