<?php
/**
 * Created by enea dhack - 27/06/2020 20:10.
 */

namespace Vaened\Sequenceable\Tests;

use Vaened\Sequenceable\Model\Sequence;
use Vaened\Sequenceable\SequenceCollection;
use Vaened\Sequenceable\Succession;
use Vaened\Sequenceable\Tests\Models\CustomSequence;
use Vaened\Sequenceable\Tests\Models\Document;

use function resolve;

class SuccessionTest extends DatabaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loadSequences();
    }

    public function test_returns_all_the_sequences_of_the_model(): void
    {
        $collection = $this->getSequenceCollection();
        $this->assertCount(2, $collection->all());
    }

    public function test_find_a_model(): void
    {
        $collection = $this->getSequenceCollection();
        $sequence   = $collection->find('number_string');

        $this->assertEquals(3, $sequence->current());
        $this->assertEquals('number_string', $sequence->getQualifiedName());
    }

    public function test_find_a_model_with_alias(): void
    {
        $collection = $this->getSequenceCollection();
        $sequence   = $collection->find('number', 'document');

        $this->assertEquals(3, $sequence->current());
        $this->assertEquals('number.document', $sequence->getQualifiedName());
    }

    public function test_returns_the_specific_model_of_the_sequence(): void
    {
        $collection = $this->getSequenceCollection();
        $this->assertInstanceOf(Sequence::class, $collection->find('number', 'document'));
        $this->assertInstanceOf(CustomSequence::class, $collection->find('number_string'));
        $this->assertNull($collection->find('another_column'));
    }

    private function getSequenceCollection(): SequenceCollection
    {
        return resolve(Succession::class)->from(Document::class);
    }

    private function loadSequences(): void
    {
        $documents = [
            new Document(),
            new Document(),
            new Document(),
        ];

        array_map(fn(Document $document) => $document->save(), $documents);
    }
}
