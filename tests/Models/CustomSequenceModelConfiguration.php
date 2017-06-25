<?php
/**
 * Created by enea dhack - 25/06/17 02:32 PM
 */

namespace Enea\Tests\Models;


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