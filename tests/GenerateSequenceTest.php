<?php
/**
 * Created by enea dhack - 24/06/17 01:42 PM
 */

namespace Enea\Tests;

use Enea\Sequenceable\Model\Sequence;
use Enea\Tests\Models\CustomSequence;
use Enea\Tests\Models\Document;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenerateSequenceTest extends DataBaseTestCase
{
    use DatabaseTransactions;

    function test_a_basic_sequence_is_generated( )
    {
        $document = new BasicSequenceConfiguration();
        $document->save();

        $this->assertDatabaseHas('documents', [
            'number' => 1,
            'number_string' => 1,
        ]);

        $this->assertDatabaseHas('sequences', ['source' => 'documents.number', 'sequence' => 1 ]);
        $this->assertDatabaseHas('sequences', ['source' => 'documents.number_string', 'sequence' => 1]);
    }

    function test_an_custom_code_sequence_is_generated( )
    {
        $document = new CustomCodeSequenceConfiguration();
        $document->save();

        $this->assertDatabaseHas('documents', [
            'number' => 1,
            'number_string' => 1,
        ]);

        $this->assertDatabaseHas('sequences', ['source' => 'documents.custom_number_code', 'sequence' => 1 ]);
        $this->assertDatabaseHas('sequences', ['source' => 'documents.custom_number_string_code', 'sequence' => 1]);
    }

    function test_a_sequence_is_generated_with_a_custom_model()
    {
        $document = new CustomSequenceModelConfiguration();
        $document->save();

        $this->assertDatabaseHas('documents', [
            'number' => 1,
            'number_string' => 1,
        ]);

        $this->assertDatabaseHas('custom_sequences', ['source' => 'documents.custom_number_code', 'sequence' => 1 ]);
        $this->assertDatabaseHas('custom_sequences', ['source' => 'documents.number_string', 'sequence' => 1]);
    }


}

class BasicSequenceConfiguration extends Document
{
    public function sequencesSetup( ): array
    {
        return [
            'number',
            'number_string'
        ];
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
            'custom_number_code' =>  'number',
            'custom_number_string_code' => [ 'number_string' ],
        ];
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
