<?php
/**
 * Created by enea dhack - 24/06/17 01:42 PM
 */

namespace Enea\Tests;

use Enea\Tests\Models\BasicSequenceConfiguration;
use Enea\Tests\Models\CustomCodeSequenceConfiguration;
use Enea\Tests\Models\CustomSequenceModelConfiguration;
use Enea\Tests\Models\DynamicCodeSequenceConfiguration;
use Enea\Tests\Models\SimpleSequenceConfiguration;

class GenerateSequenceTest extends DataBaseTestCase
{

    function test_the_sequences_are_generated()
    {
        $documents = [
            1 => new SimpleSequenceConfiguration(),
            2 => new SimpleSequenceConfiguration( ),
            3 => new SimpleSequenceConfiguration(),
            4 => new SimpleSequenceConfiguration()
        ];

        foreach ( $documents as $sequence => $document ) {
            $document->save( );

            $this->assertDatabaseHas('documents', [
                'number' => $sequence,
            ]);

            $this->assertDatabaseHas('sequences', [
                'source' => 'documents',
                'column_key' => 'number',
                'description' => 'documents.number',
                'sequence' => $sequence
            ]);
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

        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_key' => 'number',
            'description' => 'documents.number',
            'sequence' => 1
        ]);
        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_key' => 'number_string',
            'description' => 'documents.number_string',
            'sequence' => 1
        ]);
    }

    function test_an_custom_code_sequence_is_generated( )
    {
        $document = new CustomCodeSequenceConfiguration();
        $document->save();

        $this->assertDatabaseHas('documents', [
            'number' => 1,
            'number_string' => 1,
        ]);

        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_key' => 'number.custom_number_code',
            'description' => 'documents.number.custom_number_code',
            'sequence' => 1
        ]);
        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_key' => 'number_string.custom_number_string_code',
            'description' => 'documents.number_string.custom_number_string_code',
            'sequence' => 1
        ]);
    }

    function test_a_sequence_is_generated_with_a_custom_model()
    {
        $document = new CustomSequenceModelConfiguration();
        $document->save();

        $this->assertDatabaseHas('documents', [
            'number' => 1,
            'number_string' => 1,
        ]);

        $this->assertDatabaseHas('custom_sequences', [
            'source' => 'documents',
            'column_key' => 'number.ccn',
            'key' => 'ccn',
            'sequence' => 1 
        ]);
        $this->assertDatabaseHas('custom_sequences', [
            'source' => 'documents',
            'column_key' => 'number_string.cns',
            'key' => 'cns',
            'sequence' => 1
        ]);
    }

    function test_a_sequence_with_a_dynamic_value_is_generated()
    {
        $document = new DynamicCodeSequenceConfiguration([
            'type' => 'tk'
        ]);
        $document->save();
        $this->assertDatabaseHas('documents', [ 'number' => 1, 'type' => 'tk' ]);
        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_key' => 'number.ticket',
            'description' => 'documents.number.ticket', 'sequence' => 1
        ]);

        $document = new DynamicCodeSequenceConfiguration([
            'type' => 'iv'
        ]);
        $document->save();
        $this->assertDatabaseHas('documents', [ 'number' => 1, 'type' => 'iv' ]);
        $this->assertDatabaseHas('sequences', [
            'source' => 'documents',
            'column_key' => 'number.invoice',
            'description' => 'documents.number.invoice',
            'sequence' => 1
        ]);
    }

}