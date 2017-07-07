<?php
/**
 * Created by enea dhack - 07/07/17 01:47 PM.
 */

namespace Enea\Tests;

use Enea\Tests\Models\AutofillingToShowConfiguration;

class AutofillingTest extends DataBaseTestCase
{
    public function test_the_sequence_is_displayed_with_the_number_of_characters_configured()
    {
        $this->app['config']->set('sequenceable', [
            'prefix' => 'full_'
        ]);

        $document = new AutofillingToShowConfiguration();
        $document->save();

        $document->refresh();

        $this->assertDatabaseHas('documents', [
            'number' => 1,
            'number_string' => 1,
        ]);

        $this->assertTrue(strlen($document->full_number) === 8);
        $this->assertSame($document->full_number, '00000001');

        $this->assertTrue(strlen($document->full_number_string) === 10);
        $this->assertSame($document->full_number_string, '0000000001');
    }

}