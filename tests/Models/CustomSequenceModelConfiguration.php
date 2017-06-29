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
                'ccn' => 'number',
                'cns' => 'number_string',
            ],
        ];
    }
}