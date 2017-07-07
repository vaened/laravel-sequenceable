<?php
/**
 * Created by enea dhack - 25/06/17 12:50 PM.
 */

namespace Enea\Tests;

use Enea\Sequenceable\Facades\Succession;
use Enea\Tests\Models\DynamicCodeSequenceConfiguration;

class FacadeTest extends DataBaseTestCase
{
    public function test_the_sequences_of_a_model_are_obtained()
    {
        $document = new DynamicCodeSequenceConfiguration(['type' => 'tk']);
        $document->save();
        $this->assertDatabaseHas('documents', ['number' => 1, 'type' => 'tk']);

        $document = new DynamicCodeSequenceConfiguration(['type' => 'iv']);
        $document->save();
        $this->assertDatabaseHas('documents', ['number' => 1, 'type' => 'iv']);

        $collect = Succession::on(DynamicCodeSequenceConfiguration::class);

        $ticket = $collect->first()->toArray();

        $this->assertSame($ticket['sequence'], 1);
        $this->assertSame($ticket['source'], 'documents');
        $this->assertSame($ticket['column_key'], 'number.ticket');

        $invoice = $collect->last()->toArray();

        $this->assertSame($invoice['sequence'], 1);
        $this->assertSame($invoice['source'], 'documents');
        $this->assertSame($invoice['column_key'], 'number.invoice');
    }
}
