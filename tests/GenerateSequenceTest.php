<?php
/**
 * Created by enea dhack - 24/06/17 01:42 PM
 */

namespace Enea\Tests;

use Enea\Tests\Models\CustomSequence;
use Enea\Tests\Models\Document;

class GenerateSequenceTest extends DataBaseTestCase
{

    function test_the_sequences_are_generated()
    {
        $documents = [ 1 => new BasicSequenceConfiguration(), 2 => new BasicSequenceConfiguration( ), 3 => new BasicSequenceConfiguration(), 4 => new BasicSequenceConfiguration() ];

        foreach ( $documents as $sequence => $document ) {
            $document->save( );

            $this->assertDatabaseHas('documents', [
                'number' => $sequence,
            ]);

            $this->assertDatabaseHas('sequences', ['source' => 'documents.number.number', 'sequence' => $sequence ]);
        }
    }

    function test_a_basic_sequence_is_generated( )
    {
        $document = new BasicSequenceConfiguration();
        $document->save();

        $this->assertDatabaseHas('documents', [
            'number' => 1,
            'number_string' => 1,
        ]);

        $this->assertDatabaseHas('sequences', ['source' => 'documents.number.number', 'sequence' => 1 ]);
        $this->assertDatabaseHas('sequences', ['source' => 'documents.number_string.number_string', 'sequence' => 1]);
    }

    function test_an_custom_code_sequence_is_generated( )
    {
        $document = new CustomCodeSequenceConfiguration();
        $document->save();

        $this->assertDatabaseHas('documents', [
            'number' => 1,
            'number_string' => 1,
        ]);

        $this->assertDatabaseHas('sequences', ['source' => 'documents.number.custom_number_code', 'sequence' => 1 ]);
        $this->assertDatabaseHas('sequences', ['source' => 'documents.number_string.custom_number_string_code', 'sequence' => 1]);
    }

    function test_a_sequence_is_generated_with_a_custom_model()
    {
        $document = new CustomSequenceModelConfiguration();
        $document->save();

        $this->assertDatabaseHas('documents', [
            'number' => 1,
            'number_string' => 1,
        ]);

        $this->assertDatabaseHas('custom_sequences', ['source' => 'documents.number.custom_number_code', 'sequence' => 1 ]);
        $this->assertDatabaseHas('custom_sequences', ['source' => 'documents.number_string.number_string', 'sequence' => 1]);
    }

    function test_a_sequence_with_a_dynamic_value_is_generated()
    {
        $document = new DynamicCodeSequenceConfiguration([
            'type' => 'tk'
        ]);
        $document->save();
        $this->assertDatabaseHas('documents', [ 'number' => 1, 'type' => 'tk' ]);
        $this->assertDatabaseHas('sequences', ['source' => 'documents.number.ticket', 'sequence' => 1 ]);

        $document = new DynamicCodeSequenceConfiguration([
            'type' => 'iv'
        ]);
        $document->save();
        $this->assertDatabaseHas('documents', [ 'number' => 1, 'type' => 'iv' ]);
        $this->assertDatabaseHas('sequences', ['source' => 'documents.number.invoice', 'sequence' => 1]);
    }

}

class SimpleSequenceConfiguration extends Document
{
    public function sequencesSetup( ): array
    {
        return [ 'number' ];
    }
}

class BasicSequenceConfiguration extends Document
{
    public function sequencesSetup( ): array
    {
        return [ 'number', 'number_string' ];
    }

}

class CustomCodeSequenceConfiguration extends Document
{
    public function sequencesSetup(): array
    {
        return [
            'custom_number_code' =>  'number',
            'custom_number_string_code' => [ 'number_string' ],
        ];
    }
}

class DynamicCodeSequenceConfiguration extends Document
{
    public function sequencesSetup(): array
    {
        return [
            $this->getType( ) =>  'number',
        ];
    }

    public function getType( )
    {
        return $this->type === 'tk' ? 'ticket' : 'invoice';
    }
}


class CustomSequenceModelConfiguration extends Document
{
    public function sequencesSetup(): array
    {
        return [
            CustomSequence::class => [
                'custom_number_code' => 'number',
                'number_string',
            ],
        ];
    }
}
